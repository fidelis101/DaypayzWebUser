<?php
include("head.php");
?>

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
            <style type="text/css" media="all">
	@import "widgEditor/css/info.css";
	@import "widgEditor/css/main.css";
	@import "widgEditor/css/widgEditor.css";
</style>

<script type="text/javascript" src="widgEditor/scripts/widgEditor.js"></script>

        <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">
                    <div class="col-xs-6 col-sm-6">
					<?php echo @$_SESSION['supportnotice']; $_SESSION['supportnotice'] = ''; ?>
					<div class="card">
						<div class="card-body">
							<?php
							$result = mysqli_query($con,"SELECT * FROM support where Username='$_SESSION[usr]'");
							if(mysqli_num_rows($result) > 0)
							{
								while($row = mysqli_fetch_array($result))
								{
									$rw = $row['Status'];
									if($rw == "Processing" || $rw == "Processed")
									{
										echo '<div class="alert alert-success" role="alert">
												<h6 alert-heading>'.$row['Header'].'</h6>
												<p>'.$row['Message'].'<br/>
												<small class="text-success">'.$row['Username'].'</small></p>
												<hr>
												<p class="float-left">'.$rw.'</p>
												<p class="mb-0 float-right">'.$row['Date'].'</p>
												<br/>
											</div>';
											
									}
									if($rw == "Received" || $rw == "Receiving")
									{
										 echo '<div class="alert alert-warning" role="alert">
												<h4 class="alert-heading">'.$row['Department'].'<br/>'.$row['Header'].'</h4>
												<p>'.$row['Message'].'</p>
												<hr>
												<p class="mb-0 float-right">'.$row['Date'].'</p>
												<br/>
											</div>';
									mysqli_query($con,"UPDATE support SET Status='Received' where Username='$_SESSION[usr]' AND Status='Receiving'");
									}
								}
							}
								?>
                            </div>
                       </div>
                        <div class="card">
                            <div class="card-header">
                                <strong>Support</strong> 
                            </div>
                            <div class="card-body card-block">
								<form method="post" name="form1" id="form1" action="send_support.php">
									<div class="form-group">
										<label class=" form-control-label">Department</label>
										<div class="input-group">
											<select class="form-control" name="department" id="department">
										</div>
											    <option value="Customer Care">Customer Care</option>
											    <option value="Technical">Technical</option>
											    <option value="Suggestion">Suggestion</option>
									    </select>
									</div>
									
									<div class="form-group">
										<label class=" form-control-label">Subject</label>
										<div class="input-group">
											<input class="form-control" name="heading" id="heading">
										</div>
									</div>
									
									<fieldset>
										<legend for="noise">
											Message:
										</legend>
										<textarea id="message" name="message" class="widgEditor nothing"></textarea>
									</fieldset>
									
									<input type="hidden" id="action" name="action" value="text">
									<div>
										<button id="message" type="submit" class="btn btn-lg btn-primary btn-block">
											<span id="Send">Send</span>
										</button>
									</div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


<?php
	include("foot.php");
?>
