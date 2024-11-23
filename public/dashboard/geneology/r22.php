
<?php
include("dbcon.php");
if(isset($_REQUEST['username']))
$username=$_REQUEST["username"];
        $downline = $con->query("SELECT * FROM networks WHERE Leader_Id='$username'");
        if(mysqli_num_rows($downline)>0)
        {$i++;
            while($row = mysqli_fetch_array($downline)){          
            $d[$i]= $row['Username'];
            $stage[$i] = $row['Stage'];
            $i++;
            }
            $d1 = $d[1];$stage1 = $stage[1];
            $d2 = $d[2];$stage2 = $stage[2];
        }
	
?>

<div class="container">
<img src="images/r22.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $username; echo "<span style='color:green;'> "?></div>
		<div class="d1" ><?php echo $d1;?></div>
		<div class="d2" ><?php echo $d2; ?></div>
		<div class="d3" >empty<?php echo "<a href='../adduser.php?pl=$d1'>Add</a>";?></div>
		<div class="d4" >empty<?php echo "<a href='../adduser.php?pl=$d1'>Add</a>";?></div>
		<div class="d5" >empty<?php echo "<a href='../adduser.php?pl=$d2'>Add</a>";?></div>
		<div class="d6" >empty<?php echo "<a href='../adduser.php?pl=$d2'>Add</a>";?></div>

</div>