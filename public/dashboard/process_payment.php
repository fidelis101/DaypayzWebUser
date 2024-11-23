<?php

  include('head.php');
  include("dbcon.php");
  require_once("Handlers/AccountHandler.php");
  $user = $_SESSION['usr'];
		$method = mysqli_real_escape_string($con,$_POST['method']);
		$amount = mysqli_real_escape_string($con,@$_POST['amount']);
		$r = mysqli_query($con,"SELECT * FROM users WHERE Username='$user'");
		$email = mysqli_fetch_array($r)['Email'];
		$phone = mysqli_fetch_array($r)['Phone'];
		$customername = mysqli_fetch_array($r)['Firstname']." ".mysqli_fetch_array($r)['Lastname'];
		if($method == 'card-fl') 
		{
		$fee = $amount * 0.015;
		$total = $amount + $fee;
		}
		$date = date("Y-m-d H-i-s");

		if($method == 'atm_card')
		{
			echo '<script>window.location.assign("./atm_card_funding.php");</script>';
		}

		if($method == 'card-pt')
		{
		$ref = generatePSRef();
			mysqli_query($con,"INSERT INTO card_payment(Username,Transaction,Amount,Ref,Status,Path,Date) VALUES('$user','fund_wallet','$amount','$ref','Pending','paystack','$date')");
		}
		else if($method == 'card-fl')
		{
		$ref = generateRaveRef();
			mysqli_query($con,"INSERT INTO card_payment(Username,Transaction,Amount,Ref,Status,Path,Date) VALUES('$user','fund_wallet','$amount','$ref','Pending','flutter','$date')");
		}
		else if($method == 'instant')
		{
			$accountinfo = mysqli_query($con,"SELECT * FROM virtual_accounts WHERE Username='$user'");
			if(mysqli_num_rows($accountinfo)>0)
			{
				$row = mysqli_fetch_assoc($accountinfo);
				// $accountName = $row['AccountName'];
				// $accountNumber = $row['AccountNumber'];
				// $bankName = $row['BankName'];
				$account = AccountHandler::GetReservedAccounts($user);
				$accountObj = json_decode($account);
				$accountName = $accountObj->responseBody->accountName;
				$accountNumber = $accountObj->responseBody->accountNumber;
				$bankName = $accountObj->responseBody->bankName;
			}else{
				AccountHandler::ReserveTransferAccount($username);
				// $accountinfo = mysqli_query($con,"SELECT * FROM virtual_accounts WHERE Username='$user'");
				// $row = mysqli_fetch_assoc($accountinfo);
				// $accountName = $row['AccountName'];
				// $accountNumber = $row['AccountNumber'];
				// $bankName = $row['BankName'];
				$account = AccountHandler::GetReservedAccounts($user);
				$accountObj = json_decode($account);
				$accountName = $accountObj->responseBody->accountName;
				$accountNumber = $accountObj->responseBody->accountNumber;
				$bankName = $accountObj->responseBody->bankName;
			}
		}
		else if($method == 'bank')
		{
		$invoice = generateInvoice();
			mysqli_query($con,"INSERT INTO bank_payment(Username,Transaction,Amount,Ref,Status,Path,Date) VALUES('$user','fund_wallet','$amount','$invoice','Pending','bank','$date')");
		}
		else if($method == 'mtransfer')
		{
			$ref = generateMRef();
		mysqli_query($con,"INSERT INTO card_payment(Username,Transaction,Amount,Ref,Status,Path,Date) VALUES('$user','fund_wallet','$amount','$ref','Pending','monnify','$date')");
		
		}
		else
		{
			die("Could not generate Invoice");
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
	function generateMRef()
	{
		include('dbcon.php');
	   $result1 = mysqli_query($con,"SELECT * FROM card_payment WHERE Id != '' ORDER BY Id DESC LIMIT 1");
	   list($res,$id) = explode("-",mysqli_fetch_assoc($result1)['Ref']);
		 
		$MIN_SESSION_ID = 10000;
		$MAX_SESSION_ID = 99999;
		 
	    $i = 1;
	    while($i > 0)
	    {
	        $id = $id + 1;
	        $ref = "rave-".$id;
	        $result = mysqli_query($con,"SELECT * FROM card_payment WHERE Ref = '$ref'");
	        $i = mysqli_num_rows($result);
	    }
		$randId = mt_rand($MIN_SESSION_ID, $MAX_SESSION_ID).$id;
		
		return $randId;             
	}
	function generateInvoice()
	{
	   include('dbcon.php');
	   $result1 = mysqli_query($con,"SELECT * FROM bank_payment WHERE Id != '' ORDER BY Id DESC LIMIT 1");
	   list($res,$id) = explode("#",mysqli_fetch_assoc($result1)['Ref']);
		  
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
	
	if($method == 'bank')
		{
		echo "<script>
			alert(`(Now,You can pay in less than 1000 naira to fund your wallet and there will be no 50 naira duty charge debit. From 1 naira to 999 naira)

		Hi Team, plz be informed that effect from Monday, 6th January due to duty charge of 50 naira by bank on every payment made to the company's account from 1000 naira and above,  payment from 1000 naira and above made by transfer or deposit to the company's account will attract debit of 50 naira duty charge. Currently there will be no duty charge for transactions above 5000 naira as we take the loss. Daypayz will continue to serve you better. Thanks`);
		</script>";
		}
?>

<main class="app-content">
<div class="app-title">
      <div class="row col-md-12">
                

                   <div class="col-lg-6">
		<?php
			
			if($method == "card-fl")
			{
				if($_POST['amount'] !== '' && $_POST['amount'] >= 100 )
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
					<button class="btn-primary" type="button" onClick="payWithRave()">Pay With Flutterwave</button>
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
									window.location.assign("https://daypayz.com/dashboard/confirm_payment.php?ref="+txref);
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
				else{
					echo $_SESSION['fundnotification'] = "<label style='color:red;'>Amount must not be less than N100</label>";
					header('location:fund_wallet.php');
				}
				}
				
				else if($method == "card-pt")
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
												<input disabled value="'.$fee.'" type="text" class="form-control cc-name valid" data-val="true" data-val-required="Please enter the name on card" autocomplete="cc-name" aria-required="true" aria-invalid="false" aria-describedby="cc-name-error">
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
						<script src="https://js.paystack.co/v1/inline.js"></script>
						<button class="btn-primary" type="button" onclick="payWithPaystack()"> Pay With Paystack </button>
					</form>

					<script>
					
					  function payWithPaystack(){
					  $ref = "'.$ref.'";
						var handler = PaystackPop.setup({
							key: "pk_live_975843852de6801c08ee434c75ba6ca6d38b945c",
						  email: "'.$email.'",
						  amount:'.($total*100).',
						  ref:$ref, 
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
							  window.location.assign("https://daypayintl.com/dashboard/confirm_payment.php?ref="+$ref);
						  },
						  onClose: function(){
							  alert("window closed");
						  }
						});
						handler.openIframe();
					  }
					</script>
				';
				
				
				
			}
			else if($method == "mtransfer")
			{
				echo '
				<script type="text/javascript" src="https://sandbox.sdk.monnify.com/plugin/monnify.js"></script>

				<div class="card">
							<div class="card-header">
								<strong class="card-title">Bank Details</strong>
							</div>
							<div class="card-body">
								<!-- Credit Card -->
								<div id="pay-invoice">
									<div class="card-body">

							<button class="btn btn-success" onclick="payWithMonnify()">Pay with Monnify</button>
									
							</div>
							</div>

						</div>
					</div> 	
				
				<script type="text/javascript">
					function payWithMonnify() {
						MonnifySDK.initialize({
							amount: "'.$amount.'",
							currency: "NGN",
							reference: "'.$ref.'",
							customerFullName: "'.$customername.'",
							customerEmail: "'.$email.'",
							customerMobileNumber: "'.$phone.'",
							apiKey: "MK_TEST_B2XKEUZ5F7",
							contractCode: "6443957651",
							paymentDescription: "Test Pay",
							isTestMode: true,
							metadata: {
									"name": "Damilare",
									"age": 45
							},
							incomeSplitConfig:  [
								{
									"subAccountCode": "MFY_SUB_345115338981",
									"feePercentage": 50,
									"splitAmount": '.($amount/2).',
									"feeBearer": true
								},
								{
									"subAccountCode": "MFY_SUB_253997237374",
									"feePercentage": 50,
									"splitAmount": '.($amount/2).',
									"feeBearer": true
								}
							],
							onComplete: function(response){
								//Implement what happens when transaction is completed.
								window.location.assign("http://localhost/daypayz/dashboard/confirm_payment.php?ref="+'.$ref.');
							},
							onClose: function(data){
								//Implement what should happen when the modal is closed here
								console.log(data);
							}
						});
					}
				</script>';
			}
			else if($method == "instant")
			{
				
				echo
				'
					<div class="card">
								<div class="card-header">
									<strong class="card-title">Bank/Mobile Transfer (Instant)</strong>
								</div>
								<div class="card-body">
									<p class="text-info">Hi Team, you can now credit your wallet instantly anytime by making deposit or transfer (USSD ,
									Internet Banking, etc) to this your dedicated account number. 
									You can save this account number and use it to fund your wallet instantly anytime without logging in. 
									Note, A stamp duty of 35 Naira applies to every payment made. </p>
									<div id="pay-invoice">
										<div class="card-body">
											<form action="" method="post">
												<div class="form-group">
													 <div class="col-md-12">
                        
                    							</div>
													
												</div>
												<div class="form-group has-success">
													<label for="cc-name" class="control-label mb-1">Account Name: '.$accountName.'</label>
												</div>
												<div class="form-group has-success">
													<label for="cc-name" class="control-label mb-1">Bank: '.$bankName.' </label>
												</div>
												<div class="form-group">
													<label for="cc-number" class="control-label mb-1">Account Number: '.$accountNumber.'</label>
												</div>
												
											</form>
										</div>
									</div>

								</div>
							</div> 
				';
			}
			else
			{
					
				echo
				'
					<div class="card">
								<div class="card-header">
									<strong class="card-title">Instant Funding</strong>
								</div>
								<div class="card-body">
									<p>Contact <a href="bank_fund.php" class="btn btn-primary">Robot </a> for quick funding. after making mobile transfer or bank deposit</p>
									<div id="pay-invoice">
										<div class="card-body">
											<form action="" method="post">
												<div class="form-group">
													 <div class="col-md-12">
                        
                    							</div>
													
												</div>
												<div class="form-group has-success">
													<label for="cc-name" class="control-label mb-1">Account Name: DayPayz Intl Ltd</label>
												</div>
												<div class="form-group has-success">
													<label for="cc-name" class="control-label mb-1">Bank: Fidelity Bank </label>
												</div>
												<div class="form-group">
													<label for="cc-number" class="control-label mb-1">Account Number: 4011481039</label>
												</div>
												
											</form>
										</div>
									</div>

								</div>
							</div> 
				';
			}

		
		?>
           </div>
      </div>
    </div>
</main>
      <?php
        include('foot.php');
      ?>