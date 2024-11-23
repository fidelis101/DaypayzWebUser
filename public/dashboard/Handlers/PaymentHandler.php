<?php
require_once './endpoints/MonnifyEndpoint.php';
require_once 'TransactionHistoryHandler.php';
require_once  './logger.php';

class PaymentHandler
{
    public static function ConfirmMonnifyPayment($request)
    {
        include("dbcon.php");
        $log = new Logger("Handlers/log.txt");
        $log->setTimestamp("D M d 'y h.i A");
        $log->putLog("\n Monify Payment Running \n");
        $log->putlog($request->transactionReference);

        $ref = $request->transactionReference;
        $response = MonnifyEndpoint::getTrasactionStatus($ref);
        $resp = json_decode($response);
        
        if($resp->requestSuccessful == true)
        {
            $paymentStatus = $resp->responseBody->paymentStatus;
            $amount = $resp->responseBody->settlementAmount;
            $chargeResponsecode = $resp->responseCode;
            $username = $resp->responseBody->product->reference;
        }
        else
        {
            $log->putLog("\n $resp->responseMessage \n");
            return; 
        }

        if (($chargeResponsecode == "0") && ($paymentStatus == "PAID") && !empty($username)) 
        {
                $date = date("Y-m-d H:i:s");
                $amount = $amount - 2.75;
                $update = mysqli_query($con,"INSERT INTO monnify_paid(Username,Amount,Ref,Date) VALUES('$username','$amount','$ref','$date')") or $log->putLog(mysqli_error($con));
                if($update)
                {
                    $result1 = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$username'");
                    if($result1)
                    {
                        $bal = mysqli_fetch_array($result1)['Balance'];
                        $newbal = $bal + $amount;
                        mysqli_query($con,"UPDATE wallets SET Balance='$newbal' WHERE Username='$username'");
                        TransactionHistoryHandler::saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,
                                                        'Sender'=>'Card_Funding','Description'=>'Credit: Wallet Credit','init_bal'=>$bal,'final_bal'=>$newbal));
        
                    }else
                    {
                        $log->putLog("\n User does not have a wallet $username \n");
                    }
                }
                $log->putLog("\n $amount Wallet Top up Transaction was Completed \n");
        }
        else
        {
            $log->putLog("Wallet Funding Failed $paymentStatus"); 
        }
    }
}
