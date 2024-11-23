<?php

	function addUser($user)
	{
		include('dbcon.php');
		mysqli_query($con,"INSERT INTO users (Username, Firstname, Lastname, Email, Phone,RegistrationDate,ActivationDate) VALUES
			            ('$user[Username]', '$user[Firstname]', '$user[Lastname]','$user[Email]',
			            '$user[Phone]','$user[RegistrationDate]','$user[RegistrationDate]')");
	}

	function updateUser($user)
	{
		include('dbcon.php');
		mysqli_query($con,"UPDATE users SET Username='$user[Username]',Firstname='$user[Firstname]',Lastname='$user[Lastname]',
			RegistrationDate='$user[RegistrationDate]',ActivationDate='$user[ActivationDate]',Email='$user[Email]',
			Phone='$user[Phone]' WHERE Id='$user[Id]'");
	}

	function getUsers()
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM users");
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
	}

	function getUser($username)
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM users WHERE Username='$username'");
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
		else{return false;}
	}

	function deleteUser($username)
	{
		include('dbcon.php');
		mysqli_query($con,"DELETE FROM users WHERE Username='$username'");
	}
?>