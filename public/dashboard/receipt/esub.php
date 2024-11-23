<?php
	
	include('dbcon.php');
	include('get_content.php');
	include('../vtpass_link.php');
	
	$tid = $_REQUEST['td'];
	$meterno = $_REQUEST['mn'];
	$provider = $_REQUEST['p'];
	$package = $_REQUEST['pk'];
	$cost = $_REQUEST['ct'];
	$sp = $_REQUEST['sp'];
	
	if($sp == "AV")
	{
    	$resulte = curl_get_contents("https://api.airvendng.net/vas/electricity/verify/?username=daypayz.com@gmail.com&password=@Ojobe52487&account=$meterno&type=$package");
    	
    	//echo $resulte;
    	//exit; 
    	
    	$obj = simplexml_load_string($resulte);
    	$customername = $obj->details->name.$obj->details->firstName.$obj->details->Lastname;
    	$address = $obj->details->address;
    	
    	
    	$result = mysqli_query($con,"SELECT * FROM apitransactions Where Transaction_Id ='$tid' AND cost='$cost' AND Status='SUCCESS'");
    	if(mysqli_num_rows($result) > 0)
    	{
    		$row = mysqli_fetch_assoc($result);
    		$description = $row['Description'];
    		$receiptno = $row['Id'];
    		$username = $row['Username'];
    		$date = $row['Transaction_Date'];
    		$token = $row['Token'];
    		
    		if(substr_count($description,$meterno) > 0)
    		{
    			if($provider=='01')
    				$product = "Eko";
    			else if($provider=='02')
    				$product = "Ikeja";
    			else if($provider =='03')
    				$product = "Ibadan";
    			else if($provider=='04')
    				$product = "";
    			else if($provider=='05')
    				$product = "PHDC";
    			else if($provider=='06')
    				$product = "";
    			else if($provider=='07')
    				$product = "EEDC";
    		}
    		$result1 = mysqli_query($con,"SELECT * FROM users WHERE Username ='$username'");
    		$row = mysqli_fetch_assoc($result1);
    		$name = $row['Firstname']." ".$row['Lastname'];
    		$email=$row['Email'];
    		
    	}
    	else
    	{
    		$cost = 0.00;
	}
	}
	else if($sp == "VT")
	{
	    
	    $resulte = vtpass_api::verifyMerchant($provider,$meterno,$package);
		$obj = json_decode($resulte);
	  
        $customername = $obj->content->Customer_Name;
        $address = $obj->content->Address;
        
    	$result = mysqli_query($con,"SELECT * FROM apitransactions Where Request_Id ='$tid' AND cost='$cost' AND Status='TRANSACTION SUCCESSFUL'");
    	if(mysqli_num_rows($result) > 0)
    	{
    	
    		$row = mysqli_fetch_assoc($result);
    		$description = $row['Description'];
    		$receiptno = $row['Id'];
    		$username = $row['Username'];
    		$date = $row['Transaction_Date'];
    		$token = $row['Token'];
    		
    		if(substr_count($description,$meterno) > 0)
    		{
                if($provider=='eko-electric')
                    $product = "Eko";
                if($provider=='ikeja-electric')
                    $product = "Ikeja";
                if($provider=='portharcourt-electric')
                    $product = "PHDC";
                if($provider=='ibadan-electric')
                    $product = "Ibadan";
                if($provider=='kano-electric')
                    $product = "Kano";
                if($provider=='jos-electric')
                    $product = "Jos";
    		}
    		$result1 = mysqli_query($con,"SELECT * FROM users WHERE Username ='$username'");
    		$row = mysqli_fetch_assoc($result1);
    		$name = $row['Firstname']." ".$row['Lastname'];
    		$email=$row['Email'];
    	}else
    	{
    		$cost = 0.00;
	}
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
      
      <h1>Receipt Id: <?php echo $receiptno; ?></h1>
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
        <br/>
        <h3 class="text-primary">Generated Token: <?php echo $token; ?></h3>
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
            <td class="desc">
            	<p>Meter Number <?php echo $meterno; ?> </p> <p> Customer: <?php echo $customername; ?></p><br/>
            	<p>Address: <?php echo $address; ?>
            </td>
            <td class="unit">N<?php echo ($cost); ?>.00</td>
          </tr>
           <tr>
            <td class="service">Other</td>
            <td class="desc">Convenience Fee</td>
            <td class="unit">N0.00</td>
          </tr>
       
          <tr>
            <td colspan="2" class="grand total">TOTAL</td>
            <td class="grand total">N<?php echo ($cost); ?>.00</td>
          </tr>
        </tbody>
      </table>
      
    </main>
    <footer>
      Receipt was created on a computer and is valid without the signature and seal.
    </footer>
    
   
<script>

function myFunction() {
	
     window.location.href="<?php echo "https://www.daypayz.com/dashboard/lib/esub.php?td=$tid&mn=$meterno&p=$provider&ct=$cost&pk=$package&rn=$receiptno&sp=$sp";?>";
}
</script>
  </body>
</html>
