<?php

require_once 'VtlApi.php';
require_once "AirvendApi.php";
require_once "ClubKonnectApi.php";
require_once "VTPassApi.php";
require_once "MobileAirtimeApi.php";
require_once "LintolPayApi.php";
require_once("mail_notification.php");
require_once "DigitalVendorzApi.php";
require_once "./Handlers/TransactionHistoryHandler.php";
require_once "./Handlers/RefundHandler.php";

class TransactionHandler
{
    private $vtl, $vtpass, $clubkonnect, $mobileairtime, $airvend, $debtRefunder;

    public function __construct()
    {
        $this->vtl = new VtlApi;
        $this->vtpass = new VTPassApi;
        $this->clubkonnect = new ClubKonnectApi;
        $this->mobileairtime = new MobileAirtimeApi;
        $this->lintolpay = new LintolPayApi;
        $this->digitalvendorz = new DigitalVendorzApi;
        $this->airvend = new AirVendApi;
        $this->debtRefunder = new RefundHandler;
    }

    public function buyAirtime($amount, $mobilenumber, $network, $balance, $username, $interest, $newbalance, $cprofit, $cost)
    {
        include('dbcon.php');
        $request_id = uniqid();
        
        if ($network == '2') {
            $result = $this->mobileairtime->airtime($amount, $mobilenumber, $network, $balance, $username, $interest, $newbalance, $cprofit, $cost, $request_id);

            //            if(!$result)
            //            {
            //                $request_id = uniqid();
            //                $result = $this->vtl->airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id);
            //            }
            // if(!$result) 
            // $result = $this->digitalvendorz->airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id);
            if (!$result) {
                $request_id = uniqid();
                $result = $this->clubkonnect->airtime($amount, $mobilenumber, $network, $balance, $username, $interest, $newbalance, $cprofit, $cost, $request_id);
            }
        }

        if ($network == '3') {
            /*$result = $this->vtl->airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost);*/
            // $result = $this->digitalvendorz->airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost); 

            //if(!$result)
            $result = $this->clubkonnect->airtime($amount, $mobilenumber, $network, $balance, $username, $interest, $newbalance, $cprofit, $cost, $request_id);
        }

        if ($network == '1') {
            //            $result = $this->clubkonnect->airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id);
            //            
            if (!$result) {
                $request_id = uniqid();
                $result = $this->vtpass->airtime($amount, $mobilenumber, $network, $balance, $username, $interest, $newbalance, $cprofit, $cost, $request_id);
            }
            //            if(!$result)
            //            {
            //                $request_id = uniqid();
            //                $result = $this->mobileairtime->airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id);
            //            }
        }

        if ($network == '4') {
            //$result = $this->digitalvendorz->airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost); 
            //if(!$result)
            $result = $this->clubkonnect->airtime($amount, $mobilenumber, $network, $balance, $username, $interest, $newbalance, $cprofit, $cost, $request_id);

            if (!$result) {
                $request_id = uniqid();
                $result = $this->vtpass->airtime($amount, $mobilenumber, $network, $balance, $username, $interest, $newbalance, $cprofit, $cost, $request_id);
            }
        }



        if ($result) {
            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$request_id'");
            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $request_id;

            $status = $row_api["Status"];
            $provider = $row_api["Provider"];

            if ($provider == "VTP") {
                if ($status == "TRANSACTION SUCCESSFUL" || $status != "TRANSACTION FAILED")
                    $status_id = 1;
            } else {
                $status_id = 1;
            }

            $_SESSION['rechargenotice'] = "<label style='color:green;'>Transaction Successful</label>";
            $description = 'Debit: You Recharged ' . $mobilenumber;

            TransactionHistoryHandler::saveTranHistoryTran(array(
                'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $amount, 'Receiver' => $mobilenumber,
                'Sender' => $username, 'Description' => 'Debit: You Recharged ' . $mobilenumber, 'Request_Id' => $request_id, 'Tran_Id' => $tran_id, 'Status' => $status_id,
                'Transaction' => 'Airtime', 'init_bal' => $balance, 'final_bal' => $newbalance, 'Provider' => $provider
            ));

            //if($status_id != '2')
            $this->creditAirtimeBonus($username, $amount, $mobilenumber, $network);

            $description = "<p>Debit: N$amount</p><p> Description: $$description</p><p>Balance: N$newbalance</p>";
            mail_sender::send("Airtime Recharge", $description, $username);
        } else {
            $_SESSION['rechargenotice'] = "<label style='color:red;'>Connection Failed pls try again later</label>";
            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$request_id'");
            $row_api = mysqli_fetch_assoc($apitransaction);

            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $request_id;

            $provider = $row_api["Provider"];


            mysqli_query($con, "UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            TransactionHistoryHandler::saveTranHistoryTran(array(
                'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $amount, 'Receiver' => $username,
                'Sender' => 'Recharge', 'Description' => 'Refund: Airtime Recharge ' . $mobilenumber . ' failed', 'Request_Id' => $request_id, 'Tran_Id' => $tran_id,
                'Status' => '0', 'Transaction' => "Airtime", 'init_bal' => $newbalance, 'final_bal' => $balance, 'Provider' => $provider
            ));
        }
    }

    public function subData($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance)
    {
        include('dbcon.php');
        $initial_id = $request_id = uniqid();
        $corporate = false;

        TransactionHistoryHandler::saveTranHistoryTran(array(
            'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $cost, 'Receiver' => $mobilenumber,
            'Sender' => $username, 'Description' => 'Debit: Data Subscription for ' . $mobilenumber, 'Request_Id' => $initial_id, 'Tran_Id' => '', 'Status' => 0,
            'Transaction' => 'Data', 'init_bal' => $balance, 'final_bal' => $newbalance, 'Provider' => 'first'
        ));

        if ($network == '1') //airtel
        {
            $result = $this->mobileairtime->normal_data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);
            if (!$result) {
                $request_id = uniqid();
                $result = $this->digitalvendorz->data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);
            }
            // if(!$result)
            // {
            //     $request_id = uniqid();
            //     $result = $this->clubkonnect->data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$request_id);
            // }
        } elseif ($network == '2') //mtn
        {
            if (strlen($amount) < 2) {
                $corporate = true;
                $result = false;
                $result = $this->mobileairtime->data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);
            } else {
                $result = false;
                $request_id = uniqid();
                $result = $this->mobileairtime->dataSme($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);

                if (!$result) {
                    $request_id = uniqid();

                    $result = $this->digitalvendorz->data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);
                }
                if (!$result) {
                    $request_id = uniqid();
                    $result = $this->clubkonnect->data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $cprofit);
                }
            }

            // if(!$result)
            // {
            //     $request_id = uniqid();

            //     $result = $this->lintolpay->data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$request_id);

            // }
            //            if(!$result)
            //            {
            //                $request_id = uniqid();
            //
            //                $result = $this->vtl->data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$request_id);
            //                
            //            }
            //if(!$result)
            //$result = $this->clubkonnect->data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$cprofit); 
        } elseif ($network == '3') //glo
        {
            $result = $this->digitalvendorz->data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);

            if (!$result) {
                $request_id = uniqid();
                $result = $this->clubkonnect->data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);
            }
        } else {
            $result = $this->digitalvendorz->data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);

            if (!$result) {
                $request_id = uniqid();
                $result = $this->clubkonnect->data($network, $amount, $mobilenumber, $username, $cost, $balance, $newbalance, $request_id);
            }
        }


        if ($result) {
            $_SESSION['datanotice'] = "<label style='color:green;'>Transaction Successful</label>";
            $date = date("Y-m-d H-i-s");
            $date1 = date("Y-m-d");


            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$request_id'");
            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $request_id;

            $status = $row_api["Status"];
            $provider = $row_api["Provider"];

            if ($provider == "DV") {
                if ($status == "Successful")
                    $status_id = 2;
            } else {
                $status_id = 1;
            }

            mysqli_query($con, "UPDATE transactionhistory SET Request_Id='$request_id',Description='Debit: Data Subscription for $mobilenumber',
            Tran_Id='$tran_id',Status='$status_id',Transaction='Data',init_bal='$balance',final_bal='$newbalance',Provider='$provider' 
            WHERE Request_Id='$initial_id'");

            // TransactionHistoryHandler::saveTranHistoryTran(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$mobilenumber,
            // 'Sender'=>$username,'Description'=>'Debit: Data Subscription for '.$mobilenumber,'Request_Id'=>$request_id,'Tran_Id'=>$tran_id,'Status'=>$status_id,'Transaction'=>'Data','init_bal'=>$balance,'final_bal'=>$newbalance,'Provider'=>$provider));

            if ($status_id != '2')
                $this->creditDataBonus($username, $cost, $mobilenumber);
        } else {
            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$request_id'");
            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $request_id;

            $provider = $row_api["Provider"];

            mysqli_query($con, "UPDATE wallets SET Balance='$balance' WHERE Username='$username'");

            $_SESSION['datanotice'] = "<label style='color:red;'>Transaction Failed</label>";

            if ($corporate)
                $_SESSION['datanotice'] = "<label style='color:red;'>Failed, Corporate service currently down</label>";

            mysqli_query($con, "UPDATE transactionhistory SET Request_Id='$request_id',Description='Refund: Failed Data Subscription for $mobilenumber',
            Tran_Id='$tran_id',Status='0',Transaction='Data',init_bal='$newbalance',final_bal='$balance',Provider='$provider' 
            WHERE Request_Id='$initial_id'");
        }
    }

    public function subCable($invoiceperiod, $cable, $amount, $customernum, $customernumber, $customername, $smartcardno, $username, $cost, $balance, $newbalance)
    {
        include('dbcon.php');

        $requestid = uniqid();
        if ($cable == "03") {
            $result = $this->vtpass->cable($invoiceperiod, $cable, $amount, $customernum, $customernumber, $customername, $smartcardno, $username, $cost, $balance, $newbalance, $requestid);
        } else {
            $result = $this->vtpass->cable($invoiceperiod, $cable, $amount, $customernum, $customernumber, $customername, $smartcardno, $username, $cost, $balance, $newbalance, $requestid);
            // $result = $this->airvend->cable($invoiceperiod, $cable, $amount, $customernum, $customernumber, $customername, $smartcardno, $username, $cost, $balance, $newbalance, $requestid);
            // if (!$result->isSuccessful && $amount != "00") {
            //     $requestid = uniqid();
            //     $result = $this->vtpass->cable($invoiceperiod, $cable, $amount, $customernum, $customernumber, $customername, $smartcardno, $username, $cost, $balance, $newbalance, $requestid);
            // }
        }

        $reply = new stdClass;
        if ($result->isSuccessful) {
            $reply->isSuccessful = true;
            $reply->requestid = $result->requestid;
            $reply->message = "Transaction Successful";

            if ($cable == "01") {
                if ($amount == "00")
                    $description = "Debit: DStv Top up of " . ($cost - 50) . " for $smartcardno";
                else
                    $description = "Debit: DStv Subscription for $smartcardno";
            } else if ($cable == "02") {
                if ($amount == "00")
                    $description = "Debit: GOtv Top up of " . ($cost - 50) . " for $smartcardno";
                else
                    $description = "Debit: GOtv Subscription for $smartcardno";
            } else if ($cable == "03") {
                $description = "Debit: Startimes Subscription for $smartcardno";
            }

            $date = date("Y-m-d H-i-s");
            $date1 = date("Y-m-d");

            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$requestid'");
            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $requestid;

            $status = $row_api["Status"];
            $provider = $row_api["Provider"];

            if ($provider == "VTP") {
                if ($status == "TRANSACTION SUCCESSFUL")
                    $status_id = 1;
                else
                    $status_id = 2;
            } else {
                $status_id = 1;
            }

            TransactionHistoryHandler::saveTranHistoryTran(array(
                'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $cost, 'Receiver' => $smartcardno,
                'Sender' => $username, 'Description' => $description, 'Request_Id' => $requestid, 'Tran_Id' => $requestid, 'Status' => $status_id, 'Transaction' => 'Cable',
                'init_bal' => $balance, 'final_bal' => $newbalance, 'Provider' => $provider
            ));

            if ($status_id != '2')
                creditTvBonus($username, $smartcardno);
            //company profit
            //$cprofit = $avamount * 0.018;
            //mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Cable','$avamount','$cprofit','$date')");
        } else {
            mysqli_query($con, "UPDATE wallets SET Balance='$balance' WHERE Username='$username'");

            $reply->isSuccesful = false;
            $reply->requestid = $result->requestid;
            $reply->message = "Transaction Failed";

            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$requestid'");

            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $requestid;

            $provider = $row_api["Provider"];

            $description = 'Refund: Failed Cable Subscription for ' . $smartcardno;

            TransactionHistoryHandler::saveTranHistoryTran(array(
                'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $cost, 'Receiver' => $username,
                'Sender' => $smartcardno, 'Description' => $description, 'Request_Id' => $requestid, 'Tran_Id' => $tran_id, 'Status' => '0', 'Transaction' => 'Cable',
                'init_bal' => $newbalance, 'final_bal' => $balance, 'Provider' => $provider
            ));
        }

        return $reply;
    }


    public function subDisco($provider, $package, $amount, $customernumber, $meterno, $username, $balance, $newbalance)
    {
        include('dbcon.php');

        $requestid = uniqid();
        $dicoMap = ["01" => "Eko", "02" => "Ikeja", "03" => "Ibadan", "04" => "Abuja", "05" => "Portharcourt", "06" => "Kano", "07" => "EEDC", "08" => "Jos"];
        $discoProvider = $dicoMap[$provider];
        $providerCode = $provider;

        if ($provider == "05" || $provider == "06" || $provider == "08") {
            $result = $this->vtpass->disco($provider, $package, $amount, $customernumber, $meterno, $username, $balance, $newbalance, $requestid);
        } else if ($provider == "01" || $provider == "02" || $provider == "03") {
            if ($provider != "02")
                $result = $this->airvend->disco($provider, $package, $amount, $customernumber, $meterno, $username, $balance, $newbalance, $requestid);
            if (!$result->isSuccessful) {
                $requestid = uniqid();
                $result = $this->vtpass->disco($provider, $package, $amount, $customernumber, $meterno, $username, $balance, $newbalance, $requestid);
            }
        } else if ($provider == "04") {
            $result = $this->airvend->disco2($provider, $package, $amount, $customernumber, $meterno, $username, $balance, $newbalance, $requestid);
        } else {
            $result = $this->airvend->disco($provider, $package, $amount, $customernumber, $meterno, $username, $balance, $newbalance, $requestid);
        }


        if ($result->isSuccessful) {
            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$requestid'");
            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $requestid;

            $status = $row_api["Status"];
            $provider = $row_api["Provider"];

            if ($provider == "VTP") {
                if ($status == "TRANSACTION SUCCESSFUL") {
                    $status_id = '1';
                    $result->receipt = "YES";
                } else {
                    $status_id = '2';
                    $result->receipt = "NO";
                }
            } else {
                $status_id = '1';
                $result->receipt = "YES";
            }

            //if($status_id != '1')
            $host_url = $_SERVER['SERVER_NAME'];
            $tid = $result->tranid;
            $sp = $result->provider;
            $pk = $result->package;
            $receipt1 = "https://$host_url/dashboard/receipt/electric_sub.php?td=$tid&mn=$meterno&p=$providerCode&ct=$amount&pk=$pk&sp=$sp";

            $description = 'Debit: You Subscribed ' . $discoProvider . ' meter  ' . $meterno . ', Token: ' . $result->token;
            $mail_description = '<p>Debit: You Subscribed ' . $discoProvider . ' meter  ' . $meterno . '</p><p> Token: ' . $result->token . "</p> <p>Balance: N$newbalance</p><p> click the link below to download receipt  </p> <p>$receipt1</p>";

            //if($status_id == '2')
            //$description = 'Debit: You Subscribed '.$dicoMap[$provider].' meter  '.$meterno;

            TransactionHistoryHandler::saveTranHistoryTran(array(
                'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $amount, 'Receiver' => $meterno,
                'Sender' => $username, 'Description' => $description, 'Request_Id' => $requestid, 'Tran_Id' => $requestid, 'Status' => $status_id, 'Transaction' => 'Electric',
                'init_bal' => $balance, 'final_bal' => $newbalance, 'Provider' => $provider
            ));

            mail_sender::send("Disco Subscription", $mail_description, $username);
        } else {
            mysqli_query($con, "UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            $description = 'Refund: Failed ' . $dicoMap[$provider] . ' Subscription  ' . $meterno;

            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$request_id'");

            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $requestid;

            $provider = $row_api["Provider"];

            TransactionHistoryHandler::saveTranHistoryTran(array(
                'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $amount, 'Receiver' => $username,
                'Sender' => $meterno, 'Description' => $description, 'Request_Id' => $requestid, 'Tran_Id' => $requestid, 'Status' => '0', 'Transaction' => 'Electric',
                'init_bal' => $newbalance, 'final_bal' => $balance, 'Provider' => $provider
            ));
        }
        return $result;
    }

    public function BuyEducation($pintype, $pins, $cost, $username, $newbalance, $email, $balance)
    {
        include('dbcon.php');

        $requestid = uniqid();
        $eduTypes = ["01" => "Neco", "02" => "Waec"];

        if ($pintype == "01") {
            $result = $this->mobileairtime->neco($cost, $username, $newbalance, $email, $balance, $requestid);
        }
        if ($pintype == "02") {
            $result = $this->airvend->waec($pins, $cost, $username, $newbalance, $email, $balance, $requestid);
        }


        if ($result->isSuccessful) {
            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$requestid'");
            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $requestid;

            $status = $row_api["Status"];
            $provider = $row_api["Provider"];
            $status_id = '1';

            //if($status_id != '1')
            $description = 'Debit: You Purchased ' . $eduTypes[$pintype] . ' Pin(s)';

            //if($status_id == '2')
            //$description = 'Debit: You Subscribed '.$dicoMap[$provider].' meter  '.$meterno;

            TransactionHistoryHandler::saveTranHistoryTran(array(
                'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $cost, 'Receiver' => $eduTypes[$pintype],
                'Sender' => $username, 'Description' => $description, 'Request_Id' => $requestid, 'Tran_Id' => $tran_id, 'Status' => $status_id, 'Transaction' => 'Education',
                'init_bal' => $balance, 'final_bal' => $newbalance, 'Provider' => $provider
            ));

            $description = "<p>Debit: N$amount</p><p> Description: " . 'You Purchased ' . $eduTypes[$pintype] . ' Pin(s)<br/>Details: ' . $result->pins . "</p><p>Balance: N$newbalance</p>";
            mail_sender::send("$eduTypes[$pintype] Pin Purchase", $description, $username);
        } else {
            mysqli_query($con, "UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            $description = 'Refund: Failed ' . $eduTypes[$pintype] . ' Pin Purchase';

            $apitransaction = mysqli_query($con, "SELECT * FROM apitransactions WHERE Request_Id = '$requestid'");

            $row_api = mysqli_fetch_assoc($apitransaction);
            if (!empty($row_api["Transaction_Id"]))
                $tran_id = $row_api["Transaction_Id"];
            else
                $tran_id = $requestid;

            $provider = $row_api["Provider"];

            TransactionHistoryHandler::saveTranHistoryTran(array(
                'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $cost, 'Receiver' => $username,
                'Sender' => $eduTypes[$pintype], 'Description' => $description, 'Request_Id' => $requestid, 'Tran_Id' => $tran_id, 'Status' => '0', 'Transaction' => 'Education',
                'init_bal' => $newbalance, 'final_bal' => $balance, 'Provider' => $provider
            ));
        }
        return $result;
    }

    function creditAirtimeBonus($username, $amount, $number, $network)
    {
        include('dbcon.php');
        $totalbonuspaid = 0;

        $result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $balance = $row['BonusBalance'];
            $abonus = $amount * 0.02;

            $newbalance = $balance + $abonus;

            mysqli_query($con, "UPDATE wallets SET BonusBalance='$newbalance' WHERE Username='$username'");
            TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $abonus, 'Receiver' => $username, 'Sender' => '', 'Description' => "2% Bonus From Airtime Purchase N$amount ($number)", 'init_bal' => $balance, 'final_bal' => $newbalance));
        }

        $network1 = mysqli_query($con, "SELECT * FROM networks WHERE Username='$username'");
        if (mysqli_num_rows($network1) > 0) {
            //First Referral
            $row = mysqli_fetch_array($network1);
            $referal = $row['Referal_Id'];
            $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_array($result);
                $balance = $row['BonusBalance'];

                if ($network == 2) {
                    $rbonus = ($amount * 0.002);
                    $totalbonuspaid += ($amount * 0.002);
                } else {
                    $rbonus = ($amount * 0.005);
                    $totalbonuspaid += ($amount * 0.005);
                }

                $bal = $balance + $rbonus;

                mysqli_query($con, "UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
                TransactionHistoryHandler::saveTransactionHistory(array(
                    'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $amount * 0.005, 'Receiver' => $referal,
                    'Sender' => '', 'Description' => 'Credit: Direct Referal Bonus from recharge made by ' . $username, 'init_bal' => $balance, 'final_bal' => $bal
                ));

                $this->debtRefunder->refundCompanyDeptFromBonus($referal, $rbonus);
            }

            //2nd Referral
            $network1 = mysqli_query($con, "SELECT * FROM networks WHERE Username='$referal'");
            $row = mysqli_fetch_array($network1);
            $referal = $row['Referal_Id'];
            $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_array($result);
                $balance = $row['BonusBalance'];
                if ($network ==  2) {
                    $rbonus = ($amount * 0.001);
                    $totalbonuspaid += ($amount * 0.001);
                } else {
                    $rbonus = ($amount * 0.002);
                    $totalbonuspaid += ($amount * 0.002);
                }

                $bal = $balance + $rbonus;

                mysqli_query($con, "UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
                TransactionHistoryHandler::saveTransactionHistory(array(
                    'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $amount * 0.002, 'Receiver' => $referal,
                    'Sender' => '', 'Description' => 'Credit: 2nd level Referal Bonus from recharge made by ' . $username, 'init_bal' => $balance, 'final_bal' => $bal
                ));

                $this->debtRefunder->refundCompanyDeptFromBonus($referal, $rbonus);
            }

            //3rd to 7th Referral
            $i = 1;
            while ($i <= 5) {
                if ($i == 1)
                    $nth = "3rd";
                if ($i == 2)
                    $nth = "4th";
                if ($i == 3)
                    $nth = "5th";
                if ($i == 4)
                    $nth = "6th";
                if ($i == 5)
                    $nth = "7th";

                $refnetwork = mysqli_query($con, "SELECT * From networks WHERE Username='$referal'");
                $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];

                if ($referal !== '') {
                    $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
                    $row = mysqli_fetch_array($result);
                    $balance = $row['BonusBalance'];
                    if ($network ==  2) {
                        $rbonus = ($amount * 0.0005);
                        $totalbonuspaid += ($amount * 0.0005);
                    } else {
                        $rbonus = ($amount * 0.001);
                        $totalbonuspaid += ($amount * 0.001);
                    }
                    $bal = $balance + $rbonus;

                    mysqli_query($con, "UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
                    TransactionHistoryHandler::saveTransactionHistory(array(
                        'TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => $amount * 0.001, 'Receiver' => $referal,
                        'Sender' => '', 'Description' => 'Credit:' . $nth . ' level Referal Bonus from recharge made by ' . $username, 'init_bal' => $balance, 'final_bal' => $bal
                    ));

                    $this->debtRefunder->refundCompanyDeptFromBonus($referal, $rbonus);
                } else {
                    //Exit if referral is null
                    $i = 6;
                }
                $i++;
            }
        }
        return $totalbonuspaid;
    }

    function creditDataBonus($username, $cost, $number)
    {
        include('dbcon.php');
        $totalbonuspaid = 0;
        $result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $balance = $row['BonusBalance'];
            $bal = $balance + $cost * 0.01;
            $totalbonuspaid += $cost * 0.01;

            mysqli_query($con, "UPDATE wallets SET BonusBalance='$bal' WHERE Username='$username'");
            TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => ($cost * 0.01), 'Receiver' => $username, 'Sender' => '', 'Description' => "1% Bonus From Data Purchase ($number)", 'init_bal' => $balance, 'final_bal' => $bal));
        }

        $network1 = mysqli_query($con, "SELECT * FROM networks WHERE Username='$username'");
        if (mysqli_num_rows($network1) > 0) {
            //First Referral
            $row = mysqli_fetch_array($network1);
            $referal = $row['Referal_Id'];
            $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_array($result);
                $balance = $row['BonusBalance'];

                $rbonus = $cost * 0.005;
                $totalbonuspaid += $cost * 0.005;

                $bal = $balance + $rbonus;

                mysqli_query($con, "UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
                TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => ($cost * 0.005), 'Receiver' => $referal, 'Sender' => '', 'Description' => 'Direct Referal Bonus from data subscription by ' . $username, 'init_bal' => $balance, 'final_bal' => $bal));
                
                $this->debtRefunder->refundCompanyDeptFromBonus($referal, $rbonus);
            }

            //2nd Referral
            $network1 = mysqli_query($con, "SELECT * FROM networks WHERE Username='$referal'");
            $row = mysqli_fetch_array($network1);
            $referal = $row['Referal_Id'];
            $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_array($result);
                $balance = $row['BonusBalance'];

                $rbonus = $cost * 0.002;
                $totalbonuspaid += $cost * 0.002;

                $bal = $balance + $rbonus;

                mysqli_query($con, "UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
                TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => ($cost * 0.002), 'Receiver' => $referal, 'Sender' => '', 'Description' => '2nd level Referal Bonus from data subscription by ' . $username, 'init_bal' => $balance, 'final_bal' => $bal));
            
                $this->debtRefunder->refundCompanyDeptFromBonus($referal, $rbonus);
            }

            //3rd to 5th Referral
            $i = 1;
            while ($i <= 3) {
                $refnetwork = mysqli_query($con, "SELECT * From networks WHERE Username='$referal'");
                $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];

                if ($i == 1)
                    $nth = "3rd";
                if ($i == 2)
                    $nth = "4th";
                if ($i == 3)
                    $nth = "5th";

                if ($referal !== '') {
                    $result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
                    $row = mysqli_fetch_array($result);
                    $balance = $row['BonusBalance'];

                    $rbonus = $cost * 0.001;
                    $totalbonuspaid += $cost * 0.001;

                    $bal = $balance + $rbonus;

                    mysqli_query($con, "UPDATE wallets SET BonusBalance='$bal' WHERE Username='$referal'");
                    TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => ($cost * 0.001), 'Receiver' => $referal, 'Sender' => '', 'Description' => $nth . ' level Referal Bonus from data subscription by ' . $username, 'init_bal' => $balance, 'final_bal' => $bal));
                    
                    $this->debtRefunder->refundCompanyDeptFromBonus($referal, $rbonus);
                
                } else {
                    //Exit if referral is null
                    $i = 6;
                }
                $i++;
            }
        }
        return $totalbonuspaid;
    }

    function creditTvBonus($username, $smartcardno)
    {
        include('dbcon.php');

        //User
        $userresult = mysqli_query($con, "SELECT * FROM wallets WHERE Username='$username'");
        $userbalance = mysqli_fetch_array($userresult)["BonusBalance"];
        $rbonus = 30;
        $newuserbalance = $userbalance + $rbonus;
        mysqli_query($con, "UPDATE wallets SET BonusBalance='$newuserbalance' WHERE Username='$username'");
        TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => 30, 'Receiver' => $username, 'Sender' => $smartcardno, 'Description' => 'Credit: Bonus Cable Subscription of ' . $smartcardno, 'init_bal' => $userbalance, 'final_bal' => $newuserbalance));

        //Referal
        $netresult = mysqli_query($con, "SELECT * FROM networks WHERE Username='$username'");
        $ref = mysqli_fetch_array($netresult)["Referal_Id"];
        $refresult = mysqli_query($con, "SELECT * FROM wallets WHERE Username='$ref'");
        $refbalance = mysqli_fetch_array($refresult)["BonusBalance"];
        $newrefbalance = $refbalance + 20;
        mysqli_query($con, "UPDATE wallets SET BonusBalance='$newrefbalance' WHERE Username='$ref'");
        TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate' => date("Y-m-d H:i:s"), 'Amount' => 20, 'Receiver' => $ref, 'Sender' => $smartcardno, 'Description' => 'Credit: referral bonus from Cable Subscription by ' . $username, 'init_bal' => $refbalance, 'final_bal' => $newrefbalance));
    
        $this->debtRefunder->refundCompanyDeptFromBonus($ref, $rbonus);
    }
}
