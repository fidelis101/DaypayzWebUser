<?php
include('dbcon.php');
include('get_content.php');

if (isset($_POST['login'])) {
  if (preg_match(
    "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",
    $_POST['email']
  )) {
    //regular expression for email validation
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $resulto1 = mysqli_query($con, "select * from  users where Email = '$email' and Username = '$username' ") or die('could not select from users' . mysqli_error($con));
    $num1 = mysqli_num_rows($resulto1);
    while ($row = mysqli_fetch_assoc($resulto1)) {
      $j = $row['Email'];
      $user = $row['Username'];
      $name = $row['Firstname'];
    }
    if ($num1 > 0) {
      require_once 'class.phpmailer.php';

      $mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch

      try {
        $tk = rand(400, 4000000);
        $link = "https://www.daypayz.com/dashboard/daypayzpassreset.php?user=$user&tk=$tk";
        $r = curl_get_contents("https://www.daypayz.com/dashboard/mail/mail_advanced.php?user=$user&tk=$tk&name=$name&email=$j");
        if ($r = 'Message Sent OK') {
          $insert = "INSERT INTO reset_pass(username,email,tk,date) VALUES('$username','$email',$tk,now())";
          if (mysqli_query($con, $insert) > 0) {;
            $d = "A password reset link has been sent to your mail</p>\n";
          } else {
            $d = 'Not Submitted';
          }
        } else {
          $d = $r;
        }
      } catch (phpmailerException $e) {
        $d = $e->errorMessage(); //Pretty error messages from PHPMailer
      } catch (Exception $e) {
        $d = $e->getMessage(); //Boring error messages from anything else!
      }
    } else {
      $d = 'A user with this email address does not exist';
    }
  } else {
    $d = 'Your Email Address is invalid';
  }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Forgot Password</title>
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
    min-height: 40vh;
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
    font-weight: bold;
    color: #ff8800;
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
    <div class="col-md-4 p-3 mx-auto login-section">
      <form class="formp" form name="form1" method="post" action="">
        <fieldset>
          <legend><strong>Enter Your Email Address and Username to reset password</strong></legend>
          <span class="style1"><?php echo @$a; ?></span> <span class="style2"><?php echo @$d; ?></span><br>
          <div class="form-group">
            <label for="username"><strong>Username:</strong></label>
            <input class="form-control" type="text" id="username" name="username">
          </div>
          <div class="form-group">
            <label for="username"><strong>Email Address:</strong></label>
            <input class="form-control" type="text" id="email" name="email">
          </div>
          <div class="c0l-md-12">
            <input class=" btn btn-primary mr-3" type="submit" name="login" id="login" value="Submit">
          </div>
        </fieldset>
        <div class="register-link m-t-15 text-center">
          <p>Try Sign in ? <a href="login.php"> Sign in</a></p>
        </div>
      </form>
      <a href="../index.html"><u>Back To Home</u></a>
    </div>

  </div>
</body>

</html>