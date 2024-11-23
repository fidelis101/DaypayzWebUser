<?php
session_start();
include('dbcon.php');
include('transactionhistorydb.php');
include('systemdb.php');
include('post_content.php');
include("mail_notification.php");
include("vtpass_link.php");
        
$amount = $_POST['amount'];
$package = $_POST['package'];
$action = $_POST['action'];
$username = $_SESSION['usr'];
$meterno = $_POST['meterno'];
$customernumber = $_POST['cnum'];
$provider = $_POST['provider'];
$receipt = "NO";
$sp = "AV";

if($action == "utility")
{
	$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
	if($result->num_rows > 0)
	{
		$row = mysqli_fetch_array($result);
		$balance = $row['Balance'];
		if($amount >= 500){
			if($balance >= $amount)
			{
			    	$newbalance = $balance - $amount;
				if($provider == "01")
				{
					if($package=="01")
						$av_package="13";
					else if($package=="02")
						$av_package="14";
						
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$av_package");

					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->customerName;
					chargeCustomer($amount,$balance,$username);
	  	  			eko($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
  	  			}
  	  			if($provider == "02")
				{
					if($package=="01")
						$av_package="11";
					else if($package=="02")
						$av_package="10";
						
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$av_package");

					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->name;
					$customeraddress = $obj->details->address;
					chargeCustomer($amount,$balance,$username);
	  	  			ikeja($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername,$customeraddress);
  	  			}
  	  			if($provider == "03")
				{
					if($package=="01")
						$av_package="12";
					else if($package=="02")
						$av_package="23";
					
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$av_package");

					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->name;
					chargeCustomer($amount,$balance,$username);
	  	  			ibadan($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
  	  			}
  	  			if($provider == "04")
				{
					if($package=="01")
						$av_package="24";
					
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$av_package");

					$obj = simplexml_load_string($resulte);
					$customername = $obj->details->name;
					chargeCustomer($amount,$balance,$username);
	  	  			abuja($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
  	  			}
  	  			if($provider == "05" || $provider == "06" || $provider == "08")
				{
					chargeCustomer($amount,$balance,$username);
	  	  			vtpass_electricity($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
  	  			}
  	  			if($provider == "07")
				{
					if($package=="01")
						$av_package="21";
					else if($package=="02")
						$av_package="22";
					
					$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$av_package");
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

function vtpass_electricity($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
	     $providerMap = ['01'=>"eko-electric",'02'=>"ikeja-electric",'03'=>"ibadan-electric",'05'=>"portharcourt-electric",'06'=>"kano-electric",'08'=>"jos-electric"];
        $provider = $providerMap[$provider];

        $packageMap = ['01'=>"prepaid",'02'=>"postpaid"];
        $vt_package = $packageMap[$package];
        
				    
		$request_id = uniqid();
		
		$customername = str_replace(' ','%20',$customername);
		include('dbcon.php');
		
		
        $result = vtpass_api::payFix($provider,$meterno,$vt_package,$amount,$customernumber,$request_id);
        
        $obj = json_decode($result);
        
        $description = "".$meterno."$vt_package DISCO Subscription of N".$amount." for $meterno";
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username',$request_id,'$obj->transactionId','$obj->response_description','$description','$amount','$date','VTP','$obj->token')");
		$tk = $obj->payload->token;
		$tk = $tk.$obj->payload->purchased_code;
		if(empty($tk))
		{
		    $tk = $obj->content->error;
		    $tk = $obj->response_description;
		}
		if($obj->response_description == "TRANSACTION SUCCESSFUL" )
		{
			 $tkn .= $obj->purchased_code;
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label><br/>".$tkn;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed a PHED meter  '.$meterno.' Token: '.$obj->token,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			//company profit
			$cprofit = $amount * 0.01;
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$amount','$cprofit','$date')");
			$GLOBALS['tid'] = $request_id;
			$GLOBALS['sp'] = "VT";
		}
		else{
					
			$_SESSION['enotice'] = "<label style='color:red;'>Connection Failed pls try again later $tk </label>";
			
            		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>'DISCO','Description'=>'Refund: Failed Electricity Subscription '.$meterno.' failed','init_bal'=>$newbalance,'final_bal'=>$balance));
					}
			
	}
	
	function eko($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
	    include('dbcon.php');
	    
	    if($package=="01")
			$av_package="13";
		else if($package=="02")
			$av_package="14";
	    //$result = curl_get_contents("https://api.airvendng.net/electricity/?username=api@airvend.ng&password=03500&account=$meterno&type=$package&amount=$amount&customernumber=$customernumber&customername=");
		
		$customername = str_replace(' ','%20',$customername);
		
		$result = curl_get_contents("https://api.airvendng.net/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$av_package&amount=$amount&customernumber=$customernumber&customername=$customername");
		
		$obj = simplexml_load_string($result);
		$tk = $obj->vendData->creditToken;
		$tk = $tk.$obj->vendData->token;
		
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','Eko Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label>".$obj->vendData->token;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Eko meter  '.$meterno.'Token: '.$tk,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			//company profit
			$cprofit = $amount * 0.01;
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$amount','$cprofit','$date')");
			
			$description = "<p>Debit: N$amount</p><p> Description: ".'You Subscribed Eko meter  '.$meterno.'<br/>Token: '.$tk."</p><p>Balance: N$newbalance</p>";
			mail_sender::send("Airtime Recharge",$description,$username);	
		}
		else{
					
			vtpass_electricity($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
	}
	
	
	function ikeja($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername,$customeraddress)
	{
	    
		include('dbcon.php');
		
	    if($package=="01")
			$package="11";
		else if($package=="02")
			$package="10";
						
		$customername = str_replace(' ','%20',$customername);
		$customeraddress = str_replace(' ','%20',$customeraddress);
		
		$result = curl_get_contents("https://api.airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customernumber=$customernumber&contacttype=TENANT&customeraddress=$customeraddress&customername=$customername");
		
		$obj = simplexml_load_string($result);
		$tk = $obj->vendData->creditToken;
		$tk = $tk.$obj->vendData->token;
		
		$date = date("Y-m-d H-i-s");
		mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','Ikeja Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label>".$tk;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Ikeja meter  '.$meterno.'Token: '.$tk,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			//company profit
			$cprofit = $amount * 0.01;
			mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$amount','$cprofit','$date')");
			
			$description = "<p>Debit: N$amount</p><p> Description: ".'Debit: You Subscribed Ikeja meter  '.$meterno.'<br/>Token: '.$tk."</p><p>Balance: N$newbalance</p>";
			mail_sender::send("Ikeja Meter",$description,$username);	
		}
		else{
					
			vtpass_electricity($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
			
		
	}
	
	function ibadan($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
	    include('dbcon.php');
		
	    if($package=="01")
			$package="12";
		else if($package=="02")
			$package="23";
			
		$customername = str_replace(' ','%20',$customername);
		
		$result = curl_get_contents("https://api.airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customernumber=$customernumber&customername=$customername");
		
		$obj = simplexml_load_string($result);
		$tk = $obj->vendData->creditToken;
		$tk = $tk.$obj->vendData->token;
		
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','Ibadan ($package) Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label> Token: ".$obj->vendData->creditToken;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Ibadan meter  '.$meterno.", token: ".$tk,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			//company profit
		$cprofit = $amount * 0.01;
		mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$amount','$cprofit','$date')");
		$description = "<p>Debit: N$amount</p><p> Description: ".'Debit: You Subscribed Ibadan meter  '.$meterno.'<br/>Token: '.$tk."</p><p>Balance: N$newbalance</p>";
			mail_sender::send("Ibadan",$description,$username);	
		
		}
		else{
					
			vtpass_electricity($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername);
		
	}
	
	function abuja($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
	    include('dbcon.php');
	    
	    if($package=="01")
			$package="24";
			
		$customername = str_replace(' ','%20',$customername);
		
		$result = curl_get_contents("https://api.airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customernumber=$customernumber&customername=$customername");
		
		$obj = simplexml_load_string($result);
		$tk = $obj->vendData->creditToken;
		$tk = $tk.$obj->vendData->token;
		
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider,Token) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','Abuja Meter Sub $meterno','$amount','$date','AV','$tk')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label> Token: ".$obj->vendData->creditToken;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Abuja meter  '.$meterno.", token: ".$tk,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			//company profit
		$cprofit = $amount * 0.01;
		mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$amount','$cprofit','$date')");
		
		$description = "<p>Debit: N$amount</p><p> Description: ".'Debit: You Subscribed Abuja meter  '.$meterno.'<br/>Token: '.$tk."</p><p>Balance: N$newbalance</p>";
			mail_sender::send("Abuja",$description,$username);	
		}
		else{
					
			$_SESSION['enotice'] = "<label style='color:red;'>Connection Failed pls try again later</label>";
			
            		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>'Recharge','Description'=>'Refund: Failed Abuja Electricity Subscription '.$meterno.' failed','init_bal'=>$newbalance,'final_bal'=>$balance));
					}
			
	
		
	}
	function port($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
		$customername = str_replace(' ','%20',$customername);
		include('dbcon.php');
		$result = curl_get_contents("https://api.airvendng.net/electricity/?username=api@airvend.ng&password=03500&account=$meterno&type=$package&amount=$amount&customerphone=$customernumber&Customername=&customeraddress=&uniqueref=");
		
		$obj = simplexml_load_string($result);
		
		
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','Portharcourt Meter Sub $meterno','$amount','$date','AVTest')");
		
		$GLOBALS['tid'] = $obj->AVRef;
		
		if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
		{
			$GLOBALS['receipt'] = "YES";
			$_SESSION['enotice'] = "<label style='color:green;'>Transaction Successful</label>".$obj->vendData->token;
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$mobilenumber,'Sender'=>$username,'Description'=>'Debit: You Subscribed Portharcourt meter  '.$meterno,'init_bal'=>$balance,'final_bal'=>$newbalance));
			
		$description = "<p>Debit: N$amount</p><p> Description: ".'Debit: You Subscribed Portharcourt meter  '.$meterno.'<br/>Token: '.$tk."</p><p>Balance: N$newbalance</p>";
			mail_sender::send("PH Meter",$description,$username);	
		}
		else{
					
			$_SESSION['enotice'] = "<label style='color:red;'>Connection Failed pls try again later</label>";
			
            		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
            		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>'Recharge','Description'=>'Refund: Failed Port Electricity Subscription '.$meterno.' failed','init_bal'=>$newbalance,'final_bal'=>$balance));
					}
			

		
		
	}
	
	function eedc($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance,$customername)
	{
	    include('dbcon.php');
	    
	    if($package=="01")
			$package="21";
		else if($package=="02")
			$package="22";
		$customername = str_replace(' ','%20',$customername);
		
		$result = curl_get_contents("https://api.airvendng.net/vas/electricity/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package&amount=$amount&customerphone=$customernumber&customername=$customername");
		
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
		mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Electric','$amount','$cprofit','$date')");
		
		$description = "<p>Debit: N$amount</p><p> Description: ".'Debit: You Subscribed EEDC meter  '.$meterno.'<br/>Token: '.$tk."</p><p>Balance: N$newbalance</p>";
			mail_sender::send("EEDC Meter",$description,$username);	
		
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
	header("location:receipt/electric_sub.php?td=$tid&mn=$meterno&p=$provider&ct=$amount&pk=$package&sp=$sp");
	}
else
{
	header('location:utilitysub.php');
	}

//header('location:ttutilitysub.php');

/*https://www.nellobytesystems.com/APIQuery.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&OrderID=6274399481

https://www.nellobytesystems.com/APIVerifyElectricityV1.0.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&ElectricCompany=04&MeterNo=27100385213

*/

?>
