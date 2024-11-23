<?php
    include('get_content.php');
    $meternumber = $_REQUEST['meternumber'];
    $provider = $_REQUEST['provider'];
   
    
    if(true)
    {
        //2028831159
        $result = curl_get_contents("https://www.nellobytesystems.com/APIVerifyElectricityV1.0.asp?UserID=CK10049011&APIKey=7VRDEN9GSI24P0JL58625R59V1QU2KKV970PY5J33V38SW395Y4S2B7Y9605SPWG&ElectricCompany=$provider&MeterNo=$meternumber");
       
        $obj = json_decode($result);    
        
        echo "<font id='cnum1'  color='green'>".$result."</font>";
    }
 
?>