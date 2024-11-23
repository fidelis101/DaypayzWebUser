<?php
	include('dbcon.php');
	include('transactionhistorydb.php');
	include('systemdb.php');
	include('get_content.php');

		require "./endpoints/TransactionHandler1.php";

		session_start();
		$mobilenumber = mysqli_real_escape_string($con,$_POST['number']);
		$mobilenumber = str_replace(' ','',$mobilenumber);
		$amount = mysqli_real_escape_string($con,$_POST['amount']);
		$network = mysqli_real_escape_string($con,$_POST['network']);
		$action = "data";
		$username = $_SESSION['usr'];
		
		$mtnSelling = array('0.5'=>189,'1'=>298,'1D'=>1100,'2'=>596,'3'=>894,'2.5D'=>2300,'5'=>1490,'10D'=>5900,'22D'=>11800);
    
    	$mtnSme = array("1SME"=>349,'2SME'=>698,'3SME'=>1047,'5SME'=>1745);
		
        $mtnCostOthers = ["1.5GBR"=>1000,"2GBR"=>1200,"3GBR"=>1500,"4.5GBR"=>2000,"6GBR"=>2500,"8GBR"=>3000,
                        "10GBR"=>3500,"15GBR"=>5000,"40GBR"=>10000,"75GBR"=>15000,"110GBR"=>20000,
                        "1GBRD"=>350,"2GBRD"=>500,"6GBRW"=>1500,"60GBR"=>20000,"120GBR"=>50000,
                        "100GBR"=>30000,"15OGBR"=>70000,"75MBRD"=>100,"25MBRD"=>50,"200MBR-2D"=>200,
						"1GBRW"=>500,"750MBRW"=>500,"350MBRW"=>300];
						
        $gloSelling = array("0.8"=>500,'2'=>1000,"3.5"=>1500,'4.5'=>2000,'7.2'=>2500,'8.75'=>2900,'12.5'=>3900,'15.6'=>4800,'25'=>7560,'52.5'=>14200,'62.5'=>17000);
        
        $airtelSelling = ["40MBD"=>50,"100MBD"=>100,"200MBD"=>200,"750MBD"=>500,"6GBW"=>1500,"1GBW"=>500,"2GBD"=>500,"1GBD"=>300,"350MBD"=>300,"1.5GB"=>1000,"2GB"=>1200,"3GB"=>1500,"4.5GB"=>2000,"6GB"=>2500,"8GB"=>3000,"11GB"=>4000,"15GB"=>5000,"40GB"=>10000,"75GB"=>15000,"110GB"=>20000,"200GB"=>30000];
        
        //$airtelSelling = array('1.5'=>1000,'3.5'=>2000,'7'=>5250,'10'=>5000,'16'=>8100,'22'=>10000);
        
       $etisalatSelling = ["0.025D"=>50,"0.1D"=>100,"0.25W"=>200,"0.65D"=>200,"1D"=>300,"500MB"=>500,"2-3D"=>500,
			"1.5"=>1000,"3"=>1500,"7W"=>1500,"0.5"=>'500',"4.5"=>2000,"11"=>4000,"15"=>5000,"40"=>10000,"75"=>15000,
			 '1'=>1000,'2.5'=>1900,'4'=>2400,'5.5'=>4000,'11.5'=>5000,'27'=>15500];

			
		$etisalat=array('0.025D'=>'25MBD','0.1D'=>'100MBD','0.25W'=>'250MBW','0.65D'=>'650MBD','1D'=>'1GBD','2-3D'=>'2GB3D','7W'=>'7GBW','0.5'=>"500MB",
			'1.5'=>"1.5GB",'2'=>"2GB",'3'=>"3GB",'4.5'=>"4.5GB",'11'=>"11GB",'15'=>'15GB','40'=>'40GB','75'=>'75GB');

    	$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
    	if($result->num_rows > 0)
    	{
    		$row = mysqli_fetch_array($result);
    		$balance = $row['Balance'];
    		
    		$res = mysqli_query($con,"SELECT * FROM controls WHERE Id= 1");
    		$st = mysqli_fetch_assoc($res);
    		
    		if($network == "5")
    		    $network = 2;
    			
    		if($st['mtndata']=='stop' && $network =="2")
    		{
    			$_SESSION['datanotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    		}
    		else if($st['airteldata']=='stop' && $network =="1")
    		{		
    			$_SESSION['datanotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    		}
			else if($st['glodata']=='stop' && $network =="3")
    		{			
    			$_SESSION['datanotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    		}
			else if($st['etidata']=='stop' && $network =="4")
			{			
    			$_SESSION['datanotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    		}
			else
			{
    		
    		if($network =="5" || $network =="4" || $network =="3" || $network =="2" || $network=="1")
    		{
    		    if($network =="2")
    		    {
					if($amount == "1" || $amount == "2" || $amount == "3" || $amount == "5" || $amount == "0.5")
					$cost = $mtnSelling[$amount];
					else
					$cost = $mtnSme[$amount];
    		    }
                else if($network =="1")
    		    {
    		        $cost = $airtelSelling[$amount];
    		    }else if($network =="4")
    		    {
    		        $cost = $etisalatSelling[$amount];
    		    }else if($network =="3")
    		    {
    		        $cost = $gloSelling[$amount];
    		    }
    			$newbalance = $balance - $cost;
    			if($balance >= $cost)
    			{
    				
					if(chargeCustomer($cost,$username))
					{
						$tranHandler = new TransactionHandler;
						$tranHandler->subData($network,$amount,$mobilenumber,$username,$cost,$balance,$newbalance);
					}
    				else
    				$_SESSION['datanotice'] = "<label style='color:red;'>Transaction Failed, Try again else contact IT support for assistance</label>";	
    			}
    			else{
    					$_SESSION['datanotice'] = "<label style='color:red;'>Failed (insufficient Balance!)</label>";
    			}
    		}else
    		{
    			$_SESSION['datanotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    		}
    	}
    }
    
	function chargeCustomer($cost,$username)
    {
    	include('dbcon.php');
    	$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
    		if($result->num_rows > 0)
    		{
    			$row = mysqli_fetch_array($result);
    			$balance = $row['Balance'];
    			if($balance > $cost)
    			{	
    				$newbalance = $balance - $cost;
    				$debit = mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
    				if($debit)
    				{
    					return true;
    				}else
    				{
    					return false;
    				}
    			}
    			else
    			{
    				return false;
    			}
    			
    		    }else
    			{
    				return false;
    			}
    }	
    
    header('location:subscribe1.php');

?>
