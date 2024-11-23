<?php

require_once("./Handlers/PaymentHandler.php");
require_once("logger.php");
$host_url = $_SERVER['SERVER_NAME'];

function getPost()
{
    return file_get_contents('php://input'); 
}
$request =getPost(); 
$updateModel =  json_decode($request);

$log = new Logger("logs/log.txt");
$log->setTimestamp("D M d 'y h.i A");

$log->putLog("\n funding running \n");
$log->putlog($request);
if(MonnifyEndpoint::confirmTranHash(($updateModel)))
{
    PaymentHandler::ConfirmMonnifyPayment($updateModel);
}
