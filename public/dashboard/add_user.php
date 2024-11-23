<?php
    session_start();
    include('dbcon.php');
    require_once('userdb.php');
    $error = @$_SESSION['error'];
    $_SESSION['error'] = "";
    include("head.php");
     
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
							<li class="active" > Add User</li>
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
                        <div class="card-header"><strong>Add</strong><small> User</small></div>
                        <div class="card-body card-block">
                            <p><?php echo $error; ?></p>
                            <form method="post" action="Registrationint.php" autocomplete="on">
        						<div class="form-group">
        							<label>Referal ID<b style="color:red;">*</b></label>
        							<?php echo '<input required type="text" value="'.@$_SESSION['usr'].'" name="referal" class="form-control">';?>
        						</div>
        						<div class="form-group">
        							<label>Placement ID </label>
        							<?php echo '<input type="text" value="'.@$_REQUEST["pl"].'" name="placement" class="form-control">'; ?>
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
        								<input type="checkbox" name="terms" id="terms" value='yes' required> Agree the terms and policy
        							</label>
                                </div>
                                <button type="submit" id="register" style="background-color:#17A2B8;color:white;" class="btn  btn-flat m-b-30 m-t-30">Register</button>
                                    
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