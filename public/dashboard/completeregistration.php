<?php
require_once('nav1.php');
session_start();
 $uname = $_SESSION["usr"];
 if($uname==''){header('location: index.php');exit;}
  echo $_SESSION["usr"];
?>
<main class="app-content">
<div class="app-title">
      <div class="row col-md-12">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Register Bank Details</h3>
            <div class="tile-body">
              <form method="post" action="authenticateRegPayment.php">
                <fieldset>
                   <?php echo @$_SESSION["msg"];$_SESSION["msg"]=''?>
                  <legend>Activate account</legend>
                  <div class="form-group">
                    <label class="control-label">Username</label>
                    <input class="form-control" type="text" name="sender" id="sender">
                  </div>
                  <div class="form-group">
                    <label class="control-label">Transaction Password</label>
                    <input class="form-control" type="password" id="tpsd" name="tpsd"  >
                  </div>
                  <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Activate Account</button>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
        <div class="clearix"></div>
      </div>
    </div>
</main>
      <?php
        include('footer.php');
      ?>