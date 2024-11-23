<?php

	function saveTransactionHistory($transaction)
	{
		include('dbcon.php');
		mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,init_bal,final_bal) 
					VALUES('$transaction[TransactionDate]','$transaction[Amount]','$transaction[Sender]','$transaction[Receiver]',
					'$transaction[Description]','$transaction[init_bal]','$transaction[final_bal]')");
	}
	
	function updateTransactionHistory($transaction)
	{
		include('dbcon.php');
		mysqli_query($con,"UPDATE transactionhistory SET TransactionDate=$transaction[TransactionDate],
					Amount='$transaction[Amount]',Sender='$transaction[Sender]',Receiver='$transaction[Receiver]',
					Description='$transaction[Description]' WHERE Id='$transaction[Id]'");
	}

	function getAllTransactionHistorys()
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM transactionhistory");
		if(mysqli_num_rows($result)>1)
		{
			return $result;
		}
		else{return false;}
	}

	function getUserTransactions($username)
	{
		include('dbcon.php');
		if($username == "vpservices")
		{
		$result = mysqli_query($con,"SELECT * FROM transactionhistory WHERE Sender= BINARY '$username' || Receiver= BINARY '$username' ORDER BY TransactionDate DESC");
		}
		else
		{
		$result = mysqli_query($con,"SELECT * FROM transactionhistory WHERE Sender='$username' || Receiver='$username' ORDER BY TransactionDate DESC");}
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}else{return false;}
	}

	function deleteTransactionHistory($id)
	{
		include('dbcon.php');
		mysqli_query($con,"DELETE FROM transactionhistory WHERE Id='$id'");
	}
?>
