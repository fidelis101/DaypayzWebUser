<?php


class ClubKonnectApi
{
    private $cableEndpoint = "https://www.nellobytesystems.com/APIBuyCableTV.asp?";
    private $dataEndpoint = "https://www.nellobytesystems.com/APIBuy.asp?";
    private $airtimeEndpoint = "https://www.nellobytesystems.com/APIBuyAirTime.asp?";

    private $UserID="CK10049011";
    private $APIKey="7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG";
    
    function airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$cost,$request_id)
	{
        include('dbcon.php');
$networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      $transactiontype = $networkSelect[$network];
        $networkCodes = array("2"=>"01","1"=>"04","4"=>"03","3"=>"02");

		$network = $networkCodes[$network];
        
		$result = curl_get_contents($this->airtimeEndpoint."UserID=$this->UserID&APIKey=$this->APIKey&MobileNetwork=".$network."&Amount=".$amount."&MobileNumber=".$mobilenumber);
        
        $obj = json_decode($result);
		
		$description = "".$mobilenumber." Airtime recharge of N".$amount;
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions(Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
        VALUES('$username','$request_id','$obj->orderid','$obj->status','$description','$amount','$date','CK')");
		
		//return true;
		
		if($obj->status == "ORDER_RECEIVED")
		{
            if($network == '03')
                $cprofit = 0.013*$amount;
            if($network == '02')
                $cprofit = 0.035*$amount;
            if($network == '04')
                $cprofit = 0.018*$amount;
            if($network == '01')
                $cprofit = 0.008*$amount;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Airtime-$transactiontype','$amount','$cprofit','$date')");				
			
			return true;
		}
        elseif($obj->status == "INVALID_MOBILENUMBER")
        {
			return false;
		}
        elseif($obj->status == "MISSING_MOBILENUMBER")
        {
			return false;
		}
        elseif($obj->status == "MISSING_AMOUNT")
        {
			return false;
		}
        else
        {
			return false;
		}
	}
	
    function data($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance,$request_id)
    {
    	include('dbcon.php');
    $networkSelect = array("1"=>"airtel", "2"=>"mtn","3"=>"glo","4"=>"etisalat");
      $transactiontype = $networkSelect[$network];
        $networkCodes = array("2"=>"01","1"=>"04","4"=>"03","3"=>"02");
		$network = $networkCodes[$network];
		
        
        
        $mtndata=array('0.5'=>350,'1SME'=>1000,'1D'=>'1500.01','2SME'=>2000,'2.5D'=>'3500.01','5SME'=>5000,'10D'=>'10000.01','22D'=>'22000.01');

        $airteldataCk=array('1.5GB'=>1500.01,'3.5GB'=>3500.01,'4.5GB'=>3500.01,'7GB'=>7000.01,'10GB'=>10000.01,'16GB'=>16000.01,'22GB'=>22000.01);
        
        $etisalatCk=array('0.5'=>500.01,'1'=>1000.01,'1.5'=>1500.01,'2.5'=>2500.01,'3'=>3000.01,'4'=>4000.01,'4.5'=>4500.01,'5.5'=>5500.01,'11.5'=>11500.01,'15'=>15000.01,'27'=>27000);
        
        $gloCk = array('2'=>1600.01,'4.5'=>3750.01,'7.2'=>5000.01,'8.75'=>6000.01,'12.5'=>8000.01,'15.6'=>12000.01,'25'=>16000.01,'52.5'=>30000.01,'62.5'=>45000.01);
        
        $amountck = "0";
               if($network =="01")
        		    {
        		        $amountck = $mtndata[$amount];
        		    }else if($network =="04")
        		    {
        		        $amountck = $airteldataCk[$amount];
        		    }else if($network =="03")
        		    {
        		        $amountck = $etisalatCk[$amount];
        		    }else if($network =="02")
        		    {
        		        $amountck = $gloCk[$amount];
        		    }

        if(!isset($amountck))
          return false;

        $result = curl_get_contents($this->dataEndpoint."UserID=".$this->UserID."&APIKey=".$this->APIKey."&MobileNetwork="
                                    .$network."&Dataplan=".$amountck."&MobileNumber=".$mobilenumber);
    
    	$obj = json_decode($result);
    		
    	$description = "".$mobilenumber." Data Subscription, Plan $amountck of N".$cost;
    	$date = date("Y-m-d H-i-s");
    	mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) 
                            VALUES('$username','$request_id','$obj->orderid','$obj->status','$description','$cost','$date','CK')");
    			
    	if($obj->status == "ORDER_RECEIVED")
    	{
            $mtnCostPrice = array('0.5'=>300,'1'=>335,'1D'=>1100,'2'=>670,'2.5D'=>2300,'5'=>1675,'10D'=>5900,'22D'=>11800);
        
            $gloCostPrice = array('2'=>920,'4.5'=>1840,'7.2'=>2300,'8.75'=>2760,'12.5'=>3680,'15.6'=>4600,'25'=>7360,'52.5'=>13800,'62.5'=>16560);
            
            $airtelCostPrice = array('1.5GB'=>950,'3.5GB'=>1900,'4.5GB'=>1900,'7'=>4750,'10'=>4750,'16'=>7600,'22'=>9500);
            
            $etisalatCostPrice = array('0.5'=>477.5,'1'=>950,'1.5'=>955,'2.5'=>1425,'4'=>1910,'4.5'=>1910,'5.5'=>3800,'11.5'=>4750,'15'=>9500,'27'=>14250);
            
            if($network =="01")
               $cprofit = $cost-$mtnCostPrice[$amount] - $cost*0.02;
            if($network =="02")
               $cprofit = $cost-$gloCostPrice[$amount] - $cost*0.02;
            if($network =="03")
               $cprofit = $cost-$etisalatCostPrice[$amount] - $cost*0.02;
            if($network =="04")
               $cprofit = $cost-$airtelCostPrice[$amount] - $cost*0.02;

            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date)VALUES('$username','Data-$transactiontype','$cost','$cprofit','$date')");
    		return true;
    	}
        elseif($obj->status == "INVALID_MOBILENUMBER")
        {
    	    return false;
    	}
        else
        {
    	    return false;
    	}
    }

    function cable($invoiceperiod,$cable,$amount,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance)
    {
        include('dbcon.php');
        
        $result =curl_get_contents($this->cableEndpoint."UserID=".$this->UserID."&APIKey=".$this->APIKey."&CableTV=$cable&Package=$amount&SmartCardNo=$smartcardno");
        
        $obj = json_decode($result);
                
        $description = "".$smartcardno." Cable Subscription";
        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO apitransactions(Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$obj->orderid','$obj->status','$description','$cost','$date','CK')");
        if($obj->status == "ORDER_RECEIVED")
        {
            return true;
        
        }else{
            return false;
        }
    }

}
