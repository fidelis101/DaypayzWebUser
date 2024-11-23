<?php
session_start();
$user = $_SESSION["usr"];
$stage = $_SESSION["stage"];
if($stage < 1)
{
    header('location:index.php');
}
?>
<div class="">
<frameset cols="50%">
    <frame src="binarytree/tree.php?username=<?php echo $user; ?>" >
</frameset>
<div>
