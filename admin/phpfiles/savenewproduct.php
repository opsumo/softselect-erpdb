<?php
include_once '../classes/config.php';
require_once '../sanitize.php';
session_start();
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$productname = isset($_REQUEST['prodname'])?stripper($_REQUEST['prodname']):'';
$vendorid = isset($_REQUEST['vendorid'])?$_REQUEST['vendorid']:'';
$procosttemp = $_REQUEST['procost'];
$promarkettemp = $_REQUEST['promarket'];

//insert product
$sql = "insert into product(product_name,vendor_id,status)
		values('".$productname."',".$vendorid.",1)";
mysqli_query($con, $sql) or die(mysqli_error($con));
$id = mysqli_insert_id($con);

$procost = array();
$procosttemp1 = explode(",",str_replace(array("{","}"),"",$procosttemp[0]));
foreach ($procosttemp1 as $key=>$values)
{
	list($index,$val) = explode(":",$values);
	$procost[$index] = $val;
}

$promarket = array();
$promarkettemp1 = explode(",",str_replace(array("{","}"),"",$promarkettemp[0]));
foreach($promarkettemp1 as $key1=>$values1)
{
	list($index,$val) = explode(":",$values1);
	$promarket[$index] = $val;
}
$costcnt = 0;
$marketcnt = 0;
//updating product cost range
foreach ($procost as $costrangeid=>$focusvalue)
{
	if(!empty($costrangeid) && !empty($id)){
		$sql = "insert into product_cost_range(product_id,cost_range_id,focus_level) values($id,$costrangeid,$focusvalue)";
		mysqli_query($con, $sql) or die(mysqli_error($con));
	}
}

//updating product market
foreach ($promarket as $marketid=>$focusvalue)
{
	if(!empty($marketid) && !empty($id)){
		$sql = "insert into product_market(product_id,market_id,focus_level) values($id,$marketid,$focusvalue)";
		mysqli_query($con, $sql) or die(mysqli_error($con));
	}
}
echo "Done.";
?>