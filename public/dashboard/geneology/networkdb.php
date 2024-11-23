<?php
	function saveNetwork($network)
	{
		include('dbcon.php');
		mysqli_query($con,"INSERT INTO networks (Id,Username,Referal_Id,Leader_Id,Stage) 
			VALUES ('','$network[Username]'),'$network[Referal_Id]','$network[Leader_Id]','$network[Stage]'");
	}

	function updateNetwork($network)
	{
		include('dbcon.php');
		mysqli_query($con,"UPDATE networks SET Id='',Username='$network[Username]',Referal_Id='$network[Referal_Id]',
			Leader_Id='$network[Leader_Id]',Stage='$network[Stage]'");
	}

	function getNetworks()
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM networks");
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
		else{return false;}
	}

	function getUserDownline($username)
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM networks WHERE  Leader_Id='$username'");
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
		else{return false;}
	}
	
	function getUserNetwork($username)
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * FROM networks WHERE  Username='$username'");
		if(mysqli_num_rows($result)>0)
		{
			return $result;
		}
		else{return false;}
	}

	function deleteNetwork($id)
	{
		include('dbcon.php');
		mysqli_query($con,"DELETE FROM networks WHERE Id='$id'");
	}
?>