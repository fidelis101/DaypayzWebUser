<?php
	include("head.php");
?>
<script>
	function myFunction() {
	  /* Get the text field */
	  var copyText = document.getElementById("link");
	
	  /* Select the text field */
	  copyText.select();
	
	  /* Copy the text inside the text field */
	  document.execCommand("copy");
	
	  /* Alert the copied text */
	  alert("Copied the text: " + copyText.value);
	}	
</script>
<div class="container">
	<label > Referal link: <span class="text-primary">https://www.daypayz.com/dashboard/register.php?userid=<?php echo $_SESSION['usr'];?> </span></label><br/>
	
	<input hidden id="link" <?php echo "value='https://www.daypayz.com/dashboard/register.php?userid=$_SESSION[usr]'";?> />
	<button class="btn btn-primary" onclick='myFunction()'>Copy link</button><br/>
	Share Referal Link: <br/>
	
	<a class="btn btn-success" href="whatsapp://send?text=<?php echo urlencode ('https://www.daypayz.com/dashboard/register.php?userid='.$_SESSION['usr']); ?>" data-action="share/whatsapp/share">Share via Whatsapp</a> 
</div>
<?php
	include("foot.php");
?>