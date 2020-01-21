<?php
include_once '../classes/config.php';
require_once '../sanitize.php';
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$userid = isset($_REQUEST['id'])?$_REQUEST['id']:'';
$adminid = isset($_REQUEST['adminid'])?$_REQUEST['adminid']:'';
$message = "OKAY";
if(!empty($adminid)) {
	$sql = "select user_type_code from user where user_id=".$adminid;
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	$row = mysqli_fetch_array($res);
	if($row['user_type_code']) {
		$sql1 = "delete from user where user_id=".$userid;		
		$rest = mysqli_query($con, $sql1);
	}
	else {
		$message = "FAIL";
	}
}
else {
	$message = "FAIL";
}
echo $message;
?>