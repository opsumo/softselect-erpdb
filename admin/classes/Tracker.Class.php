<?php
require_once("DB.Class.php");
class Tracker extends DbCon
{
	function Tracker()
	{
		parent::DBCon();
		$this->ManageTracker();
	}
	
	function ManageTracker()
	{
		$this->template="phpfiles/managetracker.php";
		$this->LoadFile();
	}
}
?>