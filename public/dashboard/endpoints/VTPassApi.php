<?php

require_once 'HttpClient.php';
require_once './vtpass_link.php';
date_default_timezone_set("Africa/Lagos");

class VTPassApi
{
    private $user = "daypayz24@gmail.com"; //email address
    private $password = "7@7@Life@#%"; //password

    private $payflexi = 'https://vtpass.com/api/payflexi';
    private $payfix = "https://vtpass.com/api/payfix";
    private $balance = 'https://vtpass.com/api/balance';
    private $verify_merchant = " https://vtpass.com/api/merchant-verify";


    function checkBalance()
    {
        
        $curl       = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->balance,
        CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->user.":".$this->password,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
        $result = curl_exec($curl);
        $obj = json_decode($result);
        if($obj->contents->balance > $this->amount)
        {
            return true;
        }else
        {
            return false;
        }
    }

    function verifyMerchant()
    {
        
        $curl       = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->balance,
        CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->user.":".$this->password,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
        $result = curl_exec($curl);
        $obj = json_decode($result);
        if($obj->contents->balance > $this->amount)
        {
            return true;
        }else
        {
            return false;
        }
    }

    function airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id)
	{
        include('dbcon.php');
		
		
		$date = date("Ymd");
		$filename = "./logs/".$date.".txt";

        if (!file_exists($filename)) {
            $content = "Opening Log\n\n\n";
            $result = file_put_contents($filename, $content);
        } 
        
		$log = new Logger($filename);
        $log->setTimestamp("D M d 'y h.i A");
        $log->putLog("\n\n VTPass Airtime \n");
        $log->putlog("\n Request id: ".$request_id);
        
        $networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
        $transactiontype = $networkSelect[$network];
      
	    $networkid = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
	    $date = new DateTime();
        $resultF = $date->format('YmdHi');
        
	    $requestid = $resultF.(uniqid());
        $serviceid = $networkid[$network];
        
        $result = $this->payFlexi($serviceid,$amount,$mobilenumber,$requestid);
        
        $log->putlog("\n Response: ".$result);
        
        $obj = json_decode($result);
        
        $description = "(Web)".$mobilenumber." Airtime recharge of N".$amount;
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
                    VALUES('$username','$requestid','$obj->transactionId','$obj->response_description','$description','$amount','$date','VTP')");
		
           if($obj->response_description == "LOW WALLET BALANCE" || $obj->response_description == "TRANSACTION FAILED" || $obj->response_description == "SERVICE SUSPENDED" || $obj->response_description == "BELOW MINIMUM AMOUNT ALLOWED" || $obj->response_description == "LOW WALLET BALANCE")
        {
            return false;
        }
		elseif($obj->response_description == "TRANSACTION SUCCESSFUL" || $obj->response_description != "TRANSACTION FAILED")
        {
             if($network == 2)
                $cprofit = 0.005*$amount;
            else
                $cprofit = 0.01*$amount;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Airtime-$transactiontype','$amount','$cprofit','$date')");		
            
            return true;
        }else{
            return false;
        }
    }
    
    function data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$cprofit)
	{
	    include('dbcon.php');
	    if($amount == '1999')
	    {
	        $vtamount = "1999.01";
	    }else
	    {
	        $vtamount = $amount;
        }
        
	    $networkid = array("1"=>"airtel-data","3"=>"glo-data","4"=>"etisalat-data");
	    
	    $date = new DateTime();
        $resultF = $date->format('YmdHi');
        
	    $requestid = $resultF.(uniqid());
	    $serviceid=$networkid[$network];
	    
        $result = $this->payFlexi($serviceid,$vtamount,$mobilenumber,$requestid);

        $obj = json_decode($result);
        
        $description = "".$mobilenumber." Data Sunscription of ".$cost;
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
                        VALUES('$username','$requestid','$obj->transactionId','$obj->response_description','$description','$cost','$date','VTP')");
        if($obj->response_description == "TRANSACTION SUCCESSFUL")
        {
            return true;
        }else{
            return false;
        }
    }
        
    function cable($invoiceperiod,$cable,$amount,$customernum,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance,$request_id)
    {
        include('dbcon.php');
        
        $date = date("Ymd");
		$filename = "./logs/".$date.".txt";

        if (!file_exists($filename)) {
            $content = "Opening Log\n\n\n";
            $result = file_put_contents($filename, $content);
        } 
        
		$log = new Logger($filename);
        $log->setTimestamp("D M d 'y h.i A");
        $log->putLog("\n\n VTPass Cable \n");
        $log->putlog("\n Request id: ".$request_id);
        
        
        $cableid = array("01"=>"dstv","02"=>"gotv","03"=>"startimes");
        if($cable=="01")
        {
            $variationid = array("01"=>"dstv-yanga","02"=>"dstv-confam","03"=>"dstv79","04"=>"dstv7","05"=>"dstv3");
            $amountMap = ['01'=>4200,'02'=>7400,'03'=>12500,'04'=>19800,'05'=>29500];
        }
        if($cable=="02")
        {
            $variationid = array("01"=>"gotv-lite","02"=>"gotv-jinja","03"=>"gotv-jolli","04"=>"gotv-max","05"=>"gotv-supa");
            $amountMap = ['01'=>410,'02'=>2700,'03'=>3950,'04'=>5700,"05"=>"7600"];
        }
        if($cable=="03")
        {
            $variationid = array("01"=>"nova","02"=>"basic","03"=>"smart","04"=>"classic","05"=>"super");
            $amountMap = ['01'=>900,'02'=>1300,'03'=>1900,'04'=>3200,'05'=>3800];
        }
            
        
        $cost_price = $cost-50;
        
        $serviceid=$cableid[$cable];
        $billercode = $smartcardno;
        $variation_code = $variationid[$amount];
        
        $date = new DateTime();
        $resultF = $date->format('YmdHi');
        
	    $requestid = $resultF.(uniqid());

        $result = $this->payFix($serviceid,$billercode,$variation_code,"",$customernumber,$requestid);

        $log->putlog("\n Response: ".$result);
        
        $obj = json_decode($result);
            
        $description = "".$smartcardno." Cable-$variation_code Subscription of (Web) ".$amount;
        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$requestid','$obj->transactionId','$obj->response_description','$description','$cost','$date','VTP')");
        
        $GLOBALS['tid'] = $requestid;
        
                     if($obj->response_description == "LOW WALLET BALANCE" || $obj->response_description == "TRANSACTION FAILED" || $obj->response_description == "SERVICE SUSPENDED" || $obj->response_description == "BELOW MINIMUM AMOUNT ALLOWED" || $obj->response_description == "LOW WALLET BALANCE")
                     {
                         $response->isSuccessful = false;
                         $response->message = "";
                     }
        elseif($obj->response_description == "TRANSACTION SUCCESSFUL" || $obj->response_description != "TRANSACTION FAILED")
        {
            $response->isSuccessful = true;
            $response->message = "";

            $cprofit = $cost_price * 0.015;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Cable-$serviceid','$cost_price','$cprofit','$date')");

        }
        else
        {
            $response->isSuccessful = false;
            $response->message = "";
        }
        return $response;
    }

    function disco($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$request_id)
	{
        include('dbcon.php');
        
        $date = date("Ymd");
		$filename = "./logs/".$date.".txt";

        if (!file_exists($filename)) {
            $content = "Opening Log\n\n\n";
            $result = file_put_contents($filename, $content);
        } 
        
		$log = new Logger($filename);
        $log->setTimestamp("D M d 'y h.i A");
        $log->putLog("\n\n VTPass disco \n");
        $log->putlog("\n Request id: ".$request_id);
        
        $dicoMap = ["01"=>"Eko","02"=>"Ikeja","03"=>"Ibadan","04"=>"Abuja","05"=>"Portharcourt","06"=>"Kano","07"=>"EEDC","08"=>"Jos"];
        
        $electric_provider = $dicoMap[$provider];
        $providerMap = ['01'=>"eko-electric",'02'=>"ikeja-electric",'03'=>"ibadan-electric",'05'=>"portharcourt-electric",'06'=>"kano-electric",'08'=>"jos-electric",'07'=>"enugu-electric",'04'=>"abuja-electric",'09'=>'benin-electric'];
        $provider = $providerMap[$provider];

        $packageMap = ['01'=>"prepaid",'02'=>"postpaid"];
        $package = $packageMap[$package];
        
        $date = new DateTime();
        $resultF = $date->format('YmdHi');
        
	    $requestid = $resultF.(uniqid());
		
        $result = $this->payFix($provider,$meterno,$package,$amount,$customernumber,$requestid);
        
        $log->putlog("\n Response: ".$result);
        
        $obj = json_decode($result);

        $tk = $obj->payload->token;
        $tk = $tk.$obj->purchased_code;
        
        $description = "Electric-$electric_provider $meterno Sub (Web) $package";
        $date = date("Y-m-d H-i-s");

		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token)
        VALUES('$username','$requestid','$obj->transactionId','$obj->response_description','$description','$amount','$date','VTP','$tk')");
        
        $resp = new stdClass;
		if(empty($tk))
		{
		    $tk = $obj->content->error;
		    $tk = $obj->response_description;
        }
        
                     if($obj->response_description == "LOW WALLET BALANCE" || $obj->response_description == "TRANSACTION FAILED" || $obj->response_description == "SERVICE SUSPENDED" || $obj->response_description == "BELOW MINIMUM AMOUNT ALLOWED" || $obj->response_description == "LOW WALLET BALANCE")
                     {
                         $resp->tranid = $requestid;
                         $resp->provider = "VTP";
                         $resp->isSuccessful = false;
                         $resp->message = "Transaction Failed";
                     }
		elseif($obj->response_description == "TRANSACTION SUCCESSFUL" || $obj->response_description != "TRANSACTION FAILED")
		{
			$resp->tranid = $requestid;
            $resp->provider = "VTP";
            $resp->isSuccessful = true;
            $resp->token = $tk;
            $resp->package = $package;
            $resp->message = "Transaction Successful, Token: $tk";
            
            $cprofit = $amount * 0.015;
            $date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) 
            VALUES('$username','Electric-$electric_provider','$amount','$cprofit','$date')");
		}
        else
        {
            $resp->tranid = $requestid;
            $resp->provider = "VTP";
            $resp->isSuccessful = false;
            $resp->message = "Transaction Failed";
        }
        return $resp;
    }
    
    public function payFix($provider,$device_number,$package,$amount,$phone,$request_id)
	{
		$data = array(
		  	'serviceID'=> $provider, //integer e.g gotv,dstv,eko-electric,abuja-electric
		  	'billersCode'=> $device_number, // e.g smartcardNumber, meterNumber,
		  	'variation_code'=> $package, // e.g dstv1, dstv2,prepaid,(optional for somes services)
		  	'amount' =>  $amount, // integer (optional for somes services)
		  	'phone' => $phone, //integer
		  	'request_id' => $request_id // unique for every transaction from your platform
        );
        
        return CurlCon::curl_post_basic_auth($this->payfix,'POST',$data,$this->user,$this->password)['Result'];
    }
    public function verify($provider,$device_number,$package,$amount,$phone,$request_id)
	{
		$data = array(
		  	'serviceID'=> $provider, //integer e.g gotv,dstv,eko-electric,abuja-electric
		  	'billersCode'=> $device_number, // e.g smartcardNumber, meterNumber,
		  	'type'=> $package, // e.g dstv1, dstv2,prepaid,(optional for somes services)
        );
        
        return CurlCon::curl_post_basic_auth($this->payfix,'POST',$data,$this->user,$this->password)['Result'];
    }
    function payFlexi($serviceid,$amount,$mobilenumber,$requestid)
    {
        $data = array(
            'serviceID'=> $serviceid, //integer
            'amount' =>  $amount, // integer
            'phone' => $mobilenumber, //integer
            'request_id' => $requestid // unique for every transaction
          );
  
          return $result = CurlCon::curl_post_basic_auth($this->payflexi,'POST',$data,$this->user,$this->password)['Result'];
    }
    
    function generateId($username)
    {
        include('dbcon.php');
        $result1 = mysqli_query($con,"SELECT * FROM apitransactions WHERE Request_Id != '' ORDER BY Id DESC LIMIT 1");
        $id = mysqli_fetch_assoc($result1)['Request_Id'];
        $i = 1;
        while($i > 0)
        {
            $id = $id + 1;
            if(mysqli_query($con,"INSERT INTO generated_request_id (Username,Request_Id) VALUES('$username','$id')"))
            {
                $result = mysqli_query($con,"SELECT * FROM apitransactions WHERE Request_Id = '$id'");
                $i = mysqli_num_rows($result);
            }
        }
            return $id;        		        
    }
}
