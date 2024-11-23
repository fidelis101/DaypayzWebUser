<?php
	include('dbcon.php');
	session_start();
	$bankname = mysqli_real_escape_string($con, $_POST['bankname']);
	$accounttype = mysqli_real_escape_string($con, $_POST['accounttype']);
	$fname = mysqli_real_escape_string($con, $_POST['fname']);
	$mname = mysqli_real_escape_string($con, $_POST['mname']);
	$accountnumber = mysqli_real_escape_string($con, $_POST['accountnumber']);
	$uname=$_SESSION['usr'];

	//Registration
	saveData();
?>
<?php
	function saveData()
	{
		include('dbcon.php');
		global $uname,$bankname,$fname,$mname,$accounttype,$accountnumber;

		$result = mysqli_query($con,"SELECT * FROM bank_details WHERE Username='$uname'");
		if(mysqli_num_rows($result) < 1)
		{
			mysqli_query($con,"INSERT INTO bank_details (Username, Bank_Name,Account_Type,Firstname,Middlename,Account_Number) VALUES
					            ('$uname', '$bankname','$accounttype','$fname','$mname','$accountnumber')");
					            $_SESSION['dashboardnotice'] = "<span class='text-success'>Bank details added successfully</span>";
			?>
			 <script>
  window.location.href='index.php';
    </script>
    <?php
		}
		else
		{
			$_SESSION['dashboardnotice'] = "Bank details already registered for '$uname'";
			?>
			 <script>
  window.location.href='index.php';
    </script>
    <?php
		}
	}
?>