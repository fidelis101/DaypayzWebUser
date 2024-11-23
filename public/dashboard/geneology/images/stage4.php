

<head>
<link href="css/style.css" rel="stylesheet" />
</head>
<body>
<div class="holder">


<?php
include('dbcon.php');
include('networkdb.php');
session_start();
$username = $_SESSION["usr"];
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$username'");

  if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $stage[$i] = $row['Stage'];
            $i++;
            }
            $d1 = $d[1]; $stage1 = $stage[1];
            $d2 = $d[2]; $stage2 = $stage[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $stage[$i] = $row['Stage'];
            $i++;
            }
            $d3 = $d[3];$stage3 = $stage[3];
            $d4 = $d[4];$stage4 = $stage[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
             $stage[$i] = $row['Stage'];
            $i++;
            }
            $d5 = $d[5];$stage5 = $stage[5];
            $d6 = $d[6];$stage6 = $stage[6];
        }
	
?>
<h1>Geneology</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $username; ?></div>
		<div class="d1" ><?php echo $d1; ?></div>
		<div class="d2" ><?php echo $d2; ?></div>
		<div class="d3" ><?php echo $d3; ?></div>
		<div class="d4" ><?php echo $d4; ?></div>
		<div class="d5" ><?php echo $d5; ?></div>
		<div class="d6" ><?php echo $d6; ?></div>

</div>
<br/><br/><br/>

<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d3'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d7 = $d[1];
            $d8 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d9 = $d[3];
            $d10 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d11 = $d[5];
            $d12 = $d[6];
        }
	
?>

<h1>Downline 1</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d3; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>


<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d4'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d13 = $d[1];
            $d14 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d15 = $d[3];
            $d16 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d17 = $d[5];
            $d18 = $d[6];
        }
	
?>

<h1>Downline 2</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d4; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
  
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d5'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d19 = $d[1];
            $d20 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d21 = $d[3];
            $d22 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d23 = $d[5];
            $d24 = $d[6];
        }
	
?>
<h1>Downline 3</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d5; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d6'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d25 = $d[1];
            $d26 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d27 = $d[3];
            $d28 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d29= $d[5];
            $d30 = $d[6];
        }
	
?>
<h1>Downline 4</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d6; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>

<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d9'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d31 = $d[1];
            $d32 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d33 = $d[3];
            $d34 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d35 = $d[5];
            $d36 = $d[6];
        }
	
?>

<h1>Downline 5</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d9; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>


<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d10'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d37 = $d[1];
            $d38 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d39 = $d[3];
            $d40 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d41 = $d[5];
            $d42 = $d[6];
        }
	
?>

<h1>Downline 6</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d10; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
  
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d11'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d43 = $d[1];
            $d44 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d45 = $d[3];
            $d46 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id=".$d[2]);
        
        $d[5]='';$d[6]='';
         if(mysqli_num_rows($downline2)>1)
        {
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d47 = $d[5];
            $d48 = $d[6];
        }
	
?>
<h1>Downline 7</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d11; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d12'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d49 = $d[1];
            $d50 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d51 = $d[3];
            $d52 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id=".$d[2]);
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d53= $d[5];
            $d54 = $d[6];
        }
	
?>
<h1>Downline 8</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d12; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>

<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d15'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d55 = $d[1];
            $d56= $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d57 = $d[3];
            $d58 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d59 = $d[5];
            $d60 = $d[6];
        }
	
?>

<h1>Downline 9</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d15; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>


<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d16'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d61 = $d[1];
            $d62 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d63 = $d[3];
            $d64 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d65 = $d[5];
            $d66 = $d[6];
        }
	
?>

<h1>Downline 10</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d16; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
  
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d17'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d67 = $d[1];
            $d68 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d69 = $d[3];
            $d70 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d71 = $d[5];
            $d72 = $d[6];
        }
	
?>
<h1>Downline 11</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d17; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d18'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d73 = $d[1];
            $d74 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d75 = $d[3];
            $d76 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d77= $d[5];
            $d78 = $d[6];
        }
	
?>
<h1>Downline 12</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d18; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>

<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d21'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d79 = $d[1];
            $d80 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d81 = $d[3];
            $d82 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d83 = $d[5];
            $d84 = $d[6];
        }
	
?>

<h1>Downline 13</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d21; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>


<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d22'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d85 = $d[1];
            $d86 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d87 = $d[3];
            $d88 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d89 = $d[5];
            $d90 = $d[6];
        }
	
?>

<h1>Downline 14</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d22; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
  
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d23'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d91 = $d[1];
            $d92 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d93 = $d[3];
            $d94 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d95 = $d[5];
            $d96 = $d[6];
        }
	
?>
<h1>Downline 15</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d23; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d24'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d97 = $d[1];
            $d98 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d99 = $d[3];
            $d100 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d101= $d[5];
            $d102 = $d[6];
        }
	
?>
<h1>Downline 16</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d24; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>

<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d27'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d103 = $d[1];
            $d104 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d105 = $d[3];
            $d106 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d107 = $d[5];
            $d108 = $d[6];
        }
	
?>

<h1>Downline 17</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d27; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>


<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d28'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d109 = $d[1];
            $d110 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d111 = $d[3];
            $d112 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d113 = $d[5];
            $d114 = $d[6];
        }
	
?>

<h1>Downline 18</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d28; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
  
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d29'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d115 = $d[1];
            $d116 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d117 = $d[3];
            $d118 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d119 = $d[5];
            $d120 = $d[6];
        }
	
?>
<h1>Downline 19</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d29; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>



<?php
   
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d30'");
        if(mysqli_num_rows($downline)>1)
        {
        $i=1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d121 = $d[1];
            $d122 = $d[2];
        }
        $downline1 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
           while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d123 = $d[3];
            $d124 = $d[4];
        }
        $downline2 = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d[2]'");
         if(mysqli_num_rows($downline2)>1)
        {
        
        while($row = mysqli_fetch_array($downline2))
           {
            $d[$i] = $row['Username'];
            $i++;
            }
            $d125= $d[5];
            $d126 = $d[6];
        }
	
?>
<h1>Downline 20</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $d30; ?></div>
		<div class="d1" ><?php echo $d[1]; ?></div>
		<div class="d2" ><?php echo $d[2]; ?></div>
		<div class="d3" ><?php echo $d[3]; ?></div>
		<div class="d4" ><?php echo $d[4]; ?></div>
		<div class="d5" ><?php echo $d[5]; ?></div>
		<div class="d6" ><?php echo $d[6]; ?></div>

</div>
<br/><br/><br/>

<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d33'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	}
      	else
      	{
      	   $row = mysqli_fetch_array($downline);
      	   $frame = "r21";
      	   $downliner = $row['Username'];
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 21</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d33);  
     echo "<br/><br/><br/>";
 

 ?>
 
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d34'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	}
      	else
      	{
      	   $row = mysqli_fetch_array($downline);
      	   $frame = "r21";
      	   $downliner = $row['Username'];
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 21</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d34);  
     echo "<br/><br/><br/>";
 

 ?>
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d35'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 23</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d35);  
     echo "<br/><br/><br/>";
 

 ?>
 
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id= '$d36'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 24</h1>";	     		
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d36);  
     echo "<br/><br/><br/>";
 

 ?>
 
 

<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d39'");

  
$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 25</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d39);  
     echo "<br/><br/><br/>";
 

 ?>
 
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d40'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Dowline 26</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d40);  
     echo "<br/><br/><br/>";
 

 ?>
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d41'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Dowline 27</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d41);  
     echo "<br/><br/><br/>";
 

 ?>
 
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id= '$d42'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 28</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d42);  
     echo "<br/><br/><br/>";
 

 ?>
 
 

<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d45'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    { 	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Dowline 29</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d45);  
     echo "<br/><br/><br/>";
 

 ?>
 
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id= '$d46'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 30</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d46);  
     echo "<br/><br/><br/>";
 

 ?>
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id= '$d47'");
 
$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Dowline 31</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d47);  
     echo "<br/><br/><br/>";
 

 ?>
 
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id= '$d48'");

 
$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 32</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d48);  
     echo "<br/><br/><br/>";
 

 ?>
 
 

<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id= '$d51'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 33</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d51);  
     echo "<br/><br/><br/>";
 

 ?>
 
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id='$d52'");


$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 34</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d52);  
     echo "<br/><br/><br/>";
 

 ?>
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id=".$d53);


$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 35</h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d53);  
     echo "<br/><br/><br/>";
 

 ?>
 
 
<?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id=".$d54);


$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 36 </h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d54);  
     echo "<br/><br/><br/>";
 

 ?>
 
 <?php
$downline = $con->query("SELECT * FROM Networks WHERE Leader_Id= '$d57'");

$frame = "user";
    if(mysqli_num_rows($downline) !=false)
    {
      	if(mysqli_num_rows($downline)>=2)
      	{
      		$frame="r22";
      		$fr="r3";
      		while($row1=mysqli_fetch_array($downline))
      		{
      
          		$downliner[$i] = $row1['Username'];
          		$downline2 = getUserDownline($downliner[$i]);
          		if($downline2 != false)
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
      	   $downline2 = getUserDownline($downliner);
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
     echo "<h1>Downline 37 </h1>";	     
                   							
     echo file_get_contents("https://vpserviceslimited.com/user/geneology/".$frame.".php?username=".$d57);  
     echo "<br/><br/><br/>";
 

 ?>
 </div>
 </body>