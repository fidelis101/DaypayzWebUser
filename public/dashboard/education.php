<?php
include('dbcon.php');
include('transactionhistorydb.php');
include('systemdb.php');
include('get_content.php');
require "./endpoints/TransactionHandler.php";

session_start();
$pins = $_POST['pins'];
$pintype = $_POST['pintype'];
$action = $_POST['action'];
$email = $_POST['email'];
$username = $_SESSION['usr'];

/*$_SESSION['rechargenotification1'] = "<label style='color:red'> Service is in progress check back soon, currently doing our best to serve you better. </label>";
			header('location:tvsub.php');
*/

$pinSelling = ["01"=>700,"02"=>2770];

$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
if($result->num_rows > 0)
{
	$row = mysqli_fetch_array($result);
	$balance = $row['Balance'];

	if($pintype == "01")
		$cost = $pinSelling[$pintype];
	if($pintype == "02")
		$cost =$pins * $pinSelling[$pintype];

	if($balance >= $cost)
	{
	    $newbalance = $balance - $cost;
		chargeCustomer($pins,$username,$newbalance);
		$tranHandler = new TransactionHandler;
		$response = $tranHandler->BuyEducation($pintype,$pins,$cost,$username,$newbalance,$email,$balance);
		if($response->isSuccessful)
		{
			$_SESSION['edu_notice'] = "<label style='color:green;'>Transaction Successful</label>";
			$_SESSION['pins'] = $response->message;
		}
		else
		{
			$_SESSION['edu_notice'] = "<label style='color:red;'> Transaction failed try again later</label>";
		}
	}
	else{
		$_SESSION['edu_notice'] = "<label style='color:red;'>insufficient Balance</label>";
			header('location:edu.php');
	}
}

function chargeCustomer($pins,$username,$newbalance)
{
	include('dbcon.php');
	//$newbalance = $balance - ($pins * 750);
	mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
}


header('location:edu.php');
?>
