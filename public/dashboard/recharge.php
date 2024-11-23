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
							<li class="active">Recharge</li>
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
	 <?php echo @$_SESSION['rechargenotice']; ?>
	            <?php
					if(isset($_SESSION['rechargenotice']) && empty($_SESSION['rechargenotice'])==false)
					{
    						if(strpos(@$_SESSION['rechargenotice'],"Failed") == false)
    						{
    							echo '<div class="alert  alert-success alert-dismissible fade show" role="alert">
    								<span class="badge badge-pill badge-success">Success</span> '.$_SESSION['rechargenotice'].'
    								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
    								</button>
    							</div>';
    						}else if(strpos(@$_SESSION['rechargenotice'],"Failed") !== false)
    						{
    							echo '<div class="alert  alert-danger alert-dismissible fade show" role="alert">
    								<span class="badge badge-pill badge-danger">Failed</span> '.$_SESSION['rechargenotice'].'
    								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
    								</button>
    							</div>';
    						}else{
    							echo @$_SESSION['rechargenotice'];
    						}
						@$_SESSION['rechargenotice']=''; 
					}
				?>
                
            </div>
        <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="card">
                       
                            <div class="card-header">
                                <strong>Airtime</strong> <small> Recharge</small>
                            </div>
                            <div class="card-body card-block">
							
					<form onsubmit="btk()" method="post" name="form1" id="form1" action="recharge2.php">
								
                                <div class="form-group">
                                    <label class=" form-control-label">Network</label>
									<select data-placeholder="Select network..." class="standardSelect" name="network" id="network" tabindex="1" required>
										<option value="">Select Network</option>
										<option value="1">Airtel</option>
										<option value="2">Mtn</option>
										<option value="3">Glo</option>
										<option value="4">9Mobile</option>
									</select>
                                </div>
								<div class="form-group">
                                    <label class=" form-control-label">Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-naira">&#8358;</i></div>
                                        <input class="form-control" name="amount" min="50" max="10000" type="number" id="amount" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class=" form-control-label">Phone input</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                        <input class="form-control" name="mobilenumber" placeholder="eg: 08096575463" size="11" minlength="11" maxlength="11" type="text" id="mobilenumber" required>
                                    </div>
                                </div>
								
								<input type="hidden" id="action" name="action" value="airtime">
                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
                                        <span id="payment-button-amount">Recharge</span>
                                    </button>
                                </div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/popper.js/dist/umd/popper.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="vendors/chosen/chosen.jquery.min.js"></script>

<script>
    jQuery(document).ready(function() {
        jQuery(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "Oops, nothing found!",
            width: "100%"
        });
    });
</script>

</body>

</html>
