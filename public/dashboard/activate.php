<?php
    session_start();
    $data = $_SESSION["data"];
    $uname = $data["User"]["Username"];
	include('dbcon.php');
	include('transactionhistorydb.php');
	include('networkdb.php');
	require_once('userdb.php');

	require 'Handlers/ActivationHandler.php';
	
	
	if(getUserNetwork($uname) != false)
	{ 
		$usernet = mysqli_fetch_array(getUserNetwork($uname));
		if($usernet['Stage'] >=1 || $uname=='')
		{
			$_SESSION['usr'] = $uname;
			header('location: index.php');
			exit;
		}
	}
	
	$sender = mysqli_real_escape_string($con, $_POST['sender']);
	$password = mysqli_real_escape_string($con, $_POST['transactionpas']);
	
	
	$pincode1 =mysqli_query($con,"select * from wallets where Username ='$sender'") or die ('could not select from pincode'.mysqli_error($con)); 
 	$pinne2 = mysqli_num_rows($pincode1);
		while ($row = mysqli_fetch_assoc($pincode1))
		{
			$id = $row['Id'];
			$sender = $row['Username'];
			$hash = $row['Transaction_Psd'];
		}
		
	if($pinne2==0){
		session_start();
		$_SESSION["msg"] = "<label style='color:red;'>Wrong Username</label>";
		header("Location:process_activation.php");
	}
	elseif(!password_verify($password, $hash)) {
		
		session_start();
		$_SESSION["msg"] = "<label style='color:red;'>Wrong Password</label>";
		header("Location:process_activation.php");
		
	}
    else{
		$result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$sender' AND Transaction_Psd='$hash'");
		$balance = 0;
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_array($result);
			$balance = $row['Balance'];
		}
		$package = $data['Network']['Package'];
        $reg1fee = ActivationHandler::$regFees[$package];
		if($balance >= 2500 && $balance >= $reg1fee)
		{
			$referalid = $data['Network']['Referal_Id'];
			$leaderid = $data['Network']['Leader_Id'];
			$placement = $data['Network']['Placement'];
			$package = $data['Network']['Package'];
			//Debit
			if(ActivationHandler::debitRegFee($sender,$hash,$uname,$package))
			{
				if(ActivationHandler::createAccount($data))
				{
				    	//Credit Referal
					$refBonusPaid = ActivationHandler::creditReferal($referalid,$uname,$package);
					
					//Credit Registration Bonus
					$regBonusPaid = ActivationHandler::creditRegBonus($uname, $package);

					//Computes and Matches the PV
					$pvPaid = ActivationHandler::computePV($uname, $leaderid, $placement,$package);
                    
                    $fee = ActivationHandler::$regFees[$package];
                    
                    $date = date("Y-m-d H-i-s");
                    $cprofit = $fee - ($refBonusPaid + $regBonusPaid + $pvPaid + 500);
                    
                    mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$uname','Registration Wallet','$fee','$cprofit','$date')");
                    	

					$_SESSION['usr'] = $uname;
					$_SESSION['dashboardnotice'] = "<label style='color:green;'>Account activation successful, WELCOME TO DAYPAYZ!</label>";
					$_SESSION['lognotice'] = "<label style='color:green;'><font size='5'>Registration Successful</font></label>";
					
					header('location:./index.php');
				}
				else
				{
					$_SESSION["msg"] = "<label style='color:red;'>Unable to Create Account account!</label>";
					header("Location:./process_activation.php");
				}
			}
			else
			{
				$_SESSION["msg"] = "<label style='color:red;'>Unable to debit account!</label>";
				header("Location:./process_activation.php");
			}
		}else{
			$_SESSION["msg"] = "<label style='color:red;'>Insufficient balance in wallet!</label>";
			header("Location:./process_activation.php");
		}
            
}
?>