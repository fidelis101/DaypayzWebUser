<?php
	session_start();
	include('dbcon.php');
	include('transactionhistorydb.php');
	include('systemdb.php');
	include('get_content.php');
	
	
	$sender = $_POST['sender'];
	$message = $_POST['message'];
	$numbers = $_POST['mobilenumber'];
	$message_size = strlen($message);
	$username = $_SESSION['usr'];
	$cost = 2;
	
	$message_size = strlen($message);
	$cost= 2;
	$message_count = 0;
			
	if($message_size > 0 && $message_size <= 160)
	$message_count = 1;
	else{
		$message_count = floor($message_size/160);
		if(($message_size % 160)>0)
		{
			$message_count + 1;
		}
	}
	
	$numbers = str_replace(" ","",$numbers);
	$numbers_count = explode(",",$numbers);
			
	$amount = $message_count * count($numbers_count) * $cost;
	
	if(true)//$message_size < 160)
	{
		$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
		if($result->num_rows > 0)
		{
			$row = mysqli_fetch_array($result);
			$balance = $row['Balance'];
			if($balance >= $amount)
			{	
				$newbalance = $balance - $amount;
				
				$debit = mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
				send_swiftbulksms($username,$amount,$numbers,$message,$sender,$balance,$newbalance,$message_count,$numbers_count,$cost);
				//send_Ma($username,$amount,$numbers,$message,$sender,$balance,$newbalance,$message_count,$numbers_count,$cost);
			}
			else{
				$_SESSION['smsnotice'] = "<label style='color:red;'>Insufficient Balance</label>";
			}	
		}
	}else
	{
		$_SESSION['smsnotice'] = "<label style='color:red;'>Pls input 160 character per SMS ( Total character entered $message_size )</label>";
	}

	function send_Ma($username,$amount,$numbers,$message,$sender,$balance,$newbalance,$message_count,$numbers_count,$cost)
	{
		include('dbcon.php');
		$host = 'https://www.mobileairtimeng.com/smsapi/bulksms.php';
			$data = array(
			'username'=> '08064535577',
			'password' =>  '323738ba5b5442deb56b12',
			'message' => $message,
			'mobile' => $numbers,
			'sender'=>$sender,
			'route'=>1,
			'vtype'=>1,
			);
			$curl       = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => $host,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $data,
			));
			$result = curl_exec($curl);
			//2001|phone|message sent e.g. 2001|2348135534866|message sent
			//2002|phone|reason e.g. 2002|2348135534866|dnd rejected
			//2001|2348139170491|message sent,2001|2348135534866|message sent
			
			$failed_message = "";
			$success_message = "";
		if(strpos($result,","))
		{
			$res = explode(",",$result);
			$ms_length = count($res);
			$failed_count =0; $success_count = 0;
			$i = 0;
			while($i<$ms_length)
			{
				list($statusCode,$recepient,$messageStatus) = explode("|",$res[$i]);
				if($statusCode == '2001')
				{
					$success_count++;
					$success_message .= "$recepient : $messageStatus<br/>";
				}
				else if($statusCode == '2002')
				{
					$failed_message .= "$recepient : $messageStatus <br/>";
					$failed_count++;
				}
				$i++;
			}
			
		}
		list($statusCode,$recepient,$messageStatus) = explode("|",$result);
		$_SESSION['smsnotice'] = "<label style='color:green;'>Total Success = {$success_count}, <span class='text-danger'>Total Failed = {$failed_count}</span></label> <br/>";
		
		if($failed_count>0)
		{
			$amount = $amount-($failed_count*$cost*$message_count);
			$newbalance = $newbalance + ($failed_count*$cost*$message_count);
			mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
		}

		if($success_count>0)
		{
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>'Sms','Sender'=>$username,'Description'=>'Debit: Sms Message ','init_bal'=>$balance,'final_bal'=>$newbalance));    			
			$_SESSION['smsnotice'] .= "<label style='color:green;'>Successful Messages:<br/> {$success_message}</label> <br/>";
		}
		
		if($failed_count>0)
		{
			$_SESSION['smsnotice'] .= "<br/> "."<label style='color:red;'>Failed Messages:<br/> {$failed_message}</label> ";
		}

    	$description = $numbers." Sms ".$amount;
    	$date = date("Y-m-d H-i-s");
  		mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','','$messageStatus','$description','$amount','$date','SmsMA')");
    
	}

	function send_swiftbulksms($username,$amount,$numbers,$message,$sender,$balance,$newbalance,$message_count,$numbers_count,$cost)
	{
		include('dbcon.php');
		$host = 'https://swiftbulksms.com/sendsms.php';
			$data = array(
			'user'=> 'ojobe',
			'password' =>  '#ojobe77',
			'message' => $message,
			'mobile' => $numbers,
			'senderid'=>$sender,
			'dnd'=>1
			);
			$curl       = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => $host,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $data,
			));
			$result = curl_exec($curl);	

			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','','$result','Sms','$amount','$date','SwiftBulk')");
		
			if(strpos($result,":"))
			{
				list($statusCode,$response,$messageStatus) = explode(":",$result);
				if($statusCode == "1111" || $response=="SUCCESS")
				{
					$_SESSION['smsnotice'] = "<label style='color:green;'>Message Sent successfully</label>";
    				saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>'Sms','Sender'=>$username,'Description'=>'Debit: Sms Message ','init_bal'=>$balance,'final_bal'=>$newbalance));    			
				}else{
					$date = date("Y-m-d H-i-s");
					mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
					$_SESSION['smsnotice'] = "<label style='color:red;'>Message sending Failed</label>";
					
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Refund: Message sending Failed'.$mobilenumber,'init_bal'=>$newbalance,'final_bal'=>$balance));   
				}	
			}
	}
	function send_normal($username,$amount,$numbers,$message,$sender,$balance,$newbalance,$message_count)
	{
		include('dbcon.php');
		
		$message = str_replace(" ","%20",$message);
		$numbers = str_replace(" ","",$numbers);
		$result = curl_get_contents('https://smsclone.com/api/sms/sendsms?username=daypayz&password=daypayzCOUNT&sender='.$sender.'&recipient='.$numbers.'&message='.$message);
		
		
    	
    	list($statusCode,$recepient,$messageId,$messageStatus,$statusDescription) = explode("|",$result);
    	
    	
    	$description = "".$numbers." Sms ".$amount;
    	$date = date("Y-m-d H-i-s");
  		mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$messageId','$messageStatus','$description','$amount','$date','SmsClone')");
    	
    	if(strpos($statusCode,"TG00") !== false && strpos($statusCode,"0000"))
    	{
    		$_SESSION['smsnotice'] = "<label style='color:green;'>Message Sent successfully</label>";

    		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>'Sms','Sender'=>$username,'Description'=>'Debit: Sms Message ','init_bal'=>$balance,'final_bal'=>$newbalance));    			
    	}
    	else{
    	   
    	    $date = date("Y-m-d H-i-s");
    		
			mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
    		$_SESSION['smsnotice'] = "<label style='color:red;'>Message sending Failed</label>";
    		
    		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Refund: Message sending Failed'.$mobilenumber,'init_bal'=>$newbalance,'final_bal'=>$balance));   
    	}
	}
	
	function send_dnd($amount,$numbers,$message,$sender,$newbalance)
	{
		include('dbcon.php');
    
    	$result = curl_get_contents("https://smsclone.com/api/sms/dnd-route?username=daypayz&password=daypayzCOUNT&sender=@@sender@@&recipient=@@recipient@@&message=@@message@@");
    	list($statusCode,$TransactionID,$statusMessage) = explode("|",$result);
    	
    	
    	$description = "".$mobilenumber." Data Subscription of ".$cost;
    	$date = date("Y-m-d H-i-s");
  		mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$TransactionID','$statusMessage','$description','$cost','$date','EDS')");
    	
    	if($statusMessage == "completed" || $statusMessage == "in progress")
    	{
    		$_SESSION['smsnotice'] = "<label style='color:green;'>Transaction Successful</label>";
    		
    		$date = date("Y-m-d H-i-s");
    		$date1 = date("Y-m-d");
    		//company share
    		mysqli_query($con,"INSERT INTO datatransact ( Username,phone,networks,datasize,amount,ourshare,datetrans,date) VALUES('$username','$mobilenumber','$network','$amount','$cost','$ourshare','$date1','$date')");
    			
    		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: Data Subscription for '.$mobilenumber,'init_bal'=>$balance,'final_bal'=>$newbalance));
    		debitSystemWallet($cost);
    		
    			
    	}
    	else{
    	   
    	    $date = date("Y-m-d H-i-s");
    		mysqli_query($con,"INSERT INTO apitransactions ( Transaction_Id,Status,Transaction_Date) VALUES('$obj->orderid','$obj->status','$date')");
    		
			mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
    		$_SESSION['smsnotice'] = "<label style='color:red;'>Connection error, try again later</label>";
    		
    		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Refund: failed data subscription'.$mobilenumber,'init_balance'=>$balance,'final_balance'=>$newbalance));   
    	}
	}
	
	function send_normal_dnd($amount,$numbers,$message,$sender,$newbalance)
	{
		include('dbcon.php');
    
    	$result = curl_get_contents("https://smsclone.com/api/sms/dnd-route?username=yyy&password=xxx&sender=@@sender@@&recipient=@@recipient@@&message=@@message@@");
    	list($statusCode,$TransactionID,$statusMessage) = explode("|",$result);
    	
    	
    	$description = "".$mobilenumber." Data Subscription of ".$cost;
    	$date = date("Y-m-d H-i-s");
  		mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$TransactionID','$statusMessage','$description','$cost','$date','EDS')");
    	
    	if($statusMessage == "completed" || $statusMessage == "in progress")
    	{
    		$_SESSION['smsnotice'] = "<label style='color:green;'>Transaction Successful</label>";
    		
    		$date = date("Y-m-d H-i-s");
    		$date1 = date("Y-m-d");
    		//company share
    		mysqli_query($con,"INSERT INTO datatransact ( Username,phone,networks,datasize,amount,ourshare,datetrans,date) VALUES('$username','$mobilenumber','$network','$amount','$cost','$ourshare','$date1','$date')");
    			
    		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: Data Subscription for '.$mobilenumber,'init_bal'=>$balance,'final_bal'=>$newbalance));
    		debitSystemWallet($cost);
    		
    			
    	}
    	else{
    	   
    	    $date = date("Y-m-d H-i-s");
    		mysqli_query($con,"INSERT INTO apitransactions (Transaction_Id,Status,Transaction_Date) VALUES('$obj->orderid','$obj->status','$date')");
    		
		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
    		$_SESSION['smsnotice'] = "<label style='color:red;'>Connection error, try again later</label>";
    		
    		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Refund: failed data subscription'.$mobilenumber,'init_balance'=>$balance,'final_balance'=>$newbalance));   
    	}
	}
	
	header("location:sms.php");
?>
