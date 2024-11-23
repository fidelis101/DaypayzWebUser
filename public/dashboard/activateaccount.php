<?php
		
	class activateAccount{

		private $username;
		private $package;
		private $walletuser;
		private $walletpas;
			
		public function _construct($username,$package,$walletuser,$walletpas)
		{
			$this->username = $username;
			$this->package = $package;
			$this->walletuser = $walleteuser;
			$this->walletpas = $walletpas;

		}

		public function checkUser()
		{
			$checkresult = false;
			$user = $this->username;
			$result = mysqli_query($con,"SELECT * FROM networks WHERE Username='$user'");
			if($result->num_rows() > 0)
			{
				$row = $result->fetch_array();
				if($row['Stage'] == 0)
				{
					$response = "User account has not been activated";
				}else
				{
					$response = "User account has been activated";
				}
			}else
			{
				$response = "User does not exist";
			}
		}

		public function activate()
		{
			$walletuser = $this->walletuser; $walletpas = $this->walletpas;
			$result = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$walletuser'");
			if($result->num_rows() > 0)
			{
				while($row = $result->fetch_array())
				{
					$id = $row['Id'];
					$uname = $row['Username'];
					$balance = $row['Balance'];
					$hash = $row['Password'];
				}
				if(!password_verify($walletpas,$hash))
				{
					$response = "Wrong Password";
				}
				else if($balance >= $this->package)
				{
					$userexist = $con->query("SELECT * FROM networks WHERE Username='$this->username'");
					
					if($userexist)
					{
						$user = mysqli_fetch_array($userexist);
						$referalid = $user['Referal_Id'];
						$leaderid =  $user['Leader_Id'];
						$stage = $user['Stage'];
					}

					if($stage <= 0)
					{
						//Debit
						debitRegFee($uname,$hash,$this->username,$package);

						//Credit Registration Bonus
						creditRegBonus($this->username,$sender);

						//Credit Referal
						creditReferal($referalid,$this->username);

						//Credit Leader's Leader
						creditLeader($leaderid,$this->username);

						//Check For stage upgrade
						stageUpgrade($leaderid,$this->username);

						$response = "Acoount activated Successfully";
					}else
					{
						$response = "The Account is already activated";
					}
				}
				else
				{
					$response = "Insufficient Balance, you must have atleast N $this->package in your account";
				}
			}else
			{
				$response = "Wrong Wallet Username";
			}
		}	
 		 
		function debitRegFee($sender,$hash,$username,$package)
		{
			include('dbcon.php');
			$result = mysqli_query($con,"SELECT Balance FROM Wallets WHERE Username='$sender' AND Transaction_Psd='$hash'");
			if(mysqli_num_rows($result)>0)
			{
				$row = mysqli_fetch_array($result);
				$bal = $row['Balance'];
				$balance = $bal - 10000.00;
				mysqli_query($con,"UPDATE Wallets SET Balance='$balance' WHERE Username='$sender'");
				saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>10000.00,'Receiver'=>'',
												'Sender'=>$sender,'Description'=>'Debit Alert: Registration Fee of 10000.00 for '.$username,'init_bal'=>$bal,'final_bal'=>$balance));

				mysqli_query($con,"UPDATE Networks SET Stage = 1 WHERE Username='$username'");
			}
		}

 		function creditRegBonus($uname,$sender)
		{
			include('dbcon.php');
			$result1 = mysqli_query($con,"SELECT * From Wallets WHERE Username='$uname'");
			$result2 = mysqli_query($con,"SELECT * From Networks WHERE Username='$uname'");
			if($result1->num_rows)
			{
			    $row = mysqli_fetch_array($result1);
			    $ibal = $row['Balance'];
			    $balance = $row['Balance'] + 2000;
				mysqli_query($con,"UPDATE Wallets SET Balance = '$balance' WHERE Username='$uname'");
				saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>2000,'Receiver'=>$uname,
												'Sender'=>'','Description'=>'Credit Alert:Registration Bonus of 20%','init_bal'=>$ibal,'final_bal'=>$balance));
				
				$dtime = date("Y-m-d H:i:s");
				$dtime1 = date("Y-m-d");
				$amt = 5500;
				$userfee = 2000;
				mysqli_query($con,"insert into reg_fees(Username,amount,datetrans,date)values('$uname','$amt','$dtime1','$dtime')");
					
				mysqli_query($con,"insert into user_fees(id,username,amt,datetrans,date)values('$uname','$userfee','$dtime1',now())");
				
				mysqli_query($con,"UPDATE Users SET ActivationDate='$dtime' WHERE Username='$uname'");
			
			
			}
		}

		function creditLeader($leaderid,$uname)
		{
		    
			include('dbcon.php');
			$result = mysqli_query($con,"SELECT * From Networks WHERE Username='$leaderid'");
				$row = mysqli_fetch_array($result);
				$placementid = $row['Leader_Id'];
				$result = mysqli_query($con,"SELECT * FROM Wallets WHERE Username='$placementid'");
				if(mysqli_num_rows($result)>0)
				{	
					$row = mysqli_fetch_array($result);
					$bal = $row['Balance'];
					$balance = $bal + 500.00;
					mysqli_query($con,"UPDATE Wallets SET Balance='$balance' WHERE Username='$placementid'");
					saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>500.00,'Receiver'=>$placementid,
												'Sender'=>'','Description'=>"Credit Alert: Placement Bonus from ".$uname,'init_bal'=>$bal,'final_bal'=>$balance));
				$dtime1 = date("Y-m-d");
				$amt = 500;
			mysqli_query($con,"insert into indirect_bonus(username,amt,user,datetrans,date)values('$placementid','$amt','$uname',$dtime1',now())");
			}
		}
		
		function creditReferal($referal,$sender)
		{
			include('dbcon.php');
			$result = mysqli_query($con,"SELECT * From Wallets WHERE Username='$referal'");
				$row = mysqli_fetch_array($result);
				$bal = $row['Balance'];
				$balance = $bal + 2000.00;
				mysqli_query($con,"UPDATE Wallets SET Balance='$balance' WHERE Username='$referal'");
				saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>2000.00,'Receiver'=>$referal,
												'Sender'=>'','Description'=>'Credit Alert: ReferalBonus of 2000.00 from '. $sender,'init_bal'=>$bal,'final_bal'=>$balance));
			$dtime1 = date("Y-m-d");
				$amt = 2000;
			mysqli_query($con,"insert into referal_bonus(username,amt,user,datetrans,date)values('$referal','$amt','$sender',$dtime1',now())");
		}
		
		function stageUpgrade1($leaderid)
		{
		    include('dbcon.php');
		    $result = mysqli_query($con,"SELECT * FROM Networks WHERE Username='$leaderid'");
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
			$net =mysqli_query($con,"SELECT * FROM Networks where Username = '$leaderid'");
			$row1 = mysqli_fetch_array($net);
			$lleader = $row1['Leader_Id'];
			
			$net2 =mysqli_query($con,"SELECT * FROM Networks where Username = '$lleader'");
			$row2 = mysqli_fetch_array($net2);
			$stage_com = $row2['Stage'];
			$result = mysqli_query($con,"SELECT * FROM Networks WHERE Leader_Id='$leaderid'");
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
					$result = mysqli_query($con,"SELECT * FROM Networks WHERE Username='$leaderid'");
					if(mysqli_num_rows($result) > 0)
					{
						$row = mysqli_fetch_array($result);
						$l_leader = $row['Leader_Id'];

						$result = mysqli_query($con,"SELECT * FROM Networks WHERE Leader_Id='$l_leader'");
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
								$result = mysqli_query($con,"SELECT * FROM Networks WHERE Leader_Id='$l_leader'");
								while($row=mysqli_fetch_array($result))
								{
									if($row['Username'] != $leaderid)
									{
										$co_leader = $row['Username'];
										$result = mysqli_query($con,"SELECT * FROM Networks WHERE Leader_Id='$co_leader'");
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
											    
											    $result = mysqli_query($con,"SELECT * FROM Networks WHERE Username = '$l_leader'");
												$row = mysqli_fetch_array($result);
												$stage = $row['Stage'];
													$stage = $stage + 1;
													mysqli_query($con,"UPDATE Networks SET Stage = '$stage' WHERE Username='$l_leader'");
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
			$result = mysqli_query($con,"SELECT Balance FROM Wallets WHERE Username='$username'");
			if(mysqli_num_rows($result) > 0)
			{
				$row = mysqli_fetch_array($result);
				$bal = $row['Balance'];
				switch($stage)
				{
					case 3:
						$balance = $bal + 25000;
						mysqli_query($con,"UPDATE Wallets SET Balance=$balance WHERE Username='$username'");
						saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>25000,'Receiver'=>$username,
												'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 2','init_bal'=>$bal,'final_bal'=>$balance));
						break;
					case 4:
						$balance = $bal + 75000;
						mysqli_query($con,"UPDATE Wallets SET Balance=$balance WHERE Username='$username'");
						saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>75000,'Receiver'=>$username,
												'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 3','init_bal'=>$bal,'final_bal'=>$balance));
						break;
					case 5:
						$balance = $bal + 200000;
						mysqli_query($con,"UPDATE Wallets SET Balance=$balance WHERE Username='$username'");
						saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>200000,'Receiver'=>$username,
												'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 4','init_bal'=>$bal,'final_bal'=>$balance));
						break;
					case 6:
						$balance = $bal + 1000000;
						mysqli_query($con,"UPDATE Wallets SET Balance=$balance WHERE Username='$username'");
						saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>1000000,'Receiver'=>$username,
												'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 5','init_bal'=>$bal,'final_bal'=>$balance));
						break;
					case 7:
						$balance = $bal + 4000000;
						mysqli_query($con,"UPDATE Wallets SET Balance=$balance WHERE Username='$username'");
						saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>4000000,'Receiver'=>$username,
												'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 6','init_bal'=>$bal,'final_bal'=>$balance));
						break;
					case 8:
						$balance = $bal + 15000000;
						mysqli_query($con,"UPDATE Wallets SET Balance=$balance WHERE Username='$username'");
						saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>15000000,'Receiver'=>$username,
												'Sender'=>'Stage Bonus','Description'=>'Credit: Stage Bonus for stage 7','init_bal'=>$bal,'final_bal'=>$balance));
						break;
					default:
					    break;
				}
			}
		}
	}
?>