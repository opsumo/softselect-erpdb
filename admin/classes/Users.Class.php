<?php
require_once("DB.Class.php");
if($_SESSION['admin_login']!="true")
{
	echo "<script>window.location='index.php?module=Admin&mode=Logout'</script>";
}
class Users extends DBCon
	{
function Users()
	{
	parent::DBCon();
	switch($_REQUEST['mode'])
		{
		case "ManageUsers":
			$this->ManageUsers();
		break;
		
		case "UsersResult":
			$this->UsersResult();
		break;
		
		case "BlockUser":
			$this->BlockUser();
		break;
		
		case "UnBlock":
			$this->UnBlock();
		break;
		
		case "Edit":
			$this->Edit();
		break;
		
		case "Update":
			$this->Update();
		break;
		
		case "Delete":
			$this->Delete();
		break;
				
		default:
			$this->ManageUsers();
		}
	}
	
 	function ManageUsers()
	{
		$this->template="phpfiles/manageusers.php";
		$this->LoadFile();
	}
	
	function UsersResult()
	{
		$this->template="phpfiles/usersresult.php";
		$this->LoadFile();
	}
	
	function BlockUser()
	{
		$user_id = $_REQUEST['id'];
		$cur_date = date("Y-m-d H:i:s");
		$sql = "UPDATE user SET block = 'Y', blocked_to = '$cur_date' WHERE user_id = '$user_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		header('Location:index.php?module=Users&mode=UsersResult&user_id='.$user_id);
	}
	
	function UnBlock()
	{
		$user_id = $_REQUEST['id'];
		$cur_date = date("Y-m-d H:i:s");
		$sql = "UPDATE user SET block = 'N', blocked_to = '$cur_date' WHERE user_id = '$user_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		header('Location:index.php?module=Users&mode=UsersResult&user_id='.$user_id);
	}
	
	function Edit()
	{
		$this->template="phpfiles/editusers.php";
		$this->LoadFile();
	}
	
	function Update()
	{
		$txt_email = $_POST['txt_email'];
		$user_id = $_POST['user_id'];
		$sql = "UPDATE user SET
		email_address = '$_POST[txt_email]',
		firm_type = '$_POST[firm_type]',
		geo_location = '$_POST[location]',
		activated = '$_POST[txt_activation]',
		user_type_code = '$_POST[user_code]'
		WHERE user_id= '$user_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		header('Location:index.php?module=Users&mode=UsersResult&user_id='.$user_id);
	}
	
	function Delete()
	{
		$user_id = $_REQUEST['id'];
		$sql = "DELETE FROM user WHERE user_id='$user_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		echo "User Data Removed From Database";
		header('Location:index.php?module=Users&mode=ManageUsers');
    	die();
	}
}
?>