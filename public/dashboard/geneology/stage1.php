<?php
session_start();
include('dbcon.php');
include('networkdb.php');
include('get_content.php');
?>
<head>
<link href="css/style.css" rel="stylesheet" />
</head>
<body>
<div class="holder">
<?php

$username = $_SESSION["usr"];
$downline = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$username'");

    $frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliners= $row1['Username'];
          		$downline2 =  $con->query("SELECT * FROM networks WHERE Leader_Id='$downliners'");
          		if(mysqli_num_rows($downline2)>0)
          		{
          			if(mysqli_num_rows($downline2)>=2)
          			{
          				$fr=$fr."2";
          			}
          			else{
          				$fr= $fr."1";
          			}
          		}
          	
      	        }
      		if($fr != "r3")
      		{
      		    $frame=$fr;
      		}
      	}else
      	{
      	   $row = mysqli_fetch_array($downline);
      	   $frame = "r21";
      	   $downliner = $row['Username'];
      	   $downline2 =  $con->query("SELECT * FROM networks WHERE Leader_Id='$downliner'");
      	    if(mysqli_num_rows($downline2)==2)
      	   {
      	      $frame = $frame."2";
      	   }
      	   else if(mysqli_num_rows($downline2)==1)
      	   {
      	      $frame = $frame."1";
      	   }
      	}
    }else
    {
    	$frame = "user";
    }
     echo "<h1>Dowline 1</h1>";	     
     $host_url = $_SERVER['SERVER_NAME'];				
     echo curl_get_contents("http://".$host_url."/dashboard/geneology/".$frame.".php?username=".$username); 
     echo "<br/><br/><br/>";
 ?>
 </div>
 </body>