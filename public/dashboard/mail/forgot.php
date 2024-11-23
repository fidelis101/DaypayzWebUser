<?php
$user = $_REQUEST['user'];
$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$tk = $_REQUEST['tk'];

?>
<body style="margin: 10px;">
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
<div align="center"><img src="images/logo.jpg" style="height: 90px; width: 340px"></div><br>
<br>
&nbsp;Hi,<?php echo @$name; ?> <br>
<br>
Reset Password <br>
<br>
<br>
<?php echo "<a href='https://www.daypayz.com/dashboard/daypayzpassreset.php?user=$user&tk=$tk'>https://www.daypayz.com/dashboard/daypayzpassreset.php?user=$user&tk=$tk</a>";
?>
<br /><br />
Regards<br />
Daypayz International<br />
<br />
Daypayz:<br />
Email: support@daypayz.com<br />
</div>
</body>
