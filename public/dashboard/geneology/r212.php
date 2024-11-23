<?php
include("dbcon.php");
if(isset($_REQUEST['username']))
$username=$_REQUEST["username"];
        $downline = $con->query("SELECT * FROM networks WHERE Leader_Id='$username'");
        if(mysqli_num_rows($downline)>0)
        {
            $row = mysqli_fetch_array($downline);
            $d1 = $row['Username'];$stage1 = $row['Stage'];
            $downline1 = $con->query("SELECT * FROM networks WHERE Leader_Id='$d1'");
            $i = 1;
            while($rows = mysqli_fetch_array($downline1))
            {
               $d[$i] = $rows["Username"];
               $stage[$i] = $row['Stage'];
               $i++;
            }
            $d3=$d[1];$stage3 = $stage[1];
            $d4=$d[2];$stage4 = $stage[2];
        
	}
?>

<div class="container">
<img src="images/r212.jpg" alt="gene" width="100%" />
<div class="username" ><?php echo $username; ?></div>
		<div class="d1" ><?php echo $d1;?></div>
		<div class="d2" >empty<?php echo "<a href='../adduser.php?pl=$username'>Add</a>";?></div>
		<div class="d3" ><?php echo $d3; ?></div>
		<div class="d4" ><?php echo $d4; ?></div>
		<div class="d5" >empty</div>
		<div class="d6" >empty</div>

</div>