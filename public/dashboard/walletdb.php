<?php
	function saveWallet($wallet)
	{
		include('dbcon.php');
		mysqli_query($con,"INSERT INTO wallets (Username,Transaction_Psd,Balance) 
			VALUES ('$wallet[Username]'),'$wallet[Wallet_Id]','$wallet[Transaction_Psd]','$wallet[Balance]'");
	}

	function updateWallet($wallet)
	{
		include('dbcon.php');
		mysqli_query($con,"UPDATE wallets SET Id='',Username='$wallet[Username]',
			Transactin_Psd='$wallet[Transaction_Psd]',Balance='$wallet[Balance]'");
	}

	function getWallets()
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM wallets");
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
		else{return false;}
	}

	function getWallet($username)
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$username'");
		if(mysqli_num_rows($result)>0)
		{
			return mysqli_fetch_array($result);
		}
		else{return false;}
	}
?>