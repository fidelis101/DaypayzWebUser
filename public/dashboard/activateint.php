<?php
    session_start();
    $data = $_SESSION["data"];
    $uname = $data["User"]["Username"];
	include('dbcon.php');
	include('transactionhistorydb.php');
	include('networkdb.php');
	require_once('userdb.php');
	
	
	if(getUserNetwork($uname) != false){ $usernet = mysqli_fetch_array(getUserNetwork($uname));}
	if($usernet['Stage'] >=1 || $uname=='')
      {
      	$_SESSION['usr'] = $uname;
          header('location: index.php');
          exit;
      }

	$sender = mysqli_real_escape_string($con, $_POST['sender']);
	$password = mysqli_real_escape_string($con, $_POST['transactionpas']);
	
	
	$pincode1 =mysqli_query($con,"select * from wallets where Username ='$sender'") or die ('could not select from pincode'.mysql_error()); 
 	$pinne2 = mysqli_num_rows($pincode1);
		while ($row = mysqli_fetch_assoc($pincode1))
		{
			$id = $row['id'];
			$sender = $row['Username'];
			$hash = $row['Transaction_Psd'];
		}
		
	if($pinne2==0){
		session_start();
		$_SESSION["msg"] = "<label style='color:red;'>Wrong Username</label>";
		header("Location:process_activationint.php");
	}
	elseif(!password_verify($password, $hash)) {
		
		session_start();
		$_SESSION["msg"] = "<label style='color:red;'>Wrong Password</label>";
		header("Location:process_activationint.php");
		
	}
    else{
		
		$userexist = $con->query("SELECT * FROM networks WHERE Username='$uname'");
	
		$user = mysqli_fetch_array($userexist);
		$referalid = $user['Referal_Id'];
		$leaderid =  $user['Leader_Id'];
		
		$result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$sender' AND Transaction_Psd='$hash'");
		$balance = 0;
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_array($result);
			$balance = $row['Balance'];
		}

		if($balance >= 2500.00)
		{
			 $username = $data['Wallet']['Username'];
			 $password = $data['Login']['Password'];

			 $referalid = $data['Network']['Referal_Id'];
			 $leaderid = $data['Network']['Leader_Id'];

			
        	addUser($data['User']);
        					            
            $r1 = mysqli_query($con,"INSERT INTO logins (Username, Password) VALUES ('$username', '$password')");
            if($r1 > 0)
            {              
            	$r2 = mysqli_query($con,"INSERT INTO wallets (Username, Transaction_Psd, Balance) VALUES ('$username', '$password',0.00)");
            }
            if($r2 > 0)          
            $r3 = mysqli_query($con,"INSERT INTO networks (Username, Referal_Id, Leader_Id, Stage) VALUES ('$username', '$referalid', '$leaderid',0)");
            
            if($r1 > 0 && $r3>0)
            {
			//Debit
			debitRegFee($sender,$hash,$uname);

			//Credit Referal
			creditReferal($referalid,$uname);

			//Credit Leader's Leader
			//creditLeader($leaderid,$uname);
			
			//Credit Registration Bonus
			creditRegBonus($uname, $leaderid);

			//Check For stage upgrade
			stageUpgrade($leaderid,$uname);

			$_SESSION['dashboardnotice'] = "<label style='color:green;'>Account added Successfully $uname</label>";
			}
			header('location:index.php');
			
		}
    
		else
		{
		    $_SESSION["msg"] = "<label style='color:red;'>Insufficient balance in wallet!</label>";
            header("Location:process_activationint.php");
		}
}
?>

<?php

	function creditRegBonus($uname,$leaderid)
	{
		include('dbcon.php');
		$leader = $leaderid;
		$cprofit = 1350;
		
		$i =1;
		while($i <= 20)
		{
			if($leader !== '')
			{
				$result2 = mysqli_query($con,"SELECT * From wallets WHERE Username='$leader'");
				if(mysqli_num_rows($result2) > 0)
				{
					$row = mysqli_fetch_array($result2);
					$ibal = $row['Balance'];
					$balance = $row['Balance'] + 20;
					$cprofit = $cprofit - 20;
					mysqli_query($con,"UPDATE wallets SET Balance = '$balance' WHERE Username='$leader'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>20,'Receiver'=>$leader,
													'Sender'=>'30bonus','Description'=>'Credit Alert:Binary Bonus from '.$uname,'init_bal'=>$ibal,'final_bal'=>$balance));
					
					$dtime = date("Y-m-d H:i:s");
					$dtime1 = date("Y-m-d");
				
					$result1 = mysqli_query($con,"SELECT * From networks WHERE Username='$leader'");
					$leader = mysqli_fetch_array($result1)['Leader_Id'];
				}
			}
			
			$i++;
		}
		//company share
		$date = date("Y-m-d H:i:s");
				mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$uname','Registration','2500','$cprofit','$date')");
	}


	function creditLeader($leaderid,$uname)
	{
	    
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * From networks WHERE Username='$leaderid'");
			$row = mysqli_fetch_array($result);
			$placementid = $row['Leader_Id'];
			$result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$placementid'");
			if(mysqli_num_rows($result)>0)
			{	
				$row = mysqli_fetch_array($result);
				$bal = $row['Balance'];
				$balance = $bal + 150.00;
				mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$placementid'");
				saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>150.00,'Receiver'=>$placementid,
											'Sender'=>'','Description'=>"Credit Alert: Placement Bonus from ".$uname,'init_bal'=>$bal,'final_bal'=>$balance));
			$dtime1 = date("Y-m-d");
			$amt = 150;
		mysqli_query($con,"insert into indirect_bonus(username,amt,user,datetrans,date)values('$placementid','$amt','$uname',$dtime1',now())");
		}
	}
	
	function creditReferal($referal,$sender)
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT * From wallets WHERE Username='$referal'");
			$row = mysqli_fetch_array($result);
			$bal = $row['Balance'];
			$balance = $bal + 500.00;
			mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$referal'");
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>500.00,'Receiver'=>$referal,
											'Sender'=>'','Description'=>'Credit Alert: ReferalBonus of 500.00 from '. $sender,'init_bal'=>$bal,'final_bal'=>$balance));
		$dtime1 = date("Y-m-d");
			$amt = 500;
		mysqli_query($con,"insert into referal_bonus(username,amt,user,datetrans,date)values('$referal','$amt','$sender',$dtime1',now())");
	}
	
	function debitRegFee($sender,$hash,$uname)
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT Balance FROM wallets WHERE Username='$sender' AND Transaction_Psd='$hash'");
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_array($result);
			$bal = $row['Balance'];
			$balance = $bal - 2500.00;
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$sender'");
			mysqli_query($con,"UPDATE users SET ActivationDate='$date' WHERE Username='$uname'");
			mysqli_query($con,"UPDATE networks SET Stage = 1,Updated='$date' WHERE Username='$uname'");
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>2500.00,'Receiver'=>'',
											'Sender'=>$sender,'Description'=>'Debit Alert: Registration Fee of 2500.00 for '.$uname,'init_bal'=>$bal,'final_bal'=>$balance));
		}
	}
	
	function stageUpgrade1($leaderid)
	{
	    include('dbcon.php');
	    $result = mysqli_query($con,"SELECT * FROM networks WHERE Username='$leaderid'");
				if(mysqli_num_rows($result) > 0)
				{
					$row = mysqli_fetch_array($result);
					$l_leader = $row['Leader_Id'];
	                stageUpgrade($l_leader,$leaderid);
				}
	}
	function stageUpgrade($leaderid,$user)
	{	
		include('dbcon.php');
		$net =mysqli_query($con,"SELECT * FROM networks where Username = '$leaderid'");
		$row1 = mysqli_fetch_array($net);
		$lleader = $row1['Leader_Id'];
		
		$net2 =mysqli_query($con,"SELECT * FROM networks where Username = '$lleader'");
		$row2 = mysqli_fetch_array($net2);
		$stage_com = $row2['Stage'];
		$result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$leaderid'");
		if(mysqli_num_rows($result) >= 2)
		{
			$stages = array(); 
			while($row = mysqli_fetch_array($result))
			{
			    if($row['Username'] === $user)
				{
				    $stages[1] = $row['Stage'];
				}else
				{
				    $stages[2] = $row['Stage'];
				}
			}
			if($stages[2] >= $stages[1] && $stages[1]>= $stage_com)
			{
				$result = mysqli_query($con,"SELECT * FROM networks WHERE Username='$leaderid'");
				if(mysqli_num_rows($result) > 0)
				{
					$row = mysqli_fetch_array($result);
					$l_leader = $row['Leader_Id'];

					$result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$l_leader'");
					if(mysqli_num_rows($result) >= 2)
					{
					    $count =3;
						while($row = mysqli_fetch_array($result))
						{
            			
            					$stages[$count] = $row['Stage'];
            				    $count++;
						}
						if($stages[3] >= $stage_com && $stages[4] >=$stage_com )
						{
							$result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$l_leader'");
							while($row=mysqli_fetch_array($result))
							{
								if($row['Username'] != $leaderid)
								{
									$co_leader = $row['Username'];
									$result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$co_leader'");
									if(mysqli_num_rows($result) >= 2)
									{
									    $count = 5;
									    while($row = mysqli_fetch_array($result))
						                {
						                    
									   
						                    $stages[$count] =$row['Stage'];
						                    $count++;
						                }
										
										if($stages[5] >= $stage_com && $stages[6] >= $stage_com)
										{
										    
										    $result = mysqli_query($con,"SELECT * FROM networks WHERE Username = '$l_leader'");
											$row = mysqli_fetch_array($result);
											$stage = $row['Stage'];
												$stage = $stage + 1;
												$date = date("Y-m-d H-i-s");
												mysqli_query($con,"UPDATE networks SET Stage = '$stage', Updated='$date' WHERE Username='$l_leader'");
										creditStageBonus($stage,$l_leader);
										stageUpgrade1($l_leader);
										}
									}
								}
							
							}
						}
					}
				}
			}

		}
	}
	
	function creditStageBonus($stage,$username)
	{
		include('dbcon.php');
		$result = mysqli_query($con,"SELECT Balance FROM wallets WHERE Username='$username'");
		if(mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_array($result);
			$bal = $row['Balance'];
			switch($stage)
			{
				case 3:
					$balance = $bal + 2000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>2000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 1','init_bal'=>$bal,'final_bal'=>$balance));
					break;
				case 4:
					$balance = $bal + 10000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>10000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 2','init_bal'=>$bal,'final_bal'=>$balance));
					break;
				case 5:
					$balance = $bal + 20000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>20000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 3','init_bal'=>$bal,'final_bal'=>$balance));
					break;
				case 6:
					$balance = $bal + 50000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>50000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 4','init_bal'=>$bal,'final_bal'=>$balance));
					break;
				case 7:
					$balance = $bal + 120000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>120000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 5','init_bal'=>$bal,'final_bal'=>$balance));
					break;
				case 8:
					$balance = $bal + 500000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>500000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 6','init_bal'=>$bal,'final_bal'=>$balance));
					break;
					case 9:
					$balance = $bal + 2000000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>2000000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 7','init_bal'=>$bal,'final_bal'=>$balance));
					break;
					case 10:
					$balance = $bal + 4000000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>4000000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 8','init_bal'=>$bal,'final_bal'=>$balance));
					break;
					case 11:
					$balance = $bal + 15000000;
					mysqli_query($con,"UPDATE wallets SET Balance=$balance WHERE Username='$username'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>15000000,'Receiver'=>$username,
											'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 9','init_bal'=>$bal,'final_bal'=>$balance));
					break;
					
				default:
				    break;
			}
		}
	}
    
?>
