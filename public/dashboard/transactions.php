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
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Transaction Id</th>
											<th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
										$count = 1;
										if($transactions != false){
											while($row1=mysqli_fetch_array($transactions))
											 {
													echo '<tr>';
													echo "<td>".$count++."</td>";
													 echo "<td>".$row1['TransactionDate']."</td>";
													echo "<td>N".$row1['Amount']."</td>";
													echo "<td>".$row1['Description']."</td>";
                                                    echo "<td>".$row1['Tran_Id']."</td>";
													echo "<td>".$row1['final_bal']."</td>";
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
