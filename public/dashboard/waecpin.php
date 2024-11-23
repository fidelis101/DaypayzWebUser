<?php
include("head.php");
?>
<script>
function showPrice(pinnum) {
    if (pinnum.length == 0) { 
        document.getElementById("cost").innerHTML = "";
        return;
    } else {
            document.getElementById("cost").innerHTML ="N"+( pinnum * 920);
          
    }
}
</script>
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
							<li class="active">Support</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

	<div class="col-sm-12">
	            <?php
					if(ISSET($_SESSION['smsnotice']) && $_SESSION['smsnotice'] !== '')
					{
						if(strpos(@$_SESSION['smsnotice'],"Failed") == false)
						{
							echo '<div class="alert  alert-success alert-dismissible fade show" role="alert">
								<span class="badge badge-pill badge-success">Success</span> Message sent successful.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';
						}else if(strpos(@$_SESSION['smsnotice'],"Failed") !== false)
						{
							echo '<div class="alert  alert-danger alert-dismissible fade show" role="alert">
								<span class="badge badge-pill badge-danger">Failed</span> Message sending failed.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>';
						}else{
							echo @$_SESSION['smsnotice'];
						}
						
						@$_SESSION['smsnotice']=''; 
					}
					
					echo @$_SESSION['smsnotice'];
				?>
                
            </div>
        <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">
                    <div class="col-xs-6 col-sm-6">
					<div class="card">
                        <div class="card-header">
                            <strong>Tranfer</strong> 
                        </div>
                        <div class="card-body card-block">
							<?php echo @$_SESSION['waecnotification'];@$_SESSION['waecnotification']=''; 
  
						   echo "<div> ".@$_SESSION['pins']." </div>"; 
						   ?>
						   <form method="post" name="form1" id="form1" action="waecpurchase.php">	
									<div class="form-group">
										<label class=" form-control-label">Email</label>
										<div class="input-group">
											<input class="form-control" id="email" name="email">
										</div>
									</div>
									<div class="form-group">
										<label class=" form-control-label">Select pin type</label>
										<div class="input-group">
										<select name="pintype" id="pintype" required>
											<option value=''>Select pin type</option>
											<option hidden value='01'>Waec Registration</option>
											<option value='02'>Waec Result Checker</option>
										</select>
										</div>
									</div>
									<div class="form-group">
										<label class=" form-control-label">How many pins</label>
										<div class="input-group">
											<input required class="form-control" id="pins" name="pins" onkeyup="showPrice(this.value)">
										</div>
									</div>
									<p>Price: </p><span id="cost" style="color:green;"></span></p>

									<input type="hidden" id="action" name="action" value="waecpurchase">
									<div>
										<button id="message" type="submit" class="btn btn-lg btn-info btn-block">
											<span id="Send">Buy Pin(s)</span>
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
require_once('foot.php');
?>