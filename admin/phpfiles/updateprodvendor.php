<?php
include_once '../classes/config.php';
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$prodid = isset($_REQUEST['prodid'])?$_REQUEST['prodid']:'';
$vendorid = isset($_REQUEST['vendorid'])?$_REQUEST['vendorid']:'';
$sql = "update product set vendor_id=$vendorid where product_id=$prodid";
$res = mysqli_query($con, $sql) or die(mysqli_error($con));
if($res) $response = "SUCCESS";
else $response = "FAILURE";
echo $response;
?>