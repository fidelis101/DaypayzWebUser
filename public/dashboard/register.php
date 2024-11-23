<?php
session_start();
include('dbcon.php');
require_once('userdb.php');

$error = @$_SESSION['error'];
$_SESSION['error'] = ""

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
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
        max-width: 400px;
        min-height: 90vh;
    }

    form h3 {
        color: #ff8800;
    }

    .form-control {
        height: 40px !important;
        border-color: #ff8800;
    }

    /* .form-control:focus,
    .form-control:active {
        border-color: #a18f7b;
    } */

    .text-muted {
        color: rgb(201, 69, 69) !important;
    }

    .login-section a {
        font-size: 13px;
        font-weight: bold;
        color: #ff8800;
    }

    .password-link {
        color: rgb(196, 83, 83) !important;
    }

    .btn.btn-primary {
        height: 40px;
    }

    /* .btn.btn-primary a{
            color:#fff;
            
        } */
</style>

<body>
    <div class="container">
        <div class="col-md-4 mx-auto mt-5 p-3 login-section">
            <form method="post" action="Registration.php">
                <div class="py-4 text-center login-logo">
                    <a href="index.html">
                        <img class="align-content" src="img/logo1.jpg" alt="">
                    </a>
                </div>
                <p><?php echo $error;$error=""; ?></p>
                <div class="form-group">
                    <label>Referal ID<b style="color:red;">*</b></label>
                    <?php
                    if (isset($_REQUEST['userid'])) {
                        echo '<input id="ref_username"  disabled required value="' . $_REQUEST['userid'] . '" type="text" name="ref" class="form-control">';
                        echo '<input id="ref_username"  hidden value="' . $_REQUEST['userid'] . '" type="text" name="referal" class="form-control">';
                    } else
                        echo '<input id="ref_username"  required value="' . @$ref . '" type="text" name="referal" class="form-control">';
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
                        <input type="checkbox" name="terms" id="terms" value='yes' required> Agree to the <a href="../terms_conditions.html">terms and policy</a>
                    </label>
                </div>
                <button type="submit" id="register" class="btn btn-primary">Register</button>

                <div class="register-link m-t-15 text-center">
                    <p>Already have account ? <a href="login.php"> Sign in</a></p>
                </div>
            </form>
            <a href="../index.html"><u>Back To Home</u></a>
        </div>

    </div>

    <script src="js/jquery/dist/jquery.min.js"></script>
    <script src="js/popper.js/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        document.getElementById("#rdnotice").css({
            'color': 'red'
        });
    </script>
</body>

</html>