<?php
session_start();
    include('dbcon.php');
    require_once ('walletdb.php');
    require_once ('networkdb.php');
    require_once ('userdb.php');
    require_once ('bankdetailsdb.php');
    require_once ('transactionhistorydb.php');
    
    date_default_timezone_set("Africa/Lagos");
    if(!(isset($_SESSION["usr"])))
    {
        header('location: login.php');
    }
    else
    {
      if($_SESSION["check"]=="")
    {
        header('location:reconfirm_payment.php');
        $_SESSION["check"]="checked";
        
    }
        $tnewtime = strtotime("-5 day");
	    $ttime = date("Y-m-d H:i:s",$tnewtime);
        $username = @$_SESSION["usr"];
        $lastlogin = @$_SESSION["lastlogin"];
    
        //Setup wallet 
        
        
      $wallet = getWallet($username);
      $transactions = getUserTransactions($username);
      $transactions1 = getUserTransactions($username);
      $downline = getUserDownline($username);
      $userss =mysqli_query($con,"select * from users where Username = '$username'") or die ('could not select from users'.mysqli_error($con)); 
      $pinne1 = mysqli_num_rows($userss);
      while ($row = mysqli_fetch_assoc($userss))
      {
        $id = $row['Id'];
        $regdate = $row['RegistrationDate'];
      }
      if(getUserNetwork($username) != false){ $usernet = mysqli_fetch_array(getUserNetwork($username));}
      $_SESSION['stage']=$usernet['Stage'];
      $_SESSION['package'] = $usernet['Package']; 
       if(getUserBankDetails($username) != false){ $useraccount = mysqli_fetch_array(getUserBankDetails($username));}
    
      if($usernet['Stage'] <=0 )
      {
          header('location: activate_account.php');
          exit;
      }
	  else if($usernet['Stage'] <=0 &&  !(getUserBankDetails($username)))
      {
          header('location: addbankdetails.php');
          exit;
      }
      if(getUser($username) != false){ $userprofile = mysqli_fetch_array(getUser($username));}
    }

    $balance = $wallet['Balance'];
    $bonus_balance = $wallet['BonusBalance'];
    $right_Pv = $wallet['RightPv'];
    $left_Pv = $wallet['LeftPv'];
    $matched_Pv = $wallet['MatchedPv'];
    $matched_Bonus_Pv = $wallet['MatchedBonusPoint'];

    if($balance >= 500)
    {
        $withdrawable = (floor(($balance-500)/1000)*1000.00).'.00';
    }else
    {
        $withdrawable = '0.00';
    }
    $allowance = 0.00;
    $received = 0.00;
    
    if($transactions1){
      
    while($row1=mysqli_fetch_array($transactions1))
    {
         if(($row1['Sender']==='Stage Bonus' || strpos($row1['Description'],'Wallet transfer') === false )  && strpos($row1['Description'],'Debit') === false &&strpos($row1['Description'],'Refund') === false &&strpos($row1['Description'],'Withdrawal') === false &&strpos($row1['Description'],'Credited by Daypayz') === false && $row1['Sender'] !='Card_Funding' )
         {
			 if($row1['Amount'] != '')
              $allowance+=$row1['Amount'];
         }
      if($row1['Receiver']==$username && (strpos($row1['Description'],'Wallet transfer') !== false || strpos($row1['Description'],'Credited by daypayz') !== false))
         {
              $received+=$row1['Amount'];
         }
     }
 }
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Daypay Dashboard</title>
    <meta name="description" content="Day pay international">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/flag-icon.min.css">
    <link rel="stylesheet" href="css/cs-skin-elastic.css">
    <link rel="stylesheet" href="css/jqvmap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="css/buttons.bootstrap4.min.css">

	<link rel="stylesheet" href="css/chosen.min.css">

	<link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/style.css">
    

    <script type="text/javascript"> //<![CDATA[ 
    var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.trust-provider.com/" : "http://www.trustlogo.com/");
    document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
    //]]>
    </script>
</head>

<body>

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><?php echo $_SESSION['usr']." (".$_SESSION['package'].")"; ?></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="index.php"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <li><a href="transactions.php"><i class="menu-icon fa fa-exchange"></i>Transaction history</a></li>
                    
                    <li><a href="profile.php"><i class="menu-icon fa fa-user"></i>Profile</a></li>
					<li><a href="network.php"><i class="menu-icon fa fa-network"></i>Network</a></li>
					<li><a href="bank_fund.php"><i class="menu-icon fa fa-google-notification"></i>Send Payment Notification</a></li>
					<li><a href="fund_wallet.php"><i class="menu-icon fa fa-google-wallet"></i>Fund Wallet</a></li>
					<li><a href="sms.php"><i class="menu-icon fa fa-envelope"></i>Send Sms</a></li>
					<li><a href="withdraw.php"><i class="menu-icon fa fa-money"></i>Withdraw</a></li>
					<li><a href="activate_cug.php"><i class="menu-icon fa fa-toggle-on"></i>Activate CUG</a></li>
                    <li><a href="support.php"><i class="menu-icon fa fa-support"></i>Support</a></li>
                    <li><a href="logout.php"><i class="menu-icon fa fa-sign-in"></i>Logout</a></li>
                 
                </ul>
            </div>
        </nav>
    </aside>
     <div id="right-panel" class="right-panel">
			<header id="header" class="header">

					<div class="header-menu">

						<div class="col-sm-7">
							<a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
							<div class="header-left">
								
								<div class="dropdown for-notification">
								<?php 
									$result = mysqli_query($con,"SELECT * FROM support where Username='$_SESSION[usr]' AND Status='Receiving';");
									
								?>
									<button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-bell"></i>
										<span class="count bg-danger"><?php echo mysqli_num_rows($result); ?></span>
									</button>
									<div class="dropdown-menu" aria-labelledby="notification">
										<p class="red"><a href="support.php">Create new message</a></p>
										<?php
										if(mysqli_num_rows($result) > 0)
										{
											while($row = mysqli_fetch_array($result))
											{
												echo '<a class="dropdown-item media bg-flat-color-1" href="support.php">
												<i class="fa fa-check"></i>
												<p>'.$row['Department'].' Department<small>  ('.$row['Header'].')</small></p>
												</a>';
											}
										}
										?>
									</div>
								</div>
							</div>
							<div style="display:inline;" class="heder-right">
								<a href="referal_link.php" class="text-primary">Refer and Earn(Referral Link)</a><br/>
								<a href="sponsored.php" class="text-success">View Direct Sponsored Members</a>
							</div>
						</div>
					</div>

				</header>
