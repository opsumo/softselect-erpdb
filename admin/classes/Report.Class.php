<?php
require_once("DB.Class.php");
if($_SESSION['admin_login']!="true")
{
	echo "<script>menuButtonClick('Admin', 'Logout');</script>";
}
class Report extends DBCon
	{
function Report()
	{
	parent::DBCon();
	switch($_REQUEST['mode'])
		{
		case "ManageReport":
			$this->ManageReport();
		break;
						
		default:
			$this->ManageReport();
		}
	}
	
 	function ManageReport()
	{
		$this->template="phpfiles/managereport.php";
		$this->LoadFile();
	}
}
?>