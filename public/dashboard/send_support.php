<?php
	include('dbcon.php');
	session_start();
	
	$header = $_POST['heading'];
	$message = $_POST['message'];
	$department = $_POST['department'];
	$username = $_SESSION['usr'];
	if($_SESSION["usr"]=="")
    {
        header('location: login.php');
    }
	
	mysqli_query($con,"INSERT INTO support (Username,Header,Message,Status,Department,Date) 
	VALUES('$username','$header','$message','Processing','$department',now())");
	
	$_SESSION['supportnotice'] = '<Label style="color:green;"> Message sent Successfully </label>';
	
	header("location: support.php");
?>