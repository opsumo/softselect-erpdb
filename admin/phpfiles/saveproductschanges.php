<?php
include_once '../classes/config.php';
session_start();
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);
$procosttemp = $_REQUEST['procost'];
$promarkettemp = $_REQUEST['promarket'];
$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
$vendor_id = isset($_REQUEST['vendor_id'])?$_REQUEST['vendor_id']:'';
$prod_name = isset($_REQUEST['prod_name'])?$_REQUEST['prod_name']:'';
$review_date = isset($_REQUEST['review_date'])?$_REQUEST['review_date']:'';
$www = isset($_REQUEST['www'])?$_REQUEST['www']:'';
$notes = isset($_REQUEST['notes'])?$_REQUEST['notes']:'';
//$mtco = isset($_REQUEST['mtco'])?$_REQUEST['mtco']:'';
$mtco = (isset($_REQUEST['mtco']) && $_REQUEST['mtco']!="")?$_REQUEST['mtco']:0;
//$mtco = '0';
//$mtco = isset($_REQUEST['mtxo'])?$_REQUEST['mtxo']:'';
//$mtco=filter_input(INPUT_POST, 'mtxo', FILTER_SANITIZE_STRING)?"1":"0";
// sanitize the value
//$mtco = filter_input(INPUT_POST, 'mtco', FILTER_SANITIZE_STRING);
/*if(filter_has_var(INPUT_POST,'mtxo')) {
  //$mtco='1';
	$mtco=$_POST['mtxo']);
	<script>
		console.log("CHECKBOX:%s %s",filter_input(INPUT_POST, 'mtxo', FILTER_SANITIZE_STRING), $mtco);
	</script>
	echo "CHECKBOX:".filter_input(INPUT_POST, 'mtxo', FILTER_SANITIZE_STRING);
} else {
	$mtco='0';
}*/
/*if (isset($_POST['mtco'])) {
	$mtco="1";
} else $mtco = "0";*/
//$mtco = (isset($_REQUEST['mtxo']) && $_REQUEST['mtxo']!="")?1:0;
//$mtco = (empty($_POST['mtco'])) ? 1 : 0;

// fix up review date format
$review_date = (""==$review_date)?'NULL':"'".date('Y-m-d', strtotime($review_date))."'";

/*

todo: 	change new product function to call edit product with empty id
		check for dup product name?  product + vendor should be unique yes?

*/

if (empty($id)) {
	// add new product & capture ID with SELECT LAST_INSERT_ID();
	$sql = "insert product (vendor_id, product_name, status, review_date, www, notes, mtco)
			values (".$vendor_id.",'".$prod_name."',1,".$review_date.",'".$www."','".$notes."','".$mtco."')";
	mysqli_query($con, $sql) or die(mysqli_error($con));

	$result = mysqli_query($con, "SELECT LAST_INSERT_ID()") or die(mysqli_error($con));

	$id = mysqli_fetch_array($result)[0];
} else {
	// update existing product
	$sql = "update product
			set vendor_id=".$vendor_id.",
				product_name = '".$prod_name."',
				review_date = ".$review_date.",
				www = '".$www."',
				notes = '".$notes."',
				mtco = ".$mtco." where product_id=".$id;
	mysqli_query($con, $sql) or die(mysqli_error($con));
}

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
		$sql = 'delete from product_cost_range where cost_range_id='.$costrangeid.' and product_id='.$id;
		mysqli_query($con, $sql) or die(mysqli_error($con));
		$sql = 'insert into product_cost_range values('.$id.','.$costrangeid.','.$focusvalue.')';
		mysqli_query($con, $sql) or die(mysqli_error($con));
		$costcnt+=mysqli_affected_rows($con);
	}
}

//updating product market
foreach ($promarket as $marketid=>$focusvalue)
{
	if(!empty($marketid) && !empty($id)){
		//delete first
		$s = "delete from product_market where market_id={$marketid} and product_id={$id}";
		mysqli_query($con, $s) or die(mysqli_error($con));
		$sql = "insert into product_market(product_id,market_id,focus_level)
				values({$id},{$marketid},{$focusvalue})";
		mysqli_query($con, $sql) or die(mysqli_error($con));
		$marketcnt+=mysqli_affected_rows($con);
	}
}
echo "Product Cost Range Changed ROWS:".$costcnt;
echo "<br/>Product Market Changed ROWS:".$marketcnt;
?>
