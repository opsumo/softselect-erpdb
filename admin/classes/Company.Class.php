<?php
require_once("DB.Class.php");
if($_SESSION['admin_login']!="true")
{
	echo "<script>menuButtonClick('Admin', 'Logout');</script>";
}
class Company extends DBCon
	{
function Company()
	{
	parent::DBCon();
	switch($_REQUEST['mode'])
		{
		case "ManageCompanies":
			$this->ManageCompanies();
		break;
		
		case "NewCompany":
			$this->NewCompany();
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
				
		default:
			$this->ManageCompanies();
		}
	}
	
 	function NewCompany()
	{
		$mode = "Create";
		if(isset($_REQUEST['id']) && ($_REQUEST['id']!=""))
		{
			$mode = "Update";
			$company_id = $_REQUEST['id'];
			$sql = "SELECT * FROM company WHERE company_id = '$company_id'";
			$res = mysqli_query($this->con, $sql);
			if(!$res)
				echo mysqli_error($this->con);
			$row = mysqli_fetch_array($res);
		}
?>
<form action="index.php?module=Company&mode=<?php echo $mode; ?>" method="post">
	<table align="center" width="50%">
    	<tr>
        	<td colspan="2" align="center">
            	<?php if($company_id) {?>
                <h2>Edit Company Details</h2>
                <?php } else { ?>
                <h2>New Company</h2>
                <?php } ?>
            </td>
        </tr>
        <tr>
        	<td><strong>Company Size Range</strong></td>
            <td>
            	<input type="text" name="company_size" id="company_size" <?php if($company_id) echo "value='".$row['company_size']."'"; ?> />
            </td>
        </tr>
        <tr>
            <td><strong>Company Description</strong></td>
            <td>
            	<textarea cols="30" rows="5" name="company_desc" id="company_desc"><?php if($company_id) echo $row['company_description']; ?></textarea>
            </td>
        </tr>
        <tr>
        	<td align="center" colspan="2">
			<?php if($company_id) { ?>
                <input type="hidden" name="company_id" id="company_id" value="<?php echo $row['company_id']; ?>" />
            <?php } ?>
                <input type="submit" name="btn_<?php echo $mode; ?>" id="btn_<?php echo $mode; ?>" value="<?php echo $mode; ?>" />
        	</td>
        </tr>
    </table>
</form>
<?php
	}
	
 	function Create()
	{
		$sql = "INSERT INTO company SET company_size='$_POST[company_size]', company_description='$_POST[company_desc]'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		header('Location:index.php?module=Company&mode=ManageCompanies');
	}
	
 	function Update()
	{
		$company_id = $_REQUEST['company_id'];
		$sql = "UPDATE company SET company_size='$_POST[company_size]', company_description='$_POST[company_desc]' WHERE company_id = '$company_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		header('Location:index.php?module=Company&mode=ManageCompanies');
	}
	
 	function ManageCompanies()
	{
		$this->template="phpfiles/managecompanies.php";
		$this->LoadFile();
	}
	
	function Delete()
	{
		$company_id = $_REQUEST['id'];
		$sql = "DELETE FROM company WHERE company_id = '$company_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		header('Location:index.php?module=Company&mode=ManageCompanies');
	}
}
?>