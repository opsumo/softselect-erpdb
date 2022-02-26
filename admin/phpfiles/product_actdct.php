<?php
include_once '../classes/config.php';
require_once '../sanitize.php';
session_start();

$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';

$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

if(!empty($id) && !empty($mode)) {
	
	switch($mode) {
		
		case "activate":
			$sql = "update product set status=1 where product_id=".$id;
			break;
		case "deactivate":
			$sql = "update product set status=0 where product_id=".$id;
			break;
	}
	
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	
	if($res) echo "SUCCESS";
	else echo "FAILURE";
}
else echo "FAILURE";
mysqli_close($con);
?>