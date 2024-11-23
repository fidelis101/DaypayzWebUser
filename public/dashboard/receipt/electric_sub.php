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
        if($provider == "01")
            $packageMap = ['01'=>'13','02'=>'14'];
        if($provider == "02")
            $packageMap = ['01'=>'11','02'=>'10'];
        if($provider == "03")
            $packageMap = ['01'=>'12','02'=>'23'];
        if($provider == "04")
            $packageMap = ['01'=>'24'];
        if($provider == "07")
            $packageMap = ['01'=>'21','02'=>'22'];
        
        $package1 = $packageMap[$package];
    	$url = "https://api.airvendng.net/secured/seamless/verify/";
  	 $username = "daypayz.com@gmail.com";
  	 $password = "@Ojobe52487";
  	 $api_key = "CPL741704E55A7EC87490635AC4DBC5606DA0B45500CEEC64CE37A5C930D8F3E359";
  	 $requestid = uniqid;
  	 
  	 $data = [
			"details"=>[
                "ref"=>$requestid,
                "account"=>$meterno,
                "type"=>$package1
            ]
		];
    	$requestdata = json_encode($data);

        $hashed = hash("sha512", $requestdata.$api_key);

		$header = array();
		$header[]= 'username: '.$username;
        $header[]= 'password: '.$password;
        $header[]= 'hash: '.$hashed;
		$header[]= 'Content-Type: application/json';


    	$res = CurlCon::curl_post_no_auth($url,$requestdata,$header);
        
		$result = $res['Result'];

        
    	$obj = json_decode($result);
        if($obj->confirmationMessage=="OK")
        {
            $customername = $obj->details->message->name;
            $address = $obj->details->message->address;
        }
        else
        	$customername = "Could not verify meter no";
    	
    	
    	$result = mysqli_query($con,"SELECT * FROM apitransactions Where Request_Id ='$tid' AND cost='$cost' AND Status='Transaction was successful'");
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
	else if($sp == "VTP")
	{
        if($provider=='01')
            $provider='eko-electric';
        else if($provider=='02')
            $provider='ikeja-electric';
        else if($provider =='03')
            $provider='ibadan-electric';
        else if($provider=='05')
            $provider='portharcourt-electric';
        else if($provider=='06')
            $provider='kano-electric';
        else if($provider=='08')
            $provider='jos-electric';
        else if($provider=='04')
            $provider='abuja-electric';
        else if($provider=='07')
            $provider='enugu-electric';
            
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
                if($provider=='abuja-electric')
                    $product = "Abuja";
                if($provider=='enugu-electric')
                    $product = "Enugu";
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
        <div> No. 14 Colliery Avenue GRA, Enugu. </div>
        <div>09029816945</div>
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
