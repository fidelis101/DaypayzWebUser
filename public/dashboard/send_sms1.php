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
	
	$message_count = round(($message_size-80)/160)+1;
	
	$numbers_count = substr_count($numbers,",") + 1;
	
	$amount = $message_count * $numbers_count * $cost;
	
	if($message_size < 160)
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
			send_normal($username,$amount,$numbers,$message,$sender,$balance,$newbalance,$message_count);
		}
		else{
			$_SESSION['smsnotice'] = "<label style='color:red;'>Insufficient Balance</label>";
		}	
	}
	}else
	{
		$_SESSION['smsnotice'] = "<label style='color:red;'>Pls input 160 character per SMS ( Total character entered $message_size )</label>";
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
    		
    		creditBonus($username,$cost);
    		$date = date("Y-m-d H-i-s");
    		$date1 = date("Y-m-d");
    		//company share
    			$ourshare = calShare($network,$amount,$cost);
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
    		
    		creditBonus($username,$cost);
    		$date = date("Y-m-d H-i-s");
    		$date1 = date("Y-m-d");
    		//company share
    			$ourshare = calShare($network,$amount,$cost);
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