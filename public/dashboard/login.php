<?php
session_start();
include('dbcon.php');
require_once('userdb.php');
$lognotice = @$_SESSION['lognotice'];

if (isset($_SESSION["usr"])) {
    header('location:index.php');
} else {
    session_unset();
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,700,800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="../css/animate.css">

    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/magnific-popup.css">

    <link rel="stylesheet" href="../css/flaticon.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<style>
    body {
        background-color: #000;
    }

    .login-section {
        background-color: #fff;
        min-height: 50vh;
        max-width: 400px;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translateX(-50%) translateY(-50%);
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    form h3 {
        color: #ff8800;
    }

    .form-control {
        height: 40px !important;
        border-color: #ff8800;
    }

    .form-control:focus,
    .form-control:active {
        border-color: #a18f7b;
    }

    .text-muted {
        color: rgb(201, 69, 69) !important;
    }

    .login-section a {
        font-size: 13px;
        color: #ff8800;
        font-weight: bold;
    }

    .password-link {
        color: rgb(201, 69, 69) !important;
    }

    .btn.btn-primary {
        height: 40px;
    }
</style>

<body>
    <div class="container">
        <div class="col-md-4 mx-auto login-section">
            <div class="login-content">
                <div class="py-4 text-center login-logo">
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
                        <button type="submit" class="btn btn-primary">Sign in</button>

                        <div class="register-link m-t-15 text-center">
                            <p>Don't have account ? <a href="register.php"> Sign Up Here</a></p>
                        </div>
                    </form>
                </div>
            </div>
            <a href="/"><u>Back To Home</u></a>
        </div>

    </div>
</body>

</html>