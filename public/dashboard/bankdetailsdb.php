<?php

	function addBankDetail($details)
	{
		include('dbcon.php');
		mysqli_query($con,"INSERT INTO bank_details (Username, Bank_Name, Account_Type, Account_Name, Account_Number) VALUES
			            ('$details[Username]', '$details[Bank_Name]', '$details[Account_Type]','$details[Account_Name]',
			            '$details[Account_Number]')");
	}

	function updateBankDetail($details)
	{
		include('dbcon.php');
		mysqli_query($con,"UPDATE bank_details SET Username='$user[Username]',Bank_Name='$details[Bank_Name]',Account_Type='$details[Account_Type]', Account_Name='$details[Account_Name]', Account_Number='$details[Account_Number]', WHERE Id='$user[Id]'");
	}

	function getBankDetails()
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM bank_details");
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
	}

	function getUserBankDetails($username)
	{

		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM bank_details WHERE Username='$username'");
		
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
		else{return false;}
	}

	function deleteUserBankDetails($username)
	{
		include('dbcon.php');
		mysqli_query($con,"DELETE FROM bank_details WHERE Username='$username'");
	}
?>