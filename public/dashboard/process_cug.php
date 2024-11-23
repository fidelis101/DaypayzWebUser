<?php
	include('dbcon.php');
	include('transactionhistorydb.php');
	session_start();
	date_default_timezone_set("Africa/Lagos");
	
	$uname = $_SESSION['usr'];
	$password = mysqli_real_escape_string($con,$_POST['password']);
	$phone = mysqli_real_escape_string($con,$_POST['phone']);
	$network = mysqli_real_escape_string($con,$_POST['network']);
	
	$pincode1 = mysqli_query($con,"select * from logins where Username = '$uname' ") or die ('could not select from logins'); 
	$pinne2 = mysqli_num_rows($pincode1);
	while ($row = mysqli_fetch_assoc($pincode1))
	{
		$id = $row['id'];
		$uname = $row['Username'];
		$hash = $row['Password'];
	}
		
	if($pinne2==0){
	   
		$_SESSION["msg"] = "<label style='color:red;'>Username not found</label>";
		header("Location:activate_cug.php");
	}
	elseif(!password_verify($password, $hash)) {
		
		$_SESSION["msg"] = "<label style='color:red;'>Wrong Password</label>";
		header("Location:activate_cug.php");
	}
	else{
			
		$pincode =mysqli_query($con,"select * from logins where Username = '$uname'  and Password = '$hash'") or die ('could not select from logins 2'.mysql_error());
        	$pinne1 = mysqli_num_rows($pincode);
        	
		while ($row = mysqli_fetch_assoc($pincode))
		{
			
			$id = $row['id'];
			$uname = $row['Username'];
			$hash = $row['Password'];
			
		}
		if($pinne1 >0)
		{
		    $checkNumber = mysqli_query($con,"SELECT * FROM cug_activation WHERE Phone='$phone'");
		    if(mysqli_num_rows($checkNumber)<=0)
		    {
		    $check = mysqli_query($con,"SELECT * FROM cug_activation WHERE Username='$uname' AND Network='$network'");
		    if(mysqli_num_rows($check) > 0)
		    {
		        $_SESSION["msg"] = "<label class='info'>$network Cug Already Activated for $uname</label>";
		    }
		    else{
		    	$checkfirst = mysqli_query($con,"SELECT * FROM cug_activation WHERE Username='$uname'");
		    	if(mysqli_num_rows($checkfirst)>0)
		    	{
		    		$wallet = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$uname'") or die("no wallet found");
		    		$row = mysqli_fetch_assoc($wallet);
		    		$balance = $row["Balance"];
		    		if($balance >= 600)
		    		{
		    			$newbalance = $balance - 600;
		    			if(mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$uname'"))
		    			{
		    			mysqli_query($con,"INSERT INTO cug_activation (Username,Phone,Network,Status) VALUES('$uname','$phone','$network','in process')");
		    			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>600,'Receiver'=>$phone,'Sender'=>$uname,'Description'=>'Debit: Cug activaion for  '.$phone,'init_bal'=>$balance,'final_bal'=>$newbalance));
			$_SESSION["msg"] = "<label style='color:green;'>Number submited successfully</label>";
		    			}
		    			else
		    			{
		    				$_SESSION["msg"] = "<label style='color:red;'>Transaction Charge failed</label>";
		    			}
		    		}else
		    		{
		    			$_SESSION["msg"] = "<label style='color:red;'>Insufficient fund (Must have minimum of N600 in your wallet.)</label>";
		    		}
		    	}else
		    	{
			mysqli_query($con,"INSERT INTO cug_activation (Username,Phone,Network,Status) VALUES('$uname','$phone','$network','in process')");
			$_SESSION["msg"] = "<label style='color:green;'>Number submited successfully</label>";
			}
		    }
		    }else
		    {
		    	$_SESSION["msg"] = "<label style='color:red;'>$phone Already Submited for activation</label>";
		    }
		    header("Location:activate_cug.php");
		}
	}

?>