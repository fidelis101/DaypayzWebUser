<?php
require_once("head.php");
include('dbcon.php');
include('get_content.php');
$transactionid = $_POST['transactionid'];
$description = $_POST['description'];
$provider = $_POST['provider'];
$cost = $_POST['rfcost'];
?>
<div id="page-wrapper">

<?php
    
    if($provider == "CK")
    {
        $result = curl_get_contents("https://www.nellobytesystems.com/APIQuery.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&OrderID=".$transactionid);
    	$obj = json_decode($result);
    	echo "</br>Status: ".$obj->status;
    	echo "</br>Date: ".$obj->date;
    	if($obj->remark == "By APIUser"){}
    	else{echo "</br>Remark: ".$obj->remark;}
    	echo "</br>OrderType: ".$obj->ordertype;
    	
    	
         if($obj->status == "ORDER_RECEIVED" || $obj->status == "ORDER_ONHOLD")
         {
             echo "<h3>'$obj->status'<h3>";
          echo "<form action='taction.php' method='post'>
             <input type='submit' class='btn-sm btn-primary' value='Cancel/Refund' >      
             <input hidden id='description' name='description' value='$description' >
                <input hidden id='rfcost' name='rfcost' value='$cost' >
             <input hidden id='orderid' name='orderid' value='$transactionid' >
             </form>";
        }
    }
    
    if($provider == "EDS")
    {
        $result = file_get_contents("https://easydatashareng.com/http/status?userid=08036292779&pass=1d2fd3b902ecf3c"."&tid=".$transactionid);
		
	    list($statusCode,$statusMessage) = explode("|",$result);
	    
    	echo "</br>Status: ".$statusMessage;
    	echo "</br>Description: ".$description;
    	
    }
	
require_once("foot.php");
?>
