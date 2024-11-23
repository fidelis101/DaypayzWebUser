<?php
session_start();
set_time_limit(300);
include('dbcon.php');
include('transactionhistorydb.php');
include('systemdb.php');
include('post_content.php');
include("vtpass_link.php");
require "./endpoints/TransactionHandler.php";

$amount = $_POST['amount'];
$package = $_POST['package'];
$action = $_POST['action'];
$username = $_SESSION['usr'];
$meterno = $_POST['meterno'];
$customernumber = $_POST['cnum'];
$provider = $_POST['provider'];
$receipt = "NO";
$sp = "AV";
$pk = "";
$tid = "";

if(false)//$provider == "05")
{
    $_SESSION['enotice'] = "<label style='color:red;'>Service Temporarily Unavailable</label>";
}
else if(!ctype_digit($amount)){
            $_SESSION['enotice'] = "<label style='color:red;'>Invalid Amount</label>";
        }
else
{
    if($action == "utility")
    {
	$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
	if($result->num_rows > 0)
	{
		$row = mysqli_fetch_array($result);
		$balance = $row['Balance'];
		if($amount >= 10)
		{
			$newbalance = $balance - $amount;
			if($balance >= $amount)
			{
				chargeCustomer($amount,$balance,$username);
					
				$tranHandler = new TransactionHandler;
				$response = $tranHandler->subDisco($provider,$package,$amount,$customernumber,$meterno,$username,$balance,$newbalance);
				if($response->isSuccessful)
				{
					$_SESSION['enotice'] = "<label style='color:green;'>$response->message</label>";
					$receipt = $response->receipt;
					$tid = $response->tranid;
					$sp = $response->provider;	
					$pk = $response->package;
				}
				else
				{
					$_SESSION['enotice'] = "<label style='color:red;'>$response->message</label>";
				}
						
			}
			else{
				$_SESSION['enotice'] = "<label style='color:red;'>insufficient Balance</label><br/>";
			}
		}else
		{
			$_SESSION['enotice'] = "<label style='color:red;'>Minimum payable is N500</label><br/>";
		}
	}
	else{
			$_SESSION['enotice'] = "<label style='color:red;'>User not found</label><br/>";
		}
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
	header("location:receipt/electric_sub.php?td=$tid&mn=$meterno&p=$provider&ct=$amount&pk=$pk&sp=$sp");
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
