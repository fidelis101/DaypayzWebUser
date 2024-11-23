<?php
require_once "./logger.php";

class MobileAirtimeApi
{
    private $dataEndpoint = "https://mobileairtimeng.com/httpapi/cdatashare?";
    private $dataSMEEndpoint = "https://mobileairtimeng.com/httpapi/datashare?";
    private $airtimeEndpoint = "https://mobileairtimeng.com/httpapi/?";
    private $normalDataEndpoint = "https://mobileairtimeng.com/httpapi/datatopup.php?";
    
    private $necoRCEndpoint = "https://mobileairtimeng.com/httpapi/neco?";
    private $userid="08064535577";
    private $pass="4b6afee34b42c0fe948e2f5";
    
    public function airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id)
    {
      include('dbcon.php');
      $networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      $transactiontype = $networkSelect[$network];
    	$networkMap = ['2'=>'15','3'=>'6','4'=>'2','1'=>'1'];
        $network = $networkMap[$network];
		
    	$result = curl_get_contents($this->airtimeEndpoint."userid=$this->userid&pass=$this->pass&network=$network&phone=$mobilenumber&amt=$amount&jsn=json&user_ref=$request_id");
    	
    	$obj = json_decode($result);
    	
    	$description = $mobilenumber." Airtime Recharge of N".$cost;
		$date = date("Y-m-d H-i-s");
		$tran = $obj->batchno;
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
							VALUES('$username','$request_id','$tran','$obj->message','$description','$cost','$date','MA')");
	
		//0.032 paid bonus
    	if($obj->message == "Recharge successful" || $obj->code == 100)
    	{
         if($network == 15)
            $cprofit = 0.005*$amount;
         else
            $cprofit = 0.01*$amount;
         
         mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Airtime-$transactiontype','$amount','$cprofit','$date')");				
			
      	return true;
      }else
       	return false;
    }
    
    function data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$request_id)
    {
		include('dbcon.php');
		$networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      $transactiontype = $networkSelect[$network];
    	$networkMap = ['2'=>1];
        $network = $networkMap[$network];
        
        $mtndata=array('0.5'=>500,'1'=>1000,'1D'=>'1500.01','2'=>2000,'2.5D'=>'3500.01','3'=>3000,'5'=>5000,'10D'=>'10000.01','22D'=>'22000.01');
        
        $amount = $mtndata[$amount];
        
    	$result = curl_get_contents($this->dataEndpoint."userid=$this->userid&pass=$this->pass&network=$network&phone=$mobilenumber&datasize=$amount&jsn=json&user_ref=$request_id");
    
    	$obj = json_decode($result);
    	
    	$description = "".$mobilenumber." Data Subscription of N".$cost;
		$date = date("Y-m-d H-i-s");
		$tran = $obj->batchno;
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
					VALUES('$username','$request_id','$tran','$obj->message','$description','$cost','$date','MA')");
    		
    	if($obj->message == "Data recharge completed" || $obj->code == 100)
    	{
			//company share
			$date = date("Y-m-d H-i-s");
// 			$mtnours=array('500'=>150,'1000'=>240,'2000'=>480,'3000'=>720,'5000'=>1200);

//$mtnours=array('500'=>150,'1000'=>230,'2000'=>460,'3000'=>690,'5000'=>1150);

//$mtnours=array('500'=>135,'1000'=>229,'2000'=>448,'3000'=>687,'5000'=>1145);

// $mtnours=array('500'=>109,'1000'=>217,'2000'=>422,'3000'=>633,'5000'=>1055,'10000'=>2110);

$mtnours=array('500MB'=>165,'1GB'=>280,'2GB'=>560,'3GB'=>840,'5GB'=>1400,'10GB'=>2800);
			if($network == 1)
				$cprofit = $cost-$mtnours[$amount] - $cost*0.02;
			else
				$cprofit = $cost - $cost*0.98;
				
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Data-$transactiontype','$cost','$cprofit','$date')");
			
			return true;
    	}
    	else{
    	    return false;
    	}
    }
    
    function normal_data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$request_id)
    {
		include('dbcon.php');
		$networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      $transactiontype = $networkSelect[$network];
    	$networkMap = ['2'=>'15','3'=>'6','4'=>'2','1'=>'1'];
        $network = $networkMap[$network];
        
        
        if($network==1 || $network==6 || $network==2)
        $package = $amount;
        
        if($network == 1)
        $result = curl_get_contents($this->normalDataEndpoint."userid=$this->userid&pass=$this->pass&network=$network&phone=$mobilenumber&product=$package&jsn=json&user_ref=$request_id");
        else
    	$result = curl_get_contents($this->normalDataEndpoint."userid=$this->userid&pass=$this->pass&network=$network&phone=$mobilenumber&amt=$package&jsn=json&user_ref=$request_id");
        
    	$obj = json_decode($result);
    	
    	$description = "".$mobilenumber." Data Subscription of N".$cost;
		$date = date("Y-m-d H-i-s");
		$tran = $obj->batchno;
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
					VALUES('$username','$request_id','$tran','$obj->message','$description','$cost','$date','MA')");
    		
    	if($obj->message == "Recharge successful" || $obj->code == 100)
    	{
			//company share
			$date = date("Y-m-d H-i-s");
            // $airtelCostPrice = ["40MBD"=>50,"100MBD"=>100,"200MBD"=>200,"750MBD"=>500,"6GBW"=>1500,"1GBW"=>500,"2GBD"=>500,"1GBD"=>300,"350MBD"=>300,"1.5GB"=>1000,"2GB"=>1200,"3GB"=>1500,"4.5GB"=>2000,"6GB"=>2500,"8GB"=>3000,"11GB"=>4000,"15GB"=>5000,"40GB"=>10000,"75GB"=>15000,"110GB"=>20000,"200GB"=>30000];

			if($network == 1)
				$cprofit = $cost*0.0197;
			if($network == 6)
				$cprofit = $cost*0.04;
			if($network == 2)
				$cprofit = $cost*0.06;
				
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Data-$transactiontype','$cost','$cprofit','$date')");
			
			return true;
    	}
    	else{
    	    return false;
    	}
    }
    
    function dataSme($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$request_id)
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
        $log->putLog("\n\n Mobile Airtime Data \n");
        $log->putlog("\n Request id: ".$request_id);
        
		$networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      $transactiontype = $networkSelect[$network];
    	$networkMap = ['2'=>1];
        $network = $networkMap[$network];
        
        $mtndata=array('0.5SME'=>500,'1SME'=>1000,'1D'=>'1500.01','2SME'=>2000,'2.5D'=>'3500.01','3SME'=>3000,'5SME'=>5000,'10SME'=>10000,'10D'=>'10000.01','22D'=>'22000.01');
        
        $amount = $mtndata[$amount];
        
        $log->putlog("\n Request: ".$this->dataSMEEndpoint."network=$network&phone=$mobilenumber&datasize=$amount&jsn=json&user_ref=$request_id");
         
    	$result = curl_get_contents($this->dataSMEEndpoint."userid=$this->userid&pass=$this->pass&network=$network&phone=$mobilenumber&datasize=$amount&jsn=json&user_ref=$request_id");
    	
    	$log->putlog("\n Api Response: ".$result."\n");
    
    	$obj = json_decode($result);
    	
    	$description = "".$mobilenumber." Data Subscription of N".$cost;
		$date = date("Y-m-d H-i-s");
		$tran = $obj->batchno;
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
					VALUES('$username','$request_id','$tran','$obj->message','$description','$cost','$date','MA')");
    		
    	if($obj->message == "Data recharge completed" || $obj->code == 100)
    	{
			//company share
			$date = date("Y-m-d H-i-s");
            //$mtnours=array('500'=>150,'1000'=>240,'2000'=>480,'3000'=>720,'5000'=>1200);

    //$mtnours=array('500'=>135,'1000'=>229,'2000'=>448,'3000'=>687,'5000'=>1145);

        $mtnours=array('500'=>132,'1000'=>260,'2000'=>540,'3000'=>780,'5000'=>1300,'10000'=>2600);
			if($network == 1)
				$cprofit = $cost-$mtnours[$amount] - $cost*0.02;
			else
				$cprofit = $cost - $cost*0.98;
				
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Data-$transactiontype','$cost','$cprofit','$date')");
			
			return true;
    	}
    	else{
    	    return false;
    	}
    }
    
    
    public function neco($cost,$username,$newbalance,$email,$balance,$request_id)
    {
        include('dbcon.php');
      
        $result = curl_get_contents($this->necoRCEndpoint."userid=$this->userid&pass=$this->pass&jsn=json&user_ref=$request_id");

        $obj = json_decode($result);
        $description = $mobilenumber." Neco Result Checking PIN N".$cost;
        $date = date("Y-m-d H-i-s");
        $tran = $obj->batchno;
        mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost,Transaction_Date,Provider,Token) 
            VALUES('$username','$request_id','$tran','$obj->pin','$description','$cost','$date','MA','$obj->pin')");
      
        if($obj->code == 100)
        {
           
              $responseMessage ="<label> Pin:</label><label style='color:green;'>".$obj->pin." </label>";

              $resp->tranid = $obj->batchno;
              $resp->provider = "MA";
              $resp->isSuccessful = true;
              $resp->pins = $obj->pin;
              $resp->package = $av_package;
              $resp->message = $responseMessage;
          
              mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Neco','57','$cprofit','$date')");
            
             
        }else{
            $resp->tranid = $obj->batchno;
            $resp->provider = "MA";
            $resp->isSuccessful = false;
            $resp->message = "Transaction Failed";
        }
        return $resp; 
    }
}
