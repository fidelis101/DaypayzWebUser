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
$downline = $con->query("SELECT * FROM networks WHERE Leader_Id='$username'");

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
        $downline1 = $con->query("SELECT * FROM networks WHERE Leader_Id='$md[1]'");
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
        $downline2 = $con->query("SELECT * FROM networks WHERE Leader_Id='$md[2]'");
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
		<div class="d1" ><?php echo $d1; echo "<span style='color:green;'> ".$stage1."</span>";?></div>
		<div class="d2" ><?php echo $d2; echo "<span style='color:green;'> ".$stage2."</span>";?></div>
		<div class="d3" ><?php echo $d3; echo "<span style='color:green;'> ".$stage3."</span>";?></div>
		<div class="d4" ><?php echo $d4; echo "<span style='color:green;'> ".$stage4."</span>";?></div>
		<div class="d5" ><?php echo $d5; echo "<span style='color:green;'> ".$stage5."</span>";?></div>
		<div class="d6" ><?php echo $d6; echo "<span style='color:green;'> ".$stage6."</span>";?></div>

</div>
<br/><br/><br/>


<?php
$t = 3;
$j = 6;
   while($t <= 200)
   {
       $downline = $con->query("SELECT * FROM networks WHERE Leader_Id='$md[$t]'");

        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $dd[$i] = $row['Username'];
            $stage[$i] = $row['Stage'];
            $i++;
            }
            $md[$j+1] = $dd[1];
            $md[$j+2] = $dd[2];
        }
        $downline1 = $con->query("SELECT * FROM networks WHERE Leader_Id='$dd[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $dd[$i] = $row['Username'];
            $stage[$i] = $row['Stage'];
            $i++;
            }
            $md[$j+3] = $dd[3];
            $md[$j+4] = $dd[4];
        }
        $downline2 = $con->query("SELECT * FROM networks WHERE Leader_Id='$dd[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $dd[$i] = $row['Username'];
            $stage[$i] = $row['Stage'];
            $i++;
            }
            $md[$j+5] = $dd[5];
            $md[$j+6] = $dd[6];
        }
	$j = $j + 6;


    
       $t++;
       $downline_no++;
   }
?>


<?php
$g = 203;
$k = 1;
   while($g <= 570)
   {
$downline = $con->query("SELECT * FROM networks WHERE Leader_Id='$md[$g]'");

    $frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		$i = 0;
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliners = $row1['Username'];
          		$downline2 = $con->query("SELECT * FROM networks WHERE Leader_Id='$downliners'");
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
          		$i++;
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
 
 
 </div>
 </body>