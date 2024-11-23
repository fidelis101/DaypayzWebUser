<?php
    session_start();
	include('dbcon.php');
    include('transactionhistorydb.php');
    require_once 'Handlers/TransactionHistoryHandler.php';
    require_once 'Handlers/UpgradeHandler.php';
	
	$leadervalidate = new regvalidate();
	
	$sender = $_SESSION["usr"];
	$password = mysqli_real_escape_string($con, $_POST['tpsd']);
	$package = mysqli_real_escape_string($con, $_POST['package']);
    $username = $_SESSION["usr"];
    $uname = $username;
    
	$pincode1 =mysqli_query($con,"select * from wallets where Username ='$sender'") or die ('could not select from pincode'.mysql_error()); 
 	$pinne2 = mysqli_num_rows($pincode1);
    $row = mysqli_fetch_assoc($pincode1);
	$sender = $row['Username'];
	$hash = $row['Transaction_Psd'];
	
    if($pinne2==0){
        $_SESSION["upgrademsg"] = "<label style='color:red;'>Wrong Username or Password</label>";
        header("Location:accountupgrade.php");
    }
    elseif(!password_verify($password, $hash)) {
        $_SESSION["upgrademsg"] = "<label style='color:red;'>Wrong Username or Password</label>";
        header("Location:accountupgrade.php");
    }
    else{
        $rs = mysqli_query($con,"SELECT * FROM networks Where Username='$uname'");
        if($rs->num_rows)
        {
            $pack = mysqli_fetch_array($rs)['Package'];
            $package_val = UpgradeHandler::$regFees[$package];
            $pack_val = UpgradeHandler::$regFees[$pack];
            
            if( $package_val > $pack_val)
            {
                $upgrade_charge = $package_val - $pack_val;
                
                $result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$sender' AND Transaction_Psd='$hash'");
        		$balance = 0;
        		if(mysqli_num_rows($result)>0)
        		{
        			$row = mysqli_fetch_array($result);
        			$balance = $row['Balance'];
        		}

				if($balance >= $upgrade_charge)
				{
                    $rs1 = mysqli_query($con,"SELECT * FROM networks WHERE Username='$uname'");
                    $row1 = mysqli_fetch_assoc($rs1);
                    $old_package = $row1['Package'];
                    $referal = $row1['Referal_Id'];
                    $leaderid = $row1['Leader_Id'];
                    $position = $row1['Placement'];

				    if(UpgradeHandler::debitUpgradeFee($sender,$hash,$uname,$package,$old_package))
                    {
                        if(UpgradeHandler::upgradeAccount($uname,$package))
                        {
		                    
                            //Credit Registration Bonus
                            $upgradeBonusPaid = UpgradeHandler::creditUpgradeBonus($uname,$package,$old_package);
                            
                            //Credit Referal
                            $refBonusPaid = UpgradeHandler::creditReferal($referal,$uname,$package,$old_package);

                            //Computes and Matches the PV
                            $upgradePvMatched = UpgradeHandler::computeUpgradePV($uname, $leaderid,$position,$package,$old_package);
                            
                            
                            $fee = UpgradeHandler::$regFees[$package] - UpgradeHandler::$regFees[$old_package];
                            
                            $date = date("Y-m-d H-i-s");
                            $cprofit = $fee - ($refBonusPaid + $upgradeBonusPaid + $upgradePvMatched);
                    
                            mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$uname','Upgrade','$fee','$cprofit','$date')");
                
                            $_SESSION['usr'] = $uname;
                            
                            $_SESSION['upgrademsg'] = "<label style='color:green;'>Account upgrade successful!</label>";
                            $_SESSION['upgrademsg'] = "<label style='color:green;'><font size='5'>Upgrade Successful</font></label>";
                            
                            header('location:./accountupgrade.php');
                        }
                        else
                        {
                            $_SESSION["upgrademsg"] = "<label style='color:red;'>Unable to Create Account account!</label>";
                            header("Location:./accountupgrade.php");
                        }
                    }
                    else
                    {
                        $_SESSION["upgrademsg"] = "<label style='color:red;'>Unable to debit account!</label>";
                        header("Location:./accountupgrade.php");
                    }
				}
            	else
            	{
            		$_SESSION["upgrademsg"] = "<label style='color:red;'>Insufficient balance in wallet!</label>";
                    header("Location:./accountupgrade.php");
        		}
            }else
            {
                $_SESSION["upgrademsg"] = "<label style='color:red;'>The selected account is currently on a higher package!</label>";
                header("Location:./accountupgrade.php");
            }
        }else
        {
           $_SESSION["upgrademsg"] = "<label style='color:red;'>User account does not exist!</label>";
            header("Location:./accountupgrade.php");
        }
	}
?>