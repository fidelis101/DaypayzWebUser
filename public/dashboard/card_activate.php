<?php
session_start();
require 'Handlers/ActivationHandler.php';
	
 $data = $_SESSION["data"];
 $fee = ActivationHandler::$regFees[$data['Network']['Package']];
 
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
                                <strong>Activate</strong> <small>with Card</small>
                            </div>
                            <div class="card-body card-block">
							
			    <form method="post" action="process_cardactivate.php">
                            <div class="card-body card-block">
							<?php echo @$_SESSION['fundnotification'];@$_SESSION['fundnotification']=''; ?>
							<br/>
								<div class="form-group">
                                    <label for="payment_method" class="control-label mb-1">Payment Method</label>
                                    <select name="method" required data-placeholder="Select Payment Method..." class="standardSelect" tabindex="1">
                                    						<option value="">Pay with</option>
                                    						<option value="card-fl">Atm card (Flutterwave)</option>
                                    						<option value="card-pt" disabled>Atm card (Paystack)</option>
										
									</select>
                                </div>
								<div class="form-group">
                                    <label class=" form-control-label">Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-naira">&#8358;</i></div>
                                        <input class="form-control" name="amount" value="<?php echo $fee; ?>" disabled id="amount">
                                    </div>
                                </div>
                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
                                        <span id="payment-button-amount">Proceed</span>
                                    </button>
                                </div>
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