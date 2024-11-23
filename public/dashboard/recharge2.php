<?php
		session_start();
		include('dbcon.php');
		include('transactionhistorydb.php');
		include('systemdb.php');
		include('get_content.php');
		require "./endpoints/TransactionHandler.php";
		
		$mobilenumber = mysqli_real_escape_string($con,$_POST['mobilenumber']);
		$mobilenumber = str_replace(" ","",$mobilenumber);
		$amount = mysqli_real_escape_string($con,$_POST['amount']);
		$network = mysqli_real_escape_string($con,$_POST['network']);
		$action = mysqli_real_escape_string($con,$_POST['action']);
		$username = $_SESSION['usr'];
		
		/*
		$_SESSION['rechargenotice'] = "<label style='color:red;'>Service will be back shortly,currently doing our best to serve you better. We apologize for the inconveniences. Thank you. </label>";
		
			header('location:airtimerecharge.php');
        */
        if(!is_numeric($mobilenumber))
            {
                	$_SESSION['rechargenotice'] = "<label style='color:red;'>Invalid Phone Number</label>";
            }
        else if(!ctype_digit($amount)){
            $_SESSION['rechargenotice'] = "<label style='color:red;'>Invalid Amount</label>";
        }
        else if($amount < 10)
        {
                $_SESSION['rechargenotice'] = "<label style='color:red;'>Below Minimum allowed</label>";
        }
        else
		if($action == "airtime")
		{
		$res = mysqli_query($con,"SELECT * FROM controls WHERE Id= 1");
    		$st = mysqli_fetch_assoc($res);
    		
    			if($st['mtnairtime']=='stop' && $network =="2")
    			{
    				$_SESSION['rechargenotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    		
    			}
    			else if($st['airtelairtime']=='stop' && $network =="1")
    			{
    				
    				$_SESSION['rechargenotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    			}
    			else if($st['gloairtime']=='stop' && $network =="3" && $username != 'Fide')
    			{
    				
    				$_SESSION['rechargenotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    			}
    			else if($st['etiairtime']=='stop' && $network =="4")
    			{
    				
    				$_SESSION['rechargenotice'] = "<label style='color:red;'>Service is currently unavailable</label>";
    			}
    			else{
			$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
			if($result->num_rows > 0)
			{
				$row = mysqli_fetch_array($result);
				$balance = $row['Balance'];
				if($balance >= $amount)
				{	
					$newbalance = $balance - $amount;
					
					$netwrk = mysqli_query($con,"SELECT Package FROM networks WHERE Username='$username'");
					
					$debit = mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
					
					if($debit){
						$tranHandler = new TransactionHandler;
						$tranHandler->buyAirtime($amount,$mobilenumber,$network,$balance,$username,$interest,$newbalance,$cprofit,$amount);
					}
				}
				else{
					$_SESSION['rechargenotice'] = "<label style='color:red;'>Transaction Failed, Insufficient Balance</label>";
				}	
				
			}
			}
		}

		header('location:recharge.php');
	
?>

<?php
/*
echo 'file_get_contents : ', ini_get('allow_url_fopen') ? 'Enabled' : 'Disabled';
*/
?>

