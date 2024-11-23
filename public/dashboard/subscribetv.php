<?php
session_start();
set_time_limit(300);
include('dbcon.php');
include('transactionhistorydb.php');
include('systemdb.php');
include('get_content.php');
require "./endpoints/TransactionHandler.php";

$amount = $_POST['amount'];
$invoiceperiod = $_POST['ivp'];
$action = $_POST['action'];
$username = $_SESSION['usr'];
$smartcardno = $_POST['smartcardno'];
$customernumber = $_POST['cnum'];
$customername = $_POST['cname'];
$top_amount = $_POST['top_amount'];
$cable = $_POST['cable'];


$numresult = mysqli_query($con,"SELECT * FROM users WHERE Username='$username'");
$customernumber = mysqli_fetch_array($numresult)["Phone"];
/*$_SESSION['rechargenotification1'] = "<label style='color:red'> Service is in progress check back soon, currently doing our best to serve you better. </label>";
			header('location:tvsub.php');
*/

if($cable =="01")
    $costMap = ['00'=>($top_amount+50),'01'=>5150,'02'=>9350,'03'=>15750,'04'=>25050,'05'=>37050];
if($cable == "02")
    $costMap = ['00'=>($top_amount+50),'01'=>950,'02'=>3350,'03'=>4900,'04'=>7250,'05'=>9650];
if($cable == "03")
    $costMap = ['00'=>($top_amount+50),'01'=>1750,'02'=>3550,'03'=>3850,'04'=>4550,'05'=>7550];

// if($cable == "03")
//     $costMap = ['00'=>($top_amount+50),'01'=>1550,'02'=>2650,'03'=>3550,'04'=>3850,'05'=>6550];

$cost = $costMap[$amount];  	
$tvype = ['01'=>'dstv','02'=>'gotv','03'=>'startimes'];

// $resultapi = curl_get_contents("https://mobileairtimeng.com/httpapi/customercheck?userid=08064535577&pass=4b6afee34b42c0fe948e2f5&bill=".$tvype[$cable]."&smartno=".$smartcardno."&jsn=json");

// //$resultapi = curl_get_contents("https://api.airvendng.net/vas/gotv/verify/?username=eeestores@yahoo.com&password=jesuslord@&smartcard=".$smartcardno);
       
// $obj = json_decode($resultapi);
// //Receipt Parameters
// $receipt = "NO";
// $tid = '';
// $owner =  $customername;
// $customername = $obj->customerName;
// $customernum = $obj->customerNumber;
// $invoiceperiod = $obj->invoice;
$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");

$verifyDiscoEndpoint = "https://api-service.vtpass.com/api/merchant-verify";
    
$data = [
    "billersCode"=>$smartcardno,
    "serviceID"=>$tvype[$cable]
];
$requestdata = json_encode($data);
        

$header = array();
$header[]= 'api-key: f09128679b4bf2f58aa08992b7fba6cf';
$header[]= 'secret-key: SK_665917e169593b06beaa567363fe063eda227637902';
$header[]= 'Content-Type: application/json';

$res = curl_post_no_auth($verifyDiscoEndpoint,$requestdata,$header);

$obj = json_decode($res['Result']);

$customername = $obj->content->Customer_Name;
$customernum = $obj->content->Customer_Number;
$invoiceperiod = $obj->content->status;
$owner =  $customername;


if($action == "tvsub")
{
	if($result->num_rows > 0)
	{
		$row = mysqli_fetch_array($result);
		$balance = $row['Balance'];
	
		//tv subscription
    	if($cable =="01" || $cable =="02" || $cable=="03") //|| $cable=="03")
		{
			if($balance >= $cost)
			{
			    $newbalance = $balance - $cost;
				chargeCustomertv($cost,$balance,$username);
			//vtpass($invoiceperiod,$cable,$amount,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance,$cprofit);
			//Av($invoiceperiod,$cable,$amount,$amount2,$customernumber,$customernum,$customername,$smartcardno,$username,$cost,$balance,$newbalance,$avamount);
			
			$tranHandler = new TransactionHandler;
			$response = $tranHandler->subCable($invoiceperiod,$cable,$amount,$customernum,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance);
			if($response->isSuccessful)
				$_SESSION['cablenotice'] = "<label style='color:green;'>$response->message</label>";
			else
				$_SESSION['cablenotice'] = "<label style='color:danger;'>$response->message</label>";
				
 			}
			else{
				$_SESSION['cablenotice'] = "<label style='color:red;'>Failed, insufficient Balance</label><br/>";
			}
		}
		else
		{
		     $_SESSION['cablenotice'] = "<label style='color:red;'>Failed, Service is currently unavailable</label><br/>";
		}
	}
	else
	{
		$_SESSION['cablenotice'] = "<label style='color:red;'>User not found</label><br/>";
	}
}


function payTvSub($invoiceperiod,$cable,$amount,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance)
{
	include('dbcon.php');
	
	$result =curl_get_contents("https://www.nellobytesystems.com/APIBuyCableTV.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&CableTV=$cable&Package=$amount&SmartCardNo=$smartcardno");
	
    	$obj = json_decode($result);
    		
    		    $description = "".$smartcardno." Cable Subscription";
    			$date = date("Y-m-d H-i-s");
    			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$obj->orderid','$obj->status','$description','$cost','$date','CK')");
    		if($obj->status == "ORDER_RECEIVED")
    		{
    			
    			$_SESSION['cablenotice'] = "<label style='color:green;'>Transaction Successful</label>";
    			
    			$date = date("Y-m-d H-i-s");
    			$date1 = date("Y-m-d");
    				
    			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$smartcardno,'Sender'=>$username,'Description'=>'Debit: Cable Subscription for '.$smartcardno,'init_bal'=>$balance,'final_bal'=>$newbalance));
    			
    		$description = "<p>Debit: N$amount</p><p> Description: $description</p><p>Balance: N$newbalance</p>";
			mail_sender::send("Decoder Subscription",$description,$username);	
    			
    			
    
    		}else{
    		    $date = date("Y-m-d H-i-s");
    		    mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
    		    
    		    $obj->orderid;
    			mysqli_query($con,"INSERT INTO apitransactions ( Transaction_Id,Status,Transaction_Date) VALUES('$obj->orderid','$obj->status','$date')");
    		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$username,'Sender'=>$smartcardno,'Description'=>'Refund: failed Cable subscription '.$smartcardno,'init_bal'=>$newbalance,'final_bal'=>$balance));
    		
    		
    			$_SESSION['cablenotice'] = "<label style='color:red;'>Transaction Failed</label>";
   
    		}
}

function vtpass($invoiceperiod,$cable,$amount,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance,$cprofit)
{
	include('dbcon.php');
	
	$cableid = array("01"=>"dstv","02"=>"gotv","03"=>"startimes");
	if($cable=="01")
		$variationid = array("01"=>"dstv1","02"=>"dstv2","03"=>"dstv79","04"=>"dstv7","05"=>"dstv3");
	if($cable=="02")
		$variationid = array("01"=>"gotv-lite","02"=>"gotv-value","03"=>"gotv-plus","04"=>"gotv-max");
	if($cable=="03")
		$variationid = array("01"=>"nova","02"=>"basic","03"=>"smart","04"=>"classic","05"=>"super");
	
	$requestid = uniqid();
	$serviceid=$cableid[$cable];
	$billercode = $smartcardno;
	$variation_code = $variationid[$amount];
	
	$user = "daypayz.com@gmail.com"; //email address
        $password = "#Ojobe7784@"; //password
        $host = 'https://vtpass.com/api/payfix';
	
	$data = array(
		'serviceID'=> $serviceid, //integer e.g gotv,dstv,eko-electric,abuja-electric
		'billersCode'=> $billercode, // e.g smartcardNumber, meterNumber,
		'variation_code'=> $variation_code, // e.g dstv1, dstv2,prepaid,(optional for somes services)
		'amount' =>  "", // integer (optional for somes services)
		'phone' => $customernumber, //integer
		'request_id' => $requestid // unique for every transaction from your platform
	);
	$curl       = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => $host,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_USERPWD => $user.":" .$password,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $data,
	));
	$result = curl_exec($curl);
        $_SESSION['cablenotice'] = $result;
    $obj = json_decode($result);
        
    $description = "".$smartcardno." Cable Subscription of ".$amount;
	$date = date("Y-m-d H-i-s");
	mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$requestid','','$result','$description','$cost','$date','VTP')");
	
	$GLOBALS['tid'] = $requestid;
	
	if($obj->response_description == "TRANSACTION SUCCESSFUL" || $obj->response_description == "TRANSACTION FAILED")
	{
		$_SESSION['cablenotice'] = "<label style='color:green;'>Transaction Successful</label>";
		$GLOBALS['receipt'] = "YES";
		
		$date = date("Y-m-d H-i-s");
		$date1 = date("Y-m-d");
		
		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$smartcardno,'Sender'=>$username,'Description'=>'Debit: Cable Subscription for '.$smartcardno,'init_bal'=>$balance,'final_bal'=>$newbalance));
		
		creditTvBonus($username,$smartcardno);
		//company profit
		mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Cable-$serviceid','$cost','$cprofit','$date')");
		
		
		
    }
    else{
       	mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
    	$_SESSION['cablenotice'] = "<label style='color:red;'>Transaction Failed</label>";
    	
    	saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$username,'Sender'=>$smartcardno,'Description'=>'Refund: Failed Cable Subscription for '.$smartcardno,'init_bal'=>$newbalance,'final_bal'=>$balance));
    	
    }
}

function Av($invoiceperiod,$cable,$amount,$amount2,$customernumber,$customernum,$customername,$smartcardno,$username,$cost,$balance,$newbalance,$avamount)
{

	include('dbcon.php');
	$cableid = array("01"=>"dstv","02"=>"gotv","03"=>"startimes");
	
	$cableSelected = $cableid[$cable];
	
	if($cable == "01")
	{
	$result = file_get_contents("http://api.airvendng.net/vas/dstv/?username=daypayz.com@gmail.com&password=@Ojobe52487&customerNumber=".$customernum."&invoicePeriod=".$invoiceperiod."&amount=".$avamount."&customerName=".$customername);
	 $description = "".$smartcardno." DStv Subscription ".$cost;
	}else if($cable =="02")
	{
	$result = file_get_contents("http://api.airvendng.net/vas/gotv/?username=daypayz.com@gmail.com&password=@Ojobe52487&customerNumber=".$customernum."&invoicePeriod=".$invoiceperiod."&amount=".$avamount."&customerName=".$customername);
	 $description = "".$smartcardno." GOtv Subscription ".$cost;
	}
	
    $obj = simplexml_load_string($result);
			
			
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$obj->AVRef','$obj->ResponseMessage','$description','$cost','$date','AV')");
			
			
	
	if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
	{
	    		$_SESSION['cablenotice'] = "<label style='color:green;'>Transaction Successful</label>";
		$GLOBALS['receipt'] = "YES";
		
		$date = date("Y-m-d H-i-s");
		$date1 = date("Y-m-d");
		
		
		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$smartcardno,'Sender'=>$username,'Description'=>'Debit: Cable Subscription for '.$smartcardno,'init_bal'=>$balance,'final_bal'=>$newbalance));
		
		creditTvBonus($username,$smartcardno);
		//company profit
		$cprofit = $avamount * 0.018;
		mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Cable-$cableSelected','$avamount','$cprofit','$date')");
		
		
		
	}
	else{
		/*	vtpass($invoiceperiod,$cable,$amount,$customernumber,$customername,$smartcardno,$username,$cost,$balance,$newbalance,$cprofit);
			*/
			mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
    	$_SESSION['cablenotice'] = "<label style='color:red;'>Transaction Failed</label>";
    	
    	saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$cost,'Receiver'=>$username,'Sender'=>$smartcardno,'Description'=>'Refund: Failed Cable Subscription for '.$smartcardno,'init_bal'=>$newbalance,'final_bal'=>$balance));
		       

		}
		
		
}

	
function chargeCustomertv($cost,$balance,$username)
{
	include('dbcon.php');
	$newbalance = $balance - $cost;
	mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
}

function creditTvBonus($username,$smartcardno)
{
	include('dbcon.php');
	
	//User
	$userresult = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$username'");
	$userbalance = mysqli_fetch_array($userresult)["Balance"];
	$newuserbalance = $userbalance + 30;
	mysqli_query($con,"UPDATE wallets SET Balance='$newuserbalance' WHERE Username='$username'");
	saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>30,'Receiver'=>$username,'Sender'=>$smartcardno,'Description'=>'Credit: Bonus Cable Subscription of '.$smartcardno,'init_bal'=>$userbalance,'final_bal'=>$newuserbalance));
	
	//Referal
	$netresult = mysqli_query($con,"SELECT * FROM networks WHERE Username='$username'");
	$ref = mysqli_fetch_array($netresult)["Referal_Id"];
	$refresult = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$ref'");
	$refbalance = mysqli_fetch_array($refresult)["Balance"];
	$newrefbalance = $refbalance + 20;
	mysqli_query($con,"UPDATE wallets SET Balance='$newrefbalance' WHERE Username='$ref'");
	saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>20,'Receiver'=>$ref,'Sender'=>$smartcardno,'Description'=>'Credit: referral bonus from Cable Subscription by '.$username,'init_bal'=>$refbalance,'final_bal'=>$newrefbalance));
}

function checkBalance($amount)
{
    $user = "daypayz.com@gmail.com"; //email address
    $password = "@ojobe77"; //password
    $host = 'https://vtpass.com/api/balance';
	
	$curl       = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => $host,
	CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERPWD => $user.":" .$password,
		CURLOPT_CUSTOMREQUEST => "GET"
	));
	$result = curl_exec($curl);
    $obj = json_decode($result);
    if($obj->contents->balance > $amount)
    {
        return true;
    }else
    {
        return false;;
    }
}
/*
if($receipt == "YES")
{
	header("location:receipt/cable_sub.php?rd=$tid&sd=$smartcardno&cb=$cable&ct=$cost");
	}
else
{
	header('location:ttvsub.php');
	}
	*/
header('location:tvsub.php');
?>
