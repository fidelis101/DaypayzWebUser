<?php
include("head.php");
?>

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
							<li class="active">Transfer</li>
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
                            <strong>Transfer</strong> 
                        </div>
                        <div class="card-body card-block">
							<?php echo "<label style='color:green;'>".@$_SESSION['transfernotice']."</label>";@$_SESSION['transfernotice']=''; ?> 
								<form method="post" name="form1" id="form1" action="authenticateFundTransfer.php">	
									<div class="form-group">
										<label class=" form-control-label">Receiver Username:</label>
										<div class="input-group">
											<input class="form-control" id="rwallet" name="rwallet">
										</div>
									</div>
									<div class="">
										
											<button type="button" class="btn btn-primary " onclick="validatereceiver();">Validate</button>
										
									</div>
									<p id="receiver_availability_result"></p>
									<div id="transact_transfe">
									<div class="form-group">
										<label class=" form-control-label">Amount:</label>
										<div class="input-group">
											<input class="form-control" type="number" id="amount" name="amount">
										</div>
									</div>
									<div class="form-group">
										<label class=" form-control-label">Transaction Password:</label>
										<div class="input-group">
											<input class="form-control" id="tpsd" name="tpsd" type="password">
										</div>
									</div>
									<div>
										<button id="message" type="submit" class="btn btn-lg btn-primary btn-block">
											<span id="Send">Transfer</span>
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
require_once('foot.php');
?>