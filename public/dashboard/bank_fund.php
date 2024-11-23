<?php
include("head.php");
?>
<div class="content mt-3">
            <div class="animated fadeIn">
<p class="text-warning">Pls make sure you have made payments before submitting to Robot </p>
                <div class="row">
                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>Mobile/Bank</strong> <small> payment</small>
                            </div>
                            <div class="card-body card-block">
                                <div class="row">
                                    <label class="text-danger">
                                        Strictly one notification for one payment in less than 24hours if not funded, to avoid suspension of account.
                                    </label>
                                </div>
<?php echo $_SESSION['fundnotification'];$_SESSION['fundnotification']=''; ?>
	 <form method="post" name="form1" id="form1" action="submit_funding.php">  
    
     <div class="form-group">
        <label for="depositor">Depositors Name:</label>
        <input type="text" class="form-control" id="depositor" name="depositor" >
       </div>
      <div class="form-group">
        <label class="form-label" for="teller">Description(Text message received):</label>
        <input class="form-control" type="text" id="teller" name="teller" >
	</div>
	<div class="form-group">
        <label for="amount">Amount:</label>
        <input type="text" class="form-control" id="amount" name="amount" >
	</div>
	<div class="form-group">
        <label for="teller">Date of Payment:</label>
        <input size="16" type="date" id="date" name="dateofpayment" value="<?php echo date('d/m/Y'); ?>"class="form_datetime form-control">
        </div>
    <button class="btn btn-primary" type="submit">Submit</button>
  </form>
</div>
</div>
</div>
</div>
</div>
<?php
	include("foot.php");
?>
