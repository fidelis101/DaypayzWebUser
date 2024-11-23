<?php
	session_start();
	include('dbcon.php');
	include('regvalidate.php');
	require_once('userdb.php');
	
	$leadervalidate = new regvalidate();


	if (empty($_POST['username'])) 
	{ 
	    //if no name has been supplied
	   $_SESSION['error'] = "<label style='color:red;'>Username Missing</label>";
	} 
	else {
    	$uname = $_POST['username']; //else assign it a variable
    	$result = mysqli_query($con,"SELECT * FROM networks WHERE Username='$uname'"); 
    	if(mysqli_fetch_array($result)>0 || $uname=='daypayz') {   
    	   $_SESSION['error'] = "<label style='color:green;'>User has been registered </label>"; 
    	}
	}
	if (preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $_POST['username']) == true) 
	{ 
		$_SESSION['error'] = "<label style='color:red;'>Your Username must not contain any spaces</label>";
    }
	else if( preg_match('/\s/',$_POST['username']) )
    {
    	$_SESSION['error'] = "<label style='color:red;'>Your Username must not contain any spaces</label>";
    }
    else if(strlen($_POST['username']) <4)
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
		if(empty($_POST['placement']))
		{
			$result = $con->query("SELECT * FROM networks WHERE Username='$referalid'");
			if(mysqli_num_rows($result) > 0)
			{
				$leaderid = $leadervalidate->getPlacement($referalid);
			}
			else{
				$_SESSION['error'] = "<label style='color:red;'>The referral does not exist in this Package</label>";
			}
		}else{
			$leaderid = mysqli_real_escape_string($con, $_POST['placement']);
		}
		
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
			   
			  
	if(!(isset($_SESSION['error']))|| $_SESSION['error']=="")
	{
    	//Registration
    	saveData();
	}
	else{
		header("location:add_user.php");
	}
?>

<?php
	function saveData()
	{
		include('dbcon.php');
		
		global $package,$uname,$firstname,$lastname,$email,$phone,$referalid,$leaderid,$password;
		
		$result1 = mysqli_query($con,"SELECT * FROM networks WHERE Username= '$referalid'");
		if(mysqli_num_rows($result1) > 0)
		{
			$referalid = mysqli_fetch_array($result1)['Username'];
    		$result = mysqli_query($con,"SELECT * FROM networks WHERE Username= '$leaderid'");
    		if(mysqli_num_rows($result) > 0)
    		{
    			$row = mysqli_fetch_array($result);
        			if($row['Stage'] == 1)
        			{
        				$result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$leaderid'");
        				if(mysqli_num_rows($result) < 2)
        				{

        					$user = array('Username'=>$uname,'Firstname'=>$firstname,'Lastname'=>$lastname,'Email'=>$email,'Phone'=>$phone,'RegistrationDate'=>date("Y-m-d H:i:s"),'ActivationDate'=>'');
        					$login = array("Username"=>$uname,"Password"=>$password);
        					$wallet = array("Username"=>$uname,"Transaction_Psd"=>$password);
        					$network = array("Username"=>$uname,"Referal_Id"=>$referalid,"Leader_Id"=>$leaderid,"Stage"=>0);

        					$_SESSION['data'] = array("User"=>$user,"Login"=>$login,"Wallet"=>$wallet,"Network"=>$network);



        					/*
        					addUser(array('Username'=>$uname,'Firstname'=>$firstname,'Lastname'=>$lastname,'Email'=>$email,'Phone'=>$phone,'RegistrationDate'=>date("Y-m-d H:i:s"),'ActivationDate'=>''));
        					            
                            mysqli_query($con,"INSERT INTO logins (Username, Password,LastLogin) VALUES ('$uname', '$password','')");
                            
                            mysqli_query($con,"INSERT INTO wallets ( Username, Transaction_Psd, Balance) VALUES ('$uname', '$password',0.00)");
                            
                            mysqli_query($con,"INSERT INTO networks ( Username, Referal_Id, Leader_Id, Stage,Package) VALUES ( '$uname', '$referalid', '$leaderid',0,'')");
                            */
                            header("location:activate_accountint.php");
                            
                            /*$to = $email; // this is your Email address
                            $headers= "From: vpserviceslimited.com<info@vpserviceslimited.com>\r\n"; // this is the sender's Email address
                            $subject = "VPServices Limited";
                            $message =  "Welcome".' '.$firstname.' '.$lastname.' '."to VPServices Limited! Your Journey to the World of Wealth has just begun"."\n\n"."Username:".'  '.$uname."\n\n"."Password:".' '.$_POST['pswd']."\n\n"."Transaction Password:".' '.$_POST['newtpsd']."\n\n"."Call +2348031340282 For More Information";
                            mail($to,$subject,$message,$headers);
                            */
                            
        					
        				}
        				else
        				{
        					$_SESSION['lognotice'] = "<label style='color:red;'><font size='5'>Placement network is filled pls use a different Placement </font></label>";
        				}
        			}
        			else if($row['Stage'] == 0)
        			{
        				$_SESSION['lognotice'] = "<label style='color:red;'><font size='5'>Placement Account Not Activated Pls register under a different account</font></label>";
        			}
        			else
        			{
        			   $_SESSION['lognotice'] = "<label style='color:red;'><font size='5'>Placement network is filled pls use a different Placement</font></label>";
        			}
    		}
    		else
    		{
    		    $_SESSION['lognotice'] = "<label style='color:red;'>Placement Account Does Not Exist (Pls Enter Username with the right case)</label>";
    		}
		}
		else
		{
			$_SESSION['lognotice'] = "<label style='color:red;'>The Referal Username does not exist</label>";
		}
	
	}
	
?>