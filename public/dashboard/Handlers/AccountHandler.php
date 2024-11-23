<?php
require_once("./endpoints/MonnifyEndpoint.php");
require_once  './logger.php';

class AccountHandler
{
    public static function ReserveTransferAccount($username)
    {
        include("./dbcon.php");
        $log = new Logger("log.txt");
        $log->setTimestamp("D M d 'y h.i A");
        $log->putLog("\n Monify Payment Running \n");

        $user = mysqli_query($con,"SELECT * FROM users WHERE Username='$username'");
        if(mysqli_num_rows($user) > 0)
        {
            $row = mysqli_fetch_array($user);
            $email = $row['Email'];
            $firstname = $row['Firstname'];
            $lastname = $row['Lastname'];
        }

        $requestModel = new stdClass();

        $requestModel->accountReference = $username;
        $requestModel->accountName = "$username"; 
        $requestModel->currencyCode = "NGN";
        $requestModel->contractCode = MonnifyEndpoint::$contract; 
        $requestModel->customerEmail = $email; 
        $requestModel->customerName = $firstname." ".$lastname;

        // $incomeSplit->subAccountCode = "MFY_SUB_345115338981";
        // $incomeSplit->feePercentage = 0.0;
        // $incomeSplit->splitPercentage = 100; 
        // $incomeSplit->feeBearer = false; 
        
        // $requestModel->incomeSplitConfig = $incomeSplit;
        $requestModel->restrictPaymentSource = false;
        
        $res = MonnifyEndpoint::reserveCustomerAccount($requestModel);
        $log->putLog("\n Response from monnify reserve for (Username: $username) $res \n");
        $response = json_decode($res);

        if($response->requestSuccessful == true)
        {
            if($response->responseCode == 0)
            {
                $reserveAccountModel = new stdClass();
                $accountReference = $response->responseBody->accountReference;
                $accountName = $response->responseBody->accountName;
                $accountNumber = $response->responseBody->accountNumber;
                $bankName = $response->responseBody->bankName;
                $bankCode = $response->responseBody->bankCode;
                $reservationReference = $response->responseBody->reservationReference;
                $status = $response->responseBody->status;
                $createdOn = $response->responseBody->createdOn;
                
                $query = "INSERT INTO virtual_accounts (Username,AccountRef,AccountName,AccountNumber,BankName,BankCode,ReservationRef,Status,CreatedOn) 
                VALUES ('$username','$accountReference','$accountName','$accountNumber','$bankName','$bankCode','$reservationReference','$status','$createdOn')";

                mysqli_query($con,$query) or die(mysqli_error($con));
            }else{
                $log->putLog("\n $res \n");
            }
        }else{
            $log->putLog("\n Response from Reserve $res \n");
        }
    }
    
    public static function ReserveWemaTransferAccount($username)
    {
        include("./dbcon.php");
        $log = new Logger("log.txt");
        $log->setTimestamp("D M d 'y h.i A");
        $log->putLog("\n Monify Payment Running \n");

        $user = mysqli_query($con,"SELECT * FROM users WHERE Username='$username'");
        if(mysqli_num_rows($user) > 0)
        {
            $row = mysqli_fetch_array($user);
            $email = $row['Email'];
            $firstname = $row['Firstname'];
            $lastname = $row['Lastname'];
        }

        $requestModel = new stdClass();

        $requestModel->accountReference = $username;
        $requestModel->accountName = "$username"; 
        $requestModel->currencyCode = "NGN";
        $requestModel->contractCode = MonnifyEndpoint::$contract; 
        $requestModel->customerEmail = $email; 
        $requestModel->customerName = $firstname." ".$lastname;
        
        $requestModel->getAllAvailableBanks = false; 
        $requestModel->preferredBanks = ["035"];

        // $incomeSplit->subAccountCode = "MFY_SUB_345115338981";
        // $incomeSplit->feePercentage = 0.0;
        // $incomeSplit->splitPercentage = 100; 
        // $incomeSplit->feeBearer = false; 
        
        // $requestModel->incomeSplitConfig = $incomeSplit;
        $requestModel->restrictPaymentSource = false;
        
        $res = MonnifyEndpoint::reserveCustomerAccount($requestModel);
        $log->putLog("\n Response from monnify reserve for (Username: $username) $res \n");
        $response = json_decode($res);

        if($response->requestSuccessful == true)
        {
            if($response->responseCode == 0)
            {
                $reserveAccountModel = new stdClass();
                $accountReference = $response->responseBody->accountReference;
                $accountName = $response->responseBody->accountName;
                $accountNumber = $response->responseBody->accountNumber;
                $bankName = $response->responseBody->bankName;
                $bankCode = $response->responseBody->bankCode;
                $reservationReference = $response->responseBody->reservationReference;
                $status = $response->responseBody->status;
                $createdOn = $response->responseBody->createdOn;
                
                $query = "INSERT INTO virtual_accounts (Username,AccountRef,AccountName,AccountNumber2,BankName2,BankCode2,ReservationRef2,Status,CreatedOn) 
                VALUES ('$username','$accountReference','$accountName','$accountNumber','$bankName','$bankCode','$reservationReference','$status','$createdOn')";

                mysqli_query($con,$query) or die(mysqli_error($con));
            }else{
                $log->putLog("\n $res \n");
            }
        }else{
            $log->putLog("\n Response from Reserve $res \n");
        }
    }
    
    public static function GetReservedAccounts($ref)
    {
        include("./dbcon.php");
        $log = new Logger("log.txt");
        $log->setTimestamp("D M d 'y h.i A");
        $log->putLog("\n Monify Payment Running \n");
        $res = MonnifyEndpoint::getAcoountDetails($ref);
        $log->putLog("\n Response from monnify reserve for (Username: $username) $res \n");
        return $res;
    }
}
