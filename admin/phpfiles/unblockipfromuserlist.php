<?php
include_once '../classes/config.php';
session_start();
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$ip = !empty($_REQUEST['ip'])?$_REQUEST['ip']:'';
$id = "";
if(!empty($ip))
{
	$sql = "select blacklist_id from blacklist where blacklist_ip ='{$ip}'";
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	if($row = mysqli_fetch_array($res))
		$id = $row['blacklist_id'];
	mysqli_free_result($res);
	if(!empty($id))
	{
		$sql = "delete from blacklist where blacklist_id={$id}";
		mysqli_query($con, $sql) or die(mysqli_error($con));
	}
	mysqli_close($con);
}
?>