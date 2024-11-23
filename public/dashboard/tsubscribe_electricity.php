<?php
session_start();
include('dbcon.php');
include('transactionhistorydb.php');
include('systemdb.php');
include('post_content.php');
  
        
$amount = $_POST['amount'];
$package = $_POST['package'];
$action = $_POST['action'];
$username = $_SESSION['usr'];
$meterno = $_POST['meterno'];
$customernumber = $_POST['cnum'];
$provider = $_POST['provider'];
$receipt = "NO";

if($action == "utility")
{
	$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
	if($result->num_rows > 0)
	{
		$row = mysqli_fetch_array($result);
		$balance = $row['Balance'];
		if($amount >= 10){
			if($balance >= $amount)
			{
			    	$newbalance = $balance - $amount;
				if($provider == "01")
				{
					if($package=="01")
						$package="13";
					else if($package=="02")
						$package="14";
						
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package");

					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->customerName;
					chargeCustomer($amount,$balance,$username);
	  	  			eko($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
  	  			}
  	  			if($provider == "02")
				{
					if($package=="01")
						$package="11";
					else if($package=="02")
						$package="10";
						
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package");

					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->name;
					$customeraddress = $obj->details->address;
					chargeCustomer($amount,$balance,$username);
	  	  			ikeja($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername,$customeraddress);
  	  			}
  	  			if($provider == "03")
				{
					if($package=="01")
						$package="12";
					else if($package=="02")
						$package="23";
					
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package");

					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->name;
					chargeCustomer($amount,$balance,$username);
	  	  			ibadan($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
  	  			}
  	  			
  	  			if($provider == "05")
				{
					if($package=="01")
						$package="16";
					else if($package=="02")
						$package="15";
						
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package");

					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->customerName;
					$unique = $obj->details->uniqueReference;
					$address = $obj->details->customerAddress;
					chargeCustomer($amount,$balance,$username);
	  	  				port($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername,$unique,$address);
  	  			}
  	  			if($provider == "07")
				{
					if($package=="01")
						$package="21";
					else if($package=="02")
						$package="22";
					
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package");
					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->name;
					chargeCustomer($amount,$balance,$username);
	  	  			eedc($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
  	  			}
			}
			else{
				$_SESSION['enotice'] = "<label style='color:red;'>insufficient Balance</label><br/>";
			}
		}else
		{
			$_SESSION['enotice'] = "<label style='color:red;'>Minimum payable is N1000</label><br/>";
		}
	}
	else{
			$_SESSION['enotice'] = "<label style='color:red;'>User not found</label><br/>";
		}
}


	function eko($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
		$customername = str_replace(' ','%20',$customername);
		include('dbcon.php');
		$result = curl_get_contents("https://api.airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customerphone=$customernumber&customername=$customername");
		
		$obj = simplexml_load_string($result);
		
		$tk = $obj->vendData->creditToken;
		$tk = $tk.$obj->vendData->token;
		$message = $obj->ResponseMessage;
		$message = $message.$obj->message;
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$message','Eko Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label>".$tk;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Eco meter  '.$meterno." Token=".$tk,'init_bal'=>$balance,'final_bal'=>$newbalance));
			$cprofit = $amount * 0.01;
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$cost','$cprofit','$date')");
			
		
		}
		else{
					
			$_SESSION['enotice'] = "<label style='color:red;'>Connection Failed pls try again later</label>";
			
            		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>'Recharge','Description'=>'Refund: Failed Eko Electricity Subscription '.$meterno.' failed','init_bal'=>$newbalance,'final_bal'=>$balance));
					}
			

		
		
	}
	
	
	function ikeja($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername,$customeraddress)
	{
		$customername = str_replace(' ','%20',$customername);
		$customeraddress = str_replace(' ','%20',$customeraddress);
		
		include('dbcon.php');
		$result = curl_get_contents("https://airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customerphone=$customernumber&contacttype=TENANT&customeraddress=$customeraddress&customername=$customername");
		
		$obj = simplexml_load_string($result);
		$tk = $obj->vendData->creditToken;
		$tk = $tk.$obj->vendData->token;
		
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','Ibadan Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label>".$tk;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Ikeja meter  '.$meterno.'Token: '.$tk,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			//company profit
			$cprofit = $amount * 0.01;
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$cost','$cprofit','$date')");
			
			debitSystemWallet($cost);
		}
		else{
					
			$_SESSION['enotice'] = "<label style='color:red;'>Connection Failed pls try again later</label>";
			
            		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>'Recharge','Description'=>'Refund: Failed ikeja Electricity Subscription '.$meterno.' failed','init_bal'=>$newbalance,'final_bal'=>$balance));
					}
			
		
	}
	
	function ibadan($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
		$customername = str_replace(' ','%20',$customername);
		include('dbcon.php');
		$result = curl_get_contents("https://airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customerphone=$customernumber&customername=$customername");
		
		$obj = simplexml_load_string($result);
		$tk = $obj->vendData->creditToken;
		
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','Ibadan Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label> Token: ".$obj->vendData->creditToken;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Ibadan meter  '.$meterno.", token: ".$tk,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			//company profit
		$cprofit = $amount * 0.01;
		mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$cost','$cprofit','$date')");
		
		debitSystemWallet($cost);
		}
		else{
					
			$_SESSION['enotice'] = "<label style='color:red;'>Connection Failed pls try again later</label>";
			
            		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>'Recharge','Description'=>'Refund: Failed Ibadan Electricity Subscription '.$meterno.' failed','init_bal'=>$newbalance,'final_bal'=>$balance));
					}
			
	
		
	}
	
	function port($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername,$unique,$address)
	{
		$customername = str_replace(' ','%20',$customername);
		include('dbcon.php');
		$result = curl_get_contents("https://airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customerphone=$customernumber&Customername=$customername&customeraddress=$address&uniqueref=$unique");
	
		$obj = simplexml_load_string($result);
		$tk = $obj->vendData->creditToken;
		$tk = $tk.$obj->vendData->token;
		
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','Portharcourt Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label>".$tk;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Portharcourt meter  '.$meterno." Token=".$tk,'init_bal'=>$balance,'final_bal'=>$newbalance));
			$cprofit = $amount * 0.01;
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$cost','$cprofit','$date')");
			
		
		}
		else{
					
			$_SESSION['enotice'] = "<label style='color:red;'>Connection Failed pls try again later</label>";
			
            		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>'Recharge','Description'=>'Refund: Failed Port Electricity Subscription '.$meterno.' failed','init_bal'=>$newbalance,'final_bal'=>$balance));
					}
			

		
		
	}
	
	function eedc($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
		$customername = str_replace(' ','%20',$customername);
		include('dbcon.php');
		$result = curl_get_contents("https://airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customerphone=$customernumber&customername=$customername");
		
		$obj = simplexml_load_string($result);
		$tk = $obj->vendData->creditToken;
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','EEDC Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label> Token:".$obj->vendData->creditToken;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$meterno,'Sender'=>$username,'Description'=>'Debit: You Subscribed EEDC meter '.$meterno.", token: ".$obj->vendData->creditToken,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			$cprofit = $amount * 0.01;
		mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$cost','$cprofit','$date')");
		
		debitSystemWallet($cost);
		
		}
		else{
					
			$_SESSION['enotice'] = "<label style='color:red;'>Connection Failed pls try again later</label>";
			
            		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>'Recharge','Description'=>'Refund: Failed EEDC Subscription '.$meterno.' failed','init_bal'=>$newbalance,'final_bal'=>$balance));
					}
			

		
		
	}
	
	
function chargeCustomer($cost,$balance,$username)
{
	include('dbcon.php');
	$newbalance = $balance - $cost;
	mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
}


if($receipt == "YES")
{
	header("location:receipt/electric_sub.php?td=$tid&mn=$meterno&p=$provider&ct=$amount&pk=$package");
	}
else
{
	header('location:tutilitysub.php');
	}

//header('location:tutilitysub.php');

/*https://www.nellobytesystems.com/APIQuery.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&OrderID=6274399481

https://www.nellobytesystems.com/APIVerifyElectricityV1.0.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&ElectricCompany=04&MeterNo=27100385213

*/

?>
