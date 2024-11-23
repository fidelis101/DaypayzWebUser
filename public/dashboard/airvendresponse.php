<?php

/*
Array ( [address] => 22b Co-operative Boulevard Ave. T/ekulu, Enugu [arrears] => 0.0 [balance] => 31603.41 [casher] => callphone info@callphone.com [creditToken] => 62879854121752694615 [invoiceNumber] => 5082919 [message] => Bills Payment effected successfully [responseCode] => 0 [success] => 1 [tarrif] => R2S [vat] => 4.76 )

Array ( [address] => 22b Co-operative Boulevard Ave. T/ekulu, Enugu [arrears] => 0.0 [balance] => 26986.41 [casher] => callphone info@callphone.com [creditToken] => 23756434557755561387 [invoiceNumber] => 5090449 [message] => Bills Payment effected successfully [responseCode] => 0 [success] => 1 [tarrif] => R2S [vat] => 2.53 )
*/
/* 6482727 0404240408 50 12 0 SUCCESS 11666.2 550692217 ACCEPTED */
/* 
Array ( [balance] => 0 [message] => Attempting double transaction. Your initial transaction was successful. [responseCode] => 1 [success] => )
*/


$response = "Array ( [address] => 22b Co-operative Boulevard Ave. T/ekulu, Enugu [arrears] => 0.0 [balance] => 31603.41 [casher] => callphone info@callphone.com [creditToken] => 62879854121752694615 [invoiceNumber] => 5082919 [message] => Bills Payment effected successfully [responseCode] => 0 [success] => 1 [tarrif] => R2S [vat] => 4.76 )";
$response = json_decode($response);

echo $response=>responseCode;

?>

/*
code it querying everyone of the response

*/