<?php
include_once '../classes/config.php';
session_start();
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);
$ip = !empty($_REQUEST['ip'])?$_REQUEST['ip']:'';
if(!empty($ip))
{
	$d = date("Y-m-d h:i:s");
	$sql = "insert into blacklist(blacklist_ip,blacklist_date,blacklist_last_log) 
			values('{$ip}','$d','0000-00-00 00:00:00')";
	mysqli_query($con, $sql) or die(mysqli_error($con));
	mysqli_close($con);
}
?>