<?php
    include('get_content1.php');
    include('vtpass_link.php');
    require 'HttpClient.php';
    
    $meternumber = $_REQUEST['meternumber'];
    $provider = $_REQUEST['provider'];
    $type = $_REQUEST['type'];
    
    
    if($provider=="01" || $provider=="02" || $provider=="05" || $provider=="03" || $provider=="06" || $provider=="08")
    {
    	
	    $providerMap = ['01'=>"eko-electric",'02'=>"ikeja-electric",'03'=>"ibadan-electric",'05'=>"portharcourt-electric",'06'=>"kano-electric",'08'=>"jos-electric"];
        $provider = $providerMap[$provider];
	      
	     $packageMap = ['01'=>"prepaid",'02'=>"postpaid"];
        $type = $packageMap[$type];
    	
		$result = vtpass_api::verifyMerchant($provider,$meternumber,$type);
		$obj = json_decode($result);
	  	
        if($obj->content->Customer_Name != '')
        {
        	echo $obj->content->Customer_Name;
        	echo "<p>Address: ".$obj->content->Address." </p>";
        }
        else
        {
            echo "<label class='text-danger'>";
        	echo $obj->content->error;
            echo $obj->response_description;
            echo "</label>";
        }
        	
        
    }
    
    if($provider == "01")
    {
	if($type=="01")
		$type="14";
	else if($type=="02")
		$type="13";
					
	}
	if($provider == "02")
     	{
	if($type=="01")
		$type="11";
	else if($type=="02")
		$type="10";
					
	}
	if($provider == "03")
     	{
	if($type=="01")
		$type="12";
	else if($type=="02")
		$type="23";
					
	}
	if($provider == "04")
     	{
	if($type=="01")
		$type="24";
	
					
	}
	
  	if($provider == "07")
	{
	    if($type=="01")
		$type="21";
	    else if($type=="02")
		$type="22";
					
  	}
  	
  	

    
    if($provider=="04" || $provider=="07")
    {
        
  	 $url = "https://api.airvendng.net/secured/seamless/verify/";
  	 $username = "daypayz.com@gmail.com";
  	 $password = "@Ojobe52487";
  	 $api_key = "CPL741704E55A7EC87490635AC4DBC5606DA0B45500CEEC64CE37A5C930D8F3E359";
  	 $requestid = uniqid;
  	 
  	 $data = [
			"details"=>[
                "ref"=>$requestid,
                "account"=>$meternumber,
                "type"=>$type
            ]
		];
    	$requestdata = json_encode($data);

        $hashed = hash("sha512", $requestdata.$api_key);

		$header = array();
		$header[]= 'username: '.$username;
        $header[]= 'password: '.$password;
        $header[]= 'hash: '.$hashed;
		$header[]= 'Content-Type: application/json';

        //echo $this->username.$this->api_key.$requestdata;
        //exit;

    	$res = CurlCon::curl_post_no_auth($url,$requestdata,$header);
        
		$result = $res['Result'];
        //$log->putLog("\n\n Transaction by: $username, Description: $result\n\n");

        //echo json_encode($res);exit;
    	$obj = json_decode($result);
        if($obj->confirmationMessage=="OK")
        {
            echo "<p>Name: ".$obj->details->message->name." </p>";
            echo "<p>Address: ".$obj->details->message->address." </p>";
        }
        else
        	echo "Could not verify meter no";
    		
    }
    
?>