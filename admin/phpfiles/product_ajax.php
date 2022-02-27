<?php
error_reporting(0);
session_start();
// include db connection settings
include("../classes/config.php");

// get connection
$c = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($c));

// select the database
//mysqli_select_db(DB_NAME, $c);

switch($_REQUEST['ajaxaction'])
{
	case "GETRECORDS":
	    echo(json_encode(GetRecord($c)));
		break;
		
	default:
		$response = array();
		$response["message"] = "Unknown service action";
		echo(json_encode($response));
		break;
}

function GetRecord($c)
{
	$response = array();
	$response["message"] = "Database connection error";
	$response["resultdata"] = "";
	$response["rowcount"] = "";
	$product_id = $_REQUEST['id'];
	$sql = "SELECT * FROM product WHERE product_id=$product_id";	
	$res = mysqli_query($c, $sql) or die(mysqli_error($c));

	if(mysqli_num_rows($res)>0)
	{
		$response["message"] = "SUCCESS";
		$response["rowcount"] = mysqli_num_rows($res);
		$result = "<table border='1' width='100%' cellpadding='5' align='center'>";
		$row = mysqli_fetch_array($res);
		$response["resultdata"] = $row;
	}
	else
	{
		$response["message"] = "NODATA";
	}
	return $response;
}
?>