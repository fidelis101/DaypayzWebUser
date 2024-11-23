<?php

class TransactionHistoryHandler
{
	static function saveTransactionHistory($transaction)
	{
		include('dbcon.php');
		mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,init_bal,final_bal) 
					VALUES('$transaction[TransactionDate]','$transaction[Amount]','$transaction[Sender]','$transaction[Receiver]',
					'$transaction[Description]','$transaction[init_bal]','$transaction[final_bal]')");
					
	}
	static function saveTranHistory($transaction)
	{
		include('dbcon.php');
		mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,Request_Id,Tran_Id,Status,init_bal,final_bal,Provider) 
					VALUES('$transaction[TransactionDate]','$transaction[Amount]','$transaction[Sender]','$transaction[Receiver]','$transaction[Description]',
					'$transaction[Request_Id]','$transaction[Tran_Id]','$transaction[Status]','$transaction[init_bal]','$transaction[final_bal]','$transaction[Provider]')");
			
	}

	static function saveTranHistoryTran($transaction)
	{
		include('dbcon.php');
		mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,Request_Id,Tran_Id,Status,Transaction,init_bal,final_bal,Provider) 
					VALUES('$transaction[TransactionDate]','$transaction[Amount]','$transaction[Sender]','$transaction[Receiver]','$transaction[Description]',
					'$transaction[Request_Id]','$transaction[Tran_Id]','$transaction[Status]','$transaction[Transaction]','$transaction[init_bal]','$transaction[final_bal]','$transaction[Provider]')") or die(mysqli_error($con));
			
	}

	static function getUserTransactions($username)
	{
		include('dbcon.php');
		if($username == "daypayz")
		{
			$result = mysqli_query($con,"SELECT * FROM transactionhistory WHERE Sender= BINARY '$username' || Receiver= BINARY '$username' ORDER BY TransactionDate DESC");
		}
		else
		{
			$result = mysqli_query($con,"SELECT * FROM transactionhistory WHERE Sender='$username' || Receiver='$username' ORDER BY TransactionDate DESC");
		}
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
		else{return false;}
    }
}
?>