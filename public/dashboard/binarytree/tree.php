<?php
session_start();
$host_url = $_SERVER['SERVER_NAME'];
?>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/style.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<div class="container" style="padding:10px;">


<?php
$username = $_REQUEST['username'];
$result = file_get_contents("https://$host_url/dashboard/binary.php?username=$username");
$obj = json_decode($result);

if(@$obj->right->name == null && @$obj->left->name == null)
    $img = "none.jpg";
if(@$obj->right->name != null && @$obj->left->name == null)
    $img = "right.jpg";
if(@$obj->right->name == null && @$obj->left->name != null)
    $img = "left.jpg";
if(@$obj->right->name != null && @$obj->left->name != null)
    $img = "complete.jpg";
?>

<button class="btn btn-primary" onclick="dashboard()">Back to Dashboard</button>
<button class="btn btn-primary" onclick="startNet()">Start</button>
<button class="btn btn-primary" onclick="backNet()">Back</button>
<h1>Geneology</h1>
<div class="container">
<img src="images/complete.JPG" alt="gene" width="100%" />
<div class="username" ><?php echo $username; echo " (<span style='color:green;'>".@$obj->package."</span>)";?></div>
        <div class="d1" >
            <a href="tree.php?username=<?php echo @$obj->left->name; ?>">
                <?php echo @$obj->left->name; echo " (<span style='color:green;'>".(@$obj->left->package)."</span>)";?>
            </a>
        </div>
		<div class="d2" >
            <a href="tree.php?username=<?php echo @$obj->right->name; ?>">
                <?php echo @$obj->right->name; echo " (<span style='color:green;'>".(@$obj->right->package)."</span>)";?>
            </a>
        </div>
</div>
<br/><br/><br/>
</div>
</div>

    <script>
        function backNet()
        {
            window.history.back();
        }
        function startNet()
        {
            parent.window.location.href="../network.php"
        }
        function dashboard()
        {
            parent.window.location.href="../"
        }
    </script>
</body>
