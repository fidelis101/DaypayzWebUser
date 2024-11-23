<?php
	include('dbcon.php');
	session_start();
	$firstname = mysqli_real_escape_string($con,$_POST['firstname']);
	$lastname = mysqli_real_escape_string($con,$_POST['lastname']);
	$username = $_SESSION['usr'];

	mysqli_query($con,"UPDATE users SET Firstname='$firstname', Lastname='$lastname' WHERE Username='$username'");
	header('location:profile.php');
	$_SESSION['profileMessage'] = "<p class='text-success'>Update Successful</p>"
?>