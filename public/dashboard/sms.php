<?php
include("head.php");
?>
<script>
	function countsms(message)
	{
		var nums = document.getElementById('mobilenumber').value;
		
		if(nums.search(",") > 0 && nums.length >22)
		var ncount = Math.round((nums.lastIndexOf(",")-11)/11)+2;
		else
		ncount = 1;
		
		var mcount = (Math.round((message.length-81)/160)+1);
		
		if(nums.indexOf(",") <= 0 && nums.length >11)
		{
			document.getElementById('sms_count').innerHTML = '<h5 style="color:red;">Pls Insert a Comma after each number </h5>';
		}
		else if(nums.length > 10)
		{
		document.getElementById('sms_count').innerHTML = "Page(s): "+mcount+ ", Price: N" + (ncount *mcount*2) 
		}
		else
		{
		document.getElementById('sms_count').innerHTML = "Pls input number(s)";
		}
	}
</script>
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
							<li class="active">Send Sms</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

	<div class="col-sm-12">
	            <?php
					echo @$_SESSION['smsnotice'];
					@$_SESSION['smsnotice']="";
				?>
                
            </div>
        <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">
                    <div class="col-xs-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>Send</strong> <small> sms</small>
                            </div>
                            <div class="card-body card-block">
								<form method="post" name="form1" id="form1" action="send_sms.php">
									<div class="form-group">
										<label class=" form-control-label">Sender ID</label>
										<div class="input-group">
											<input required maxlength="11" placeholder="Not more than 10 characters" class="form-control" name="sender" id="sender">
										</div>
									</div>
									<label class="text-info">pls put a comma after each number in case of multiple numbers(eg. 080235...,081809...)</label>
									<div class="form-group">
										<label class=" form-control-label">Phone Number(s)</label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-phone"></i></div>
											<textarea placeholder="08067555457,09056444543,08135457654" class="form-control" name="mobilenumber" id="mobilenumber" ></textarea>
										</div>
									</div>
									
									<div class="form-group">
										<label class=" form-control-label">Message</label>
										<div class="input-group">
											<textarea onkeyup="countsms(this.value)" class="form-control" name="message" id="message" ></textarea>
										</div>
									</div>
									
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
