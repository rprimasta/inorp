<?php
include 'dbconfig.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//echo ($_POST['user'].",".$_POST['pass'].",".$_POST['method']);
if(isset($_POST['user'],$_POST['pass'])){
	if(userAuth($_POST['user'],$_POST['pass'],$conn)){
		$jObj = array();
		switch($_POST['method']){
			case "auth":
				$res = userInfo($_POST['user'],$_POST['pass'],$conn);
				if(!is_null($res))
				{
					$jObj['status'] = 0;
					$jObj['statusDesc'] = statusDesc($jObj['status']);
					$jObj['data'] = $res;
				}
				else
					$jObj['status'] = 3;
					$jObj['statusDesc'] = statusDesc($jObj['status']);

				break;
			default:
				$jObj['status'] = 1;
				$jObj['statusDesc'] = statusDesc($jObj['status']);
				break;
		}
	}
	else
	{
		$jObj['status'] = 2;
		$jObj['statusDesc'] = statusDesc($jObj['status']);
	}
	echo json_encode($jObj);
}


function statusDesc($retCode)
{
	$msg = "";
	switch($retCode)
	{
		case 0: $msg = "Success"; break;
		case 1: $msg = "Invalid Method"; break;
		case 2: $msg = "Authentication Failed"; break;
		case 3: $msg = "No Data"; break;
		default : $msg = "Unknown Error"; break;
	}
	return $msg;
}

function userAuth($user,$pass, $connector){
	$sql = "SELECT * FROM users WHERE user='". $user ."' AND pass=SHA1('" . $pass . "');";
	//echo $sql . "<br>";
	$result = $connector->query($sql);
	if ($result->num_rows > 0) {
		return true;
	}
	else{
		return false;
	}
}

function userInfo($user,$pass, $connector){
	$sql = "SELECT * FROM users WHERE user='". $user ."' AND pass=SHA1('" . $pass . "');";
	//echo $sql;
	$result = $connector->query($sql);
	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) {
				return $row;				
		}
		
	}
	else
	{
		return NULL;
	}
}


?>
