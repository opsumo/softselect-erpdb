<?php
	session_start();

	require_once("classes/DB.Class.php");
	$Obj = new DBCon();								
	if(isset($_GET['module'])){
		$getModule = $_GET['module'];
		$file_name = "classes/".$getModule.".Class.php";
		$Class_Name = $getModule; // Get the class name
		if (file_exists($file_name)) {
			require_once($file_name); // Include that file
			$Class_Obj = new $Class_Name; // Create an instance for that class
		}else {
				$err_msg = "<center> Module not found </center>";
				echo $err_msg;
		}
	} 
										
?>
