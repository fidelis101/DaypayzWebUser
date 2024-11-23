	<?php
	
	function curl_get_contents($url)
	{
		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"GET");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//So that curl_exec returns the contents of the cURL; rather than echoing it
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
		
		//execute post
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	?>