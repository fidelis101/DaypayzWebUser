<?php
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
							<li class="active">Withraw</li>
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
                                <strong>Cash</strong> <small> Withdrawal</small>
                            </div>
                            <div class="card-body card-block">
							<?php echo @$_SESSION['withdrawnotification'];@$_SESSION['withdrawnotification']=''; ?>
							<form method="post" name="form1" id="form1" action="submitwithdrawal.php">
								<div class="form-group">
                                    <label for="cc-number" class="control-label mb-1">Withdraw from</label>
                                    <select disabled data-placeholder="Choose a Country..." class="standardSelect" tabindex="1">
										<option value="ewallet">E-Wallet</option>
									</select>
                                </div>
                                <label class="text-primary">Pls note that you will be charged N100 for this transaction</label>
                                <div class="form-group">
                                    <label class=" form-control-label">Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-naira">&#8358;</i></div>
                                        <input class="form-control" name="amount" id="amount">
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class=" form-control-label">Transaction Password</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-naira">*</i></div>
                                        <input type="password" class="form-control" name="tpsd" id="tpsd">
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> Submit
                                    </button>
                                </div>
							</form>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Pending Withdrawals</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Request Date</th>
                                            <th>Amount</th>
                                            <th>Account_Number</th>
											<th>Bank</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$transac = mysqli_query($con,"SELECT * FROM pendingwithdrawals WHERE Username='".$_SESSION["usr"]."'");
										$count = 1;
										if($transac != false){
											while($row1=mysqli_fetch_array($transac))
											 {
													echo '<tr>';
													echo "<td>".$count++."</td>";
													 echo "<td>".$row1['Request_Date']."</td>";
													echo "<td>N".$row1['Amount']."</td>";
													echo "<td>".$row1['Account_Number']."</td>";
													echo "<td>".$row1['Bank']."</td>";
													echo '</tr>';
											 }
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
        </div><!-- .content -->
<?php
	include("foot.php");
?>