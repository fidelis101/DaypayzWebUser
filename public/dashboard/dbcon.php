<?php
date_default_timezone_set("Africa/Lagos");
 $con = mysqli_connect("mysqldb.cj688k4iyjmm.us-east-1.rds.amazonaws.com","admin","Fidelis101") 
 or 
 die ("All our Services will be back shortly, We appologise for all inconveniences");
 
 mysqli_select_db($con,"daypayz_db");
    
$basePath = "/home/daypay5/public_html/dashboard";

?>
