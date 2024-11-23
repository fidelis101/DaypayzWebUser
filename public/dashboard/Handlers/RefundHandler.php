<?php

class RefundHandler{
    public function refundCompanyDept($username,$addedAmount)
    {
        include('dbcon.php');

        $checkDebt = mysqli_query($con,"SELECT * FROM company_debtors WHERE Username='$username'");

        if(mysqli_num_rows($checkDebt) > 0)
        {
            $userDebt = mysqli_fetch_assoc($checkDebt);
            $amount = $userDebt['Amount'];

            //Check if amount greater than 0
            if($amount > 0)
            {
                //Check if added amount is greater than debt
                if($addedAmount < $amount)
                {
                    $userWallet = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$username'");
                    $balance = mysqli_fetch_assoc($userWallet)['Balance'];
                    $newBalance = $balance - $addedAmount;
                    mysqli_query($con,"UPDATE wallets SET Balance='$newBalance' WHERE Username='$username'");

                    //New amount
                    $newDebt = $amount - $addedAmount;
                    mysqli_query($con,"UPDATE company_debtors SET Amount='$newDebt' WHERE Username='$username'");

                    $date = date("Y-m-d H:i:s");
                    $description = "Debit: Pending Debit";
                    mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,init_bal,final_bal) 
					VALUES('$date','$addedAmount','$username','Company Debt',
					'$description','$balance','$newBalance')");

                }else
                {
                    $userWallet = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$username'");
                    $balance = mysqli_fetch_assoc($userWallet)['Balance'];
                    $newBalance = $balance - $amount;
                    mysqli_query($con,"UPDATE wallets SET Balance='$newBalance' WHERE Username='$username'");

                    //New amount
                    mysqli_query($con,"UPDATE company_debtors SET Amount=0 WHERE Username='$username'");

                    mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,init_bal,final_bal) 
					VALUES('$date','$amount','$username','Company Debt',
					'$description','$balance','$newBalance')");
                }

            }
        }
    }

    public function refundCompanyDeptFromBonus($username,$addedAmount)
    {
        include('dbcon.php');

        $checkDebt = mysqli_query($con,"SELECT * FROM company_debtors WHERE Username='$username'");

        if(mysqli_num_rows($checkDebt) > 0)
        {
            $userDebt = mysqli_fetch_assoc($checkDebt);
            $amount = $userDebt['Amount'];

            //Check if amount greater than 0
            if($amount > 0)
            {
                //Check if added amount is greater than debt
                if($addedAmount < $amount)
                {
                    $userWallet = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$username'");
                    $balance = mysqli_fetch_assoc($userWallet)['BonusBalance'];
                    $newBalance = $balance - $addedAmount;
                    mysqli_query($con,"UPDATE wallets SET BonusBalance='$newBalance' WHERE Username='$username'");

                    //New amount
                    $newDebt = $amount - $addedAmount;
                    mysqli_query($con,"UPDATE company_debtors SET Amount='$newDebt' WHERE Username='$username'");

                    $date = date("Y-m-d H:i:s");
                    $description = "Debit: From Bonus Pending Debit";
                    mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,init_bal,final_bal) 
					VALUES('$date','$addedAmount','$username','Company Debt',
					'$description','$balance','$newBalance')");

                }else
                {
                    $userWallet = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$username'");
                    $balance = mysqli_fetch_assoc($userWallet)['BonusBalance'];
                    $newBalance = $balance - $amount;
                    mysqli_query($con,"UPDATE wallets SET BonusBalance='$newBalance' WHERE Username='$username'");

                    //New amount
                    mysqli_query($con,"UPDATE company_debtors SET Amount=0 WHERE Username='$username'");

                    $date = date("Y-m-d H:i:s");
                    $description = "Debit: From Bonus Pending Debit";
                    mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,init_bal,final_bal) 
					VALUES('$date','$amount','$username','Company Debt',
					'$description','$balance','$newBalance')");
                }

            }
        }
    }
}