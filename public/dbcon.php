<?php
date_default_timezone_set("Africa/Lagos");
 $con = mysqli_connect("localhost","root","") 
 or 
 die ("could not connect to database");
 
 mysqli_select_db($con,"daypayz_db");

?>