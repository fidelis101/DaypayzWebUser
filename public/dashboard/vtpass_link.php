<?php
class vtpass_api
{

	static public function payFlexi($provider,$amount,$phone,$request_id)
	{
		
		$username = "daypayz.com@gmail.com";
		$password = "7@7@Life@#%";
		$host = 'https://vtpass.com/api/payflexi';
		$data = array(
		  	'serviceID'=> $provider, //integer e.g mtn,airtel
		  	'amount' =>  $amount, // integer
		  	'phone' => $phone, //integer
		  	'request_id' => $request_id // unique for every transaction from your platform
		);
		$curl       = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $host,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_USERPWD => $username.":" .$password,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $data,
		));
		$result = curl_exec($curl);
		return $result;
	}
	
	static public function payFix($provider,$device_number,$package,$amount,$phone,$request_id)
	{
		$username = "daypayz.com@gmail.com";
		$password = "7@7@Life@#%";
		$host = 'https://vtpass.com/api/payfix';
		$data = array(
		  	'serviceID'=> $provider, //integer e.g gotv,dstv,eko-electric,abuja-electric
		  	'billersCode'=> $device_number, // e.g smartcardNumber, meterNumber,
		  	'variation_code'=> $package, // e.g dstv1, dstv2,prepaid,(optional for somes services)
		  	'amount' =>  $amount, // integer (optional for somes services)
		  	'phone' => $phone, //integer
		  	'request_id' => $request_id // unique for every transaction from your platform
		);
		$curl       = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $host,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_USERPWD => $username.":" .$password,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $data,
		));
		
		return curl_exec($curl);
		
		//For testing purpose
		/*
		$ress = '{"code":"000","content":{"transactions":{"status":"initiated","channel":"api","transactionId":"1564569455428","method":"api","platform":"api","is_api":1,"discount":null,"customer_id":64766,"email":"Daypayz.com@gmail.com","phone":"08064535577","type":"Electricity Bill","convinience_fee":"0.00","commission":2,"amount":"100","total_amount":98,"quantity":1,"unit_price":"100","updated_at":"2019-07-31 11:37:35","created_at":"2019-07-31 11:37:35","id":7755383}},"response_description":"TRANSACTION SUCCESSFUL","requestId":"1134962","payload":"{\"serviceID\":\"mtn\",\"amount\":\"100\",\"phone\":\"08103418955\",\"request_id\":\"1134962\",\"email\":\"Daypayz.com@gmail.com\"}","amount":"100.00","transaction_date":{"date":"2019-07-31 11:37:33.000000","timezone_type":3,"timezone":"Africa\/Lagos"},"purchased_code":"Token : 21363750562708482383","meterNumber":"0124000038214","customerName":null,"address":null,"token":"21363750562708482383","tokenAmount":"100","tokenValue":"100","businessCenter":null,"exchangeReference":"310720191207192","units":"3.2"}';
		
		return $ress;
		*/
		
	}
	
	static public function verifyMerchant($provider,$device_number,$package)
	{
		$username = "daypayz24@gmail.com";
		$password = "7@7@Life@#%";
		$host = 'https://api-service.vtpass.com/api/merchant-verify';
		$data = array(
		  	'serviceID'=> $provider, //integer e.g gotv,dstv,eko-electric,abuja-electric
		  	'billersCode'=> $device_number, // e.g smartcardNumber, meterNumber,
		  	'type'=> $package, // e.g dstv1, dstv2,prepaid,(optional for somes services)
		);
		$curl       = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $host,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_USERPWD => $username.":" .$password,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $data,
		));
		return curl_exec($curl);
		
	}
	static public function generateId($username)
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

?>
