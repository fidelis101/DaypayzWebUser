<?php
require_once 'HttpClient.php';

class MonnifyEndpoint
{
    private static $user="MK_PROD_2732U74PDN";
    private static $password = "DAXURF7TCBTTHNCEYGK94G3LQP4WCM4R";
    public static $contract = "961176145574";
    private static $baseUrl = "https://api.monnify.com";
    private static $login = "/api/v1/auth/login";
    private static $transactionStatus = "/api/v2/transactions/";//query?transactionReference=";
    private static $reserveAccount = "/api/v1/bank-transfer/reserved-accounts";
    private static $getAccountDetails = "/api/v1/bank-transfer/reserved-accounts/";
    private static $updateIncomeSplit = "/api/v1/bank-transfer/reserved-accounts/update-income-split-config/";
    private static $getAllTransactions = "/api/v1/bank-transfer/reserved-accounts/transactions?";
    private static $deallocatingAccount = "/api/v1/bank-transfer/reserved-accounts/";

    public static function login()
    {
        $data = array(); 
        $result = CurlCon::curl_post_basic_auth(self::$baseUrl.self::$login,'POST',$data,self::$user,self::$password)['Result'];
        $response = json_decode($result);
        if($response->requestSuccessful == 'true')
            return $response->responseBody->accessToken;
        else
        return "";

    }

    public static function getTrasactionStatus($ref)
    {
        $ref = str_replace("|","%7C",$ref);
        $url = self::$baseUrl.self::$transactionStatus.$ref;
        $token = self::login();
        $headr = array();
        $headr[] = 'Authorization: Bearer '.$token;
        $response = CurlCon::curl_get($url,$headr);
        return $response['Result'];
    }

    public static function getAcoountDetails($ref)
    {
        $url = self::$baseUrl.self::$getAccountDetails.$ref;
        $token = self::login();
        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$token; 
        return $response = CurlCon::curl_get($url,$headr)["Result"];
    }

    public static function updateIcomeSplit($ref,$request)
    {
        $url = self::$baseUrl.self::$updateIncomeSplit.$ref;
        $token = self::login();
        $headr = array();
        $data = json_encode($request);
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$token; 
        return $response = CurlCon::curl_post($url,$data,$headr)["Result"]; 
    }
    
    public static function reserveCustomerAccount($request)
    {
        $url = self::$baseUrl.self::$reserveAccount;
        $token = self::login();
        $headr = array();
        $data = json_encode($request);
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$token; 
        return $response = CurlCon::curl_post($url,$data,$headr)["Result"]; 
    }

    public static function getAllTransactions($ref,$page,$size)
    {
        $param = "accountReference=$ref&page=$page&size=$size";
        $url = self::$baseUrl.self::$getAllTransactions.$param;
        $token = self::login();
        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$token; 
        return $response = CurlCon::curl_get($url,$headr)["Result"];
    }
    public static function dellocateAccount($ref)
    {
        $url = self::$baseUrl.self::$deallocatingAccount.$ref;
        $token = self::login();
        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer '.$token; 
        return $response = CurlCon::curl_get($url,$headr)["Result"];
    }

    public static function confirmTranHash($request)
    {
        $data = self::$password."|$request->paymentReference|{$request->amountPaid}|{$request->paidOn}|{$request->transactionReference}";
        $hashed = hash('sha512', $data);
        if($hashed == $request->transactionHash)
        return true;
        else
        return false;
    }
}