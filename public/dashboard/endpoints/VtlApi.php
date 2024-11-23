<?php

class VtlApi
{
   private $tokenEndpoint = "https://vtlhub.com/api/token/";
   private $balanceEndpoint = "https://vtlhub.com/api/bal/";

   private $airtimeEndpoint = "https://vtlhub.com/api/airtime/";
   private $dataEndpoint = "https://vtlhub.com/api/data/";
   private $discoEndpoint = "https://vtlhub.com/api/phcn/";
   private $cableEndpoint = "https://vtlhub.com/api/tv/";

   private $username = "ojobespeaks";
   private $pass = "@ugwu@34";

	public function balance($data)
    {
       $network = $data['network'];
       $number = $data['number'];
       $size = $data['size'];
       $tkn = file_get_contents($this->tokenEndpoint.$this->username."/".$this->pass);
       $balance = file_get_contents($this->balanceEndpoint."$tkn");
       return $balance;
    }

    public function airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id)
    {
      include('dbcon.php');
      $tkn = $this->token();
      $networkMap = ['2'=>'mtn','3'=>'glo','4'=>'etisalat','1'=>'airtel'];
      $networkvtl =  $networkMap[$network];
      $result = file_get_contents($this->airtimeEndpoint."$tkn/$networkvtl/$mobilenumber/$amount");
      $description = $mobilenumber." Airtime Recharge of N".$cost;
    	$date = date("Y-m-d H-i-s");
    	mysqli_query($con,"INSERT INTO apitransactions(Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
                  VALUES('$username','$request_id','','$result','$description','$cost','$date','VTL')");
    			
      if($result=="Successful")
      {
         if($network == 2)
            $cprofit = 0.01*$amount;
         else
            $cprofit = 0.01*$amount;
         
         mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Airtime','$amount','$cprofit','$date')");				
			
      	return true;
      }else
       	return false;
    }

    function data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$cprofit,$request_id)
    {
      include('dbcon.php');
      
      $mtndata=array('0.5'=>'0.5GB','1'=>'1GB','1D'=>'1500.01','2'=>'2GB','2.5D'=>'3500.01','5'=>'5GB','10D'=>'10000.01','22D'=>'22000.01');

        $airteldataCk=array('1.5'=>'1.5GB','3.5'=>'3.5GB','7'=>'7GB','10'=>'10GB','16'=>'16GB','22'=>'22GB');
        
        $etisalatCk=array('0.5'=>'0.5GB','1'=>'1GB','1.5'=>'1.5GB','2.5'=>'2.5GB','4'=>'4GB','5.5'=>'5.5GB','11.5'=>'11.5GB','15'=>'15GB','27'=>'27GB');
        
        $gloCk = array('2'=>'2GB','4.5'=>'4.5GB','7.2'=>'7.2GB','8.75'=>'8.75GB','12.5'=>'12.5GB','15.6'=>'16.6GB','25'=>'25GB','52.5'=>'52.5GB','62.5'=>'62.5GB');
      
      $networkMap = ['2'=>'mtn','3'=>'glo','4'=>'etisalat','1'=>'airtel'];
      $network = $networkMap[$network];
      
      if($network =="mtn")
      {
          $size = $mtndata[$amount];
        }
        else if($network =="airtel")
        {
          $size = $airteldataCk[$amount];
        }
        else if($network =="etisalat")
        {
           $size = $etisalatCk[$amount];
        }
        else if($network =="glo")
        {
            $size = $gloCk[$amount];
        }
        		    
      $tkn = $tkn = $this->token();
      $result = file_get_contents($this->dataEndpoint."$tkn/$network/$mobilenumber/$size");

      $description = "".$mobilenumber." Data Subscription of N".$cost;
    	$date = date("Y-m-d H-i-s");
    	mysqli_query($con,"INSERT INTO apitransactions(Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
                     VALUES('$username',$request_id,'','$result','$description','$cost','$date','VTL')");
    			
      if($result=="Successful")
      {
			//company share
		   $date = date("Y-m-d H-i-s");
         $mtnours=array('1'=>420,'2'=>840,'5'=>2100);
         if($network=="mtn")
            $cprofit = $cost-$mtnours[$amount] - $cost*0.02;
         else
            $cprofit = $cost - $cost*0.98;

         mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Data','$cost','$cprofit','$date')");
        
         return true;
      }else
       	return false;
    }

    function cable($invoiceperiod,$cable,$amount,$customernum,$customername,$smartcardno,$username,$cost,$balance,$newbalance)
    {
      include('dbcon.php');
        
      if($cable =="01")
          $amountMap = ['01'=>'access','02'=>'family','03'=>'compact','04'=>'compack plus','05'=>'premium'];
      if($cable == "02")
         $amountMap = ['01'=>'lite','02'=>'value','03'=>'plus','04'=>'max'];
      if($cable == "03")
         $amountMap = ['01'=>'nova','02'=>'basic','03'=>'smart','04'=>'classic','05'=>'super'];
        
      $cableMap = ['01'=>'dstv','02'=>'gotv','03'=>'startimes'];
      
      $service = $cableMap[$cable];
      $smartCardNo = $smartCardNo;
      $plan = $amountMap[$amount];
      $tkn = $tkn = $this->token();

      $result = file_get_contents($this->cableEndpoint."$tkn/$service/$smartCardNo/$plan");
      if($cable == "01")
      {
        $description = "".$smartcardno." DStv Subscription ".$cost;
      }
      else if($cable =="02")
      {
         $description = "".$smartcardno." GOtv Subscription ".$cost;
      }
        
      $obj = simplexml_load_string($result);
        
      $date = date("Y-m-d H-i-s");
      mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','$description','$cost','$date','VTL')");
                
      if($result=='Successful')
      {
      	return true;
      }else
       	return false;
    }

    public function disco($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance)
    {
      include('dbcon.php');
        
      $providerMap = ['05'=>"portharcourt-electric"];
      $provider = $providerMap[$provider];

      $packageMap = ['01'=>"prepaid",'02'=>"postpaid"];
      $package = $packageMap[$package];

      $tkn = $tkn = $this->token();
      $result = file_get_contents($this->discoEndpoint."$tkn/$provider/$package/$meterno/$amount");
      
      $description = "".$mobilenumber." DISCO Subscription of N".$amount." for $meterno";
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','','','$result','$description','$amount','$date','VTL','$obj->token')");
        
      if($result=='successful')
      {
      	return true;
      }else
       	return false;
    }

    public function token()
    {
       return file_get_contents($this->tokenEndpoint."$this->username/$this->pass");
    }

}