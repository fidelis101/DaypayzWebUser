<?php
session_start();
include('dbcon.php');
if (isset($_POST['username2'])) {
	$uname = mysqli_real_escape_string($con, $_POST['username2']);
	$password = mysqli_real_escape_string($con, $_POST['password']);

	$pincode1 = mysqli_query($con, "select * from logins where Username = '$uname'") or die('could not select from logins');
	$pinne2 = mysqli_num_rows($pincode1);
	while ($row = mysqli_fetch_assoc($pincode1)) {
		$id = $row['id'];
		$uname = $row['Username'];
		$hash = $row['Password'];
	}

	if ($pinne2 == 0) {

		$_SESSION["lognotice"] = "<label style='color:red;'>Wrong Username</label>";
		header("Location:login.php");
	} elseif (!password_verify($password, $hash)) {

		$_SESSION["lognotice"] = "<label style='color:red;'>Wrong Password</label>";
		header("Location:login.php");
	} else {

		$pincode = mysqli_query($con, "select * from logins where Username = '$uname'  and Password = '$hash'") or die('could not select from logins 2' . mysqli_error($con));
		$pinne1 = mysqli_num_rows($pincode);
		while ($row = mysqli_fetch_assoc($pincode)) {
			$id = $row['id'];
			$uname = $row['Username'];
			$hash = $row['Password'];
		}
		if ($pinne1 > 0) {
			$_SESSION['usr'] = $uname;
			$_SESSION["lastlogin"] = $row['LastLogin'];



			$date = date("Y-m-d H:i:s");
			mysqli_query($con, "UPDATE logins SET LastLogin=now() WHERE Username='$uname'");
			$checkuser = mysqli_query($con, "select * from plus_login where userid = '$uname'") or die('could not select from registeration' . mysqli_error($con));
			$user = mysqli_num_rows($checkuser);
			while ($row = mysqli_fetch_assoc($checkuser)) {
				$b = $row['userid'];
			}
			if ($user == 0) {
				$involve = "INSERT INTO plus_login(userid,ip,status,tm,date)
			VALUES('$uname','','','',now())";
				mysqli_query($con, $involve);
			}
			$tm = date("Y-m-d H:i:s");
			$q = mysqli_query($con, "update plus_login set status='ON',tm='$tm' where userid = '$uname'");

			// Find out who is online /////////
			$gap = 2; // change this to change the time in minutes, This is the time for which active users are collected. 
			$tm = date("Y-m-d H:i:s", mktime(date("H"), date("i") - $gap, date("s"), date("m"), date("d"), date("Y")));

			//// Let us update the table and set the status to OFF 
			////for the users who have not interacted with 
			////pages in last 10 minutes ( set by $gap variable above ) 
			$ut = mysqli_query($con, "update plus_login set status='OFF' where tm < '$tm'");
			echo mysqli_error($con);

			/// Now let us collect the userids from table who are online ////////
			$qt = mysqli_query($con, "select userid from plus_login where tm > '$tm' and status='ON'");
			mysqli_error($con);
			header("Location:index.php");
		} else {
			$_SESSION["lognotice"] = "Wrong Username or Password";
			header("Location:login.php");
		}
	}
} else {
	$_SESSION["lognotice"] = "<label style='color:red;'>Wrong Username</label>";
	header("Location:login.php");
}
