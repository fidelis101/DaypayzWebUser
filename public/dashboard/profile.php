<?php
	include("head.php");
	
	if(isset($_POST['pchange'])){
  if (empty($_POST['new_password'])) { //if no name has been supplied
         $error = "<label style='color:red;'>New Password Missing</label>";
      
        } else {
          $pass1 = $_POST['new_password'];
          $options = ['cost' => 12]; //else assign it a variable
            $password1 = password_hash($pass1, PASSWORD_DEFAULT, $options);
        }
        if (empty($_POST['old_password'])) { //if no name has been supplied
          $error = "<label style='color:red;'>Password Missing</label>";
    
        } else {
          $old_pass = $_POST['old_password']; //else assign it a variable
      
        }
      if (empty($_POST['password_confirm'])) { //if no name has been supplied
          $error = "<label style='color:red;'>Confirm Password Missing</label>";
    
        } else {
          $password2 = $_POST['password_confirm']; //else assign it a variable
      
        }
      if (strlen($_POST['new_password']) < 6) 
      { 
       $error = "<label style='color:red;'>Your First Password must be greater than 6</label>";
        
      } 
      if (strlen($_POST['password_confirm']) < 6) 
      { 
       $error = "<label style='color:red;'>Your Second passwored must be greater than 6</label>";
       
      } 
      if ($_POST['new_password'] !== $_POST['password_confirm']) 
      { 
        $error = "<label style='color:red;'>Password do not match</label>"; }
        if(empty($error)){
            
      $username = $_SESSION['usr'];
      
      $pincode1 = mysqli_query($con,"select * from logins where Username = '$username' ") or die ('could not select from logins'); 
    	$pinne2 = mysqli_num_rows($pincode1);
		while ($row = mysqli_fetch_assoc($pincode1))
		{
			$id = $row['id'];
			$uname = $row['Username'];
			$hash = $row['Password'];
		}
    if(!password_verify($old_pass, $hash)) {
		
		$error = "<label style='color:red;'>Wrong Password</label>";
		
	}
	else{
	mysqli_query($con,"UPDATE logins SET Password='$password1' WHERE Username='$username'");
	$error = "<p class='text-success'>Password Changed Successfully</p>";

     }
     }
	}

	if(isset($_POST['tpchange'])){
  if (empty($_POST['new_tpassword'])) { //if no name has been supplied
         $errort = "<label style='color:red;'>New Password Missing</label>";
      
        } else {
          $pass1 = $_POST['new_tpassword'];
          $options = ['cost' => 12]; //else assign it a variable
            $password1 = password_hash($pass1, PASSWORD_DEFAULT, $options);
        }
        if (empty($_POST['old_tpassword'])) { //if no name has been supplied
          $error = "<label style='color:red;'>Confirm Password Missing</label>";
    
        } else {
          $old_tpass = $_POST['old_tpassword']; //else assign it a variable
      
        }
      if (empty($_POST['tpassword_confirm'])) { //if no name has been supplied
          $errort = "<label style='color:red;'>Confirm Password Missing</label>";
    
        } else {
          $password2 = $_POST['tpassword_confirm']; //else assign it a variable
      
        }
      if (strlen($_POST['new_tpassword']) < 6) 
      { 
       $errort = "<label style='color:red;'>Your First Password must be greater than 6</label>";
        
      } 
      if (strlen($_POST['tpassword_confirm']) < 6) 
      { 
       $errort = "<label style='color:red;'>Your Second passwored must be greater than 6</label>";
       
      } 
      if ($_POST['new_tpassword'] !== $_POST['tpassword_confirm']) 
      { 
        $errort = "<label style='color:red;'>Password do not match</label>"; }
        if(empty($errort)){
            
      $username = $_SESSION['usr'];
      
      $pincode1 = mysqli_query($con,"select * from wallets where Username = '$username' ") or die ('could not select from logins'); 
    	$pinne2 = mysqli_num_rows($pincode1);
		while ($row = mysqli_fetch_assoc($pincode1))
		{
			$id = $row['id'];
			$uname = $row['Username'];
			$hash = $row['Transaction_Psd'];
		}
    if(!password_verify($old_tpass, $hash)) {
		
		$errort = "<label style='color:red;'>Wrong Password</label>";
		
	}
	else{

  mysqli_query($con,"UPDATE wallets SET Transaction_Psd='$password1' WHERE Username='$username'");
  $errort = "<p class='text-success'> Transaction Password Changed Successfully</p>";

     }
     }
	}
?>
    <div id="right-panel" class="right-panel">
	<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="index.php">Dashboard</a></li>
							<li class="active" > Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
	<?php
	if(false)
	echo '
	 <div class="col-sm-12">
                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                    <span class="badge badge-pill badge-success">Success</span> You successfully read this important alert message.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>';
	?>
    <div class="content mt-3">
		
        <div class="animated fadeIn">
            <div class="row">

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><strong>Personal</strong><small> information</small></div>
                        <div class="card-body card-block">
							<form action="saveProfileEdit.php" method="post" >
								<div class="form-group">
									<label for="firstname" class=" form-control-label">Firstname</label>
									<input value=<?php echo $userprofile['Firstname']; ?> id="firstname" name="firstname" type="text" class="form-control">
								</div>
								<div class="form-group">
									<label for="lastname" class=" form-control-label">Lastname</label>
									<input value=<?php echo $userprofile['Lastname']; ?> id="lastname" name="lastname" type="text" class="form-control">
								</div>
								<div class="form-group">
									<label for="email" class=" form-control-label">Email</label>
									<input value=<?php echo $userprofile['Email']; ?> id="email" name="email" type="email" class="form-control">
								</div>
								<div class="form-group">
									<label for="phone" class=" form-control-label">Phone</label>
									<input value=<?php echo $userprofile['Phone']; ?> id="phone" name="phone" type="text" class="form-control">
								</div>
								<div>
									<button id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
										<span id="payment-button-amount">Save</span>
									</button>
								</div>
							</form>
						</div>
                    </div>
				</div>
				
				<div class="col-lg-6" >
					<div class="card">
						<div class="card-header">
							<strong class="card-title">Bank Details</strong>
						</div>
						<div class="card-body">
							<!-- Credit Card -->
							<div >
								<div class="card-body">
									<form action="" method="post" id="bk">
										<div class="form-group">
											<label for="cc-payment" class="control-label mb-1">Surname</label>
											<input value=<?php echo "'".$useraccount['Firstname']."'"; ?> type="text" class="form-control" aria-required="true" aria-invalid="false">
										</div>
										<div class="form-group has-success">
											<label for="cc-name" class="control-label mb-1">Lastname</label>
											<input value=<?php echo "'".$useraccount['Middlename']."'"; ?> type="text" class="form-control cc-name valid" data-val="true" data-val-required="Please enter the name on card" autocomplete="cc-name" aria-required="true" aria-invalid="false" aria-describedby="cc-name-error">
											<span class="help-block field-validation-valid" data-valmsg-for="cc-name" data-valmsg-replace="true"></span>
										</div>
										<div class="form-group">
											<label for="cc-number" class="control-label mb-1">Bank Name</label>
											<input value=<?php echo "'".$useraccount['Bank_Name']."'"; ?> type="text" class="form-control cc-number identified visa" value="" data-val="true" data-val-required="Please enter the card number" data-val-cc-number="Please enter a valid card number" autocomplete="cc-number">
											<span class="help-block" data-valmsg-for="cc-number" data-valmsg-replace="true"></span>
										</div>
										<div class="form-group">
											<label for="cc-number" class="control-label mb-1">Account Type</label>
											<input value=<?php echo "'".$useraccount['Account_Type']."'"; ?> type="text" class="form-control cc-number identified visa" value="" data-val="true" data-val-required="Please enter the card number" data-val-cc-number="Please enter a valid card number" autocomplete="cc-number">
											<span class="help-block" data-valmsg-for="cc-number" data-valmsg-replace="true"></span>
										</div>
										<div class="form-group">
											<label for="cc-number" class="control-label mb-1">Account Number</label>
											<input value=<?php echo "'".$useraccount['Account_number']."'"; ?> type="number" class="form-control cc-number identified visa" value="" data-val="true" data-val-required="Please enter the card number" data-val-cc-number="Please enter a valid card number" autocomplete="cc-number">
											<span class="help-block" data-valmsg-for="cc-number" data-valmsg-replace="true"></span>
										</div>
									</form>
									<?php 
    										if(!(getUserBankDetails($username)))
    										{
    										echo '<script>document.getElementById("bk").style ="display:none;";</script>';
    										    echo '<a href="addbankdetails.php" class="btn btn-primary">Add Bank Details</a>';
    										}
										?>
                                </div>
                            </div>

                        </div>
						
                    </div> 

                </div>
				<div class="col-lg-6">
					<div class="card">
						<div class="card-body">
							<div class="card-title">
								<h3 class="text-center">Change Password</h3>
							</div>
							<hr>
		
							<form action="" method="post" >
							<?php echo @$a;  ?> <?php echo @$error;  ?>
								<div class="form-group">
									<label for="cc-payment" class="control-label mb-1">Current Password</label>
									<input id="old_password" name="old_password" type="password" class="form-control">
								</div>
								<div class="form-group has-success">
									<label for="cc-name" class="control-label mb-1">New Password</label>
									<input id="new_password" name="new_password" type="password" class="form-control cc-name valid">
								</div>
								<div class="form-group">
									<label for="cc-number" class="control-label mb-1">Confirm Password</label>
									<input type="password" id="password_confirm" name="password_confirm" class="form-control cc-number identified visa" >
								</div>
								
								<div>
									<button  name="pchange" id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
										<span id="payment-button-amount">Change Password</span>
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="card">
						<div class="card-body">
							<div class="card-title">
								<h3 class="text-center">Change Transaction Password</h3>
							</div>
							<hr>
		
							<form action="" method="post" >
							<?php echo @$a;  ?> <?php echo @$errort;  ?>
								<div class="form-group">
									<label for="cc-payment" class="control-label mb-1">Current Transaction Password</label>
									<input id="old_tpassword" name="old_tpassword" type="password" class="form-control">
								</div>
								<div class="form-group has-success">
									<label for="cc-name" class="control-label mb-1">New Transaction Password</label>
									<input id="new_tpassword" name="new_tpassword" type="password" class="form-control cc-name valid">
								</div>
								<div class="form-group">
									<label for="cc-number" class="control-label mb-1">Confirm Password</label>
									<input type="password" id="tpassword_confirm" name="tpassword_confirm" class="form-control cc-number identified visa" >
								</div>
								
								<div>
									<button  name="tpchange" id="payment-button" type="submit" class="btn btn-lg btn-primary btn-block">
										<span id="payment-button-amount">Change Password</span>
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	include("foot.php");
?>