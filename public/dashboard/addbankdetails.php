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
							<li class="active">Bank Details</li>
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
                                <strong>Add</strong> <small>Bank Details</small>
                            </div>
                            <div class="card-body card-block">
							
              <form method="post" action="savebankdetail.php">
                <div class="form-group">
                  <label class="control-label">Surname</label>
                  <input class="form-control" type="text" id="fname" name="fname" >
                </div>
                <div class="form-group">
                  <label class="control-label">Othernames</label>
                  <input class="form-control" type="text" id="mname" name="mname"  >
                </div>
                <div class="form-group">
                  <label class="control-label">Account Number</label>
                  <input class="form-control" type="text" id="accountnumber" name="accountnumber" >
                </div>
                <div class="form-group">
                  <label class="control-label">Account Type</label>
                  <select type="text" id="accounttype" name="accounttype" >
                    <option value="Savings">Savings</option>
                    <option value="Current">Current</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="control-label">Bank Name</label>
                  <input class="form-control" type="text" id="bankname" name="bankname"  >
                </div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
	include("foot.php");
?>