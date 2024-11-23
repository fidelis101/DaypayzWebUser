<?php
	include("head.php");
?>
<script>
function btk()
        	{
        		document.getElementById("payment-button").innerHTML = '<span id="payment-button-amount">Processing...</span>';
        		document.getElementById("payment-button").disabled=true;
        		return true;
        	}
</script>
	<script src="js/esub1.js"></script>
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
							<li class="active">Electricity Subscription</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
	
		<div class="col-sm-12">
           
                
        </div>
        <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">

                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>Electricity</strong> <small> Subscription</small>
                            </div>
                            <div class="card-body card-block">
							<h5><?php echo @$_SESSION['enotice']; $_SESSION['enotice']='' ?></h5>
							<form method="post" onsubmit="btk()" name="form1" id="form1" action="subscribe_electricity.php">
							
                                <div class="form-group">
                                    <label class=" form-control-label">Distribution Company:</label>
									<select class="standardSelect" id="provider" name="provider" required>
										<option value="" selected>Select Company</option>
										<option value="01">Eko Electricity</option>
										<option value="02">Ikeja Electricity</option>
										<option value="03">Ibadan Electricity</option>
										<option value="04">Abuja Electricity</option>
										<option value="05">PH Electricity</option>
										<option value="06">Kano Electricity</option>
										<option value="07">EEDC</option>
										<option value="08">Jos Electricity</option>
										<option value="09">Benin Electricity</option>
									</select>
                                </div>
								<div class="form-group">
                                    <label class="form-control-label">Type</label>
									<select  name="package" id="package" required>
										<option value="" selected>Select Type</option>
										<option value="01">Prepaid</option>
										<option value="02">Postpaid</option>
									</select>
                                </div>
                                
								<div class="form-group">
                                    <label class="form-control-label">Amount</label>
									<div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-naira">&#8358;</i></div>
                                        <input class="form-control" placeholder="Note: N1000 minimum" type="text" id="amount" name="amount"  required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class=" form-control-label">Meter Number</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-number"></i></div>
  <input class="form-control" onkeyup="validate(this.value,document.getElementById('provider').value,document.getElementById('package').value)" onblur="validate(this.value,document.getElementById('provider').value,document.getElementById('package').value)" type="text" id="meterno" name="meterno"  required>
                                    </div>
                                </div>
                                <label id="result" ></label>
				<div class="form-group">
                                    <label class="form-control-label">Phone</label>
				     <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                        <input class="form-control" type="text" id="cnum" name="cnum"  required>
                                    </div>
                                </div>
                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
                                        <span id="payment-button-amount">Subscribe</span>
                                    </button>
                                </div>
                                
				<input type="text" hidden id="action" value="utility" name="action" >
				
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
