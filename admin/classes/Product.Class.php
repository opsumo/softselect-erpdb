<?php
require_once("DB.Class.php");
if($_SESSION['admin_login']!="true")
{
	echo "<script>menuButtonClick('Admin', 'Logout');</script>";
}
class Product extends DBCon
	{
function Product()
	{
	parent::DBCon();
	switch($_REQUEST['mode'])
		{
		case "ManageProduct":
			$this->ManageProduct();
		break;
		
		case "NewProduct":
			$this->NewProduct();
		break;
		
		case "Activate":
			$this->Activate();
		break;
		
		case "Deactivate":
			$this->Deactivate();
		break;
		
		case "Delete":
			$this->Delete();
		break;
				
		default:
			$this->ManageProduct();
		}
	}
	
 	function ManageProduct()
	{
		$this->template="phpfiles/manageproduct.php";
		$this->LoadFile();
	}
	
 	function NewProduct()
	{
		if(isset($_REQUEST['id']) && ($_REQUEST['id']!=""))
		{
			$product_id = $_REQUEST['id'];
			$sql = "UPDATE product SET product_name='$_REQUEST[name]' WHERE product_id = '$product_id'";
			$res = mysqli_query($this->con, $sql);
			if(!$res)
				echo mysqli_error($this->con);
		}
		else
		{
			print_r($_REQUEST);
			echo "Product Name-> ".$_REQUEST['name'];
			$sql = "INSERT INTO product SET status = 1, product_name='$_REQUEST[name]'";
			$res = mysqli_query($this->con, $sql);
			if(!$res)
				echo mysqli_error($this->con);
		}
	} 
	
	function Activate()
	{
		$product_id = $_REQUEST['id'];
		$sql = "UPDATE product SET status='1' WHERE product_id = '$product_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res) echo mysqli_error($this->con);
		
		$s = "select vendor_id from product where product_id=".$product_id;
		$re = mysqli_query($this->con, $s) or die(mysqli_error($this->con));
		$id = mysqli_fetch_array($re);
		mysqli_free_result($re);
		
		$sq = "select status from product where status=1 and vendor_id=".$id[0];
		echo $sq;
		$r = mysqli_query($this->con, $sq) or die(mysqli_error($this->con));
		$num = mysqli_num_rows($r);
		mysqli_free_result($r);
		if($num>0){
			$new = "update vendor set status=1 where vendor_id={$id[0]}";
			mysqli_query($this->con, $new) or die(mysqli_error($this->con));
		}
	}
	
	function Deactivate()
	{
		$sql = "UPDATE product SET status = 0 WHERE product_id = ".$_REQUEST['id'];
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);

		$sql = "update vendor set status = 0 where status != 0
				and not exists (select * from product where product.vendor_id = vendor.vendor_id and product.status = 1)";
		mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
	}
	
	function Delete()
	{
		$product_id = $_REQUEST['id'];

		$sql = "DELETE FROM product_cost_range WHERE product_id = $product_id";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		$sql = "DELETE FROM product_market WHERE product_id = $product_id";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
/*
		$sql = "update vendor set status=0 where vendor_id = (select vendor_id from product where product_id = {$product_id} 
				and not exists (select * from product where product.status = 1 and product.vendor_id = vendor.vendor_id and product.product_id != {$product_id})";
		mysqli_query($this->con, $sql) or die(mysqli_error($this->con)));
*/
		$sql = "DELETE FROM product WHERE product_id = $product_id";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);

		$sql = "update vendor set status = 0 where status != 0
				and not exists (select * from product where product.vendor_id = vendor.vendor_id and product.status = 1)";
		mysqli_query($this->con, $sql) or die(mysqli_error($this->con));

	}
}
?>