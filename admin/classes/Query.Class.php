<?php
require_once("DB.Class.php");
class Query extends DbCon
{
	function Query()
	{
		parent::DBCon();
		$this->ManageQuery();
	}
	
	function ManageQuery()
	{
		$this->template="phpfiles/managequery.php";
		$this->LoadFile();
	}
}
?>