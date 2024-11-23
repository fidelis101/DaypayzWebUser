<?php
    include("dbcon.php");
    $user = $_REQUEST['username'];
    
    $result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$user'");
    if(mysqli_num_rows($result)>0)
    {
        $right = new stdClass;
        $left = new stdClass;
        $obj = new stdClass;
        $obj->right = $right;
        $obj->left = $left;
        
        $result1 = mysqli_query($con,"SELECT * FROM networks WHERE Username='$user'");
        $row1 = mysqli_fetch_assoc($result1);
        $obj->package = $row1['Package'];
        
        while($row = mysqli_fetch_assoc($result))
        {
            if($row['Placement']=='left')
            {
                $obj->left->name = $row['Username'];
                $obj->left->package = $row['Package'];
            }
            if($row['Placement']=='right')
            {
                $obj->right->name = $row['Username'];
                $obj->right->package = $row['Package'];
            }
        }
        echo json_encode($obj);
    }
