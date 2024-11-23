<?php
session_start();
require 'Handlers/ActivationHandler.php';
$host_url = $_SERVER['SERVER_NAME'];
  
  include("dbcon.php");
  $data = $_SESSION['data'];
  $user = $data['User']['Username'];
  $email = $_SESSION['data']['User']['Email'];
  $phone = $_SESSION['data']['User']['Phone'];
   $amount = ActivationHandler::$regFees[$data['Network']['Package']];
		
		$r = mysqli_query($con,"SELECT * FROM users WHERE Username='$user'");
		
		$fee = $amount * 0.015;
		$total = $amount + $fee;
		$date = date("Y-m-d H-i-s");
		if($user != '')
		{
		$ref = generateRaveRef();
			mysqli_query($con,"INSERT INTO card_payment(Username,Transaction,Amount,Ref,Status,Path,Date) VALUES('$user','activation','$amount','$ref','Pending','flutter','$date')");
		}else
		{
			header("location:register.php");
		}
		
	
	function generateRaveRef()
	{
	   include('dbcon.php');
	   $result1 = mysqli_query($con,"SELECT * FROM card_payment WHERE Id != '' ORDER BY Id DESC LIMIT 1");
	   list($res,$id) = explode("-",mysqli_fetch_assoc($result1)['Ref']);
		  
	    $i = 1;
	    while($i > 0)
	    {
	        $id = $id + 1;
	        $ref = "rave-".$id;
	        $result = mysqli_query($con,"SELECT * FROM card_payment WHERE Ref = '$ref'");
	        $i = mysqli_num_rows($result);
	    }
	    return "rave-".$id;        		        
	}
	function generateInvoice()
	{
	   include('dbcon.php');
	  
	   $id = 100001;
	  
       $i = 1;
       while($i > 0)
       {
           $id = $id + 1;
           $ref = "#".$id;
           $result = mysqli_query($con,"SELECT * FROM bank_payment WHERE Ref = '$ref'");
             $i = mysqli_num_rows($result);
        }
        return "#".$id;        		        
	}
	
	function generatePSRef()
	{
	   include('dbcon.php');
	   $ref = "7PVGX8MEk85tgeE";
	   $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 ); // 
       $i = 1;
       while($i > 0)
       {
           
	   shuffle($seed); // probably optional since array_is randomized; this may be redundant
	   $rand = '';
	   foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];
	   $refx = $ref.$rand;
           $result = mysqli_query($con,"SELECT * FROM card_payment WHERE Ref = '$refx'");
             $i = mysqli_num_rows($result);
        }
        return $ref.$rand;        		        
	}
	
?>
<html class="no-js" lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Daypay Dashboard</title>
    <meta name="description" content="Day pay international">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/flag-icon.min.css">
    <link rel="stylesheet" href="css/cs-skin-elastic.css">
    <link rel="stylesheet" href="css/jqvmap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/buttons.bootstrap4.min.css">

	<link rel="stylesheet" href="css/chosen.min.css">

    <link rel="stylesheet" href="css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body>

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><?php echo $user; ?></a>
            </div>

        </nav>
    </aside>


    <div id="right-panel" class="right-panel">
	<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="index.php">Dashboard</a></li>
							<li class="active">Activation</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

	
        <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">
                    <div class="col-xs-6 col-sm-6">
		<?php
			if($amount !== '' && $amount >= 2500)
			{
			if(true)
			{
				
				echo
					'
						<div class="card">
							<div class="card-header">
								<strong class="card-title">Bank Details</strong>
							</div>
							<div class="card-body">
								<!-- Credit Card -->
								<div id="pay-invoice">
									<div class="card-body">
										<form action="" method="post">
											<div class="form-group">
												<label for="cc-payment" class="control-label mb-1">Amount</label>
												<input disabled value='.$amount.' type="text" class="form-control" aria-required="true" aria-invalid="false">
											</div>
											<div class="form-group has-success">
												<label for="cc-name" class="control-label mb-1">Convenience Fee (Naira)</label>
												<input disabled value='.$fee.' type="text" class="form-control cc-name valid" data-val="true" data-val-required="Please enter the name on card" autocomplete="cc-name" aria-required="true" aria-invalid="false" aria-describedby="cc-name-error">
												<span class="help-block field-validation-valid" data-valmsg-for="cc-name" data-valmsg-replace="true"></span>
											</div>
											<div class="form-group">
												<label for="cc-number" class="control-label mb-1">Total (Naira)</label>
												<input disabled value='.$total.' type="text" class="form-control cc-number identified visa" value="" data-val="true" data-val-required="Please enter the card number" data-val-cc-number="Please enter a valid card number" autocomplete="cc-number">
												<span class="help-block" data-valmsg-for="cc-number" data-valmsg-replace="true"></span>
											</div>
											
										</form>
									</div>
								</div>
	
							</div>
						</div> 
					';
				echo 
				'
				<form>
					<script type="text/javascript" src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
					<button class="btn btn-primary" type="button" onClick="payWithRave()">Pay With Flutterwave</button>
				</form>

				<script>
					const API_publicKey = "FLWPUBK-2c3570822ca3ff468a7d891bb6920524-X";

					function payWithRave() {
						var x = getpaidSetup({
							PBFPubKey: "FLWPUBK-2c3570822ca3ff468a7d891bb6920524-X",
							customer_email: "'.$email.'",
							amount: '.$total.',
							customer_phone: "'.$phone.'",
							currency: "NGN",
							payment_options: "card,account",
							txref: "'.$ref.'",
							meta: [{
								metaname: "flightID",
								metavalue: "AP1234"
							}],
							onclose: function() {},
							callback: function(response) {
								var txref = response.tx.txRef; // collect flwRef returned and pass to a 					server page to complete status check.
								console.log("This is the response returned after a charge", response);
								if (
									response.tx.chargeResponseCode == "00" ||
									response.tx.chargeResponseCode == "0"
								) {
									window.location.assign("https://'.$host_url.'/dashboard/confirm_payment.php?ref="+txref);
								} else {
									// redirect to a failure page.
								}

								x.close(); // use this to close the modal immediately after payment.
							}
						});
					}
				</script>
				';
				}
				
				
			}
		else
	{
		echo $_SESSION['fundnotification'] = "<label style='color:red;'>Amount must not be less than N100</label>";
		header('location:fund_wallet.php');
	
	}
		?>
         </div>
                </div>
            </div>
        </div>

<?php
	include("foot.php");
?>