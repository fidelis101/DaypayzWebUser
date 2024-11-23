<?php
/*

The sandbox url is https://sandbox.vtpass.com/api/.

Use phone : 08123456789 and billersCode : 1111111111 for sandbox request

Transactions with similar billersCode within 90 Seconds are void and are seen as duplicate .

Use phone : 08222222222 and billersCode : 1111111111 to simulate duplicate transaction

*/
payfix();

function payflexi()
{
$username = "ugwufidelis007@gmail.com"; //email address
$password = "makeitcount4u"; //password
$host = 'http://sandbox.vtpass.com/api/payflexi';

$data = array(
  'serviceID'=> $_POST['serviceID'], //integer
  'amount' =>  $_POST['amount'], // integer
  'phone' => $_POST['recepient'], //integer
  'request_id' => '901059298909' // unique for every transaction
);
$curl       = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => $host,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_USERPWD => $username.":" .$password,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => $data,
));
echo curl_exec($curl);
}

function payfix()
{
	$username = "ugwufidelis007@gmail.com"; //email address
$password = "makeitcount4u"; //password
$host = 'http://sandbox.vtpass.com/api/payfix';

$data = array(
  'serviceID'=> 'eko-electric', //integer
  'amount' =>  '1000', // integer
  'phone' => '08123456789', //integer
  'request_id' => '9010592989', // unique for every transaction
  'billersCode'=>'1111111111'
  
);
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => $host,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_USERPWD => $username.":" .$password,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => $data,
));
echo curl_exec($curl);
}

function verifymerchant()
{

}
?>