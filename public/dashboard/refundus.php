<?php
include('dbcon.php');


$result = mysqli_query($con,"SELECT SUM(Amount), Receiver
FROM transactionhistory
WHERE `Sender` LIKE 'Auto_Refund' AND Description LIKE '%Data%' AND TransactionDate BETWEEN '2021-05-10 20:18:29' AND '2021-05-11 20:18:29'
GROUP BY Receiver
ORDER BY SUM(Amount) DESC") or die(mysqli_error($con));

while($row = mysqli_fetch_array($result))
{
    $user = $row['Receiver'];
    $sum = $row['SUM(Amount)']; 

    $takenresult = mysqli_query($con,"SELECT * FROM takenback WHERE Username='$user'");

    if(mysqli_num_rows($takenresult) <= 0)
    {
        $userwallet = mysqli_query($con,"SELECT * FROM wallets WHERE Username='$user'") or die(mysqli_error($con));
        $balance = mysqli_fetch_assoc($userwallet)['Balance'];
        $newBal = $balance - $sum;
    
        mysqli_query($con,"UPDATE wallets SET Balance='$newBal' WHERE Username='$user'") or die(mysqli_error($con)."Line 20");
    
        mysqli_query($con,"INSERT INTO takenback(Username,Amount,Done,Initial,Final) VALUES ('$user','$sum',1,'$balance','$newBal')") or die(mysqli_error($con)."Line 22");
    
        $date = date("Y-m-d H-i-s");
        mysqli_query($con,"INSERT INTO transactionhistory (TransactionDate,Amount,Sender,Receiver,Description,Request_Id,Tran_Id,Status,Transaction,init_bal,final_bal,Provider) 
                        VALUES('$date','$sum','$user','daypayz_refunded','Debit: All Successful Transactions',
                        '','','1','Refund','$balance','$newBal','daypayz')") or die(mysqli_error($con));
    }
}

echo "Done";