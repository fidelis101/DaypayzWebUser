<?php
require_once 'HttpClient.php';
class LintolPayApi
{
	private $dataEndpoint = "https://www.lintolpay.com/api/data/";
	private $airtimeEndpoint = "https://digitalvendorz.com/Api/VendData.php?";
     private $token = "7989d467e67f77f14405ecf11811861cebb7279c";
    
    function data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$request_id)
    {
		include('dbcon.php');
		$networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      	$transactiontype = $networkSelect[$network];
    	$networkMap = ['2'=>"1",'1'=>"4",'3'=>"2","4"=>"3"];
        $networklp = $networkMap[$network];
		
		$mtndata=array('0.5'=>"69",'1'=>"63",'1D'=>'1500.01','2'=>"49",'3'=>"50",'2.5D'=>'3500.01','5'=>"43",'10D'=>'10000.01','22D'=>'22000.01');
		
		//$airteldata=array('1.5'=>'1.5GB','4.5'=>'4.5GB','5'=>'5GB','9'=>'9GB','10'=>"10GB",'16'=>"16GB",'22'=>"22GB",'30GB');

		$glodata = array('0.8'=>'800MB','2'=>'2GB','3.5'=>'3.5GB','4.5'=>'4.5GB','7.2'=>'7.2GB','8.75'=>'8.78GB','12.5'=>'12.5GB');

		$etisalat=array('0.025D'=>'25MBD','0.1D'=>'100MBD','0.25W'=>'250MBW','0.65D'=>'650MBD','1D'=>'1GBD','2-3D'=>'2GB3D','7W'=>'7GBW','0.5'=>"500MB",
						'1.5'=>"1.5GB",'2'=>"2GB",'3'=>"3GB",'4.5'=>"4.5GB",'11'=>"11GB",'15'=>'15GB','40'=>'40GB','75'=>'75GB');
         
		if($network == "2")
		{
			if($amount == 1 || $amount == 2 ||$amount == 3 ||$amount == 5 || $amount == 0.5)
			$amountlp = $mtndata[$amount];
			else
			return false;	
		}
		if($network == "1")
			$amountlp = $amount;		
		if($network == "4")
			$amountlp = $etisalat[$amount];
		if($network == "3")
			$amountlp = $glodata[$amount]; 
			
		if(empty($amountlp))
			return false;

		$data = [
			"network"=>$networklp,
			"mobile_number"=>$mobilenumber,
			"plan"=>$amountlp
		];
		$requestdata = json_encode($data);
		$header = array();
		$header[]= 'Authorization: Token '.$this->token;
		$header[]= 'Content-Type: application/json';
		
    	$result = CurlCon::curl_post_no_auth($this->dataEndpoint,$requestdata,$header);
		$res = $result['Result'];
    	$obj = json_decode($res);
		$stat = isset($obj->Status)?$obj->Status:$res;

    	$description = "".$mobilenumber." Data Subscription of N".$cost;
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
        VALUES('$username','$request_id','$obj->id','$stat','$description','$cost','$date','LP')") or die(mysqli_error($con));
    		
    	if($obj->Status == "processing" || $obj->Status == "successful")
    	{
			//company share
			$date = date("Y-m-d H-i-s");
			$mtnours=array('0.5'=>150,'1'=>260,'2'=>520,'3'=>780,'5'=>1300);
            
            $airtelCostPrice = ["1.5GB"=>1000,"2GB"=>1200,"3GB"=>1500,"4.5GB"=>2000,"6GB"=>2500,"8GB"=>3000,"11GB"=>4000,"6GBW"=>1500,"1GBW"=>500,
            "2GBD"=>500,"1GBD"=>300,"350MBD"=>300,"110GB"=>20000,"75MBD"=>100,"75GB"=>15000,"40GB"=>10000,"15GB"=>5000];
			
			$etisalatCostPrice = ["25MBD"=>50,"100MBD"=>100,"250MBW"=>200,"650MBD"=>200,"1GBD"=>300,"500MB"=>500,"2GB3D"=>500,
			"1.5GB"=>1000,"3GB"=>1500,"7GBW"=>1500,"4.5GB"=>2000,"11GB"=>4000,"15GB"=>5000,"40GB"=>10000,"75GB"=>15000];
			

			$gloCostPrice = array("0.8"=>460,'2'=>920,"3.5"=>1380,'4.5'=>1840,'7.2'=>2300,'8.75'=>2760,'12.5'=>3680);
            
			if($network == "MTN")
			{
				if($amount == 1 || $amount == 2 ||$amount == 3 ||$amount == 5 || $amount == 0.5)
				$cprofit = $cost-$mtnours[$amount] - $cost*0.02;
				else
				$cprofit = $cost * 0.05;	
			}
			if($network =="ETISALAT")
				$cprofit = $etisalatCostPrice[$amountlp] * 0.05;
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
   
	function airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id)
	{
        include('dbcon.php');
		$networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      $transactiontype = $networkSelect[$network];
	  $networkMap = ['2'=>"MTN",'1'=>"AIRTEL",'3'=>"GLO","4"=>"ETISALAT"];
	  $network = $networkMap[$network];;
	   
	  $result = curl_get_contents($this->airtimeEndpoint."username=$this->username&password=$this->password&network=$network&number=$mobilenumber&amount=$amount");
    
        $obj = json_decode($result);
		
		$description = "".$mobilenumber." Airtime recharge of N".$amount;
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions(Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
						VALUES('$username',$request_id','$obj->id','$obj->message','$description','$amount','$date','LP')");
		
		if($obj->message == "Successful")
    	{
            if($network == 'ETISALAT')
                $cprofit = 0.013*$amount;
            if($network == 'GLO')
                $cprofit = 0.035*$amount;
            if($network == 'AIRTEL')
                $cprofit = 0.018*$amount;
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
}