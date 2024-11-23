<?php
date_default_timezone_set("Africa/Lagos");
 $con = mysqli_connect("localhost","daypay5_fidel","Fidelis101.Daypayz") 
 or 
 die ("couldnot connect to database");
 
 mysqli_select_db($con,"daypay5_base");

?>