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
	
		class CurlCon
	{
		public static function curl_no_auth($url,$method)
		{
			//open connection
			$ch = curl_init();
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$method);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			//So that curl_exec returns the contents of the cURL; rather than echoing it
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
			//execute post
			$result = curl_exec($ch);
			$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			curl_close($ch);

			return ['Result'=>$result,'StatusCode'=>$statusCode];
		}

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
		
		public static function curl_post_basic_auth($host,$method,$data,$user,$password)
		{
			$curl = curl_init();
	        curl_setopt_array($curl, array(
	        CURLOPT_URL => $host,
	        	CURLOPT_RETURNTRANSFER => true,
	        	CURLOPT_ENCODING => "",
	        	CURLOPT_MAXREDIRS => 10,
	        	CURLOPT_USERPWD => $user.":".$password,
	        	CURLOPT_TIMEOUT => 30,
	        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        	CURLOPT_CUSTOMREQUEST => $method,
	        	CURLOPT_POSTFIELDS => $data,
	        ));
			$result = curl_exec($curl);
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
			return ['Result'=>$result,'StatusCode'=>$statusCode];
		}

        public static function curl_post_no_auth($url,$data,$header)
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
		
		public static function curl_post($url,$data,$headers)
		{
			$curl = curl_init();
            curl_setopt($curl, CURLOPT_URL,$url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);  //Post Fields
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
			$request = curl_exec ($curl);
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$err = curl_error($curl);
			
			$result = ['Result'=>$request,'Error'=>$err,'StatusCode'=>$statusCode];
			
			curl_close ($curl);
			
			return $result;
		}

		public static function curl_get($url,$headers)
		{
			$curl = curl_init();
            curl_setopt($curl, CURLOPT_URL,$url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
			$request = curl_exec($curl);
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$err = curl_error($curl);
			
			$result = ['Result'=>$request,'Error'=>$err,'StatusCode'=>$statusCode];
			
			curl_close ($curl);
			
			return $result;
		}

		public static function file_get($url,$header,$method)
		{
			// Create a stream
			$opts = array(
				'http'=>array(
				'method'=>"$method",
				'header'=>$header
				)
			);
			
			$context = stream_context_create($opts);
			
			// Open the file using the HTTP headers set above
			RETURN $file = file_get_contents($url, false, $context);
		}
	}

	
?>