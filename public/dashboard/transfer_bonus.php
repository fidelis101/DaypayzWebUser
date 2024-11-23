<?php
include("head.php");
require_once "./Handlers/TransactionHistoryHandler.php";

$bonus_bal = $bonus_balance;
if (isset($_POST['amount'])) {
    $amount = $_POST['amount'];

    $wallet_result = mysqli_query($con, "SELECT * FROM wallets WHERE Username='$username'");
    if (mysqli_num_rows($wallet_result) > 0) {
        $wallet = mysqli_fetch_assoc($wallet_result);
        $balance = $wallet['Balance'];
        $bonus = $wallet['BonusBalance'];
        $tran_id = uniqid();

        if ($bonus >= $amount && $amount > 0) {
            $newBalance = $balance + $amount;
            $newBonusBalance = $bonus - $amount;

            if (mysqli_query($con, "UPDATE wallets SET BonusBalance='$newBonusBalance' WHERE Username='$username'")) {
                mysqli_query($con, "UPDATE wallets SET Balance='$newBalance' WHERE Username='$username'");
                $notification = "<label class='text-success'> Transfer Successful </label>";

                TransactionHistoryHandler::saveTranHistoryTran(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,
            'Sender'=>$username,'Description'=>'Debit: Bonus transfer wallet','Request_Id'=>$tran_id,'Tran_Id'=>$tran_id,'Status'=>1,
            'Transaction'=>'BonusTransfer','init_bal'=>$balance,'final_bal'=>$newBalance,'Provider'=>"Daypayz_Bonus"));
            }
        } else {
            $notification = "<label class='text-danger'> Transfer Failed, Insufficient Bonus Balance </label>";
        }
    } else {
        $notification = "<label class='text-danger'> Wallet not found </label>";
    }
}
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
        function btk() {
            document.getElementById("transfer-button").innerHTML = '<span id="payment-button-amount">Processing...</span>';
            document.getElementById("transfer-button").disabled = true;
            return true;
        }
    </script>

    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-xs-6 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <strong>Data</strong> <small> Subscription</small>
                        </div>
                        <div class="card-body card-block">
                            <div class="row">
                                <?php
                                if (isset($notification)) {
                                    echo $notification;
                                    $notification = "";
                                }
                                ?>
                            </div>
                            <form onsubmit="btk()" method="post" name="form1" id="form1" action="">

                                <div class="form-group">
                                    <label class="form-control-label">Bonus Balance</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                        <input value="<?php echo $bonus_bal;?>" class="form-control" size="11" required disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class=" form-control-label">Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-money"></i></div>
                                        <input class="form-control" size="11" required placeholder="eg: 20000.00" name="amount">
                                    </div>
                                </div>
                                <div>
                                    <button id="transfer-button" name="transfer_bonus" type="submit" class="btn btn-lg btn-primary btn-block">
                                        <span>Transfer to Wallet</span>
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