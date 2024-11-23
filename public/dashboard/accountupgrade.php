<?php
require_once('head.php');
require_once('Handlers/UpgradeHandler.php');
?>

<script>
    function accountselect(acc)
    {
        if(acc=="o-account")
        {
            document.getElementById("useraccount").style.display = "block"; 
        }
        if(acc=="self")
        {
            document.getElementById("useraccount").style.display = "none"; 
        }
    }
    
    function leaderselect(lead)
    {
        if(lead=="system")
        {
            document.getElementById("leaderd").style.display = "none"; 
        }
        if(lead=="self")
        {
            document.getElementById("leaderd").style.display = "block"; 
        }
    }
</script>
 <div id="right-panel" >
 <div class="content mt-12">
    <div class="animated fadeIn">
        <div class="card">
            <div class="card-header">
                <strong>Account</strong> <small> Upgrade</small>
            </div>
            <div class="card-body card-block">
                <p>Current Package : <?php echo $package = $_SESSION['package'] ?> </p>
                <div class="col-md-12 col-lg-12">
                <form method="post" name="form1" id="form1" action="authenticateUpgrade.php">	
                    <fieldset>
                    <legend>Account Upgrade</legend>
                    <?php echo @$_SESSION["upgrademsg"];@$_SESSION["upgrademsg"]=''?>
                    <div class="form-group">
                        <label class="form-label" for="lastname">Package Type:</label>
                        <select class="form-control" type="text" id="package" name="package" >
                            <option value="">Select package</option>
                            <?php
                                if(UpgradeHandler::$regFees[$package] < 2500)
                                echo '<option value="daypayzite">Daypayzite (N'.(2500 - UpgradeHandler::$regFees[$package]).')</option>';

                                if(UpgradeHandler::$regFees[$package] < 5500)
                                echo '<option value="builder">Builder (N'.(5500 - UpgradeHandler::$regFees[$package]).')</option>';

                                if(UpgradeHandler::$regFees[$package] < 10500)
                                echo '<option value="team_leader">Team Leader (N'.(10500 - UpgradeHandler::$regFees[$package]).')</option>';

                                if(UpgradeHandler::$regFees[$package] < 20500)
                                echo '<option value="manager">Manager (N'.(20500 - UpgradeHandler::$regFees[$package]).')</option>';

                                if(UpgradeHandler::$regFees[$package] < 50500)
                                echo '<option value="senior_manager">Senior Manager (N'.(50500 - UpgradeHandler::$regFees[$package]).')</option>';
                            ?>
                        </select>
                    </div>

                    <div class="form-group" id="useraccount" style="display:none;">
                        <label class="form-label" for="username">Username(Account to upgrade)</label>
                        <input class="form-control" type="text" name="username" id="username" onblur="checkUser()">
                    </div>

                    </fielset>
                    <fielset>
                        <?php echo @$_SESSION["msg"];@$_SESSION["msg"]=''?>
                        <legend>Pay Using E-wallet</legend>
                        <div class="form-group">
                            <label class="form-label" for="transactionpas">Transaction Password:</label>
                            <input class="form-control" type="password" id="tpsd" name="tpsd">
                        </div>
                    </fieldset>
                    <button class="btn btn-primary" type="submit">Upgrade</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<?php
require_once('foot.php');
?>
