<?php
    session_start();
    include('dbcon.php');
    require_once('userdb.php');
	$lognotice = @$_SESSION['lognotice'];
    
    if(isset($_SESSION["usr"]))
    {
        header('location:index.php');
    }
    else
    {
        session_unset();
        session_destroy();
    }  
?>

<!doctype html>
<html class="no-js" lang="en">


<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Daypayz Dashboard</title>
    <meta name="description" content="Daypayz Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">


    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
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
                    <a href="index.html">
                        <img class="align-content" src="img/logo1.jpg" alt="">
                    </a>
                </div>
                <div class="login-form">
                    <?php echo $lognotice ?>
                    <form method="post" action="authenticatelogin.php" autocomplete="on">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" required placeholder="Username" id="username2" name="username2">
                        </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input required type="password" class="form-control" placeholder="Password" id="password" name="password">
                        </div>
                                <div class="checkbox">
                                    
                                    <label class="pull-right">
                                <a href="forgot.php">Forgot Password?</a>
                            </label>

                                </div>
                                <button type="submit" style="background-color:#17A2B8;color:white;" class="btn btn-flat m-b-30 m-t-30">Sign in</button>
                                
                                <div class="register-link m-t-15 text-center">
                                    <p>Don't have account ? <a href="register.php"> Sign Up Here</a></p>
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


</body>

</html>
