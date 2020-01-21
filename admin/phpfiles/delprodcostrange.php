<?php
include_once '../classes/config.php';
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$prodid = isset($_REQUEST['prodid'])?$_REQUEST['prodid']:'';
$costid = isset($_REQUEST['costid'])?$_REQUEST['costid']:'';
$sql = "delete from product_cost_range where product_id=$prodid and cost_range_id=$costid";
$res = mysqli_query($con, $sql) or die(mysqli_error($con));
if($res) $response = "SUCCESS";
else $response = "FAILURE";
echo $response;
?>