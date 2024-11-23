<?php

include("activationFees.php");
session_start();
 $data = $_SESSION["data"];
 $uname = $data['User']['Username'];
 if($_SESSION["data"]=="")
    {
        header('location: register.php');
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
                <a class="navbar-brand" href="./"><?php echo $data['User']['Username']; ?></a>
            </div>

        </nav>
    </aside>


</script>
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
                        <div class="card">
                            <div class="card-header">
                                <strong>Account</strong> <small>Activation</small>
                            </div>
                            <div class="card-body card-block">
							
					<form method="post" name="form1" id="form1" action="process_activation.php">
								<div class="form-group">
								<label>I made card payment for activation of <?php echo $uname; ?> <a href="reconfirm_payment.php">Click here </a></label><br/><br/>
                                    <label for="cc-number" class="control-label mb-1">Payment Method</label>
                                    <select data-placeholder="Choose a Country..." name="paymentmethod" class="standardSelect" tabindex="1">
                                    						<option value="">Select Pyment Method</option>
										<option selected value="ewallet">E-Wallet</option>
										<option value="card">Atm card </option>
									</select>
                                </div>
								<div class="form-group">
                                    <label class=" form-control-label">Package to Activate</label>
                                    <div class="input-group">
                                        <input disabled value="<?php echo @$_SESSION['data']['Network']['Package'];?>" class="form-control" name="amount" id="amount">
                                    </div>
                                </div>
                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
                                        <span id="payment-button-amount">Pay</span>
                                    </button>
                                    
                                </div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
	include("foot.php");
?>