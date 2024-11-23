
<?php

include "/home/daypay5/public_html/dashboard/dbcon.php";
include ($basePath."/Handlers/TransactionHistoryHandler.php");
require_once $basePath."/logger.php";
    
$log = new Logger("$basePath/cron_jobs/log.txt");
$log->setTimestamp("D M d 'y h.i A");
$log->putLog("\n\n refund running \n\n");

$reQueryHandler = new ReQueryHandler;

$transactionHistory = mysqli_query($con,"SELECT * FROM transactionhistory WHERE Status='2' LIMIT 0,50")or die(mysqli_error($con));
if(mysqli_num_rows($transactionHistory))
{
    while($row = mysqli_fetch_array($transactionHistory))
    {
        $tran_id = $row["Tran_Id"];
        $provider = $row["Provider"];
        $amount = $row['Amount'];
        $tranType = $row['Transaction'];
        $username = $row['Sender'];
        $description = $row['Description'];
        $receiver = $row['Receiver'];

        if($provider == "LP")
        {
            $lpResponse = $reQueryHandler->LpTransactionStatus($tran_id);
            $log->putLog("LP Status Check: $lpResponse");

            //echo $tran_id;
            $responseModel = json_decode($lpResponse);
            //echo $responseModel->status;
            if(isset($responseModel->Status))
            {
                $log->putLog("Status is set: $responseModel->Status");
               if($responseModel->Status == 'failed')
                   $tranStatus = 0;
               elseif($responseModel->Status == 'successful')
                   $tranStatus = 1;
               elseif($responseModel->status == 'processing')
                   $tranStatus = 2;
               else
                   $tranStatus = 3;
            }else{
               $tranStatus = 3;
            }
        }

        if($provider == "DV")
        {
            $dvResponse = $reQueryHandler->DvTransactionStatus($tran_id);
            $log->putLog("DV Status Check: $dvResponse");

            //echo $tran_id;
            $responseModel = json_decode($dvResponse);
            //echo $responseModel->status;
            if(isset($responseModel->status))
            {
               if($responseModel->status == '0')
                   $tranStatus = 0;
               if($responseModel->status == '1')
                   $tranStatus = 1;
               if($responseModel->status == '2')
                   $tranStatus = 2;
               if($responseModel->status == '3')
                   $tranStatus = 3;
            }else{
               $tranStatus = 3;
            }
        }

        if($provider == "VTP")
        {
            $vtpResponse = $reQueryHandler->VTPTransactionStatus($tran_id);
             $log->putLog("VTP Status Check: $vtpResponse");
             $responseModel = json_decode($vtpResponse);
            
                if($responseModel->response_description == "TRANSACTION SUCCESSFUL")
                {
                    $log->putLog("\n TranStatus set to success Tran_Id='$tran_id'\n");
                    $tranStatus = 1;
                }
                elseif($responseModel->response_description == "TRANSACTION FAILED" || $responseModel->response_description == "TRANSACTION REVERSED" 
                || $responseModel->response_description == "SERVICE SUSPENDED" || $responseModel->response_description == "INVALID REQUEST ID" 
                || $responseModel->response_description == "BELOW MINIMUM AMOUNT ALLOWED" || $responseModel->response_description == "LOW WALLET BALANCE")
                {
                     $log->putLog("\n TranStatus set to failed Tran_Id='$tran_id'\n");
                    $tranStatus = 0;
                }
                elseif($responseModel->response_description == "TRANSACTION IS PROCESSING" || $responseModel->response_description == "TRANSACTION PROCESSING - PENDING")
                {
                     $log->putLog("\n TranStatus set to processing Tran_Id='$tran_id'\n");
                    $tranStatus = 2;
                }
                else
                    $tranStatus = 3;
            
        }
        if($provider == "AV")
        {
            $avResponse = $reQueryHandler->AVTransactionStatus($tran_id);
             $log->putLog("AV Status Check: $avResponse");

            $responseModel = json_decode($avResponse);
            if($responseModel->status == 1)
                $tranStatus = 1;
        }
        if($provider == "MA")
        {
            $maResponse = $reQueryHandler->MATransactionStatus($tran_id);
             $log->putLog("MA Status Check: $maResponse");

            $responseModel = json_decode($maResponse);
            if($responseModel->status == 1)
                $tranStatus = 1;
        }

        if(isset($tranStatus))
        {
             $log->putLog("\n TranStatus $tranStatus Tran_Id='$tran_id'\n");
            if($tranStatus === 1 )//"success"
            {
                $tranHis = mysqli_query($con,"SELECT Id FROM transactionhistory WHERE Tran_Id='$tran_id'");
                $row = mysqli_fetch_array($tranHis);
                $id = $row['Id'];
                
                mysqli_query($con,"UPDATE transactionhistory SET Status='1' WHERE Id='$id'");
                $log->putLog("\n Transaction Confirmed Bonus Credited Tran_Id='$tran_id'\n");
                $date_now = date("Y-m-d H:i:s");
                $confirmed_transaction = mysqli_query($con,"INSERT INTO confirmed_transactions(Username,Amount,Description,Tran_Id,Date_Confirmed)
                                        VALUES('$username','$amount','$description','$tran_id','$date_now')");//or $log->putLog(mysqli_error($con));
                if($confirmed_transaction)
                {
                    if($tranType == "Data")
                    creditDataBonus($username,$amount,$receiver);
                    if($tranType == "Airtime")
                        creditAirtimeBonus($username,$amount,$receiver);
                    if($tranType == "Cable")
                        creditTvBonus($username,$receiver);
                    if($tranType == "Electric")
                    {
                        if($provider == "VTP")
                        {
                            $token = $responseModel->purchased_code;
                            $description = $description.", ".$token;
                            $log->putLog("\ $description.", ".$token \n");
                            mysqli_query($con,"UPDATE transactionhistory SET Description='$description' WHERE Tran_Id='$tran_id'") or $log->putLog(mysqli_error($con));
                        }
                    }
                }else{
                    $log->putLog("\n Transaction Already Confirmed Tran_Id='$tran_id'\n");
                }
                
            }
            elseif($tranStatus == "2")//"processing"
                {
                   $log->putLog("\n Transaction still Processing Tran_Id='$tran_id'\n");
                  }
            elseif($tranStatus == "3")//"Flagged"
            {
                $log->putLog("\n Transaction Flagged Tran_Id='$tran_id'\n");
                //echo " \n Refunding Customer \n";
                $tranHis = mysqli_query($con,"SELECT Id FROM transactionhistory WHERE Tran_Id='$tran_id'");
                $row = mysqli_fetch_array($tranHis);
                $id = $row['Id'];
                mysqli_query($con,"UPDATE transactionhistory SET Status='3' WHERE Id='$id'");
            }
            elseif($tranStatus == "0")
            {
                 $log->putLog(" \n Refunding Customer \n");
                //echo " \n Refunding Customer \n";
                $refunded_transaction = mysqli_query($con,"SELECT * FROM refunded_transactions WHERE Tran_Id='$tran_id'") or $log->putLog(mysqli_error($con));
                if(mysqli_num_rows($refunded_transaction) <= 0)
                {
                    $tranHis = mysqli_query($con,"SELECT Id FROM transactionhistory WHERE Tran_Id='$tran_id'");
                    $row = mysqli_fetch_array($tranHis);
                    $id = $row['Id'];
                    
                    $result = mysqli_query($con,"UPDATE transactionhistory SET Status='0' WHERE Id='$id'");
                    
                    refundCustomer($username,$amount,$description,$tran_id);
                     $log->putLog(" \n customer $username refunded $tran_id  \n");
                    //echo " \n Customer refunded $tran_id \n";
                    
                }else{
                    $tranHis = mysqli_query($con,"SELECT Id FROM transactionhistory WHERE Tran_Id='$tran_id'");
                    $row = mysqli_fetch_array($tranHis);
                    $id = $row['Id'];
                    
                    $result = mysqli_query($con,"UPDATE transactionhistory SET Status='0' WHERE Id='$id'");
                     $log->putLog("\n already refunded $tran_id \n");
                    //echo "already refunded $tran_id";
                }
            }

        }else
        {
            //echo " \n Flagged Customer \n";
            $tranHis = mysqli_query($con,"SELECT Id FROM transactionhistory WHERE Tran_Id='$tran_id'");
                    $row = mysqli_fetch_array($tranHis);
                    $id = $row['Id'];
                    
            $result = mysqli_query($con,"UPDATE transactionhistory SET Status='3' WHERE Id='$id'");
             $log->putLog("\n transaction flagged $tran_id \n");
        }
    }
}

function refundCustomer($username,$amount,$description,$tran_id)
{
    include "/home/daypay5/public_html/dashboard/dbcon.php";
    $log = new Logger("$basePath/cron_jobs/log.txt");
    $log->setTimestamp("D M d 'y h.i A");

    $log->putLog("\n refund running \n");

    if(!empty($username) && $amount>0 && $amount<100000)
    {
        $result = mysqli_query($con,"SELECT * FROM wallets WHERE Username = '$username'") or $log->putLog(mysqli_error($con));
         $log->putLog("Not Yet Refunded $username");
        if($result->num_rows > 0)
        {
            $row1 = mysqli_fetch_array($result);
            $balance = $row1['Balance'];
            $newbalance = $balance + $amount;
            
            if($result)
            {
                $refunded_transaction = mysqli_query($con,"INSERT INTO refunded_transactions(Username,Amount,Description,Tran_Id)
                                        VALUES('$username','$amount','$description','$tran_id')");//or $log->putLog(mysqli_error($con));
                if($refunded_transaction)
                {
                    mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
                
                    $date_now = date("Y-m-d H:i:s");
                    TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>$date_now,'Amount'=>$amount,'Receiver'=>$username,
                            'Sender'=>"Auto_Refund",'Description'=>"Credit: Refund on failed Transaction ($description)","Status"=>'1','init_bal'=>$balance,'final_bal'=>$newbalance));
                            
                     $log->putLog("User Refunded Tran_Id $tran_id");
                }
            }
        }
    }
}


function creditAirtimeBonus($username,$amount,$number)
{
    include "/home/daypay5/public_html/dashboard/dbcon.php";
    $log = new Logger("$basePath/cron_jobs/log.txt");
    $log->setTimestamp("D M d 'y h.i A");
                                                     
    $totalbonuspaid = 0;
    
    $result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
    if($result->num_rows > 0)
    {
        $row = mysqli_fetch_array($result);
        $balance = $row['BonusBalance'];
        $abonus = $amount*0.02;
        $newbalance = $balance + $abonus;

        mysqli_query($con,"UPDATE wallets SET BonusBalance='$newbalance' WHERE Username='$username'");
            TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$abonus,'Receiver'=>$username,'Sender'=>'Airtime Bonus','Description'=>"2% Bonus From Airtime Purchase N$amount ($number)",'init_bal'=>$balance,'final_bal'=>$newbalance));
    }
    $network1 = mysqli_query($con,"SELECT * FROM networks WHERE Username='$username'");
    if(mysqli_num_rows($network1)>0)
    {
        //First Referral
        $row = mysqli_fetch_array($network1);
        $referal = $row['Referal_Id'];
        $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
        if($result->num_rows > 0)
        {
            $row = mysqli_fetch_array($result);
            $balance = $row['BonusBalance'];
            $bal = $balance + ($amount*0.005);
            $totalbonuspaid += ($amount*0.005);
            mysqli_query($con,"UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
            TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount*0.005,'Receiver'=>$referal,
                'Sender'=>'Airtime Bonus','Description'=>'Credit: Direct Referal Bonus from recharge made by '.$username,'init_bal'=>$balance,'final_bal'=>$bal));
        }
        //2nd Referral
        $network1 = mysqli_query($con,"SELECT * FROM networks WHERE Username='$referal'");
        $row = mysqli_fetch_array($network1);
        $referal = $row['Referal_Id'];
        $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
        if($result->num_rows > 0)
        {
            $row = mysqli_fetch_array($result);
            $balance = $row['BonusBalance'];
            $bal = $balance + ($amount*0.002);
            $totalbonuspaid += ($amount*0.002);
            mysqli_query($con,"UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
            TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount*0.002,'Receiver'=>$referal,
                'Sender'=>'Airtime Bonus','Description'=>'Credit: 2nd level Referal Bonus from recharge made by '.$username,'init_bal'=>$balance,'final_bal'=>$bal));
        }
        //3rd to 7th Referral
        $i =1;
        while($i <= 5)
        {
            if($i == 1)
                $nth = "3rd";
            if($i == 2)
                $nth = "4th";
            if($i == 3)
                $nth = "5th";
            if($i == 4)
                $nth = "6th";
            if($i == 5)
                $nth = "7th";
                
            $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
            $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];
            
            if($referal !== '')
            {
                 $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
                $row = mysqli_fetch_array($result);
                $balance = $row['BonusBalance'];
                $bal = $balance + ($amount*0.001);
                $totalbonuspaid += ($amount*0.001);
                mysqli_query($con,"UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
                TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount*0.001,'Receiver'=>$referal,
                                                'Sender'=>'Airtime Bonus','Description'=>'Credit:'.$nth.' level Referal Bonus from recharge made by '.$username,'init_bal'=>$balance,'final_bal'=>$bal));
            }else{
                //Exit if referral is null
                $i = 6;
            }
            $i++;
        }
    }
    return $totalbonuspaid;
}
    
function creditDataBonus($username,$cost,$number)
{
    include "/home/daypay5/public_html/dashboard/dbcon.php";
    $log = new Logger("$basePath/cron_jobs/log.txt");
    $log->setTimestamp("D M d 'y h.i A");
    
    $log->putLog("\n Crediting Data Bonus for $username \n");
    $totalbonuspaid = 0;
    $result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
    if($result->num_rows > 0)
    {
        $row = mysqli_fetch_array($result);
        $balance = $row['BonusBalance'];
        $bal = $balance + $cost*0.01;
        $totalbonuspaid += $cost*0.01;
        
        mysqli_query($con,"UPDATE wallets SET BonusBalance='$bal' WHERE Username='$username'");
        TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($cost * 0.01),'Receiver'=>$username,'Sender'=>'Data Bonus','Description'=>"1% Bonus From Data Purchase ($number)",'init_bal'=>$balance,'final_bal'=>$bal));
    }
            
    $network1 = mysqli_query($con,"SELECT * FROM networks WHERE Username='$username'") or log($mysqli_error($con));;
    if(mysqli_num_rows($network1)>0)
    {
        //First Referral
        $row = mysqli_fetch_array($network1);
        $referal = $row['Referal_Id'];
        $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'") or log($mysqli_error($con));;
        if($result->num_rows > 0)
        {
            $row = mysqli_fetch_array($result);
            $balance = $row['BonusBalance'];
            $bal = $balance + $cost*0.005;
            $totalbonuspaid += $cost*0.005;
            
            mysqli_query($con,"UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
            TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($cost * 0.005),'Receiver'=>$referal,'Sender'=>'Data Bonus','Description'=>'Direct Referal Bonus from data subscription by '.$username,'init_bal'=>$balance,'final_bal'=>$bal));
        }
        
        //2nd Referral
        $network1 = mysqli_query($con,"SELECT * FROM networks WHERE Username='$referal'") or log($mysqli_error($con));;
        $row = mysqli_fetch_array($network1);
        $referal = $row['Referal_Id'];
        $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'") or log($mysqli_error($con));;
        if($result->num_rows > 0)
        {
            $row = mysqli_fetch_array($result);
            $balance = $row['BonusBalance'];
            $bal = $balance + $cost*0.002;
            $totalbonuspaid += $cost*0.002;
            
            mysqli_query($con,"UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'") or log($mysqli_error($con));;
            TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($cost * 0.002),'Receiver'=>$referal,'Sender'=>'Data Bonus','Description'=>'2nd level Referal Bonus from data subscription by '.$username,'init_bal'=>$balance,'final_bal'=>$bal));
        }
        
        //3rd to 5th Referral
        $i =1;
        while($i <= 3)
        {
            $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'") or log($mysqli_error($con));;
            $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];
            
            if($i == 1)
             $nth = "3rd";
            if($i == 2)
             $nth = "4th";
            if($i == 3)
             $nth = "5th";
             
            if($referal !== '')
            {
                $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'") or log($mysqli_error($con));;
                $row = mysqli_fetch_array($result);
                $balance = $row['BonusBalance'];
                $bal = $balance + $cost*0.001;
                $totalbonuspaid += $cost*0.001;
                
                mysqli_query($con,"UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'") or log($mysqli_error($con));;
                TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($cost * 0.001),'Receiver'=>$referal,'Sender'=>'Data Bonus','Description'=>$nth.' level Referal Bonus from data subscription by '.$username,'init_bal'=>$balance,'final_bal'=>$bal));
                }else{
                //Exit if referral is null
                $i = 6;
            }
            $i++;
        }

    }
    return $totalbonuspaid;
}
function creditTvBonus($username,$smartcardno)
{
     include "/home/daypay5/public_html/dashboard/dbcon.php";
       $log = new Logger("$basePath/cron_jobs/log.txt");
       $log->setTimestamp("D M d 'y h.i A");
       
       $log->putLog("\n Crediting Cable Bonus for $username \n");
    
    //User
    $userresult = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$username'");
    $userbalance = mysqli_fetch_array($userresult)["BonusBalance"];
    $newuserbalance = $userbalance + 30;
    mysqli_query($con,"UPDATE wallets SET BonusBalance='$newuserbalance' WHERE Username='$username'");
    TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>30,'Receiver'=>$username,'Sender'=>"Cable Bonus",'Description'=>'Credit: Bonus Cable Subscription of '.$smartcardno,'init_bal'=>$userbalance,'final_bal'=>$newuserbalance));
    
    //Referal
    $netresult = mysqli_query($con,"SELECT * FROM networks WHERE Username='$username'");
    $ref = mysqli_fetch_array($netresult)["Referal_Id"];
    $refresult = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$ref'");
    $refbalance = mysqli_fetch_array($refresult)["BonusBalance"];
    $newrefbalance = $refbalance + 20;
    mysqli_query($con,"UPDATE wallets SET BonusBalance='$newrefbalance' WHERE Username='$ref'");
    TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>20,'Receiver'=>$ref,'Sender'=>"Cable Bonus",'Description'=>'Credit: referral bonus from Cable Subscription by '.$username,'init_bal'=>$refbalance,'final_bal'=>$newrefbalance));
}


class ReQueryHandler
{
    private $vtpUsername = "daypayz.com@gmail.com";
    private $vtpPassword = "&#Ugwu@34@";
    private $lptoken = "7989d467e67f77f14405ecf11811861cebb7279c";

    private $providerUrl = [
        "DV"=>"https://digitalvendorz.com/Api/TransactionStatus.php?ref=",
        "AV"=>"",
        "VTP"=>"https://vtpass.com/api/requery",
        "MA"=>"",
        "VTL"=>"",
        "LP"=>"https://www.lintolpay.com/api/data/"
    ];

    public function DvTransactionStatus($tran_id)
    {
        $dvEnpoint = $this->providerUrl["DV"];

        $url = $dvEnpoint."$tran_id";
        
        $result = CurlCon::curl_get($url,[]);
        $response = $result['Result'];
        return $response;
    }

    public function LpTransactionStatus($tran_id)
    {
        $lpEndpoint = $this->providerUrl["LP"];

        $url = $lpEndpoint."$tran_id";
        $header = array();
        $header[]= 'Authorization: Token '.$this->lptoken;
        
        $result = CurlCon::curl_get($url,$header);
        $response = $result['Result'];
        return $response;
    }

    public function VTPTransactionStatus($tran_id)
    {
        $data = array(
            'request_id' => $tran_id // unique for every transaction from your platform
        );
        $vtpEnpoint = $this->providerUrl["VTP"];
        $result = CurlCon::curl_post_basic_auth($vtpEnpoint,'POST',$data,$this->vtpUsername,$this->vtpPassword);
        $response = $result['Result'];
        return $response;
    }

    public function AVTransactionStatus()
    {

    }

    public function MATransactionStatus()
    {
        
    }
}

class CurlCon
{
    public static function curl_post($url,$data,$headers)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);  //Post Fields
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
        $request = curl_exec ($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        
        $result = ['Result'=>$request,'Error'=>$err,'StatusCode'=>$statusCode];
        
        curl_close ($curl);
        
        return $result;
    }
    public static function curl_get($url,$headers)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
        $request = curl_exec ($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        
        $result = ['Result'=>$request,'Error'=>$err,'StatusCode'=>$statusCode];
        
        curl_close ($curl);
        
        return $result;
    }
    public static function curl_post_basic_auth($host,$method,$data,$user,$password)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_USERPWD => $user.":".$password,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
        ));
        $request = curl_exec ($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        
        $result = ['Result'=>$request,'Error'=>$err,'StatusCode'=>$statusCode];
        
        return $result;
    }
}
?>

