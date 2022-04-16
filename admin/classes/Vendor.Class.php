<?php
require_once("DB.Class.php");
require_once ('sanitize.php');

if($_SESSION['admin_login']!="true")
{
	echo "<script>menuButtonClick('Admin', 'Logout');</script>";
}
class Vendor extends DBCon
	{
function Vendor()
	{
	parent::DBCon();
	switch($_REQUEST['mode'])
		{
		case "ManageVendor":
			$this->ManageVendor();
		break;
		
		case "NewVendor":
			$this->NewVendor();
		break;
		
		case "Create":
			$this->Create();
		break;
		
		case "Update":
			$this->Update();
		break;

        case "Delete":
            $this->Delete();
            break;

        case "Activate":
            $this->Activate();
            break;

        case "Deactivate":
            $this->Deactivate();
            break;


        default:
			$this->ManageVendor();
		}
	}
	
 	function ManageVendor()
	{
		$this->template="phpfiles/managevendor.php";
		$this->LoadFile();
	}
	
 	function NewVendor()
	{
		$mode = "Create";
		if(isset($_REQUEST['id']) && ($_REQUEST['id']!=""))
		{
			$mode = "Update";
			$vendor_id = $_REQUEST['id'];
			$sql = "SELECT * FROM vendor WHERE vendor_id = '$vendor_id'";
			$res = mysqli_query($this->con, $sql);
			if(!$res)
				echo mysqli_error($this->con);
			$row = mysqli_fetch_array($res);
		}
?>
<form action="index.php" method="post" id="vendorform" name="vendorform">
	<table align="center" width="55%">
    	<tr>
        	<td colspan="2" align="center">
            	<?php if(isset($vendor_id)) {?>
                <h2>Edit Vendor Details</h2>
                <?php } else { ?>
                <h2>New Vendor</h2>
                <?php } ?>
            </td>
        </tr>
        <tr>
        	<td nowrap><strong>Vendor Name</strong></td>
            <td>
            	<input type="text" name="vendor_name" id="vendor_name" <?php if(isset($vendor_id)) echo "value='".$row['vendor_name']."'"; ?> />
            </td>
        </tr>
        <tr>
            <td nowrap><strong>Vendor Website</strong></td>
            <td nowrap>
                <textarea cols="25" rows="5" name="vendor_desc" id="vendor_desc"><?php if(isset($vendor_id)) echo $row['www']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap><strong>Review Date</strong></td>
            <td nowrap>
                <input type="text" name="review_date" id="vendor_review_date" value="<?php echo $row['review_date'];?>" />
            </td>
        </tr>
        <tr>
            <td nowrap><strong>Notes</strong></td>
            <td nowrap>
            	<textarea cols="50" rows="20" name="notes" id="notes"><?php if(isset($vendor_id)) echo $row['notes']; ?></textarea>
            </td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td align="left">
			<?php if(isset($vendor_id)) { ?>
                <input type="hidden" name="vendor_id" id="vendor_id" value="<?php echo $row['vendor_id']; ?>" />
            <?php } ?>
                <input type="hidden" name="module" value="Vendor" />
                <input type="hidden" name="mode" id="hid_mode" value="<?php echo $mode; ?>" />
                <input type="submit" name="btn_mode" id="btn_mode" value="<?php echo $mode; ?>" />
                <input type="button" name="goback" id="goback" value="Cancel" onclick="click_cancel();" />
        	</td>
        </tr>
    </table>
</form>
<script>
    function click_cancel() {
        var frm = document.getElementById('vendorform');
        frm.reset();
        $('#hid_mode').val('ManageVendor');
        frm.submit();
    }
	$(function(){
		$("#vendorform").validate({
			rules:{
				vendor_name: {required:true}
			},
			submitHandler: function(form) {
				form.submit();
			}
		});
	});
</script>
<?php
	}

    function GotoManage()
    {
        echo "<form id='nav' action='index.php' method='post'>";
        echo "   <input type='hidden' name='module' value='Vendor' />";
        echo "   <input type='hidden' name='mode' value='ManageVendor' />";
        echo "</form>";
        echo "<script type='text/javascript'>document.getElementById('nav').submit();</script>";
    }

 	function Create()
	{
		//$sql = "INSERT INTO vendor SET status = 1, vendor_name='".stripper($_POST['vendor_name'])."', www='".stripper($_POST['vendor_desc']."', notes='".stripper($_POST['notes'])."'";
		$sql = "INSERT INTO vendor (status, vendor_name, www, review_date, notes) values (1, '".stripper($_POST['vendor_name'])."', '".stripper($_POST['vendor_desc'])."', '".stripper($_POST['review_date'])."', '".stripper($_POST['notes'])."')";
		$res = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
		if(!$res)
			echo mysqli_error($this->con);

        $this->GotoManage();
	}
	
 	function Update()
    {
        $vendor_id = $_REQUEST['vendor_id'];

        if ($_REQUEST['review_date']) {
            $review_date = date_format(date_create($_POST['review_date']), "Y-m-d");
        } else {
            $review_date = null;
        }

        $stmt = mysqli_prepare($this->con,
            'UPDATE vendor SET vendor_name=?,www=?,review_date=?,notes=? WHERE vendor_id=?');

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $_REQUEST['vendor_name'],
            $_REQUEST['vendor_desc'],
            $review_date,
            $_REQUEST['notes'],
            $vendor_id
        );

		$res = mysqli_stmt_execute($stmt) or die(mysqli_error($this->con));

        $this->GotoManage();

	}

    function Delete()
    {
        $vendor_id = $_REQUEST['id'];
        $sql = "DELETE FROM vendor WHERE vendor_id = $vendor_id AND NOT EXISTS (SELECT * FROM product WHERE vendor_id = $vendor_id)";
        $res = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));

        if (0 == mysqli_affected_rows($this->con))
            echo "<script>alert ('Vendor not deleted - products exist for this vendor');</script>";

        $this->GotoManage();
/*
		if(!$res)
			echo mysqli_error($this->con);
		$row = mysqli_fetch_array($res);
		$product_count = $row[1];
		mysqli_free_result($res)
		
		if (0 == $product_count) 
		{
			$sql = "DELETE FROM vendor WHERE vendor_id = $vendor_id";
			$res = mysqli_query($this->con, $sql);
			if($res)
				$msg = "VendorDeleted";
			else
				echo mysqli_error($this->con);
			
		}
		else
			$msg = "DeleteFailedProductExists";
		echo "<script>window.location='index.php?module=Vendor&mode=ManageVendor&msg=$msg'</script>";
*/
	}

    function Activate()
    {
        $vendor_id = $_REQUEST['id'];
        $sql = "UPDATE vendor SET status = 1 WHERE vendor_id = $vendor_id";
        $res = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
    }

    function Deactivate()
    {
        $vendor_id = $_REQUEST['id'];
        $sql = "UPDATE vendor SET status = 0 WHERE vendor_id = $vendor_id AND NOT EXISTS (SELECT * FROM product WHERE status = 1 AND vendor_id = $vendor_id)";
        $res = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));

        if (0 == mysqli_affected_rows($this->con))
            echo "<script>alert ('Vendor not deactivated - active products exist for this vendor');</script>";
    }

    function checkBrokenLinks($url) {
        $h = get_headers($url);
        $status = array();
        preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0] , $status);
        return ($status[1] == 200);
    }
}
?>