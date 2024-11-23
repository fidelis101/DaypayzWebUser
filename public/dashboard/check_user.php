<?php
include('dbcon.php');

    $username =  $con->real_escape_string($_POST['username']);
    $sqlNet="SELECT Username FROM networks WHERE Username ='$username'";
    $sqlUser="SELECT Username FROM users WHERE Username ='$username'";
    $sqlWallet="SELECT Username FROM wallets WHERE Username ='$username'";
    $sqlLogins="SELECT Username FROM logins WHERE Username ='$username'";

    $resNet=$con->query($sqlNet);
    $resUser=$con->query($sqlUser);
    $resWallet=$con->query($sqlWallet);
    $resLogins=$con->query($sqlLogins);

    echo $rows_returned = $resUser->num_rows + $resNet->num_rows + $resWallet->num_rows + $resLogins->num_rows;

?>