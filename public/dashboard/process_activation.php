<?php
session_start();
require 'Handlers/ActivationHandler.php';
	if(@$_POST['paymentmethod'] !== '')
	{
		$method = @$_POST['paymentmethod'];
		$amount = $fee = ActivationHandler::$regFees[@$_SESSION['data']['Network']['Package']];
		$fee = $amount * 0.015;
		$total = $amount + $fee;
	}else
	{
		header('location:activate_account.php');
	}
?>
<?php
 $data = $_SESSION["data"];
 if($data==''){header('location: index.php');}
 if($method == 'card'){header("location:card_activate.php");}
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
                <a class="navbar-brand" href="./"><?php echo $data['User']['Username']; ?></a>
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
							<li class="active">Processing</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
	<?php
	if(false)
	echo '
	 <div class="col-sm-12">
                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                    <span class="badge badge-pill badge-success">Success</span> You successfully read this important alert message.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>';
	?>
    <div class="content mt-3">
		
        <div class="animated fadeIn">
            <div class="row">
				
				<div class="col-lg-6">
		<?php
			if($method == "card")
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
												<label for="cc-name" class="control-label mb-1">Convenience Fee</label>
												<input disabled value='.$fee.' type="text" class="form-control cc-name valid" data-val="true" data-val-required="Please enter the name on card" autocomplete="cc-name" aria-required="true" aria-invalid="false" aria-describedby="cc-name-error">
												<span class="help-block field-validation-valid" data-valmsg-for="cc-name" data-valmsg-replace="true"></span>
											</div>
											<div class="form-group">
												<label for="cc-number" class="control-label mb-1">Total</label>
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
					<script type="text/javascript" src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
					<button class="btn-primary" type="button" onClick="payWithRave()">Pay With Flutterwave</button>
				</form>

				<script>
					const API_publicKey = "FLWPUBK-1d34cbd7974806205a0705875bb91ccb-X";

					function payWithRave() {
						var x = getpaidSetup({
							PBFPubKey: "FLWPUBK-1d34cbd7974806205a0705875bb91ccb-X",
							customer_email: "ugwufidelis1@gmail.com",
							amount: '.$total.',
							customer_phone: "09065135368",
							currency: "NGN",
							payment_options: "card,account",
							txref: "rave-123487",
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
									window.location.assign("http://elinks.ektech.com.ng/user/verifyflutterpayment.php?txRef="+txref);
								} else {
									// redirect to a failure page.
								}

								x.close(); // use this to close the modal immediately after payment.
							}
						});
					}
				</script>
				';
				
				echo 
				'
					<form>
						<script src="https://js.paystack.co/v1/inline.js"></script>
						<button class="btn-primary" type="button" onclick="payWithPaystack()"> Pay With Paystack </button>
					</form>

					<script>
					$ref = "7PVGX8MEk85tgeEpVDtL";
					  function payWithPaystack(){
						var handler = PaystackPop.setup({
							key: "pk_test_10e4cf9a49b1ab06f1a3c2193a8af1d4089dcc2b",
						  email: "ugwufidelis1@gmail.com",
						  amount: '.$total.',
						  ref: $ref, // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
						  metadata: {
							 custom_fields: [
								{
									display_name: "Mobile Number",
									variable_name: "mobile_number",
									value: "+2348012345678"
								}
							 ]
						  },
						  callback: function(response){
							  window.location.assign("http://elinks.ektech.com.ng/user/verifypayment.php?ref="+$ref);
						  },
						  onClose: function(){
							  alert("window closed");
						  }
						});
						handler.openIframe();
					  }
					</script>
				';
			}else
			{
				echo
				'
					<div class="card">
								<div class="card-header">
									<strong class="card-title">Pay from E-wallet</strong>
								</div>
								<div class="card-body">
									<!-- Credit Card -->
									<div id="pay-invoice">
										<div class="card-body">
											<form action="activate.php" method="post">
												'.@$_SESSION["msg"].'
												<div class="form-group">
													<label for="cc-payment" class="control-label mb-1">Amount</label>
													<input disabled value=N'.$amount.' type="text" class="form-control" aria-required="true" aria-invalid="false">
												</div>
												<div class="form-group">
													<label for="cc-payment" class="control-label mb-1">Username</label>
													<input name="sender" type="text" class="form-control" aria-required="true" aria-invalid="false">
												</div>
												<div class="form-group">
													<label for="cc-payment" class="control-label mb-1">Transaction password</label>
													<input name="transactionpas" type="password" class="form-control" aria-required="true" aria-invalid="false">
												</div>
												 <div class="form-group">
													<button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
														<span id="payment-button-amount">Pay</span>
													</button>
												</div>
											</form>
										</div>
									</div>

								</div>
							</div> 
				';
				@$_SESSION["msg"] = '';
			}
		?>
		</div>
		</div>
		</div>
	</div>
<?php
	include("foot.php");
?>