<?php
	include("head.php");
	
	
	function checkNetCug($network)
	{
	    include('dbcon.php');
	    $user = $_SESSION['usr'];
	    $result = mysqli_query($con,"SELECT * FROM cug_activation WHERE Username ='$user' AND Network='$network'");
	    if(mysqli_num_rows($result)>0)
	        echo "none";
	    else
	        echo "block";
	}
	
	function checkfirst()
	{
	    include('dbcon.php');
	    $user = $_SESSION['usr'];
	    $result = mysqli_query($con,"SELECT * FROM cug_activation WHERE Username ='$user' and network='mtn'");
	    if(mysqli_num_rows($result)>0)
	        echo "Note: By clicking activate CUG and to proceed to submit your mobile number, it means you have given your consent for your line to be activated into Daypayz Intl LTD CUG plan by GLO network and you have paid for it. If you dont have need for it, pls use other services and do not submit your line for CUG.? Click OK to Accept or Cancel to reject";
	    else
	        echo "By clicking activate CUG and to proceed to submit your mobile number, it means you have given your consent for your line to be activated into Daypayz Intl LTD CUG plan by MTN network and you have paid for it. If you dont have need for it, pls use other services and do not submit your line for CUG.? Click OK to Accept or Cancel to reject";
	}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
      </script>
      <script>
      $(document).ready(function(){
      functionAlert();
      });
         function functionAlert(msg, myYes) {
            var confirmBox = $("#confirm");
            confirmBox.find(".message").text(msg);
            confirmBox.find(".yes").unbind().click(function() {
               confirmBox.hide();
            });
            confirmBox.find(".yes").click(myYes);
            confirmBox.show();
         }
         function gloAlert(msg, myYes) {
            var confirmBox = $("#confirm1");
            confirmBox.find(".message").text(msg);
            confirmBox.find(".yes").unbind().click(function() {
               confirmBox.hide();
            });
            confirmBox.find(".yes").click(myYes);
            confirmBox.show();
         }
      </script>
      <style>
         #confirm, #confirm1 {
            display: none;
            background-color: #91FF00;
            border: 1px solid #aaa;
            position: fixed;
            width: 300px;
            height:300px;
            left: 40%;
            Top:40%;
            margin-left: -100px;
            padding: 6px 8px 8px;
            box-sizing: border-box;
            text-align: center;
         }
         #confirm button, #confirm1 button{
            background-color: #48E5DA;
            display: inline-block;
            border-radius: 5px;
            border: 1px solid #aaa;
            padding: 5px;
            text-align: center;
            width: 80px;
            cursor: pointer;
         }
         id[^="confirm"] .message {
            text-align: center;
         }
      </style>
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
							<li class="active">Activate CUG Sim</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
	<?php
	if(false)
	echo '
	 <div class="col-sm-12">
                <div class="alert  alert-warning alert-dismissible col-sm-10 fade show" role="alert">
                   
                    	<h3 class="text-danger">STRICT CAUTION!</h3>
                    	<p class="text-danger">
                    	Please do not submit a line with borrowed airtime, or clear the debt before you submit. And do not borrow airtime in the process
                    	of your CUG activation. Failure to keep to this will result to your CUG activation failure and there will be no reversal
                    	</p>
                    
                    
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>';
	?>
       <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">

                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
			<?php echo @$_SESSION['msg']; @$_SESSION['msg']=''; ?>
			<form method="post" action="process_cug.php" onSubmit="if(!confirm('<?php checkfirst();  ?>')){return false;}">
                            <div class="card-header">
                                <strong>Activate</strong> <small> Cug</small>
                            </div>
                            <div class="card-body card-block">
				<div class="form-group">
                                    <label class="form-control-label">Phone number</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                        <input class="form-control" placeholder="081232..." name="phone" id="phone" required>
                                    </div>
                                </div>
                                <div class="fom-group">
                                    <label class="control-label">Select Network </label>
                                    <select onclick="gloAlert();"  name="network" class="form-control" required>
                                    	<option value="" required>Select Network</option>
                                        <option disabled value="mtn" style="display:<?php checkNetCug('mtn'); ?>;">Mtn</option>
                                        <option onclick="gloAlert();"  value="glo" style="display:<?php checkNetCug('glo'); ?>;">Glo</option>
                                        <option value="9mobile" style="display:none;">9Mobile</option>
                                        <option value="airtel" style="display:none;">Airtel</option>
                                    </select>
                                </dv>
                                <br/>
				<div class="form-group">
                                    <label class=" form-control-label">Transaction Password</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-asterisk"></i></div>
                                        <input class="form-control" type="password" name="password" id="password" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> Submit
                                    </button>
                                </div>
                            </div>
							</form>
                        </div>
                    </div>
					
					<div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Activation History</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Phone</th>
					    <th>Status</th>
					    <th>Network</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$transac = mysqli_query($con,"SELECT * FROM cug_activation WHERE Username='".$_SESSION["usr"]."'");
										$count = 1;
										if($transac != false){
											while($row1=mysqli_fetch_array($transac))
											 {
													echo '<tr>';
													echo "<td>".$count++."</td>";
													echo "<td>".$row1['Phone']."</td>";
													echo "<td>".$row1['Status']."</td>";
													echo "<td>".$row1['Network']."</td>";
													echo '</tr>';
											 }
											}
										  ?>
                                        
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->

	 <div id = "confirm">
	 <br/>
         <div class = "message">
             Please, Submit registered new line for your CUG.
             <h6>Note: Activation/Subscription of the first number you submit is free for the first month, 
             other numbers you submit after the first will attract activation/subscription fee of N600.
             (Consecutive months will be charged by your service provider ie mtn or glo ...).</h6>
        </div>
         <br/>
         <button class = "yes">Continue</button>
      </div>
    
     <div id = "confirm1" style="background-color:white; height:200px;">
	 <br/>
         <div class = "message">
             <h6 class="text-danger">Please, Kindly note that by submitting a glo number, you will be required to re-register your line once it is added to CUG.</h6>
        </div>
         <br/>
         <button class = "yes btn-primary">Continue</button>
      </div>
<?php
	include("foot.php");
?>