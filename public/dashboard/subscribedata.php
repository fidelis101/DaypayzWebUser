<?php
	include('dbcon.php');
	include('transactionhistorydb.php');
	include('systemdb.php');
	include('get_content.php');

		require "./endpoints/TransactionHandler.php";

		session_start();
		$mobilenumber = mysqli_real_escape_string($con,$_POST['number']);
		$mobilenumber = str_replace(' ','',$mobilenumber);
		$amount = mysqli_real_escape_string($con,$_POST['amount']);
		$network = mysqli_real_escape_string($con,$_POST['network']);
		$action = "data";
		$username = $_SESSION['usr'];
		
		$mtnSelling = array('0.5'=>179,'1'=>269,'1D'=>1100,'2'=>538,'3'=>807,'2.5D'=>2300,'5'=>1345,'10D'=>5900,'22D'=>11800);
    
    	$mtnSme = array("0.5SME"=>185,"1SME"=>295,'2SME'=>590,'3SME'=>885,'5SME'=>1475,'10SME'=>2950);
		
        $mtnCostOthers = ["1.5GBR"=>1000,"2GBR"=>1200,"3GBR"=>1500,"4.5GBR"=>2000,"6GBR"=>2500,"8GBR"=>3000,
                        "10GBR"=>3500,"15GBR"=>5000,"40GBR"=>10000,"75GBR"=>15000,"110GBR"=>20000,
                        "1GBRD"=>350,"2GBRD"=>500,"6GBRW"=>1500,"60GBR"=>20000,"120GBR"=>50000,
                        "100GBR"=>30000,"15OGBR"=>70000,"75MBRD"=>100,"25MBRD"=>50,"200MBR-2D"=>200,
						"1GBRW"=>500,"750MBRW"=>500,"350MBRW"=>300];
        
        $airtelSelling = ["D-MFIN-1-40MB"=>50,"D-MFIN-1-100MB"=>100,"D-MFIN-1-200MB"=>200,"D-MFIN-1-350MB"=>300,"D-MFIN-1-750MB"=>500,"D-MFIN-1-1.5GB"=>1000,"D-MFIN-1-3GB"=>1500,"D-MFIN-1-6GB"=>2500,"D-MFIN-1-11GB"=>4000,"D-MFIN-1-20GB"=>5000,"D-MFIN-1-40GB"=>10000,"D-MFIN-1-75GB"=>15000,"D-MFIN-1-8GB"=>3000,"D-MFIN-1-120GB"=>20000,"D-MFIN-1-1GB1D"=>300,"D-MFIN-1-2GB1D"=>500,"D-MFIN-1-6GB1W"=>1500,"D-MFIN-1-2GB1M"=>1200,"D-MFIN-1-4.5GB"=>2000,"D-MFIN-1-25GB1M"=>8000,"D-MFIN-1-200GB1M"=>30000];

			
		$etisalat=array('0.025D'=>'25MBD','0.1D'=>'100MBD','0.25W'=>'250MBW','0.65D'=>'650MBD','1D'=>'1GBD','2-3D'=>'2GB3D','7W'=>'7GBW','0.5'=>"500MB",'1.5'=>"1.5GB",'2'=>"2GB",'3'=>"3GB",'4.5'=>"4.5GB",'11'=>"11GB",'15'=>'15GB','40'=>'40GB','75'=>'75GB');

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
    		        $cost = intval($amount);
    		    }else if($network =="3")
    		    {
    		        $cost = intval($amount);
    		        
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
    
    header('location:subscribe.php');

?>
