<?php
include("head.php");
require_once('Handlers/UpgradeHandler.php');
?>
<?php

if ($_SESSION["check"] == "") {
	header('location:reconfirm_payment.php');
	$_SESSION["check"] = "checked";
}

?>
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
				<h1>Dashboard</h1>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<label>Need Help or Enquiries? <a class="text-primary" href="support.php">Contact Support</a> or Call (09135496831, 08170988604)</label>
	</div>
</div>
<marquee>To check your data balance MTN -> *461*4#, ETISALAT -> *228#, GLO >> *127*0#, AIRTEL -> *140# ... Customer care line: 09135496831, 08170988604. 9AM to 6PM Monday to Saturday.. Thank you. </marquee>
<?php
if (true) {
	$d_result = mysqli_query($con, "SELECT * FROM d_message");
	if (mysqli_num_rows($d_result) > 0) {
		$d_message = mysqli_fetch_assoc($d_result);
		$body = $d_message['Message'];
		$status = $d_message['Status'];
	}
	if ($status == "ON") {
		echo '
	 <div class="col-sm-12">
                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                    <p>' . @$body . '</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>';
	}
}
echo @$_SESSION['dashboardnotice'];
@$_SESSION['dashboardnotice'] = '';
?>

<div class="content mt-3">
	<div class="col-lg-4 col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="stat-widget-one">
					<div class="stat-icon dib"><i class="text-warning border-warning">&#8358;</i></div>
					<div class="stat-content dib">
						<div class="sta`t-text">Total Comission Received</div>
						<div class="stat-digit"><?php echo $allowance; ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4 col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="stat-widget-one">
					<div class="stat-icon dib"><i class="text-info border-info">&#8358;</i></div>
					<div class="stat-content dib">
						<div class="sta t-text">Bonus Balance</div>
						<div class="stat-digit"><?php echo $bonus_balance; ?> <br />
							<small>
								<a class="text-primary" href="transfer_bonus.php">Transfer Bonus</a>
							</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-lg-4 col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="stat-widget-one">
					<div class="stat-icon dib"><i class="text-success border-success">&#8358;</i></div>
					<div class="stat-content dib">
						<div class="sta t-text">Wallet Balance</div>
						<div class="stat-digit"><?php echo $balance; ?> <br />
							<small>
								<a class="text-primary" href="fund_wallet.php">Fund Wallet</a>
							</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="">
					<p class="text-primary">Membership Status (<?php echo $_SESSION['package']; ?>)
					</p>
					<?php if (UpgradeHandler::$regFees[$_SESSION['package']] < 50000)
						echo '<a class="btn-primary btn" href="accountupgrade.php">Upgrade Account</a>';
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-12 well">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body" style="background-color:blue">
					<div class="">
						<p class="text-white">Matched Promo Points: <?php echo $matched_Bonus_Pv; ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card">
				<div class="card-body" style="background-color:orange">
					<div class="">
						<p class="text-white">Total Matched GPV: <?php echo $matched_Pv; ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card">
				<div class="card-body" style="background-color:#a63922;;">
					<div class="">
						<p class="text-white">GPV (Left): <?php echo $left_Pv - $matched_Pv; ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card">
				<div class="card-body" style="background-color:#a63922;;">
					<div class="">
						<p class="text-white">GPV (Right): <?php echo $right_Pv - $matched_Pv; ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="col-12 col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="clearfix"><a href="subscribe.php">
						<i class="fa fa-cogs bg-flat-color-5 p-3 font-2xl mr-3 float-left text-light"></i>
						<div class="h5 text-secondary mb-0 mt-1">DATA</div>
						<div class="text-muted  font-weight-bold font-xs small">Mtn,Airtel,Glo ...</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!--/.col-->
	<div class="col-12 col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="clearfix"><a href="recharge.php">
						<i class="fa fa-laptop bg-info p-3 font-2xl mr-3 float-left text-light"></i>
						<div class="h5 text-secondary mb-0 mt-1">Airtime</div>
						<div class="text-muted  font-weight-bold font-xs small">Mtn, Airtel,Glo ...</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!--/.col-->
	<div class="col-12 col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="clearfix"><a href="tvsub.php">
						<i class="fa fa-moon-o bg-warning p-3 font-2xl mr-3 float-left text-light"></i>
						<div class="h5 text-secondary mb-0 mt-1">TV SUB</div>
						<div class="text-muted font-weight-bold font-xs small">GOtv, DStv ...</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!--/.col-->

	<div class="col-12 col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="clearfix"><a href="sms.php">
						<i class="fa fa-envelope-o bg-success p-3 font-2xl mr-3 float-left text-light"></i>
						<div class="h5 text-secondary mb-0 mt-1">Bulk Sms</div>
						<div class="text-muted font-weight-bold font-xs small">Single or multiple numbers</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!--/.col-->
	<div class="col-12 col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="clearfix"><a href="edu.php">
						<i class="fa fa-key bg-info p-3 font-2xl mr-3 float-left text-light"></i>
						<div class="h5 text-secondary mb-0 mt-1">Education</div>
						<div class="text-muted font-weight-bold font-xs small">Result Checker, Reg ...</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<!--/.col-->
	<div class="col-12 col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="clearfix"><a href="utilitysub.php">
						<i class="fa fa-bell bg-danger p-3 font-2xl mr-3 float-left text-light"></i>
						<div class="h5 text-secondary mb-0 mt-1">UTILITY</div>
						<div class="text-muted font-weight-bold font-xs small">Electricity ...</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="clearfix"><a href="wallet_transfer.php">
						<i class="fa fa-exchange bg-success p-3 font-2xl mr-3 float-left text-light"></i>
						<div class="h5 text-secondary mb-0 mt-1">Transfer</div>
						<div class="text-muted font-weight-bold font-xs small">User to User</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="content mt-3">
		<div class="animated fadeIn">
			<div class="row">

				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<strong class="card-title">Transaction History
								<!--<u><a class="text-primary" href="thistory.php">View Transaction Status</a></u>-->
							</strong>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="bootstrap-data-table-export" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>S/N</th>
											<th>Date</th>
											<th>Amount</th>
											<th>Description</th>
											<th>Status</th>
											<th>Transaction Id</th>
											<th>Balance</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = 1;
										if ($username == "Hannah") {
											$transactions1 = mysqli_query($con, "SELECT * FROM transactionhistory WHERE Sender='$username' || Receiver='$username' ORDER BY TransactionDate DESC LIMIT 50");
										} else {
											$transactions1 = mysqli_query($con, "SELECT * FROM transactionhistory WHERE Sender='$username' || Receiver='$username' ORDER BY TransactionDate DESC LIMIT 50");
										}
										if ($transactions1 != false) {
											while ($row1 = mysqli_fetch_array($transactions1)) {
												echo '<tr>';
												echo "<td>" . $count++ . "</td>";
												echo "<td>" . $row1['TransactionDate'] . "</td>";
												echo "<td>N" . $row1['Amount'] . "</td>";
												echo "<td>" . $row1['Description'] . "</td>";
												if ($row1['Status'] == '0')
													echo "<td><span class='badge badge-primary p-1'>Reversed</span></td>";
												elseif ($row1['Status'] == 1)
													echo "<td><span class='badge badge-success p-1'>Successful</span></td>";
												elseif ($row1['Status'] == 2)
													echo "<td><span class='badge badge-info p-1'>Processing</span></td>";
												elseif ($row1['Status'] == 3)
													echo "<td><span class='badge badge-warning p-1'>Flagged</span></td>";
												else
													echo "<td><span class='badge'>Done</span></td>";
												echo "<td>" . $row1['Tran_Id'] . "</td>";
												echo "<td>" . $row1['final_bal'] . "</td>";
												echo '</tr>';
											}
										} else {
											echo "No Transaction made yet";
										}
										?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div><!-- .animated -->
	</div>
</div>
<?php
include("foot.php");
?>