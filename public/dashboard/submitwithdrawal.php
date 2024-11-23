<?php 
	include('dbcon.php');
	include('transactionhistorydb.php');
	session_start();
	$uname = $_SESSION['usr'];
	$tpsd = mysqli_real_escape_string($con, $_POST["tpsd"]);
	$amount = mysqli_real_escape_string($con, $_POST["amount"]);

	$result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$uname'");
	$resultbank = mysqli_query($con,"SELECT * FROM bank_details WHERE Username='$uname'");
	
	if(mysqli_num_rows($resultbank)>0)
	{
    $balance = 0;
	if(mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_array($result);
		$hash= $row['Transaction_Psd'];
	}
	if(!password_verify($tpsd, $hash)) {
    
	$_SESSION["withdrawnotification"] = "<label style='color:red;'>Wrong Password</label>";
   header("location:withdraw.php");
    
}
	else{
	    $amount = mysqli_real_escape_string($con, $_POST["amount"]);
	    $requestdate = date("Y-m-d H-i-s");

	    $wallet = mysqli_query($con,"SELECT Balance FROM wallets WHERE Username='$uname'");
	    if(mysqli_num_rows($wallet)>0)
	    {
	    	$row = mysqli_fetch_array($wallet);
	    	$balance = $row['Balance'];
	    	$withdrawable = $balance - 100;
	    	
            if( $amount >=3000){
            
    	    	if($withdrawable >= $amount)
    	    	{
    	    		$newbalance = $balance - $amount - 100;
    		    	mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$uname'");
    
    		    	$result = mysqli_query($con,"SELECT * FROM bank_details WHERE Username='$uname'");
    		    	if(mysqli_num_rows($result)>0)
    		    	{
    			    	$row = mysqli_fetch_array($result);
    			    	$firstname = $row['Firstname'];
    			    	$middlename = $row['Middlename'];
    			    	$accounttype = $row['Account_Type'];
    			    	$accountnumber= $row['Account_number'];
    			    	$bank = $row['Bank_Name'];
    		   		}else
    		   		{

	    		   		$_SESSION['withdrawnotification'] = "<label style='color:green;'>No Bank Details</label>";
	    		   		return;
	    	    	}
	    	    	$dt = date("Y-m-d H:i:s");
	    
    	    		mysqli_query($con,"INSERT INTO pendingwithdrawals (Username,Firstname,Middlename,Bank,Account_Type,Account_Number,Amount,Request_Date) VALUES('$uname','$firstname','$middlename','$bank','$accounttype','$accountnumber','$amount','$dt')");

    	    		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>'','Sender'=>$uname,'Description'=>'Withdrawal: Cash withdrawal of N'.$amount,'init_bal'=>$balance,'final_bal'=>$newbalance));
    	    		
    	    		$_SESSION['withdrawnotification'] = "<label style='color:green;'>Request Submitted Successfully</label>";
    	    		header('location:withdraw.php');
    	    	}
    	    	else
    	    	{
    	    		$_SESSION['withdrawnotification'] = "<label style='color:red;'> Insufficient Balance</label>";
    	    		header('location:withdraw.php');
    	    	}
	    }
	    else{
	        $_SESSION['withdrawnotification'] = "<label style='color:red;'>Minimum withdrawal is 3000</label>";
	        header('location:withdraw.php');
	        }
		}
		else
		{
			$_SESSION['withdrawnotification'] = "<label style='color:red;'>You do not have a wallet</label>";
			header('location:withdraw.php');
		}
	}
	}else
		{
			$_SESSION['withdrawnotification'] = "<label style='color:red;'>Pls Add bank details</label>";
			header('location:addbankdetails.php');
		}

?>
