<?php
require 'HttpClient.php';
require_once "./logger.php";
    
class AirvendApi
{
    private $airtimeEndpoint = "https://api.airvendng.net/se/payments/vtu/?";
    private $dstvEndpoint = "https://api.airvendng.net/vas/dstv/?";
    private $gotvEndpoint = "https://api.airvendng.net/vas/gotv/?";
    private $discoEndpoint = "https://api.airvendng.net/vas/electricity/?";
    private $disco2Endpoint = "https://api.airvendng.net/secured/seamless/vend/";
    private $waecEndpoint = "https://api.airvendng.net/vas/waec/?";
    private $verifyDiscoEndpoint = "https://api.airvendng.net/vas/electricity/verify/?";

    private $username="daypayz.com@gmail.com";
    private $password="@Ojobe52487";
    private $api_key = "CPL741704E55A7EC87490635AC4DBC5606DA0B45500CEEC64CE37A5C930D8F3E359";

    private $test = '<?xml version="1.0" encoding="UTF-8"?><VendResponse>
        <AVRef>8012389</AVRef>
        <Ref></Ref>
        <Account>54150234604</Account>
        <Amount>10</Amount>
        <ProductType>11</ProductType>
        <ResponseCode>0</ResponseCode>
        <ResponseMessage>SUCCESS</ResponseMessage>
        <Balance>331947</Balance>
        <vendData>
            <tokenAmount>0.4KWh</tokenAmount>
            <errorCode></errorCode>
            <creditToken>3935 1639 1822 2310 4341 </creditToken>
            <exchangeReference>230640512</exchangeReference>
            <tariff>0.4KWh</tariff>
            <status>ACCEPTED</status>
            <amountOfPower>0.4KWh</amountOfPower>
        </vendData>
    </VendResponse>';

    function airtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$request_id)
    {
        include('dbcon.php');

        $result = CurlCon::curl_no_auth($this->airtimeEndpoint."msisdn=".$mobilenumber."&networkid=".$network."&amount=".$amount."&type=1&username=$this->username&password=$this->password","GET");
       
        $obj = simplexml_load_string($result['Result']);
        
        $description = $mobilenumber." Airtime recharge of ".$amount;
        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider)
                            VALUES('$username','$obj->AVRef','$obj->ResponseMessage','$result','$amount','$date','AV')");
        
        if($obj->ResponseCode == "0" || $obj->ResponseMessage=="Success")
        {
            if($network == 2)
                $cprofit = 0.01*$amount;
            else
                $cprofit = 0.01*$amount;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Airtime','$amount','$cprofit','$date')");
            
            return true;
        }else{
            return false;
        }
    }
    
    function cable2($invoiceperiod,$cable,$amount,$customernum,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance,$requestid)
    {
        include('dbcon.php');
         $customername = str_replace(" ","%20",$customername);
        $cableid = array("01"=>"dstv","02"=>"gotv","03"=>"startimes");
        $cableSelected = $cableid[$cable];

        // if($cable =="01")
        //     $amountMap = ['00'=>($cost-50),'01'=>2565,'02'=>4615,'03'=>7900,'04'=>12400,'05'=>18400];
        // if($cable == "02")
        //     $amountMap = ['00'=>($cost-50),'01'=>410,'02'=>1640,'03'=>2460,'04'=>3600];
        // if($cable == "03")
        //     $amountMap = ['00'=>($cost-50),'01'=>900,'02'=>1300,'03'=>1900,'04'=>3200,'05'=>3800];
        
        //$avamount = $amountMap[$amount];
        $avamount = $cost-50;
        
        if($cable == "01")
        {
            $result = curl_get_contents($this->dstvEndpoint."username=".$this->username."&password=".$this->password."&customerNumber=".$customernum."&invoicePeriod=".$invoiceperiod."&amount=".$avamount."&customerName=".$customername);
            $description = "".$smartcardno." DStv Subscription (Web) ".$cost;
        }
        else if($cable =="02")
        {
            $result = curl_get_contents($this->gotvEndpoint."username=".$this->username."&password=".$this->password."&customerNumber=".$customernum."&invoicePeriod=".$invoiceperiod."&amount=".$avamount."&customerName=".$customername);
            $description = "".$smartcardno." GOtv Subscription (Web)".$cost;
        }
        
        $obj = simplexml_load_string($result);
        
        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO apitransactions(Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$requestid','$obj->AVRef','$obj->ResponseMessage','$description','$cost','$date','AV')");
        
        $response = new stdClass;
        if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
        {
            $response->isSuccessful = true;
            $response->message = "";

            $cprofit = $avamount * 0.015;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Cable-$cableSelected','$avamount','$cprofit','$date')");

        }
        else
        {
            $response->isSuccessful = false;
            $response->message = "";
        }
        return $response;
    }

    function cable($invoiceperiod,$cable,$amount,$customernum,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance,$requestid)
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
        $log->putLog("\n\n Airvend Cable \n");
        $log->putlog("\n Request id: ".$request_id);
        
        
         $customername = str_replace(" ","%20",$customername);
        $cableid = array("01"=>"dstv","02"=>"gotv","03"=>"startimes");
        $cableSelected = $cableid[$cable];

        // if($cable =="01")
        //     $amountMap = ['00'=>($cost-50),'01'=>2565,'02'=>4615,'03'=>7900,'04'=>12400,'05'=>18400];
        // if($cable == "02")
        //     $amountMap = ['00'=>($cost-50),'01'=>410,'02'=>1640,'03'=>2460,'04'=>3600];
        // if($cable == "03")
        //     $amountMap = ['00'=>($cost-50),'01'=>900,'02'=>1300,'03'=>1900,'04'=>3200,'05'=>3800];
        
        //$avamount = $amountMap[$amount];
        $avamount = $cost-50;
        
        if($cable == "01")
            $decoderType = 30;
        else if($cable =="02")
            $decoderType = 40;
        else if($cable =="03")
            $decoderType = 70;

        if(!isset($decoderType))
        {
            $response->isSuccessful = false;
        $response->message = "";
        }
        
        $data = [
			"details"=>[
                "ref"=>$requestid,
                "account"=>$smartcardno,
                "customername"=>$customername,
                "type"=>$decoderType,
                "invoicePeriod"=> $invoiceperiod,
                "amount"=>$avamount,
                "customernumber"=>$customernum
            ]
		];

        $requestdata = json_encode($data);
        //echo $requestdata;exit;
        
        $log->putLog("\n\n Request Data: $requestdata \n");
        
        $hashed = hash("sha512", $requestdata.$this->api_key);

		$header = array();
		$header[]= 'username: '.$this->username;
        $header[]= 'password: '.$this->password;
        $header[]= 'hash: '.$hashed;
		$header[]= 'Content-Type: application/json';

        //echo $this->username.$this->api_key.$requestdata;
        //exit;

    	$res = CurlCon::curl_post_no_auth($this->disco2Endpoint,$requestdata,$header);
        
		$result = $res['Result'];
        $log->putLog("\n\n Status Code: ".$res['StatusCode']." \n Error: ".$res['Error']."\n Transaction by: $username, \n Description: $result\n\n");

        //echo json_encode($res);exit;
    	$obj = json_decode($result);

        $tk = $obj->details->creditToken->creditToken;
        $avRef = $obj->details->TransactionID;
        $resMessage = trim($obj->details->message);
        
        $electric_provider = $dicoMap[$provider];
        $description = "Decoder-$cableSelected $smartcardno Sub (Web) $decoderType";

        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$requestid','$avRef','$resMessage','$description','$avamount','$date','AV','$tk')");
        
        $log->putLog("\n\n Transaction by: $username, Description: $result\n\n");
        
        if($obj->confirmationCode >= 400 && $obj->confirmationCode < 500)
        {
            $resp->tranid = $obj->AVRef;
            $resp->provider = "AV";
            $resp->isSuccessful = false;
            $resp->message = "Transaction Failed";
            

        }else{
            $resp->isSuccessful = true;
            $resp->message = "Transaction Successful";
            $resp->requestid = $requestid;

            $cprofit = $avamount * 0.015;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Cable-$cableSelected','$avamount','$cprofit','$date')");
        }
        return $resp;
    }

    function disco($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$requestid)
    {
        $log = new Logger("./log.txt");
        $log->setTimestamp("D M d 'y h.i A");
        include('dbcon.php');
        $dicoMap = ["01"=>"Eko","02"=>"Ikeja","03"=>"Ibadan","04"=>"Abuja","05"=>"Portharcourt","06"=>"Kano","07"=>"EEDC","08"=>"Jos"];

        if($provider == "01")
            $packageMap = ['01'=>'13','02'=>'14'];
        if($provider == "02")
            $packageMap = ['01'=>'11','02'=>'10'];
        if($provider == "03")
            $packageMap = ['01'=>'12','02'=>'23'];
        if($provider == "04")
            $packageMap = ['01'=>'24'];
        if($provider == "07")
            $packageMap = ['01'=>'21','02'=>'22'];

        $av_package = $packageMap[$package];
        

        $resulte = curl_get_contents($this->verifyDiscoEndpoint."username=".$this->username."&password=".$this->password."&account=$meterno&type=$package");

        $obj = simplexml_load_string($resulte);
        $customername = $obj->details->name;
        $customeraddress = $obj->details->address;
        $customername = str_replace(' ','%20',$customername);
        $customeraddress = str_replace(' ','%20',$customeraddress);

        if($provider == '01')
            $customername = $obj->details->customerName;

        if($provider == "01")//eko
            $result = curl_get_contents($this->discoEndpoint."username=".$this->username."&password=".$this->password
                        ."&account=$meterno&type=$av_package&amount=$amount&customernumber=$customernumber&customername=$customername");

        if($provider == "02")//ikeja
            $result = curl_get_contents($this->discoEndpoint."username=".$this->username."&password=".$this->password
                                ."&account=$meterno&type=$av_package&amount=$amount&customernumber=$customernumber&contacttype=TENANT&
                                customeraddress=$customeraddress&customername=$customername");
        
        if($provider == "03")//ibadan
            $result = curl_get_contents($this->discoEndpoint."username=".$this->username."&password=".$this->password
                        ."&account=$meterno&type=$av_package&amount=$amount&customernumber=$customernumber&customername=$customername");
        
        if($provider == "04")//abuja
            $result = curl_get_contents($this->discoEndpoint."username=".$this->username."&password=".$this->password
                        ."&account=$meterno&type=$av_package&amount=$amount&customernumber=$customernumber&customername=$customername");
        
        if($provider == "07")//eedc
        {
            $requestStr = $this->discoEndpoint."username=".$this->username."&password=".$this->password
                            ."&account=$meterno&type=$av_package&amount=$amount&customerphone=$customernumber&customername=$customername";
           $result = curl_get_contents($requestStr);                 
        }
            
                            
         $log->putLog("\n\n Transaction by: $username, Request:$requestStr Description: $result \n\n");
         
        $obj = simplexml_load_string($result);
        $tk = $obj->vendData->creditToken;
        $tk = $tk.$obj->vendData->token;
        
        $electric_provider = $dicoMap[$provider];
        $description = "Electric-$electric_provider $meterno Sub (Web) $av_package";

        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token)
                           VALUES('$username','$requestid','$obj->AVRef','$obj->ResponseMessage','$description','$amount','$date','AV','$tk')");
        
        
        
        if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
        {
            $resp->tranid = $requestid;
            $resp->provider = "AV";
            $resp->isSuccessful = true;
            $resp->token = $tk;
            $resp->package = $package;
            $resp->message = "Transaction Successful, token: $tk";

            $cprofit = $amount * 0.01;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric-$electric_provider'.$dicoMap[$provider],'$amount','$cprofit','$date')");
            
        }else{
            $resp->tranid = $obj->AVRef;
            $resp->provider = "AV";
            $resp->isSuccessful = false;
            $resp->message = "Transaction Failed";
        }
        return $resp;
    }
    
    function disco2($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$requestid)
    {
        $date = date("Ymd");
		$filename = "./logs/".$date.".txt";

        if (!file_exists($filename)) {
            $content = "Opening Log\n\n\n";
            $result = file_put_contents($filename, $content);
        } 
        
		$log = new Logger($filename);
        $log->setTimestamp("D M d 'y h.i A");
        $log->putLog("\n\n Airvend Disco \n");
        $log->putlog("\n Request id: ".$request_id);
        
        include('dbcon.php');
        $dicoMap = ["01"=>"Eko","02"=>"Ikeja","03"=>"Ibadan","04"=>"Abuja","05"=>"Portharcourt","06"=>"Kano","07"=>"EEDC","08"=>"Jos"];

        if($provider == "01")
            $packageMap = ['01'=>'13','02'=>'14'];
        if($provider == "02")
            $packageMap = ['01'=>'11','02'=>'10'];
        if($provider == "03")
            $packageMap = ['01'=>'12','02'=>'23'];
        if($provider == "04")
            $packageMap = ['01'=>'24'];
        if($provider == "07")
            $packageMap = ['01'=>'21','02'=>'22'];
            
        $av_package = $packageMap[$package];

        $data = [
			"details"=>[
                "ref"=>$requestid,
                "account"=>$meterno,
                "type"=>$av_package,
                "amount"=>$amount,
                "customerphone"=>$customernumber
            ]
		];
    	$requestdata = json_encode($data);
        
        $log->putLog("\n\n Request Data: $requestdata \n");
        
        $hashed = hash("sha512", $requestdata.$this->api_key);

		$header = array();
		$header[]= 'username: '.$this->username;
        $header[]= 'password: '.$this->password;
        $header[]= 'hash: '.$hashed;
		$header[]= 'Content-Type: application/json';

        //echo $this->username.$this->api_key.$requestdata;
        //exit;

    	$res = CurlCon::curl_post_no_auth($this->disco2Endpoint,$requestdata,$header);
        
		$result = $res['Result'];
        $log->putLog("\n\n Status Code: ".$res['StatusCode']." \n Error: ".$res['Error']."\n Transaction by: $username, \n Description: $result\n\n");

        //echo json_encode($res);exit;
    	$obj = json_decode($result);

        $tk = $obj->details->creditToken->creditToken;
        $avRef = $obj->details->TransactionID;
        $resMessage = trim($obj->details->message);
        
        $electric_provider = $dicoMap[$provider];
        $description = "Electric-$electric_provider $meterno Sub (Web) $av_package";

        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$requestid','$avRef','$resMessage','$description','$amount','$date','AV','$tk')");
        
        $log->putLog("\n\n Transaction by: $username, Description: $result\n\n");
        
        if($obj->confirmationCode >= 400 && $obj->confirmationCode < 500)
        {
            $resp->tranid = $obj->AVRef;
            $resp->provider = "AV";
            $resp->isSuccessful = false;
            $resp->message = "Transaction Failed";
            
        }else{
            $resp->tranid = $requestid;
            $resp->provider = "AV";
            $resp->isSuccessful = true;
            $resp->token = $tk;
            $resp->package = $package;
            $resp->message = "Transaction Successful, token: $tk";

            $cprofit = $amount * 0.01;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric-$electric_provider'.$dicoMap[$provider],'$amount','$cprofit','$date')");
        }
        return $resp;

    }

    function waec($pins,$cost,$username,$newbalance,$email,$balance,$requestid)
    {
        include('dbcon.php');
        $result = curl_get_contents($this->waecEndpoint."username=".$this->username."&password=".$this->password."&pins=".$pins."&pinvalue=1800&amount=".(1800 * $pins));
        
        $obj = simplexml_load_string($result);
                
        $description = "".$pins." Waec Pins ".$amount;
        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$requestid','$obj->AVRef','$obj->ResponseMessage','$description',($pins*900), '$date','AV')");
                
            
        if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
        {
            $i = 0;
            while($i < $pins)
            {
                $responseMessage = $responseMessage."<label> Serial number:</label><label style='color:green;'>".$obj->vendData->pins->pin[$i]->serialNumber." </label></br>
                <label> Pincode:</label><label style='color:green;'>".$obj->vendData->pins->pin[$i]->pinCode." </label>
                </br></br>";
                   
                   $desc = $desc." Serial number: ".$obj->vendData->pins->pin[$i]->serialNumber." Pincode: ".$obj->vendData->pins->pin[$i]->pinCode. ",";
                   $i++;
            }
            $resp->tranid = $obj->AVRef;
            $resp->provider = "AV";
            $resp->isSuccessful = true;
            $resp->pins = $desc;
            $resp->package = $av_package;
            $resp->message = $responseMessage;

            $cprofit = $pins * 20;
            $am = $pins*1800;
            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Waec','$am','$cprofit','$date')");
            
            $date = date("Y-m-d H-i-s");
            mysqli_query($con,"INSERT INTO waecpins (Username,Pins,Description,TransactionDate,Email) VALUES('$username','$pins','$desc','$date','$email')");
                //company share
            
        }
        else{
            $resp->tranid = $obj->AVRef;
            $resp->provider = "AV";
            $resp->isSuccessful = false;
            $resp->message = "Transaction Failed";
        }
        return $resp;
    }
}
