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
							<li class="active">Cable Subscription</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
	<script>
        	function btk()
        	{
        		document.getElementById("payment-button").innerHTML = '<span id="payment-button-amount">Processing...</span>';
        		document.getElementById("payment-button").disabled=true;
        		return true;
        	}
        	
        </script>
		<div class="col-sm-12">
                <?php
					if(@$_SESSION['cablenotice'] !='')
					{
						if(strpos(@$_SESSION['cablenotice'],"Failed") == false)
						{
							echo '<div class="alert  alert-success alert-dismissible fade show" role="alert">
								<span class="badge badge-pill badge-success">Success</span> Cable Subscription successful.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';
						}else if(strpos(@$_SESSION['cablenotice'],"Failed") !== false)
						{
							echo '<div class="alert  alert-danger alert-dismissible fade show" role="alert">
								<span class="badge badge-pill badge-danger">Failed</span> Cable Subscription failed.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';
						}else{
							echo @$_SESSION['cablenotice'];
						}
					}
				?>
                
        </div>
		<script src="js/tvsub.js"></script>
        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">
					<div class="col-md-12">
						<label>
						If your view didn't come up instantly contact DSTV/GOTV customer care with this line 
						<a class="text-primary" href="tel:+2348039044688">08039044688</a> to send fresh signal to the decoder
						</label>
					</div>
                    <div class="col-xs-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Data</strong> <small> Subscription</small>
                            </div>
                            <div class="card-body card-block">
							<h5><?php echo @$_SESSION['cablenotice']; $_SESSION['cablenotice']='' ?></h5>
							<form onsubmit="btk()" method="post" name="form1" id="form1" action="subscribetv.php">
                                <div class="form-group">
                                    <label class=" form-control-label">Cable</label>
									<select class="standardSelect" id="cable" name="cable" onchange="loadpackage(this.value)" required>
										<option value="" selected>Select Cable</option>
										<option value="01">DStv</option>
										<option value="02">GOtv</option>
										<option value="03">Startimes</option>
									</select>
                                </div>
								<div class="form-group">
                                    <label class="form-control-label">Package</label>
									<select  name="amount" id="amount" onchange="packageselected(this.value)" required>
										<option>Select Cable First</option>
									</select>
								</div>
								<div id="topup_box" class="form-group" style="display:none">
								<label class=" form-control-label">Amount to Top Up (<span class="text-primary">Please kindly note that Top Ups Attract a N50 Processing fee</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-cash"></i></div>
                                        <input class="form-control" name="top_amount" type="number" min="10" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="">Smartcard Number</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-number"></i></div>
                                        <input class="form-control" id="smartcardno" name="smartcardno" onkeyup="validate(this.value)" required>
                                    </div>
									<label id="result"></label>
                                </div>
                                
                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
                                        <span id="payment-button-amount">Subscribe</span>
                                    </button>
                                </div>
								
								<input type="text" hidden  id="cname" name="cname">
								<input type="text" hidden id="cnum" name="cnum">
								<input type="text" hidden id="ivp" name="ivp">
								<input type="text" hidden id="action" value="tvsub" name="action" >
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->

   <?php
	include("foot.php");
?>
