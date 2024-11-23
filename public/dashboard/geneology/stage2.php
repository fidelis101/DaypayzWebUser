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
$downline_no = 1;
$downline = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$username'");

  if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $md[$i] = $row['Username'];
            $stage[$i] = $row['Stage'];
            $i++;
            }
            $d1 = $md[1]; $stage1 = $stage[1];
            $d2 = $md[2]; $stage2 = $stage[2];
        }
        $downline1 = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$md[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $md[$i] = $row['Username'];
            $stage[$i] = $row['Stage'];
            $i++;
            }
            $d3 = $md[3];$stage3 = $stage[3];
            $d4 = $md[4];$stage4 = $stage[4];
        }
        $downline2 = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$md[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $md[$i] = $row['Username'];
             $stage[$i] = $row['Stage'];
            $i++;
            }
            $d5 = $md[5];$stage5 = $stage[5];
            $d6 = $md[6];$stage6 = $stage[6];
        }
	
?>
<h1>Geneology</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $username; echo "<span style='color:green;'> ".$_SESSION['stage'];?></div>
		<div class="d1" ><?php echo $d1; ?></div>
		<div class="d2" ><?php echo $d2; ?></div>
		<div class="d3" ><?php echo $d3; ?></div>
		<div class="d4" ><?php echo $d4; ?></div>
		<div class="d5" ><?php echo $d5; ?></div>
		<div class="d6" ><?php echo $d6; ?></div>

</div>
<br/><br/><br/>


<?php
$g = 3;
$k = 1;
   while($g <= 6)
   {
$downline = $con->query("SELECT * FROM networks WHERE Leader_Id='$md[$g]'");

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
          		$downline2 = $con->query("SELECT * FROM networks WHERE Leader_Id='$downliners'");
          		if(mysqli_num_rows($downline2)>0)
          		{
          			if(mysqli_num_rows($downline2)==2)
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
      	   $downline2 = $con->query("SELECT * FROM networks WHERE Leader_Id='$downliner'");
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
     echo "<h1>Downline $downline_no</h1>";	     
     $host_url = $_SERVER['SERVER_NAME'];						
     echo curl_get_contents("https://".$host_url."/dashboard/geneology/".$frame.".php?username=".$md[$g]);  
     echo "<br/><br/><br/>";
 
     if($g%3)
     {
         $g++;$k = 0;
     }else
     {
         if($k == 0){
         $g +=3; $k=1;}
         else
         {$g++;}
     }
     $downline_no++;
}
 ?>
