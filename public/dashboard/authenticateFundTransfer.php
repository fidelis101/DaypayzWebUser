<?php
	include('dbcon.php');
	require_once('transactionhistorydb.php');
	include("mail_notification.php");
	session_start();
	$tpsd = mysqli_real_escape_string($con, $_POST['tpsd']);
	$amount = mysqli_real_escape_string($con, $_POST['amount']);
	$receiver = mysqli_real_escape_string($con, $_POST['rwallet']);
	
	$sender = $_SESSION['usr'];
	if($sender == $receiver)
	{
		$_SESSION['transfernotice'] = "<label style='color:red;'>Cannot Send to self!</label>";
	}
	elseif($amount > 5.00)
	{

        $result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$sender'");
        $balance = 0;
    	if(mysqli_num_rows($result)>0)
    	{
    		$row = mysqli_fetch_array($result);
    		$hash= $row['Transaction_Psd'];
    	}
    	if(!password_verify($tpsd, $hash)) {
        
        
    	$_SESSION["transfernotice"] = "<label style='color:red;'>wrong Password</label>";
       header("location:wallet_transfer.php");
            
        }
    	else{
        	$rcv = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$receiver'");
        	if(mysqli_num_rows($rcv) >0)
        	{
            	$result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$sender' AND Transaction_Psd='$hash'");
            	$balance = 0;
            	if(mysqli_num_rows($result)>0)
            	{
            		$row = mysqli_fetch_array($result);
            		$balance = $row['Balance'];
            		//$sender = $row['Username'];
            	}
            	if($balance >= $amount)
            		{
            			//Debit Sender
            			$result = mysqli_query($con,"SELECT * From wallets WHERE Username='$sender' AND Transaction_Psd='$hash'");
            			if(mysqli_num_rows($result)>0)
            			{
            
            				$row = mysqli_fetch_array($result);
            				$balance = $row['Balance'];
            				$newbalance = $balance - $amount;
            				mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$sender' AND Transaction_Psd='$hash'");
            				$description = "<p>Description: Wallet transfer of N$amount to $receiver was successful </p> <p>Debit: N$amount </p> <p>Balalance: N$newbalance</p>";
            				saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>'',
            											'Sender'=>$sender,'Description'=>'Wallet transfer to '.$receiver,'init_bal'=>$balance,'final_bal'=>$newbalance));
            				mail_sender::send("Wallet Transfer",$description,$sender);						
            			}
        
        			//Credit Receiver
        			$result = mysqli_query($con,"SELECT * From wallets WHERE Username='$receiver'");
        			if(mysqli_num_rows($result)>0)
        			{
        
        				$row = mysqli_fetch_array($result);
        				$balance = $row['Balance'];
        				$newbalance = $balance + $amount;
        				mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$receiver'");
        			}
        			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$receiver,
        											'Sender'=>'','Description'=>'Wallet transfer from '.$sender,'init_bal'=>$balance,'final_bal'=>$newbalance));
        		$description = "<p>Description: Wallet transfer from $sender </p><p>Credit: N$amount </p><p>Balalance: N$newbalance</p> ";
        		mail_sender::send("Wallet Transfer",$description,$receiver);
        		$_SESSION['transfernotice'] = "Transfer N$amount from $sender to $receiver was successful";
        	
        		}else
        		{
        			$_SESSION['transfernotice'] = "Insufficient Balance pls try sending somthing less";
        		}
    		}
    		else
    		{
    			$_SESSION['transfernotice'] = "No user with he username $receiver";
    		}
		}
	}else
	{
		$_SESSION['transfernotice'] = "<label style='color:red;'>Failed, Below Minimum allowed</label>";
	}
		
	header("location:wallet_transfer.php");
?>