<?php
    class RegistrationHandler
    {
        
	static function saveData($data)
	{
		include('./dbcon.php');
		
        $uname = $data->uname;
        $package=$data->package;
        $firstname = $data->firstname;
        $lastname = $data->lastname;
        $email = $data->email;
        $phone = $data->phone;
        $referalid = $data->referalid;
        $leaderid = $data->leaderid;
        $placement = $data->placement;
        $password = $data->password;
        
		$redirect = "";

		if(self::checkUser($uname) > 0)
		{
			$_SESSION['error'] = "<label style='color:red;'>Username $uname already taken</label>";
				$redirect = "./register.php";
		}
		else{	
		$result1 = mysqli_query($con,"SELECT * FROM networks WHERE Username= '$referalid'");
		if(mysqli_num_rows($result1) > 0)
		{
			$referalid = mysqli_fetch_array($result1)['Username'];
    		$result = mysqli_query($con,"SELECT * FROM networks WHERE Username= '$leaderid'");
    		if(mysqli_num_rows($result) > 0)
    		{
    			$row = mysqli_fetch_array($result);
        			if($row['Stage'] > 0)
        			{
        				$result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$leaderid' AND Placement='$placement'");
        				if(mysqli_num_rows($result) < 2)
        				{

        					$user = array('Username'=>$uname,'Firstname'=>$firstname,'Lastname'=>$lastname,'Email'=>$email,'Phone'=>$phone,'RegistrationDate'=>date("Y-m-d H:i:s"),'ActivationDate'=>'');
        					$login = array("Username"=>$uname,"Password"=>$password);
        					$wallet = array("Username"=>$uname,"Transaction_Psd"=>$password);
        					$network = array("Username"=>$uname,"Referal_Id"=>$referalid,"Leader_Id"=>$leaderid,"Stage"=>0,"Placement"=>$placement,"Package"=>$package);

        					$_SESSION['data'] = array("User"=>$user,"Login"=>$login,"Wallet"=>$wallet,"Network"=>$network);

                            $redirect ="./activate_account.php";
        				}
        				else
        				{
        					$_SESSION['error'] = "<label style='color:red;'><font size='5'>Placement network is filled pls use a different Placement </font></label>";
        					$redirect = "./register.php";
        				}
        			}
        			else if($row['Stage'] == 0)
        			{
        				$_SESSION['error'] = "<label style='color:red;'><font size='5'>Placement($leaderid) Account Not Activated Pls register under a different account</font></label>";
        				$redirect = "./register.php";
        			}
        			else
        			{
        			   $_SESSION['error'] = "<label style='color:red;'><font size='5'>Placement network is filled pls use a different Placement</font></label>";
        			   $redirect = "./register.php";
        			}
    		}
    		else
    		{
    		    $_SESSION['error'] = "<label style='color:red;'>Placement Account Does Not Exist $leaderid (Pls Enter Username with the right case)</label>";
    		   $redirect = "./register.php";
    		}
		}
		else
		{
			$_SESSION['error'] = "<label style='color:red;'>The Referal Username does not exist</label>";
			$redirect = "./register.php";
		}
	}
	
	return $redirect;
	}
	
	static function checkUser($username)
	{
		include('./dbcon.php');

		$sqlNet="SELECT Username FROM networks WHERE Username ='$username'";
		$sqlUser="SELECT Username FROM users WHERE Username ='$username'";
		$sqlWallet="SELECT Username FROM wallets WHERE Username ='$username'";
		$sqlLogins="SELECT Username FROM logins WHERE Username ='$username'";

		$resNet=$con->query($sqlNet);
		$resUser=$con->query($sqlUser);
		$resWallet=$con->query($sqlWallet);
		$resLogins=$con->query($sqlLogins);

		 $rows_returned = $resUser->num_rows + $resNet->num_rows + $resWallet->num_rows + $resLogins->num_rows;
		 return $rows_returned;
	}
}
?>