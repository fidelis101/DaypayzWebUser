<?php
session_start();
$stage = $_SESSION["stage"];
if($stage < 4)
{
 $_SESSION['dashboardnotice'] = "<label style='color:green;'>Your current stage is not permitted to view that stage</label>";
 header('location:index.php');
}
?>
<frameset cols="100%">
    <frame src="geneology/stage4.php" >
</frameset>
