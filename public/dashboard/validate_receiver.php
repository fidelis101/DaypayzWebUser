<?php
include('dbcon.php');

$username =  $con->real_escape_string($_POST['receiver']);
    $sqlUser="SELECT * FROM users WHERE Username ='$username'";
    $resUser=$con->query($sqlUser);
    if($resUser === false) {
        trigger_error('Error: ' . $con->error, E_USER_ERROR);
    } else {
    	$row = mysqli_fetch_array($resUser);
        echo $rows_returned = $row['Firstname'].' '.$row['Lastname'];
    }

?>