<?php

$user = "daypayz.com@gmail.com"; //email address
    $password = "@ojobe77"; //password
    $host = 'https://vtpass.com/api/balance';
	
	$curl       = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => $host,
		CURLOPT_USERPWD => $user.":" .$password,
	));
	$result = curl_exec($curl);
    $obj = json_decode($result);
    echo $obj->contents->balance;
    
    ?>