
<?php
include("dbcon.php");
if(isset($_REQUEST['username']))
$username=$_REQUEST["username"];
	
?>

<div class="container">
<img src="images/usr.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $username; ?></div>
		<div class="d1" >empty<?php echo "<a href='../adduser.php?pl=$username'>Add</a>";?></div>
		<div class="d2" >empty<?php echo "<a href='../adduser.php?pl=$username'>Add</a>";?></div>
		<div class="d3" >empty</div>
		<div class="d4" >empty</div>
		<div class="d5" >empty</div>
		<div class="d6" >empty</div>

</div>