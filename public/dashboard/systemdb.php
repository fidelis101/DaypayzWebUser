<?php 
function debitSystemWallet($amount)
{
    include('dbcon.php');
    $result = $con->query("SELECT * FROM systemwallet");
    $wallet = mysqli_fetch_array($result);
    $id = $wallet['Id'];
    $balance = $wallet['Balance'];
    $balance = $balance - $amount;
    $con->query("UPDATE systemwallet SET Balance='$balance' WHERE Id = '$id'");
    
}

?>