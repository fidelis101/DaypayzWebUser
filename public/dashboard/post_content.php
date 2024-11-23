<?php
	
	function curl_get_contents($url)
	{
		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//So that curl_exec returns the contents of the cURL; rather than echoing it
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
		
		//execute post
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	function curl_post_no_auth($url,$data,$header)
                {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //Post Fields
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //Post Fields
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
                    
                    $request = curl_exec($ch);
                    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $err = curl_error($ch);
                    return ['Result'=>$request,'Error'=>$err,'StatusCode'=>$statusCode];
                }
	
?>