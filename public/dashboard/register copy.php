<?php
    session_start();
    include('dbcon.php');
    require_once('userdb.php');
    
    $error = @$_SESSION['error'];
    $_SESSION['error'] = ""
     
?>
<html class="no-js" lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Daypayz Dashboard</title>
    <meta name="description" content="Daypayz Dashboard - Register">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">


    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/flag-icon.min.css">
    <link rel="stylesheet" href="css/cs-skin-elastic.css">

    <link rel="stylesheet" href="css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body class="bg-dark">

    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.php">
                        <img class="align-content" src="img/logo1.jpg" alt="">
                    </a>
                </div>
                <div class="login-form">
                    <p><?php echo $error;$error=""; ?></p>
                    <form method="post" action="Registration.php">
						<div class="form-group">
							<label>Referal ID<b style="color:red;">*</b></label>
							<?php 
							if(isset($_REQUEST['userid']))
							{
							echo '<input id="ref_username"  disabled required value="'.$_REQUEST['userid'].'" type="text" name="ref" class="form-control">';
							echo '<input id="ref_username"  hidden value="'.$_REQUEST['userid'].'" type="text" name="referal" class="form-control">';
							
							}
							else
							    echo '<input id="ref_username"  required value="'.@$ref.'" type="text" name="referal" class="form-control">';
							?>
						</div>
						<div class="form-group"> 
							<div class="ref_availability_result" id="ref_availability_result"></div>
						</div>
						<div class="form-group">
							<label>Placement </label>
							<select name="placement" class="form-control" required>
                                <option value="">Select Placement</option>
                                <option value="right">Right</option>
                                <option value="left">Left</option>
                            </select>
                        </div>
                        <div class="form-group">
							<label class="label">Package</label>
							<select class="form-control" name="package" required>
                                <option value="">Select Package</option>
                                <option value="daypayzite">Daypayzite (N2,500)</option>
                                <option value="builder">Builder (N5,500)</option>
                                <option value="team_leader">Team Leader (N10,500)</option>
                                <option value="manager">Manager (N20,500)</option>
                                <option value="senior_manager">Senior Manager (N50,500)</option>
                            </select>
						</div>
						<div class="form-group">
							<label>Username<b style="color:red;">*</b></label>
							<input required type="text" id="username" name="username" class="form-control">
						</div>
						<div class="form-group"> 
							<div class="username_availability_result" id="username_availability_result"></div>
						</div>
						<div class="form-group">
							<label>Firstname<b style="color:red;">*</b></label>
							<input required type="text" name="firstname" class="form-control">
						</div>
						<div class="form-group">
							<label>Lastname<b style="color:red;">*</b></label>
							<input required type="text" name="lastname" class="form-control">
						</div>
						<div class="form-group">
							<label>Phone<b style="color:red;">*</b></label>
							<input required type="phone" name="phone" class="form-control">
						</div>
						<div class="form-group">
							<label>Email address<b style="color:red;">*</b></label>
							<input required type="email" name="email" class="form-control" placeholder="Email">
						</div>
                        <div class="form-group">
                            <label>Password<b style="color:red;">*</b></label>
                            <input required type="password" name="passwordsignup" class="form-control" placeholder="Password">
						</div>
						<div class="form-group">
                            <label>Confirm Password<b style="color:red;">*</b></label>
                            <input required type="password" name="passwordsignup_confirm" class="form-control" placeholder="Password">
						</div>
                        <div class="checkbox">
							<label>
								<input type="checkbox" name="terms" id="terms" value='yes' required> Agree to the <a href="../terms_conditions.html" >terms and policy</a>
							</label>
                        </div>
                        <button type="submit" id="register" style="background-color:#17A2B8;color:white;" class="btn  btn-flat m-b-30 m-t-30">Register</button>
                            
                        <div class="register-link m-t-15 text-center">
                            <p>Already have account ? <a href="login.php"> Sign in</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="js/jquery/dist/jquery.min.js"></script>
    <script src="js/popper.js/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

    
	<script>

    document.getElementById("#rdnotice").css({'color':'red'});
    </script>

</body>

</html>
