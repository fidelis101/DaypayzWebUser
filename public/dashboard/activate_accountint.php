<?php
session_start();
 $data = $_SESSION["data"];
 if($_SESSION["data"]=="")
    {
        header('location: adduser.php');
    }
?>
<?php
    session_start();
    include('dbcon.php');
    require_once('userdb.php');
    $error = @$_SESSION['error'];
    include("head.php");
     
?>
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
							<li class="active" > Add User</li>
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
                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>Account</strong> <small>Activation</small>
                            </div>
                            <div class="card-body card-block">
							
					<form method="post" name="form1" id="form1" action="process_activationint.php">
								<div class="form-group">
                                    <label for="cc-number" class="control-label mb-1">Payment Method</label>
                                    <select data-placeholder="Choose a Country..." name="paymentmethod" class="standardSelect" tabindex="1">
										<option selected value="ewallet">E-Wallet</option>
										<option disabled value="card">Atm card </option>
									</select>
                                </div>
								<div class="form-group">
                                    <label class=" form-control-label">Activation fee</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-naira">&#8358;</i></div>
                                        <input disabled value="2500" class="form-control" name="amount" id="amount">
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
