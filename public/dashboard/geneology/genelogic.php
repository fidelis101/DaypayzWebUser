<head>
<link href="css/style.css" rel="stylesheet" />
</head>
<body>
<div class="holder">
<?php
/*
include('dbcon.php');
require_once ('networkdb.php');
session_start();
$username = $_SESSION["usr"];
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$username'");

    $frame = "usr";
    if($downline !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($row1['Username']);
          		if($downline2 != false)
          		{
          			if(mysqli_num_rows($downline2)>=2)
          			{
          				$fr+="2";
          			}
          			else{
          				$fr+="1";
          			}
          		}
          		$i++;
      	    }
      		if($fr != "r3")
      		{
      		    $frame=$fr;
      		}
      	}
    }else
    {
    	$frame = "r21";
    }*/
     echo "<h1>Dowline 1</h1>";	                          							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/r21.php?username=fidelis101");  
     echo "<br/><br/><br/>";
 
 //require_once('footer.php');
 ?>
 </div>
 </body>