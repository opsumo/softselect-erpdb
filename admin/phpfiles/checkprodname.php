<?php
include_once '../classes/config.php';
session_start();
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$name = isset($_REQUEST['name'])?urldecode($_REQUEST['name']):'';
$mes = "";
$sql = "select * from product where product_name='{$name}'";
$res = mysqli_query($con, $sql) or die(mysqli_error($con));
$rows = mysqli_num_rows($res);
if($rows == 0) $mes = "OKAY";
else if($rows > 0) $mes = "DUPLICATE";
else $mes = "";
mysqli_free_result($res);
mysqli_close($con);
echo $mes;
?>