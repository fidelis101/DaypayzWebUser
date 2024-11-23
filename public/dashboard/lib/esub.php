<?php
	include('get_content.php');
	$tid = $_REQUEST['td'];
	$meterno = $_REQUEST['mn'];
	$provider = $_REQUEST['p'];
	$package = $_REQUEST['pk'];
	$cost = $_REQUEST['ct'];
	$receiptno = $_REQUEST['rn'];
	$sp = $_REQUEST['sp'];
//ob_start();
//require_once('../receipt/index.html');
//$template = ob_get_clean();

$template = curl_get_contents("https://www.daypayz.com/dashboard/receipt/electric_sub.php?td=$tid&mn=$meterno&p=$provider&ct=$cost&pk=$package&sp=$sp");
	
include('mpdf/mpdf.php');
$mpdf=new mPDF();
$mpdf->WriteHTML($template);
//$mpdf->WriteHTML('<p style="color:red;">Hallo World<br/>Fisrt sentencee</p>');


//pop file up
$mpdf->Output('receipt-'.$receiptno.'.pdf','I');   exit;


//write pdf to directory
//$mpdf->Output('pdfs/message-'.time().'.pdf','F'); 

?>