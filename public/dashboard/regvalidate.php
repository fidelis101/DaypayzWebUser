<?php
include('dbcon.php');

class regvalidate
{
	private $ref='';
	private $package='';
	private $refs = array();
	private $response = '';

	function getPlacement($ref,$placement)
	{
		include('dbcon.php');
		$leader = $ref;
		$search_status = true;

		$result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$ref' AND Placement='$placement'");
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_array($result);
			$leader = $row['Username'];
		}else{
			$leader = $ref;
			$this->response = (object)array('leader'=>$leader,'placement'=>$placement);
			$search_status = false;
		}

		while($search_status)
		{
			$rf=$leader;
			$result = mysqli_query($con,"SELECT * FROM networks WHERE Leader_Id='$rf' AND Placement='right'");
			if(mysqli_num_rows($result)>0)
			{
				$row = mysqli_fetch_array($result);
				$leader = $row['Username'];
				if($leader == $ref)
					$search_status = false;
			}
			else
			{
				$search_status = false;
				$this->response = (object)array('leader'=>$leader,'placement'=>'right');
			}
		}
		return $this->response;
	}
}

?>