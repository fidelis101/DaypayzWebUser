<?php
	
	function curl_get_contents($url,$data)
	{
	    $curl  = curl_init();
    	curl_setopt_array($curl, array(
    	    CURLOPT_URL => $url,
    		CURLOPT_RETURNTRANSFER => true,
    		CURLOPT_ENCODING => "UTF-8",
    		CURLOPT_MAXREDIRS => 10,
    		CURLOPT_TIMEOUT => 30,
    		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    		CURLOPT_CUSTOMREQUEST => "POST",
    		CURLOPT_POSTFIELDS => $data,
    	));
    	$response = curl_exec($curl);
	    curl_close($curl);
	    return $response;
	}
	
?>