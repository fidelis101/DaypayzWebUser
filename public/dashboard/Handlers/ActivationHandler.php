<?php
require 'TransactionHistoryHandler.php';
require 'regvalidate.php';

class ActivationHandler
{
    public static $regFees = ["daypayzite"=>2500.00,"builder"=>5500.00,"team_leader"=>10500.00,"manager"=>20500.00,"senior_manager"=>50500.00];
	static $pvTable = ["daypayzite"=>10,"builder"=>20,"team_leader"=>40,"manager"=>80,"senior_manager"=>200];
	static $pvMatchTable = ["daypayzite"=>10,"builder"=>15,"team_leader"=>20,"manager"=>25,"senior_manager"=>30];
	static $regBonus = 0.1;

    static function createAccount($data)
    {
        include('dbcon.php');
        
        $username = $data['Wallet']['Username'];
        $password = $data['Login']['Password'];

        $referalid = $data['Network']['Referal_Id'];
        $leaderid = $data['Network']['Leader_Id'];
        $placement = $data['Network']['Placement'];
        $package = $data['Network']['Package'];

        $user = $data['User'];

        $useradded = mysqli_query($con,"INSERT INTO users (Username, Firstname, Lastname, Email, Phone,RegistrationDate,ActivationDate) VALUES
                            ('$user[Username]', '$user[Firstname]', '$user[Lastname]','$user[Email]',
                            '$user[Phone]','$user[RegistrationDate]','$user[RegistrationDate]')");
        
        $networkadded = mysqli_query($con,"INSERT INTO networks (Username, Referal_Id, Leader_Id, Stage,Placement,Package) VALUES ('$username', '$referalid', '$leaderid',1,'$placement','$package')");
        
        $walletadded = mysqli_query($con,"INSERT INTO wallets (Username, Transaction_Psd, Balance,RightPv,LeftPv,MatchedPv,ComissionEarned) VALUES ('$username', '$password',0.00,0.00,0.00,0.00,0.00)");
          			            
        $loginadded = mysqli_query($con,"INSERT INTO logins (Username, Password) VALUES ('$username', '$password')");
           
       	return true;
	}
	
	static function UpgradeAccount($uname,$package,$balance,$newbalance,$sender)
	{
		include('dbcon.php');
		mysqli_query($con,"UPDATE networks SET Package='$package' WHERE Username='$uname'");
				   
		TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$package,'Receiver'=>'Account Upgrade','Sender'=>$sender,'Description'=>"Debit: Account Upgrade",'init_bal'=>$balance,'final_bal'=>$newbalance));
		return true;
	}

    static function computePV($uname, $leaderid, $position,$package)
    {
		include('dbcon.php');
		$leader = $leaderid;
		$totalPaid = 0;
		//count current leader right and left
		$i = 1;
		while($i > 0)
		{
			if(isset($leader) && $leader != '')
			{
				if($position == "right")
				{
					$result = mysqli_query($con,"SELECT RightPv,RightBonusPoint FROM wallets WHERE Username='$leader'");
					$row = mysqli_fetch_assoc($result);
					$rightPv = $row['RightPv'];
					$rightBonusPv = $row['RightBonusPoint'];
					$rightPv = self::$pvTable[$package] + $rightPv;
					$rightBonusPv = self::$pvTable[$package] + $rightBonusPv;
					mysqli_query($con,"UPDATE wallets SET RightPv='$rightPv',RightBonusPoint='$rightBonusPv' WHERE Username='$leader'");
					$totalPaid += self::matchPV($leader);
				}
				elseif($position == "left")
				{
					$result = mysqli_query($con,"SELECT LeftPv,LeftBonusPoint FROM wallets WHERE Username='$leader'");
					$row = mysqli_fetch_assoc($result);
					$leftPv = $row['LeftPv'];
					$leftBonusPv = $row['LeftBonusPoint'];
					$leftPv = self::$pvTable[$package] + $leftPv;
					$leftBonusPv = self::$pvTable[$package] + $leftBonusPv;
					mysqli_query($con,"UPDATE wallets SET LeftPv='$leftPv',LeftBonusPoint='$leftBonusPv' WHERE Username='$leader'");
					$totalPaid += self::matchPV($leader);
				}
			}else
			{
				$i = 0;
			}

			$result1 = mysqli_query($con,"SELECT Leader_Id,Placement FROM networks WHERE Username='$leader'");
			if(mysqli_num_rows($result1)>0)
			{
				$row = mysqli_fetch_assoc($result1);
				$leader = $row['Leader_Id'];
				$position = $row['Placement'];
			}else{
				$leader = '';
				$i = 0;
			}
		}
		return $totalPaid;
	}

	public static function matchPV($username)
	{
		include('dbcon.php');
		$totalMatchedAmount = 0;
		$result = mysqli_query($con,"SELECT RightPv,LeftPv,MatchedPv,MatchedBonusPoint,Balance FROM wallets WHERE Username='$username'");

		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_array($result);
			$rightPv = $row['RightPv'];
			$leftPv = $row['LeftPv'];
			$matchedPv = $row['MatchedPv'];
			$matchedBonusPoint = $row['MatchedBonusPoint'];
			$balance = $row['Balance'];
			$pendingPv = 0;

			//Normal Pv Matching
			if($rightPv > $leftPv)
				$pendingPv = $leftPv;
			elseif($rightPv < $leftPv)
				$pendingPv = $rightPv;
			else
				$pendingPv = $leftPv;

			if(($pendingPv != 0) && ($pendingPv > $matchedPv))
			{
				$pvToPay = $pendingPv - $matchedPv;
				$package = '';
				$result = mysqli_query($con,"SELECT Package FROM networks WHERE Username='$username'");
				if(mysqli_num_rows($result)>0)
				{
					$row = mysqli_fetch_array($result);
					$package = $row['Package'];
				}
				if($package != '')
				{
					$multiple = self::$pvMatchTable[$package];
					$pvAmount = $pvToPay * $multiple;
					$matchedPv = $pendingPv;
					$matchedBonusPoint = $matchedBonusPoint + $pvToPay;
					$newbalance = $balance + $pvAmount;
					$totalMatchedAmount += $pvAmount;
					
					mysqli_query($con,"UPDATE wallets SET Balance='$newbalance',MatchedPv='$matchedPv',MatchedBonusPoint='$matchedBonusPoint' WHERE Username='$username'");
					TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$pvAmount,'Receiver'=>$username,'Sender'=>'MatchedPv','Description'=>"Credit Alert: $pvToPay GPV Matched by N$multiple per GPV",'init_bal'=>$balance,'final_bal'=>$newbalance));
				}
			}
		}
		return $totalMatchedAmount;
	}
	
    static function debitRegFee($sender,$hash,$uname,$package)
	{
        include('dbcon.php');
        $fee = self::$regFees[$package];
		$result = mysqli_query($con,"SELECT Balance FROM wallets WHERE Username='$sender' AND Transaction_Psd='$hash'");
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_array($result);
			$bal = $row['Balance'];
			$balance = $bal - $fee;
			$date = date("Y-m-d H-i-s");
            if(mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$sender'"))
            {
                mysqli_query($con,"UPDATE users SET ActivationDate='$date' WHERE Username='$uname'");
                TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$fee,'Receiver'=>'','Sender'=>$sender,'Description'=>"Debit Alert: Registration Fee of $fee for ".$uname,'init_bal'=>$bal,'final_bal'=>$balance));
                return true;
            }
            else
                return false;
        }
    }
    
    static function creditRegBonus($uname,$package)
	{
        include('dbcon.php');
        //Calculating The bonus
        $bonus = (self::$regBonus) * (self::$regFees[$package] - 500);

		$result = mysqli_query($con,"SELECT * From wallets WHERE Username='$uname'");
		if(mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_array($result);
			$ibal = $row['Balance'];
			$balance = $row['Balance'] + $bonus;
			mysqli_query($con,"UPDATE wallets SET Balance = '$balance' WHERE Username='$uname'");
			TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$bonus,'Receiver'=>$uname,'Sender'=>'registration_bonus','Description'=>'Credit Alert: Registration Bonus','init_bal'=>$ibal,'final_bal'=>$balance));
        }
        return $bonus;
	}
	
	static function creditReferal($referal,$uname,$package)
	{
        include('dbcon.php');
        $totalRefBonusPaid = 0;
        $fee = self::$regFees[$package];
        $fee = $fee - 500;
        //First Referral 18%
        ActivationHandler::creditRef($uname,$referal,(0.18 * $fee),"Referral Bonus from $uname");
        $totalRefBonusPaid += (0.18 * $fee);
        
        //Second Ref 2.5%
        $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
        $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];
        ActivationHandler::creditRef($uname,$referal,(0.025 * $fee),"2nd Level Referral Bonus from $uname");
        $totalRefBonusPaid += (0.025 * $fee);
        
        //3rd and 4th 1.5%
		$i =1;
		while($i <= 2)
		{
            $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
            $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];
            
            $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
            $refPack = mysqli_fetch_array($refnetwork)['Package'];
            
			if($referal !== '')
			{
				$result2 = mysqli_query($con,"SELECT * From wallets WHERE Username='$referal'");
				if(mysqli_num_rows($result2) > 0)
				{
				    if ($i ==1)
				        $nth = "3rd";
				    else
				        $nth = "4th";
				    
				    if($i != 1 && $refPack == "daypayzite")
				    {
				        
				    }else
				    {
    					ActivationHandler::creditRef($uname,$referal,(0.015 * $fee),"$nth Level Referral Bonus from $uname");
    					$totalRefBonusPaid += (0.015 * $fee);
				    }
				}
			}
			$i++;
        }
        
        
            //5th and 6th 1.2%
            $i =1;
    		while($i <= 2)
    		{
                $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
                $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];
                
                $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
                $refPack = mysqli_fetch_array($refnetwork)['Package'];
                
    			if($referal !== '')
    			{
    				$result2 = mysqli_query($con,"SELECT * From wallets WHERE Username='$referal'");
    				if(mysqli_num_rows($result2) > 0)
    				{
    				    if ($i ==1)
    				        $nth = "5th";
    				    else
    				        $nth = "6th";
    				    
    				    if(($i != 1 && $refPack == "builder") || $refPack == "daypayzite")
    				    {
    				        
    				    }else
    				    {
    				       ActivationHandler::creditRef($uname,$referal,(0.012 * $fee),"$nth Level Referral Bonus from $uname"); 
    				       $totalRefBonusPaid += (0.012 * $fee);
    				    }
    				}
    			}
    			$i++;
    		}
        
        //7th Ref 1.1%
            $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
            $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];
            
            $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
            $refPack = mysqli_fetch_array($refnetwork)['Package'];
            
            if($refPack == "builder" || $refPack == "daypayzite" || $refPack == "team_leader")
    		{
    		}else
    		{
                ActivationHandler::creditRef($uname,$referal,(0.011 * $fee),"7th Level Referral Bonus from $uname");
                $totalRefBonusPaid += (0.011 * $fee);
    		}
            //8th, 9th and 10th 0.01%
            $i =1;
    		while($i <= 3)
    		{
                $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
                $referal = mysqli_fetch_array($refnetwork)['Referal_Id'];
                
                $refnetwork = mysqli_query($con,"SELECT * From networks WHERE Username='$referal'");
                $refPack = mysqli_fetch_array($refnetwork)['Package'];
            
    			if($referal !== '')
    			{
    				$result2 = mysqli_query($con,"SELECT * From wallets WHERE Username='$referal'");
    				if(mysqli_num_rows($result2) > 0)
    				{
    				    if ($i ==1)
    				        $nth = "8th";
    				    else if($i == 2)
    				        $nth = "9th";
    				    else
    				        $nth = "10th";
    				    
    				    if(($i != 1 && $refPack == "manager") || $refPack == "builder" || $refPack == "daypayzite" || $refPack == "team_leader")
    				    {
    				        
    				    }else
    				    {
    					ActivationHandler::creditRef($uname,$referal,(0.01 * $fee),"$nth Level Referral Bonus from $uname");
    					$totalRefBonusPaid += (0.01 * $fee);
    				    }
    				}
    			}
    			$i++;
            }
        return $totalRefBonusPaid;
	}
    
    static function creditRef($uname,$referal,$bonus,$description)
    {
        include('dbcon.php');
        $result2 = mysqli_query($con,"SELECT * From wallets WHERE Username='$referal'");
        if(mysqli_num_rows($result2) > 0)
        {
            $row = mysqli_fetch_array($result2);
            $ibal = $row['Balance'];
			$balance = $row['Balance'] + $bonus;
			
            mysqli_query($con,"UPDATE wallets SET Balance = '$balance' WHERE Username='$referal'");
            TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$bonus,'Receiver'=>$referal,'Sender'=>'ref_bonus','Description'=>$description,'init_bal'=>$ibal,'final_bal'=>$balance));
        }
    }    
}
?>