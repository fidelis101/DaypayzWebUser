<?php
	session_start();
	include('dbcon.php');
	include('regvalidate.php');
	require_once('userdb.php');
	include ('Handlers/RegistrationHandler.php');
	$leadervalidate = new regvalidate();


if (empty($_POST['username'])) 
{ 
    //if no name has been supplied
   $_SESSION['error'] = "<label style='color:red;'>Username Missing</label>";
} 
else {
    $uname = $_POST['username']; //else assign it a variable
    $result = mysqli_query($con,"SELECT * FROM networks WHERE Username='$uname'"); 
    if(mysqli_fetch_array($result)>0 || $uname=='daypayz') 
    {   
       $_SESSION['error'] = "<label style='color:green;'>User has been registered </label>"; 
    }
    	
}
if (preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $_POST['username']) == true) 
{ 
	$_SESSION['error'] = "<label style='color:red;'>Your Username must not contain any special character</label>";
    }
   else if( preg_match('/\s/',$_POST['username']) )
    {
    	$_SESSION['error'] = "<label style='color:red;'>Your Username must not contain any spaces</label>";
    }
    if(strlen($_POST['username']) <4)
    {
    	$_SESSION['error'] = "<label style='color:red;'>Your Username must not be less than 4 characters</label>";
    }

	if(empty($_POST['firstname'])){
	  $_SESSION['error'] = "<label style='color:red;'>Firstname Missing</label>";
	}
	else
	{
	    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
	}

	if(empty($_POST['lastname'])){
	  $_SESSION['error'] = "<label style='color:red;'>Lastname Missing</label>";
	}
	else{
	    $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
	}

	if(empty($_POST['referal'])){
	  $_SESSION['error'] = "<label style='color:red;'>Referal Username Missing</label>";
	}
	else
	{
		$referalid = mysqli_real_escape_string($con, $_POST['referal']);
	}

	if(empty($_POST['placement']))
	{
		$_SESSION['error'] = "<label style='color:red;'>Placement location is Missing</label>";
	}else{
		$placement = mysqli_real_escape_string($con, $_POST['placement']);
		$netPlace = $leadervalidate->getPlacement($referalid,$placement);
		$placement = $netPlace->placement;
		$leaderid = $netPlace->leader;
	}
		

	if (empty($_POST['email'])) 
	{
        $_SESSION['error'] = "<label style='color:red;'>Email Address missing</label>";
	} 
	else 
	{
	    if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",
        $_POST['email'])) 
        {
	       //regular expression for email validation
	       $email = $_POST['email'];
            
        } else 
        {
	       $_SESSION['error'] = "<label style='color:red;'>Invalid Email Address</label>";
	    }
	}
	if(empty($_POST['phone']))
	{
	  $_SESSION['error'] = "<label style='color:red;'>Phone Number Missing</label>";
	}
	else
	{
	    $phone = mysqli_real_escape_string($con, $_POST['phone']);
	}
	if(empty($_POST['package']))
	{
	  $_SESSION['error'] = "<label style='color:red;'>No Package Selected</label>";
	}
	else
	{
	    $package = mysqli_real_escape_string($con, $_POST['package']);
	}	
	if (empty($_POST['passwordsignup'])) 
	{ //if no name has been supplied
	    $_SESSION['error'] = "<label style='color:red;'>First Password Missing</label>";
	 } 
	 else 
	 {
	   $pass = $_POST['passwordsignup']; //else assign it a variable
	   $options = ['cost' => 12];
	   $password = password_hash($pass, PASSWORD_DEFAULT, $options);
	 }

	if (empty($_POST['passwordsignup_confirm'])) 
	{ //if no name has been supplied
	   $_SESSION['error'] = "<label style='color:red;'>Confirm Password Missing</label>";
	} else 
	{
	   $password2 = $_POST['passwordsignup_confirm']; //else assign it a variable
	}

	if (strlen($_POST['passwordsignup']) < 6) 
	{ 
		$_SESSION['error'] = "<label style='color:red;'>Your First Password must be greater than 6</label>";
	}
	if ($_POST['passwordsignup'] !== $_POST['passwordsignup_confirm']) 
	{ 
		$_SESSION['error'] = "<label style='color:red;'>Password do not match</label>";
	}
			   
			  
	if(!(isset($_SESSION['error'])) || $_SESSION['error']=="")
	{
		//Registration
		$data = (object)Array(
			'uname' => $uname,
			'package'=>$package,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'email' => $email,
			'phone' => $phone,
			'referalid' => $referalid,
			'leaderid' => $leaderid,
			'placement'=>$placement,
			'password' => $password
		);
    	$redirect = RegistrationHandler::saveData($data);
    	header("location:$redirect");
	}
	else{
		header("location:register.php");
	}
?>