<?php
require_once("head.php");
include('dbcon.php');
$description = $_POST['description'];
?>
<div id="page-wrapper">


<?php

$orderid = $_POST['orderid'];
$username = $_SESSION['usr'];
$amount =  $_POST['rfcost'];

$result = file_get_contents("https://www.nellobytesystems.com/APIQuery.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&OrderID=".$orderid);
     $obj1 = json_decode($result);
     
     if($obj1->status == "ORDER_RECEIVED" || $obj1->status == "ORDER_ONHOLD")
     {
     $res = file_get_contents("https://www.nellobytesystems.com/APICancel.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&OrderID=".$orderid);
     $obj2 = json_decode($res);
     if($obj2->status == "ORDER_CANCELLED" || $obj2->status == "Pending Refund")
     {
              if (strpos($description,"Data") !==false){
    		
    		    debitDataBonus($username, $amount);
        		include('dbcon.php');
        		$row3=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM wallets WHERE Username ='$username'"));
        		$bal = $row3['Balance'];
        		include('dbcon.php');
        		
        		$balance = $bal + $amount;
        		mysqli_query($con,"UPDATE Wallets SET Balance='$balance' WHERE Username ='$username'");
        		 
        		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>"Refund. .1",'Description'=>"Refund Request by user ".$description,'init_bal'=>$bal,'final_bal'=>$balance));}
        		
        		else{
        		debitAirtimeBonus($username,$amount);
        		$row3=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM wallets WHERE Username ='$username'"));
        		$bal = $row3['Balance'];
        		include('dbcon.php');
        		
        		$balance = $bal + $amount*0.99;
        		mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username ='$username'");
        		
        		saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,'Sender'=>"Refund",'Description'=>"Refund Request by user ".$description,'init_bal'=>$bal,'final_bal'=>$balance));
    		}
    		
    	}
         
             echo "<h3>'Order has been cancelled and refund was succesful'<h3>";
         
    
    }
    else
    {
        echo "Transaction Status: ".$obj1->status;
        echo "Transaction Remark: ".$obj1->remark;
    }
   
    
  
function debitDataBonus($username,$cost)
	{
		include('dbcon.php');
		$network1 = mysqli_query($con,"SELECT * FROM networks WHERE Username='$username'");
		if(mysqli_num_rows($network1)>0)
		{
			$row = mysqli_fetch_array($network1);
			$referal = $row['Referal_Id'];
			$result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
			if($result->num_rows > 0)
			{
				$row = mysqli_fetch_array($result);
				$balance = $row['Balance'];
				$bal = $balance - $cost*0.01;
				mysqli_query($con,"UPDATE wallets SET Balance='$bal' WHERE Username='$referal'");
				saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($cost * 0.01),'Receiver'=>$referal,'Sender'=>'','Description'=>'Debit: Cancelled Direct Referal Bonus from data by '.$username));
			}
		}
	} 

function debitAirtimeBonus($username,$amount)
	{
		include('dbcon.php');
		$network1 = mysqli_query($con,"SELECT * FROM networks WHERE Username='$username'");
		if(mysqli_num_rows($network1)>0)
		{
			$row = mysqli_fetch_array($network1);
			$referal = $row['Referal_Id'];
			$result = $con->query("SELECT * FROM wallets WHERE Username = '$referal'");
			if($result->num_rows > 0)
			{
				$row = mysqli_fetch_array($result);
				$balance = $row['Balance'];
				$bal = $balance - ($amount*0.01);
				mysqli_query($con,"UPDATE Wallets SET Balance='$bal' WHERE Username='$referal'");
				saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount*0.01,'Receiver'=>$referal,
											'Sender'=>'','Description'=>'Debit: Cancelled Referal Bonus by '.$username));
			}
		}
	} 

require_once("foot.php");

?>