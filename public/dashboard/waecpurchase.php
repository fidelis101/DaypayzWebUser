<?php
include('dbcon.php');
include('transactionhistorydb.php');
include('systemdb.php');
include('get_content.php');
require_once 'class.phpmailer.php';
session_start();
$pins = $_POST['pins'];
$pintype = $_POST['pintype'];
$action = $_POST['action'];
$email = $_POST['email'];
$username = $_SESSION['usr'];

/*$_SESSION['rechargenotification1'] = "<label style='color:red'> Service is in progress check back soon, currently doing our best to serve you better. </label>";
			header('location:tvsub.php');
*/

if($action == "waecpurchase")
{
	$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
	if($result->num_rows > 0)
	{
		$row = mysqli_fetch_array($result);
		$balance = $row['Balance'];
	
		    $cost = $pins * 900;
			if($balance >= $cost)
			{
			    $newbalance = $balance - ($pins * 920);
				chargeCustomer($pins,$username,$newbalance);
				//payVtpassWaec($pins,$cost,$pintype,$username,$newbalance,$email,$balance);
				payWaec($pins,$cost,$username,$newbalance,$email,$balance);
			}
			else{
				$_SESSION['waecnotification'] = "<label style='color:red;'>insufficient Balance</label>";
					header('location:waecpin.php');
			}
	}
}

if($action == "necopurchase")
{
	$result = $con->query("SELECT * FROM wallets WHERE Username = '$username'");
	if($result->num_rows > 0)
	{
		$row = mysqli_fetch_array($result);
		$balance = $row['Balance'];
	
		    $cost = 700;
			if($balance >= $cost)
			{
			    $newbalance = $balance - 700;
				chargeCustomer($pins,$username,$newbalance);
				//payVtpassWaec($pins,$cost,$pintype,$username,$newbalance,$email,$balance);
				neco($cost,$username,$newbalance,$email,$balance);
			}
			else{
				$_SESSION['neconotification'] = "<label style='color:red;'>insufficient Balance</label>";
					header('location:necopin.php');
			}
	}
}


function payWaec($pins,$amount,$username,$newbalance,$email,$balance)
{
	include('dbcon.php');
	$mail = new PHPMailer(true);
	$result = curl_get_contents("https://api.airvendng.net/vas/waec/?username=daypayz.com@gmail.com&password=@Ojobe52487&pins=".$pins."&pinvalue=900&amount=".$amount);
    $obj = simplexml_load_string($result);
			
			 $description = "".$pins." Waec Pins ".$amount;
			$date = date("Y-m-d H-i-s");
			mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username','$obj->AVRef','$result','$description',($pins*900), '$date','AV')");
			
		
	
	if($obj->ResponseCode == "0" || $obj->ResponseMessage=="SUCCESS")
	{
			$_SESSION['waecnotification'] = "<label style='color:green;'>Transaction Successful</label>";
			
			$i = 0;
			while($i < $pins)
			{
			   
			$_SESSION['pins'] = $_SESSION['pins']."<label> Serial number:</label><label style='color:green;'>".$obj->vendData->pins->pin[$i]->serialNumber." </label></br>
			<label> Pincode:</label><label style='color:green;'>".$obj->vendData->pins->pin[$i]->pinCode." </label>
			</br></br>";
			   
			   $desc = $desc." Serial number: ".$obj->vendData->pins->pin[$i]->serialNumber." Pincode: ".$obj->vendData->pins->pin[$i]->pinCode;
			   $i++;
			}
		
			$cprofit = $pins * 20;
			$am = $pins*900;
		mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Waec','$am','$cprofit','$date')");
		
			$date = date("Y-m-d H-i-s");
		    mysqli_query($con,"INSERT INTO waecpins (Username,Pins,Description,TransactionDate,Email) VALUES('$username','$pins','$desc','$date','$email')");
			//company share
			
			$date1 = date("Y-m-d");
				
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($pins*920),'Receiver'=>'waec','Sender'=>$username,'Description'=> "Purchase of waec pin",'init_bal'=>$balance,'final_bal'=>$newbalance));
			
			try {
			  $mail->AddAddress($email, $username);
			  $mail->SetFrom('info@daypayz.com', 'Daypayz');
			  $mail->AddReplyTo('support@daypayz.com', 'Daypayz Support');
			  $mail->Subject = 'Waec Pin Purchase';
			  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
			  $mail->MsgHTML($_SESSION['pins']);
			  $mail->Send();
			  $_SESSION['pins'] = $_SESSION['pins']."Sent to mail";
			} catch (phpmailerException $e) {
			  $_SESSION['pins'] =$_SESSION['pins'].$e->errorMessage(); //Pretty error messages from PHPMailer
			} catch (Exception $e) {
			  $_SESSION['pins'] = $_SESSION['pins'].$e->getMessage(); //Boring error messages from anything else!
			}

		    header('location:waecpin.php');
		
	}
	else{
			
			$_SESSION['waecnotification'] = "<label style='color:red'> Transaction failed try again later</label>";
			mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($pins * 920),'Receiver'=>$username,
											'Sender'=>'waec','Description'=>'WAEC Pin purchase failed','init_bal'=>$newbalance,'final_bal'=>$balance));
		       
			header('location:waecpin.php');

		}
		
		
}


function chargeCustomer($pins,$username,$newbalance)
{
	include('dbcon.php');
	//$newbalance = $balance - ($pins * 750);
	mysqli_query($con,"UPDATE wallets SET Balance='$newbalance' WHERE Username='$username'");
}

 
    function neco($cost,$username,$newbalance,$email,$balance)
    {
      	include('dbcon.php');
      
	    $requestId = uniqid();
    	$result = curl_get_contents("https://mobileairtimeng.com/httpapi/neco?userid=08064535577&pass=33288cd17fbb56fd91722&jsn=json&user_ref=$requestId");
    	
    	$obj = json_decode($result);
    	
    	$description = $mobilenumber." Neco Result Checking PIN N".$cost;
		$date = date("Y-m-d H-i-s");
		$tran = $requestId."-".$obj->batchno;
		mysqli_query($con,"INSERT INTO apitransactions (Username,Transaction_Id,Status,Description,cost,Transaction_Date,Provider,Token) 
					VALUES('$username','$tran','$obj->pin','$description','$cost','$date','MA','$obj->pin')");
    	
    	if($obj->code == 100)
    	{
         
    	  $_SESSION['pins'] ="<label> Pin:</label><label style='color:green;'>".$obj->pin." </label>";
    	  
          mysqli_query($con,"INSERT INTO company_profit (Username,Transaction,Amount,Profit,Date) VALUES('$username','Neco','50','$cprofit','$date')");	
          
		  saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($cost),'Receiver'=>'neco','Sender'=>$username,'Description'=> "Purchase of neco pin $obj->pin",'init_bal'=>$balance,'final_bal'=>$newbalance));
		       
		       
		       try {
			  $mail->AddAddress($email, $username);
			  $mail->SetFrom('info@daypayz.com', 'Daypayz');
			  $mail->AddReplyTo('support@daypayz.com', 'Daypayz Support');
			  $mail->Subject = 'Neco Pin Purchase';
			  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
			  $mail->MsgHTML($_SESSION['pins']);
			  $mail->Send();
			  $_SESSION['pins'] = $_SESSION['pins']."Sent to mail";
			} catch (phpmailerException $e) {
			  $_SESSION['pins'] =$_SESSION['pins'].$e->errorMessage(); //Pretty error messages from PHPMailer
			} catch (Exception $e) {
			  $_SESSION['pins'] = $_SESSION['pins'].$e->getMessage(); //Boring error messages from anything else!
			}
			
			header('location:necopin.php');
      }else
       	$_SESSION['neconotification'] = "<label style='color:red'> Transaction failed try again later</label>";
			mysqli_query($con,"UPDATE wallets SET Balance='$balance' WHERE Username='$username'");
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($pins * 920),'Receiver'=>$username,
											'Sender'=>'waec','Description'=>'NECO Pin purchase failed','init_bal'=>$newbalance,'final_bal'=>$balance));
		       
			header('location:necopin.php');
    }
    
function payVtpassWaec($pins,$cost,$pintype,$username,$newbalance,$email,$balance)
{
	include('dbcon.php');
	
	$serviceid = array("01"=>"waec","02"=>"waec-registration");
	$variationid = array("01"=>"prepaid","02"=>"postpaid");
	
	$requestid = generateId();
	$serviceid=$providerid[$provider];
	$billercode = $meterno;
	$variation_code = $variationid[$package];
	
	$user = "ugwufidelis1@gmail.com"; //email address
    $password = "makeitcount4u"; //password
    $host = 'https://vtpass.com/api/payfix';
	
	$data = array(
		'serviceID'=> $serviceid, //integer e.g gotv,dstv,eko-electric,abuja-electric
		'billersCode'=> '', // e.g smartcardNumber, meterNumber,
		'variation_code'=> '',
		'amount' =>  $amount, // integer (optional for somes services)
		'phone' => $customernumber, //integer
		'request_id' => $requestid // unique for every transaction from your platform
	);
	$curl       = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => $host,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_USERPWD => $user.":" .$password,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $data,
	));
	$result = curl_exec($curl);
        
    $obj = json_decode($result);
       $_SESSION['utilitynotice'] =  $result;
    $description = "".$meterno." Electricity Subscription of ".$amount;
	$date = date("Y-m-d H-i-s");
	mysqli_query($con,"INSERT INTO apitransactions (Username,Request_Id,Transaction_Id,Status,Description,cost, Transaction_Date,Provider) VALUES('$username',$requestid,'$obj->transactionId','$obj->response_description','$description','$amount','$date','VTP')");
		
	if($obj->response_description == "TRANSACTION SUCCESSFUL")
	{
		$_SESSION['waecnotification'] = "<label style='color:green;'>Transaction Successful</label>";
			
			/*$i = 0;
			while($i < $pins)
			{
			   
			$_SESSION['pins'] = $_SESSION['pins']."<label> Serial number:</label><label style='color:green;'>".$obj->vendData->pins->pin[$i]->serialNumber." </label></br>
			<label> Pincode:</label><label style='color:green;'>".$obj->vendData->pins->pin[$i]->pinCode." </label>
			</br></br>";
			   
			   $desc = $desc." Serial number: ".$obj->vendData->pins->pin[$i]->serialNumber." Pincode: ".$obj->vendData->pins->pin[$i]->pinCode;
			   $i++;
			}
		*/
		
			$_SESSION['pins'] = $_SESSION['pins']."<label> Serial number:</label><label style='color:green;'>".$obj->purchased_code." </label></br>
			<label> Pincode:</label><label style='color:green;'>".$obj->purchased_code." </label>
			</br></br>";
			   
			   $desc = $desc." Serial number: ".$obj->purchased_code." Pincode: ".$obj->purchased_code;
			   
			$date = date("Y-m-d H-i-s");
		    mysqli_query($con,"INSERT INTO Waecpins (Username,Pins,Description,TransactionDate,Email) VALUES('$username','$pins','$desc','$date','$email')");
			//company share
			
			$date1 = date("Y-m-d");
				
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>($pins*580),'Receiver'=>'waec','Sender'=>$username,'Description'=> "Purchase of waec pin",'init_bal'=>$balance,'final_bal'=>$newbalance));
		       header('location:waecpin.php');
    }
    else{
       $_SESSION['waecnotification'] = "<label style='color:red'> Transaction failed try again later</label>";
			mysqli_query($con,"UPDATE Wallets SET Balance='$balance' WHERE Username='$username'");
			saveTransactionHistory(array('TransactionDate'=>date("Y-m-d H:i:s"),'Amount'=>$amount,'Receiver'=>$username,
											'Sender'=>'waec','Description'=>'WAEC Pin purchase failed','init_bal'=>$newbalance,'final_bal'=>$balance));
		       
			header('location:waecpin.php');
    }
}
function generateId()
{
   include('dbcon.php');
   $id = 100;
      $i = 1;
      while($i > 0)
      {
          $id = $id + 1;
          $result = mysqli_query($con,"SELECT * FROM apiTransactions WHERE Request_Id = '$id'");
            $i = mysqli_num_rows($result);
       }
       return $id;        		        
}

header('location:waecpin.php');
?>
