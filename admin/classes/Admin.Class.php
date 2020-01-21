<?php
require_once("DB.Class.php");
class Admin extends DBCon
	{
function Admin()
	{
	parent::DBCon();		
	switch($_REQUEST['mode']) 
		{
		case "Login":
			$this->Login();
		break;
		
		case "Home":
			$this->Home();
		break;
		
		case "ResetPassword":
			$this->ResetPassword();
		break;
		
		case "ChangePassword":
			$this->ChangePassword();
		break;
		
		case "UpdatePassword":
			$this->UpdatePassword();
		break;
		
		case "Logout":
			session_destroy();
			echo "<script>window.location='index.php'</script>";
		break;
		
		default:
			echo "<h2 align='center'>Page Under Construction</h2>";
		}
	}
	
 	function Login()
	{
		$sql = "SELECT * FROM user WHERE email_address='$_POST[txt_uname]' 
				and user_type_code = '1' and activated = 'Y' 
				AND password = '".sha1($_POST['txt_pword'])."'";
		
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		if(mysqli_num_rows($res)==1)
		{
			$row = mysqli_fetch_array($res);
			$_SESSION['admin_login'] = "true";
			$_SESSION['user_id'] = $row['user_id'];
			echo "<script>window.location='index.php?module=Admin&mode=Home'</script>";
		}
		else
		{
			session_destroy();
			echo "<script>window.location='index.php?error=1'</script>";
		}
	}
	
	function Home()
	{
		$this->template="phpfiles/home.php";
		$this->LoadFile();
	}
	
	function ResetPassword()
	{
		function generatePassword($length=9, $strength=8) {
			$vowels = 'aeuy';
			$consonants = 'bdghjmnpqrstvz';
			if ($strength & 1) {
				$consonants .= 'BDGHJLMNPQRSTVWXZ';
			}
			if ($strength & 2) {
				$vowels .= "AEUY";
			}
			if ($strength & 4) {
				$consonants .= '23456789';
			}
			if ($strength & 8) {
				$consonants .= '@#$%';
			}
		 
			$password = '';
			$alt = time() % 2;
			for ($i = 0; $i < $length; $i++) {
				if ($alt == 1) {
					$password .= $consonants[(rand() % strlen($consonants))];
					$alt = 0;
				} else {
					$password .= $vowels[(rand() % strlen($vowels))];
					$alt = 1;
				}
			}
			return $password;
		}

		if(isset($_POST['txt_uname']))
		{
			if($_POST['txt_uname']=="")
			{
				echo "<script>window.location='index.php?module=Admin&mode=ResetPassword'</script>";
			}
			$sql = "SELECT * FROM user WHERE email_address='$_POST[txt_uname]'";
			$res = mysqli_query($this->con, $sql);
			if(!$res)
				echo mysqli_error($this->con);
			if(mysqli_num_rows($res)==1)
			{
				$row = mysqli_fetch_array($res);
				$admin_id = $row['user_id'];
				$admin_email = $row['email_address'];
				$newpass = generatePassword();
				$encrypted = sha1($newpass);
				$sql1 = "UPDATE user SET password='$encrypted' WHERE user_id='$admin_id'";
				$res1 = mysqli_query($this->con, $sql1);
				if(!$res1)
					echo mysqli_error($this->con);
				$to      = $admin_email;
				$subject = 'New Admin Password';
				$message = "New password: ".$newpass;
				$headers  = 'MIME-Version: 1.0'."\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
				$headers = 'From: marke@softselect.com'."\r\n".
					'Reply-To: marke@softselect.com'."\r\n".
					'X-Mailer: PHP/'.phpversion();
				
				mail($to, $subject, $message, $headers);
				echo "New Password sent to your email id. Please check your mail and login with new password.";
				echo "<a href='http://dev.softselect.com/exERP-Comparison-Database/admin/index.php'>Click Here</a> to Login!";
			}
		}
		else
		{
			$this->template="phpfiles/resetpassword.php";
			$this->LoadFile();
		}
	}
	
	function ChangePassword()
	{
		$this->template="phpfiles/changepassword.php";
		$this->LoadFile();
	}
	
	function UpdatePassword()
	{
		$sql = "SELECT * FROM user WHERE user_id='".$_SESSION['user_id']."'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo "Cannot Select From Admin Table<br>".mysqli_error($this->con);
		$row = mysqli_fetch_array($res);
		if($row['password']==$_POST['txt_pword1'])
		{
			$sql1 = "UPDATE user SET password='".sha1($_POST['txt_pword2'])."' WHERE user_id='".$_SESSION['user_id']."'";
			$res1 = mysqli_query($this->con, $sql1);
			if(!$res1)
			{
				echo "Cannot Update Password<br>".mysqli_error($this->con);
				echo "<script>window.location='index.php?module=Admin&mode=ChangePassword'</script>";
			}
			echo "<script>window.location='index.php?module=Admin&mode=Home'</script>";
		}
		else
		{
			echo "<script>window.location='index.php?module=Admin&mode=ChangePassword'</script>";
		}
	}
}
?>