<?php
	
	include('dbcon.php');
	include('get_content.php');
	$tid = $_REQUEST['rd'];
	$smartcard = $_REQUEST['sd'];
	$cable = $_REQUEST['cb'];
	$cost = $_REQUEST['ct'];
	$owner = curl_get_contents("https://www.daypayz.com/dashboard/verifytvuser.php?provider=gotv&cardnumber=$smartcard");
	
	$result = mysqli_query($con,"SELECT * FROM apitransactions Where Request_Id ='$tid' AND cost='$cost' AND Status='TRANSACTION SUCCESSFUL'");
	if(mysqli_num_rows($result) > 0)
	{
		$row = mysqli_fetch_assoc($result);
		$description = $row['Description'];
		$receiptno = $row['Id'];
		$username = $row['Username'];
		$date = $row['Transaction_Date'];
		
		if(substr_count($description,$smartcard) > 0)
		{
			if($cable=='01')
				$product = "DSTv Sub";
			else if($cable=='02')
				$product = "GOTv Sub";
			else if($cable=='03')
				$product = "Startime Sub";
		}
		$result1 = mysqli_query($con,"SELECT * FROM users WHERE Username ='$username'");
		$row = mysqli_fetch_assoc($result1);
		$name = $row['Firstname']." ".$row['Lastname'];
		$email=$row['Email'];
		
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Daypayz Receipt</title>
    <link rel="stylesheet" href="../receipt/style.css" media="all" />
  </head>
  <body>
    <header >
    <button onclick="myFunction();">Print</button>
      <div id="logo">
        <img src="../receipt/logo.jpg">
      </div>
      
      <h1>Receipt No: <?php echo $receiptno; ?></h1>
      <div id="company" class="clearfix">
        <div>Daypayz Intl ltd</div>
        <div>NO 22 Ogui Road, Enugu. </div>
        <div>08034098319</div>
        <div><a href="mailto:info@daypayz.com">info@daypayz.com</a></div>
      </div>
      <div id="project">
        <div><span>Customer</span> <?php echo $name; ?></div>
        <div><span>Email</span> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>
        <div><span>Payment Date</span> <?php echo $date; ?></div>
      </div>
    </header>
    <main >
      <table class="clearfix" style="background: url(../receipt/Paid.jpg);background-size: 300px 100px;background-repeat: no-repeat; background-position: center; ">
        <thead>
          <tr>
            <th class="service">Product</th>
            <th class="desc">DESCRIPTION</th>
            <th>PRICE</th>
          </tr>
        </thead>
        <tbody >
          <tr>
            <td class="service"><?php echo $product; ?></td>
            <td class="desc">Smartcard Number <?php echo $smartcard; ?>, owner: <?php echo $owner; ?></td>
            <td class="unit">N<?php echo ($cost-50); ?>.00</td>
          </tr>
           <tr>
            <td class="service">Other</td>
            <td class="desc">Convenience Fee</td>
            <td class="unit">N50.00</td>
          </tr>
          <tr>
            <td colspan="2">Discount</td>
            <td class="total">-N30.OO</td>
          </tr>
       
          <tr>
            <td colspan="2" class="grand total">TOTAL</td>
            <td class="grand total">N<?php echo ($cost-30); ?>.00</td>
          </tr>
        </tbody>
      </table>
      
    </main>
    <footer>
      Receipt was created on a computer and is valid without the signature and seal.
    </footer>
    
    
<script>

function myFunction() {
	
     window.location.href="<?php echo "https://www.daypayz.com/dashboard/lib/mp.php?rd=$tid&sd=$smartcard&cb=$cable&ct=$cost&rn=$receiptno";?>";
}
</script>
  </body>
</html>