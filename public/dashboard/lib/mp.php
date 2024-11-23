<?php
include('get_content.php');
$tid = $_REQUEST['rd'];
	$smartcard = $_REQUEST['sd'];
	$cable = $_REQUEST['cb'];
	$cost = $_REQUEST['ct'];
	$receiptno = $_REQUEST['rn'];
	
//ob_start();
//require_once('../receipt/index.html');
//$template = ob_get_clean();

$template = curl_get_contents("https://www.daypayz.com/dashboard/receipt/cable_sub.php?rd=$tid&sd=$smartcard&cb=$cable&ct=$cost");
	
include('mpdf/mpdf.php');
$mpdf=new mPDF();
$mpdf->WriteHTML($template);
//$mpdf->WriteHTML('<p style="color:red;">Hallo World<br/>Fisrt sentencee</p>');


//pop file up
$mpdf->Output('receipt-'.$receiptno.'.pdf','I');   exit;


//write pdf to directory
//$mpdf->Output('pdfs/message-'.time().'.pdf','F'); 

?>