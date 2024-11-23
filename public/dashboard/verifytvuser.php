<?php
    include('post_content.php');
    //include('HttpClient.php');
    $cardnumber = $_REQUEST['cardnumber'];
    $provider = $_REQUEST['provider'];
    
    // $verifyDiscoEndpoint = "https://api.airvendng.net/secured/seamless/verify/?";

    // $username="daypayz.com@gmail.com";
    // $password="@Ojobe52487";
    // $api_key = "CPL741704E55A7EC87490635AC4DBC5606DA0B45500CEEC64CE37A5C930D8F3E359";
    
    // $decoder = $provider == "gotv"?'40':'30';
    //     $data = [
	// 		"details"=>[
    //             "ref"=>'',
    //             "account"=>$cardnumber,
    //             "type"=>$decoder
    //         ]
	// 	];
    // 	$requestdata = json_encode($data);
        
    //     $hashed = hash("sha512", $requestdata.$api_key);

	// 	$header = array();
	// 	$header[]= 'username: '.$username;
    //     $header[]= 'password: '.$password;
    //     $header[]= 'hash: '.$hashed;
	// 	$header[]= 'Content-Type: application/json';

    //     // echo $api_key.$requestdata;
    //     // exit;

    // 	$res = curl_post_no_auth($verifyDiscoEndpoint,$requestdata,$header);
        
	// 	$result = $res['Result'];

    //     // echo json_encode($res);exit;
    // 	$obj = json_decode($res['Result']);

       
    //     $obj = json_decode($res['Result']);
        
    //     echo $obj->details->message->name;



    // $tvype = ['01'=>'dstv','02'=>'gotv','03'=>'startimes'];
    // $resultapi = curl_get_contents("https://mobileairtimeng.com/httpapi/customercheck?userid=08064535577&pass=4b6afee34b42c0fe948e2f5&bill=".$provider."&smartno=".$cardnumber."&jsn=json");

    // //$resultapi = curl_get_contents("https://api.airvendng.net/vas/gotv/verify/?username=eeestores@yahoo.com&password=jesuslord@&smartcard=".$smartcardno);
       
    // $obj = json_decode($resultapi);

    // if($obj->code == '100')
    // echo $obj->customerName;

    $tvype = ['01'=>'dstv','02'=>'gotv','03'=>'startimes'];
    $verifyDiscoEndpoint = "https://api-service.vtpass.com/api/merchant-verify";
    
        $data = [
            "billersCode"=>$cardnumber,
            "serviceID"=>$tvype[$provider]
        ];
    	$requestdata = json_encode($data);
        

		$header = array();
		$header[]= 'api-key: f09128679b4bf2f58aa08992b7fba6cf';
        $header[]= 'secret-key: SK_665917e169593b06beaa567363fe063eda227637902';
		$header[]= 'Content-Type: application/json';

        // echo $api_key.$requestdata;
        // exit;

    	$res = curl_post_no_auth($verifyDiscoEndpoint,$requestdata,$header);
        
		$result = $res['Result'];

        // echo json_encode($res);exit;
    	$obj = json_decode($res['Result']);
        
        if($obj->code == '000')
        echo $obj->content->Customer_Name;
        else
        echo "Could not validate decoder number"
        
 
?>