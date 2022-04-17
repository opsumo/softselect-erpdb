<?php
require_once("DB.Class.php");
if($_SESSION['admin_login']!="true")
{
	echo "<script>menuButtonClick('Admin', 'Logout');</script>";
}
class Industry extends DBCon
	{
function Industry()
	{
	parent::DBCon();
	switch($_REQUEST['mode'])
		{
		case "ManageIndustries":
			$this->ManageIndustries();
		break;
		
		case "NewIndustry":
			$this->NewIndustry();
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
				
		case "Cancel":
			$this->Cancel();
		break;
				
		default:
			$this->ManageIndustries();
		}
	}
	
 	function NewIndustry()
	{
		$mode = "Create";
		if(isset($_REQUEST['id']) && ($_REQUEST['id']!=""))
		{
			$mode = "Update";
			$industry_id = $_REQUEST['id'];
			$sql = "SELECT * FROM target_market WHERE market_id = '$industry_id'";
			$res = mysqli_query($this->con, $sql);
			if(!$res)
				echo mysqli_error($this->con);
			$row = mysqli_fetch_array($res);
		}
$industry_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
?>
<form action="index.php?module=Industry&mode=<?php echo $mode; ?>" method="post">
	<table align="center" width="50%">
    	<tr>
        	<td colspan="2" align="center">
            	<?php if($industry_id) {?>
                <h2>Edit Industry Details</h2>
                <?php } else { ?>
                <h2>New Industry</h2>
                <?php } ?>
            </td>
        </tr>
        <tr>
        	<td><strong>Industry Name</strong></td>
            <td>
            	<input type="text" size="35" name="industry_name" id="industry_name" <?php if($industry_id) echo "value='".$row['market_description']."'"; ?> />
            </td>
        </tr>
        <tr>
            <td nowrap><strong>Sub Industries</strong></td>
            <td nowrap>
                <textarea cols="50" rows="10" name="sub_industries" id="sub_industries"><?php echo $row['sub_industries']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap><strong>Notes</strong></td>
            <td nowrap>
                <textarea cols="50" rows="20" name="notes" id="notes"><?php echo $row['notes']; ?></textarea>
            </td>
        </tr>
        <tr>
        	<td align="center" colspan="2">
			<?php if($industry_id) { ?>
                <input type="hidden" name="industry_id" id="industry_id" value="<?php echo $row['market_id']; ?>" />
            <?php } ?>
                <input type="submit" name="btn_<?php echo $mode; ?>" id="btn_<?php echo $mode; ?>" value="<?php echo $mode; ?>" />
                <input type="button" name="btn_Cancel" id="btn_Cancel" value="Cancel" onClick="window.location='index.php?module=Industry&mode=ManageIndustries'" />
        	</td>
        </tr>
    </table>
</form>
<?php
	}
	
 	function Create()
	{
		$s = "select market_id from target_market order by market_id desc limit 0,1";
		$r = mysqli_query($this->con, $s) or die(mysqli_error($this->con));
		$row = mysqli_fetch_array($r);
		mysqli_free_result($r);
		$id = $row['market_id'] + 1;
		$sql = "INSERT INTO target_market(market_id, market_description, sub_industries, notes, status) values({$id}, '$_POST[industry_name]', '$_POST[sub_industries]', '$_POST[notes]',1)";
		
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		echo "<script>menuButtonClick('Industry', 'ManageIndustries');</script>";
	}
	
 	function Update()
	{
		$industry_id = $_REQUEST['industry_id'];
		$sql = "UPDATE target_market SET market_description='$_POST[industry_name]',  sub_industries='$_POST[sub_industries]',  notes='$_POST[notes]' WHERE market_id = '$industry_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		echo "<script>menuButtonClick('Industry', 'ManageIndustries');</script>";
	}
	
 	function ManageIndustries()
	{
		$this->template="phpfiles/manageindustries.php";
		$this->LoadFile();
	}
	
	function Delete()
	{
		$industry_id = $_REQUEST['id'];
		$sql = "DELETE FROM target_market WHERE market_id = '$industry_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		echo "<script>menuButtonClick('Industry', 'ManageIndustries');</script>";
	}

	function Cancel()
	{
		echo "<script>menuButtonClick('Industry', 'ManageIndustries');</script>";
	}
}
?>