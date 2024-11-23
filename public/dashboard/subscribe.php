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
							<li class="active">Data Subscription</li>
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
					if(isset($_SESSION['datanotice']) && empty($_SESSION['datanotice'])==false)
					{
    						if(strpos(@$_SESSION['datanotice'],"Failed") == false)
    						{
    							echo '<div class="alert  alert-success alert-dismissible fade show" role="alert">
    								<span class="badge badge-pill badge-success">Success</span> '.$_SESSION['datanotice'].'
    								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
    								</button>
    							</div>';
    						}else if(strpos(@$_SESSION['datanotice'],"Failed") !== false)
    						{
    							echo '<div class="alert  alert-danger alert-dismissible fade show" role="alert">
    								<span class="badge badge-pill badge-danger">Failed</span> '.$_SESSION['datanotice'].'
    								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    									<span aria-hidden="true">&times;</span>
    								</button>
    							</div>';
    						}else{
    							echo @$_SESSION['datanotice'];
    						}
						@$_SESSION['datanotice']=''; 
					}
				?>
                
            </div>
        <script src="js/datalist3.js"></script>
        <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">

                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>Data</strong> <small> Subscription</small>
                            </div>
                            <div class="card-body card-block">
							<form onsubmit="btk()" method="post" name="form1" id="form1" action="subscribedata.php">
							
                                <div class="form-group" id="df">
                                    <label class=" form-control-label">Network</label>
									<select required onchange="validateNetwork(this.value,10000)" class="standardSelect" name="network" id="network">
										<option value="" selected>Select Network</option>
										<option value="1">Airtel</option>
										<option disabled value="2">Mtn Corporate Gifting</option>
										<option value="5">Mtn SME Data</option>
										<option value="3">Glo</option>
										<option value="4">9Mobile</option>
									</select>
                                </div>
								<div class="form-group">
                                    <label class="form-control-label">Data Size</label>
									<select required name="amount" id="amount" required>
										<option>Select Network first</option>
									</select>
                                </div>
                                <div class="form-group">
                                    <label class=" form-control-label">Phone Number</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                        <input class="form-control" size="11" required placeholder="eg: 08093476524" name="number" minlength="11" maxlength="11" id="number">
                                    </div>
                                </div>
                                <div onclick="">
                                    <button  id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
                                        <span  id="payment-button-amount">Subscribe</span>
                                    </button>
                                </div>
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
