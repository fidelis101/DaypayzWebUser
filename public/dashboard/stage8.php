<?php
session_start();
$stage = $_SESSION["stage"];
if($stage < 8)
{
 $_SESSION['dashboardnotice'] = "<label style='color:green;'>Your current stage is not permitted to viewb that stage</label>";
 header('location:index.php');
}
?>
<frameset cols="100%">
    <frame src="geneology/stage8.php" >
</frameset>
