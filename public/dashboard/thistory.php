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
							<li class="active"> Transactions</li>
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

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Data Table</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                           <th>action</th>
                                            <th>Description</th>
                                            <th>Cost</th>
											<th>Date</th>
											
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										   $user = $_SESSION['usr'];
                    $count = 1;
                  
                    $receipt = mysqli_query($con,"select * from apitransactions where Username='$user' AND Provider='CK' AND Transaction_Id !='' order by Id desc") or die ('could not select from registeration'.mysql_error());
                if(mysqli_num_rows($receipt) > 0)
                {
                            while ($row = mysqli_fetch_array($receipt))
                            {   
                                
                                
                                echo '<tr>';
                                    echo "<td>".$count++."</td>";
                                    if($row['Transaction_Id'] !='' && $row['Provider'] == 'CK' )
                                    {
                                       
                                    echo " <td><form method='post' action='view_status.php' >
                                         <input class='btn-sm btn-primary' value='View Status' type='submit'>
                                         <input hidden name='transactionid' id='transactionid' value='$row[Transaction_Id]'/>
                                         <input hidden name='description' id='description' value='$row[Description]'/>
                                         <input hidden name='provider' id='provider' value='$row[Provider]'/>
                                         <input hidden name='rfcost' id='rfcost' value='$row[cost]'/>
                                         
                                         </form></td>";
                                    }
                                    else
                                    {
                                       echo "<td><a>View</a></td>";
                                    }
                                   
                                    echo "<td>".$row['Description']."</td>";
                                      echo "<td>".$row['cost']."</td>";
                                    echo "<td>".$row['Transaction_Date']."</td>";
                                    
													
				
													echo '</tr>';
											 }
											}
											else{
											  echo "No Transaction made yet";
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
