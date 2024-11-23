<?php
  include('head.php');
?>
<main class="app-content">
<div class="app-title">
      <div class="row col-md-12">
                

                    <div class="col-xs-6 col-xs-offset-3 col-sm-6 col-sm-offset-3">
                        <div class="card">
                            <div class="card-header">
                                <strong>Fund</strong> <small> wallet</small>
                            </div>
                            
			             <form method="post" action="process_payment.php">
			    
                            <div class="card-body card-block">
                            
							<?php echo @$_SESSION['fundnotification'];@$_SESSION['fundnotification']=''; ?>
							<br/>
								<div class="form-group">
                                    <label for="payment_method" class="control-label mb-1">Payment Method</label>
                                    <select name="method" required data-placeholder="Select Payment Method..." class="standardSelect" tabindex="1">
                                    	<option value="">Select Payment Method</option>
										<option value="instant">Instant Funding (Wema Bank)</option>
                                        <option value="atm_card">Atm card (Flutterwave)</option>
										<option value="bank">Company's Account (Fidelity Bank)</option>
										
									</select>
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
   
</main>
      <?php
        include('foot.php');
      ?>
