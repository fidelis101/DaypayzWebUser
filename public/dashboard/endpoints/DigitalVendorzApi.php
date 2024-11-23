<?php
require_once 'HttpClient.php';

class DigitalVendorzApi
{
	private $dataEndpoint = "https://api3.digitalvendorz.com/api/data";
	private $airtimeEndpoint = "https://api3.digitalvendorz.com/api/airtime";
	private $discoEndpoint = "https://api3.digitalvendorz.com/api/disco";
	private $verifyDiscoEndpoint = "https://api3.digitalvendorz.com/api/disco/verify";
	private $decoderEndpoint = "https://api3.digitalvendorz.com/api/decoder";
	private $loginEndpoint = "https://api3.digitalvendorz.com/api/auth/login";
	private $dataPackagesEndpoint = "https://api3.digitalvendorz.com/api/data/packages/";
	private $username = "daypayz";
	private $password = "Ojobe%#@7u";
    
    function data($network,$amount,$mobilenumber,$username,$cost,$request_id)
    { 
        $log = new Logger("./log.txt");
        $log->setTimestamp("D M d 'y h.i A");
		include('dbcon.php');
		$networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      	$transactiontype = $networkSelect[$network];
    	$networkMap = ['2'=>"MTN",'1'=>"AIRTEL",'3'=>"GLO","4"=>"9MOBILE"];
        $network = $networkMap[$network];
        
        $dvAmountMap=array('0.5SME'=>"500MB",'1SME'=>"1GB",'2SME'=>"2GB",'3SME'=>"3GB",'5SME'=>"5GB");
        
		$amountdv = $dvAmountMap[$amount];

		if(empty($amountdv))
			return false;
    	
    	$data = [
			"network"=>$network,
			"number"=>$mobilenumber,
			"amount"=>$amountdv
		];
    	$requestdata = json_encode($data);
		$header = array();
		$header[]= 'Authorization: Bearer '.$this->GetToken();
		$header[]= 'Content-Type: application/json';
		
    	$res = CurlCon::curl_post_no_auth($this->dataEndpoint,$requestdata,$header);
		$result = $res['Result'];
		
		$log->putLog("\n\n Transaction by: $username, Request:$requestdata Description: $result \n\n");
    
    	$obj = json_decode($result);
    	
    	$description = "".$mobilenumber." Data Subscription of N".$cost;
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
        VALUES('$username','$request_id','$obj->id','$obj->responseMessage','$description','$cost','$date','DV')") or die(mysqli_error($con));
    		
    	if($obj->isSuccessful)
    	{
			//company share
			$date = date("Y-m-d H-i-s");
			//$mtnours=array('500MB'=>150,'1GB'=>230,'2GB'=>460,'3GB'=>690,'5GB'=>1150);
			
			$mtnours=array("500MB"=>145,"1GB"=>283,"2GB"=>566,"3GB"=>849,"5GB"=>1415,"10GB"=>2830);
            
            $airtelCostPrice = ["1.5GB"=>1000,"2GB"=>1200,"3GB"=>1500,"4.5GB"=>2000,"6GB"=>2500,"8GB"=>3000,"11GB"=>4000,"6GBW"=>1500,"1GBW"=>500,
            "2GBD"=>500,"1GBD"=>300,"350MBD"=>300,"110GB"=>20000,"75MBD"=>100,"75GB"=>15000,"40GB"=>10000,"15GB"=>5000];
			
			$etisalatCostPrice = ["25MBD"=>50,"100MBD"=>100,"250MBW"=>200,"650MBD"=>200,"1GBD"=>300,"500MB"=>500,"2GB3D"=>500,
			"1.5GB"=>1000,"3GB"=>1500,"7GBW"=>1500,"4.5GB"=>2000,"11GB"=>4000,"15GB"=>5000,"40GB"=>10000,"75GB"=>15000];
			

			$gloCostPrice = array("0.8"=>460,'2'=>920,"3.5"=>1380,'4.5'=>1840,'7.2'=>2300,'8.75'=>2760,'12.5'=>3680);
            
			if($network == "MTN")
			{
				if($amount == "1" || $amount == "2" ||$amount == "3" ||$amount == "5" || $amount == "0.5" || $amount == "0.5SME" || $amount == "1SME" || $amount == "2SME" ||$amount == "3SME" ||$amount == "5SME")
				$cprofit = $cost-$mtnours[$amountdv] - $cost*0.02;
				else
				$cprofit = $cost * 0.05;	
			}
			if($network =="ETISALAT")
				$cprofit = $etisalatCostPrice[$amountdv] * 0.03;
			if($network =="AIRTEL")
				$cprofit = $cost * 0.02;
			if($network =="GLO")
				$cprofit = $cost-$gloCostPrice[$amount] - $cost*0.02;
				
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Data-$transactiontype','$cost','$cprofit','$date')");
			
			return true;
    	}
    	else{
    	    return false;
    	}
    }
   
	function airtime($amount,$mobilenumber,$network,$username,$cprofit,$request_id)
	{
        include('dbcon.php');
		$networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      $transactiontype = $networkSelect[$network];
	  $networkMap = ['2'=>"MTN",'1'=>"AIRTEL",'3'=>"GLO","4"=>"9MOBILE"];
	  $network = $networkMap[$network];
	  
	  $data = [
			"network"=>$network,
			"number"=>$mobilenumber,
			"amount"=>$amount
		];
    	$requestdata = json_encode($data);
		$header = array();
		$header[]= 'Authorization: Bearer '.$this->GetToken();
		$header[]= 'Content-Type: application/json';
		
    	$res = CurlCon::curl_post_no_auth($this->airtimeEndpoint,$requestdata,$header);
		$result = $res['Result'];

    
        $obj = json_decode($result);
		
		$description = "".$mobilenumber." Airtime recharge of N".$amount;
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO ApiTransactions(Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$request_id','$obj->id','$obj->responseMessage','$description','$amount','$date','DV')");
		
		if($obj->isSuccessful)
    	{
            if($network == 'ETISALAT')
                $cprofit = 0.0285*$amount;
            if($network == 'GLO')
                $cprofit = 0.0185*$amount;
            if($network == 'AIRTEL')
                $cprofit = 0.008*$amount;
            if($network == 'MTN')
                $cprofit = 0.008*$amount;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Airtime-$transactiontype','$amount','$cprofit','$date')");				
			
			return true;
		}
        else
        {
			return false;
		}
	}

	function cable($cable,$amount,$smartcardno,$username,$cost,$requestid)
    {
        include('dbcon.php');
        
        $cableid = array("01"=>"dstv","02"=>"gotv","03"=>"startimes");
        if($cable=="01")
        {
            $variationid = array("01"=>"NNJ1E36","02"=>"NNJ2E36","03"=>"dstv79","04"=>"dstv7","05"=>"dstv3");
            $amountMap = ['01'=>2565,'02'=>4615,'03'=>6975,'04'=>10925,'05'=>16200];
        }
        if($cable=="02")
        {
            $variationid = array("01"=>"gotv-lite","02"=>"GOTVNJ1","03"=>"GOTVNJ2","04"=>"GOTVMAX");
            $amountMap = ['01'=>410,'02'=>1640,'03'=>2460,'04'=>3280];
        }
        if($cable=="03")
        {
            $variationid = array("01"=>"NOVA","02"=>"BASIC","03"=>"SMART","04"=>"CLASSIC","05"=>"SUPER");
            $amountMap = ['01'=>900,'02'=>1300,'03'=>1900,'04'=>3200,'05'=>3800];
        }
            
        
        $cost_price = $amountMap[$amount];
        
        $variation_code = $variationid[$amount];

        $data = [
			"product"=>$cableid[$cable],
			"card_number"=>$smartcardno,
			"plan"=>$variation_code
		];
    	$requestdata = json_encode($data);
		$header = array();
		$header[]= 'Authorization: Bearer '.$this->GetToken();
		$header[]= 'Content-Type: application/json';
		
    	$res = CurlCon::curl_post_no_auth($this->decoderEndpoint,$requestdata,$header);
		$result = $res['Result'];

        $obj = json_decode($result);
            
        $description = "".$smartcardno." Cable-$variation_code Subscription of (Web) ".$amount;
        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO ApiTransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$requestid','$obj->id','$obj->responseMessage','$description','$cost','$date','DV')");
        
        $GLOBALS['tid'] = $requestid;
     
        if($obj->isSuccessful)
        {
            $response->isSuccessful = true;
            $response->message = "";

            $cprofit = $cost_price * 0.015;
            //mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Cable-$serviceid','$cost_price','$cprofit','$date')");

        }
        else
        {
            $response->isSuccessful = false;
            $response->message = "";
        }
        return $response;
    }

	function disco($provider,$package,$amount,$meterno,$username,$request_id)
	{
        include('dbcon.php');
        $dicoMap = ["01"=>"Eko","02"=>"Ikeja","03"=>"Ibadan","04"=>"Abuja","05"=>"Portharcourt","06"=>"Kano","07"=>"EEDC","08"=>"Jos","09"=>"Kaduna"];
        
        $electric_provider = $dicoMap[$provider];
        $providerMap = ['01'=>"EKO",'02'=>"IKEJA",'03'=>"IBADAN","04"=>"ABUJA",'05'=>"PH",'06'=>"KANO","07"=>"EEDC",'08'=>"JOS","09"=>"KADUNA"];
        $provider = $providerMap[$provider];

        $packageMap = ['01'=>"prepaid",'02'=>"postpaid"];
        $package = $packageMap[$package];
    
		
        $data = [
			"disco"=>$provider,
			"meter_number"=>$meterno,
			"amount"=>$amount
		];
    	$requestdata = json_encode($data);
		$header = array();
		$header[]= 'Authorization: Bearer '.$this->GetToken();
		$header[]= 'Content-Type: application/json';
		
    	$res = CurlCon::curl_post_no_auth($this->discoEndpoint,$requestdata,$header);
		$result = $res['Result'];

        $obj = json_decode($result);

        $tk = $obj->responseData->token;
        
        $description = "Electric-$electric_provider $meterno Sub (Web) $package";
        $date = date("Y-m-d H-i-s");

		mysqli_query($con,"INSERT INTO ApiTransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token)
        VALUES('$username','$request_id','$request_id','$obj->responseMessage','$description','$amount','$date','DV','$tk')");
        
        $resp = new stdClass;
		
        
        if($obj->isSuccessful)
		{
			$resp->tranid = $request_id;
            $resp->provider = "DV";
            $resp->isSuccessful = true;
            $resp->token = $tk;
            $resp->package = $package;
            $resp->message = "Transaction Successful, Token: $tk";
            
            $cprofit = $amount * 0.015;
            $date = date("Y-m-d H-i-s");
			//mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) 
            //VALUES('$username','Electric-$electric_provider','$amount','$cprofit','$date')");
		}
        else
        {
            $resp->tranid = $request_id;
            $resp->provider = "DV";
            $resp->isSuccessful = false;
            $resp->message = "Transaction Failed";
        }
        return $resp;
    }

	function verifyDisco($provider,$meterno)
	{
        $providerMap = ['01'=>"EKO",'02'=>"IKEJA",'03'=>"IBADAN","04"=>"ABUJA",'05'=>"PH",'06'=>"KANO","07"=>"EEDC",'08'=>"JOS","09"=>"KADUNA"];
        $provider = $providerMap[$provider];
		
        $data = [
			"disco"=>$provider,
			"meter_number"=>$meterno
		];
    	$requestdata = json_encode($data);
		$header = array();
		$header[]= 'Authorization: Bearer '.$this->GetToken();
		$header[]= 'Content-Type: application/json';
		
    	$res = CurlCon::curl_post_no_auth($this->verifyDiscoEndpoint,$requestdata,$header);
		$result = $res['Result'];

        $obj = json_decode($result);

        return $obj;
    }

	// function getDataPackages($network)
	// {
	// 	$header = array();
	// 	$header[]= 'Authorization: Bearer '.$this->GetToken();
	// 	$header[]= 'Content-Type: application/json';
		
    // 	$res = CurlCon::curl_get($this->dataPackagesEndpoint.$network,$header);
	// 	$result = $res['Result'];

    //     $obj = json_decode($result);

    //     return $obj;
    // }

	public function GetToken()
    {
        $log = new Logger("./log.txt");
        $log->setTimestamp("D M d 'y h.i A");
        $data = new \stdClass();
        $data->username = $this->username;
        $data->password = $this->password;
        $requestModel = json_encode($data);

        //$log->putLog("\n\n Getting Token data: $requestModel \n\n");

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $response = CurlCon::curl_post($this->loginEndpoint, $requestModel, $headr);

		//echo $response["Result"]; exit;
        $log->putLog("\n\n Getting Token Response: ".$response["Result"]." \n\n");

        $responseModel = json_decode($response["Result"]);
        $status = isset($responseModel->responseCode)?$responseModel->responseCode:"";
        if ($status == "00")
            return $responseModel->token;
        else
            return "";
    }
}
