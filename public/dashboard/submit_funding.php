<?php 
include('dbcon.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $depositor = mysqli_real_escape_string($con, $_POST["depositor"]);
    $dateofpayment = mysqli_real_escape_string($con, $_POST["dateofpayment"]);
    $tnumber = mysqli_real_escape_string($con, $_POST["teller"]);
    $amount = mysqli_real_escape_string($con, $_POST["amount"]);
    $placementdate = date("Y-m-d H-i-s");
    $uname = $_SESSION['usr'];

    mysqli_query($con,"INSERT INTO pendingfunds (Username,Depositor,Transaction_Details,Amount,Date_of_Payment,Placement_Date) VALUES('$uname','$depositor','$tnumber','$amount','$dateofpayment','$placementdate')");
    
    $_SESSION['fundnotification'] = "<p class='text-success'>Request Placed Successfully We Will Attend to You Shortly</p>";
}
header('location:bank_fund.php');
?>