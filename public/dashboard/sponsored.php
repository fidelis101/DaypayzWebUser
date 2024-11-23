<?php
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
							<li class="active">Sponsored Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

       <div class="content mt-3">
            <div class="animated fadeIn">

                <div class="row">

					<div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                    <strong class="card-title">Sponsored History</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Username</th>
                                            <th>Name</th>
                    					    <th>Phone</th>
                    					    <th>Package</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$snet = mysqli_query($con,"SELECT * FROM networks WHERE Referal_Id='".$_SESSION["usr"]."'");
										$count = 1;
										if($snet != false){
											while($row1=mysqli_fetch_array($snet))
											 {
											     $user = mysqli_query($con,"SELECT * FROM users WHERE Username='".$row1["Username"]."'");
											     $row2 = mysqli_fetch_assoc($user);
											     
													echo '<tr>';
													echo "<td>".$count++."</td>";
													echo "<td>".$row2['Username']."</td>";
													echo "<td>".$row2['Firstname']." ".$row2['Lastname']."</td>";
													echo "<td>".$row2['Phone']."</td>";
													echo "<td>".$row1['Package']."</td>";
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
<?php
	include("foot.php");
?>