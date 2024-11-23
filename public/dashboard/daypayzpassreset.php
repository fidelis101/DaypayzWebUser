<?php
include('dbcon.php');
if(isset($_GET['user'])){
$users = $_GET['user'];
$tk = $_GET['tk'];

$resulto1 =mysqli_query($con,"select * from  reset_pass where Username = '$users' AND tk='$tk' ") or die ('could not select from reset_pass'.mysqli_error());
		$num1 = mysqli_num_rows($resulto1);
		while ($row = mysqli_fetch_assoc($resulto1))
		{
			$j = $row['email'];
			$user = $row['username'];
}
}

if(isset($_POST['reset'])){
if (empty($_POST['password'])) { //if no name has been supplied
	       $error = "<label style='color:red;'>First Password Missing</label>";
			
	    	} else {
	        $pass = $_POST['password']; //else assign it a variable
	        $options = ['cost' => 12];
            $password = password_hash($pass, PASSWORD_DEFAULT, $options);
	    	}
			if (empty($_POST['cpassword'])) { //if no name has been supplied
	        $error = "<label style='color:red;'>Confirm Password Missing</label>";
		
	    	} else {
	        $password2 = $_POST['cpassword']; //else assign it a variable
			
	    	}
			if (strlen($_POST['password']) < 6) 
			{ 
			 $error = "<label style='color:red;'>Your First Password must be greater than 6</label>";
			  
			} 
			if (strlen($_POST['cpassword']) < 6) 
			{ 
			 $error = "<label style='color:red;'>Your Second passwored must be greater than 6</label>";
			 
			} 
			if ($_POST['password'] !== $_POST['cpassword']) 
			{ 
			  $error = "<label style='color:red;'>Password do not match</label>"; }
			  if(empty($error)){
			
$querys = mysqli_query($con,"update logins set Password = '$password' where Username = '$user'")or die ('could not select from logings'.mysqli_error()) ; 
if($querys){
  $delete = "DELETE FROM  reset_pass WHERE username='$user'";
mysqli_query($con,$delete);
    $a='Password Reset Successfully';}
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

    <link rel="stylesheet" href="css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body>
<div class="col-md-6">
    <div class="container">	  
<?php  if($num1 >0){?>
		<form class="formp" form name="form1" method="post"  action="">
		  <fieldset>
	    <span class="style3"><?php echo @$error; ?> </span><span class="style2"><?php echo @$a; ?></span>
	    <legend class="style1">Reset Password <span class="style2">(<?php echo @$users; ?>)</span></legend>
	    <div>
		  <label for="password"><strong>Password:</strong></label>
		  <input type="password" id="password" name="password">
		  </div>
		  <div class="from-group">
          <label for="cpassword"><strong>Confirm Password:</strong></label>
				<input type="password" id="cpassword" name="cpassword">
			</div>
                <input class="btn-primary" type="submit"  name="reset" id="reset" value="Reset" >
                </input>
		  </fieldset>
                            
   
  </form>
  <?php } else {echo 'This link has expired';}?>
</div>
</div>
        <p>&nbsp;</p>
</body>
</html>