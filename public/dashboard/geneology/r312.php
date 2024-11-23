
<?php
include("dbcon.php");
if(isset($_REQUEST['username']))
$username=$_REQUEST["username"];
        $downline = $con->query("SELECT * FROM networks WHERE Leader_Id='$username'");
        if(mysqli_num_rows($downline)>1)
        {
        $i = 1;
        while($row = mysqli_fetch_array($downline))
           {
            $d[$i] = $row['Username'];$stage[$i] = $row['Stage'];
            $i++;
            }
            $d1 = $d[1];$stage1 = $stage[1];
            $d2 = $d[2];$stage2 = $stage[2];
        }
        
        $downline2 = $con->query("SELECT * FROM networks WHERE Leader_Id='$d[1]'");
        if(mysqli_num_rows($downline2)>0)
        {
            $row = mysqli_fetch_array($downline2);
            $d3= $row['Username'];$stage3 = $row['Stage'];
        }
        
        $downline1 = $con->query("SELECT * FROM networks WHERE Leader_Id='$d[2]'");
        if(mysqli_num_rows($downline1)>1)
        {
        
        while($row = mysqli_fetch_array($downline1))
           {
            $d[$i] = $row['Username'];$stage[$i] = $row['Stage'];
            $i++;
            }
            $d5 = $d[3];$stage5 = $stage[3];
            $d6 = $d[4];$stage6 = $stage[4];
        }
	
?>

<div class="container">
<img src="images/r312.jpg" alt="gene" width="100%" />
<div class="username" ><?php echo $username; ?></div>
		<div class="d1" ><?php echo $d1; ?></div>
		<div class="d2" ><?php echo $d2; ?></div>
		<div class="d3" ><?php echo $d3; ?></div>
		<div class="d4" >empty<?php echo "<a href='../adduser.php?pl=$d1'>Add</a>";?></div>
		<div class="d5" ><?php echo $d5; ?></div>
		<div class="d6" ><?php echo $d6; ?></div>

</div>