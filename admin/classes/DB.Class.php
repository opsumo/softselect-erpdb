<?php
	// This class will create the connection
	// And do all other fuctions in a Database

    include_once("../../wp-config.php");
    define("USER_NAME",DB_USER);
    define("PASS",DB_PASSWORD);
    define("HOST_NAME",DB_HOST);
    //	define("DB_NAME",DB_NAME);

	require_once("pager.Class.php");
	require_once ("settings.Class.php");

//	include_once("HTML.Class.php");

	class DBCon  extends Settings{


		  var $host_name; // Host name leave blank if it is local host
		  var $user_name; // Database user name
		  var $password; // Database password
		  var $db_name; // Database name
		  var $table_name; // Storing table name
		  var $con; // For connection
		  var $result;
		  var $sql;
		  var $array_fields = array();
		  var $res_array = array();
		  var $insert_fields = array();
		  var $insert_values = array();
		  var $sel_condition = "";
		  var $update_fields = array();
		  var $update_values = array();
		  var $update_condition = "";
		  var $spl_cond = "";
		  var $rec_pp;
		  var $error;


		function DBCon(){
			// Initilizing
			// Creating connecton and Selecting the Database
			//$_today = date("Y-m-d");

			$_SESSION['fromdatecombos']=$this->ReturnDate('frommm','fromdd','fromyy',date("m"),date("d"),date("Y"),date("Y"),date("Y")+25);
			$_SESSION['todatecombos']=$this->ReturnDate('tomm','todd','toyy',date("m"),date("d"),date("Y"),date("Y"),date("Y")+25);

			$this->host_name = HOST_NAME;

			$this->user_name = USER_NAME;

			$this->password = PASS;

			$this->db_name = DB_NAME;

			$this->rec_pp = 5;

			$this->con = mysqli_connect($this->host_name,$this->user_name,$this->password, $this->db_name) OR $this->error_log(mysqli_error($this->con));
//			mysqli_select_db($this->db_name) OR $this->error_log(mysqli_error($this->con));


		} // End constructor

		function error_log($error){
			// Displaying the Error
			echo $error;

		}

		  function Exec(){

			// function for executing the Query
			// Parameters SQL statements
			// Return ID
			//echo $this->sql;
			$this->result = mysqli_query($this->con, $this->sql) or $this->error_log("Cannot Execute ".mysqli_error($this->con));
			return  $this->result;
		} // End function

		  function Fetch($res){

			// This function takes the result id as a parameter and returns
			// a two dimentional array contains the full data

			$return_array = array();
			$i = 0;
			$j = 0;
			while ($row = mysqli_fetch_array($res)) {
				for($j=0;$j<count($this->res_array);$j++){
					$return_array[$i][$this->res_array[$j]] = $row[$this->res_array[$j]];
				}// End for
			$i++;
			} // End while

			return $return_array;

		}// end Function

	  function  SelectSQL(){
			$this->sql = "SELECT ";
			if(count($this->array_fields) == 0)
				$this->sql .= " * ";
			else {
					// Extract fields from the array
					// And creating the SQL statements
				$i = count($this->array_fields);
				for($j=0;$j<count($this->array_fields);$j++){

					if($i == $j || $j == 0)
						$this->sql .= " ".$this->array_fields[$j];
					else {
						$this->sql .= ", ".$this->array_fields[$j];
						}

				} // End for

			}// End else

			$this->sql .= " from ".$this->table_name;
			if($this->sel_condition != ""){
				$this->sql .= "  WHERE ".$this->sel_condition;
			}
			if($this->spl_cond != "")
				$this->sql .= $this->spl_cond;
			/*echo "<br>".$this->sql."<br>";*/

			$res = $this->Exec();
			return  $res;



		} // End SelectSQL function

		  function Insert (){
			$this->sql = "INSERT INTO ".$this->table_name . "(";
			$i = count($this->insert_fields);
			for($j=0;$j<count($this->insert_fields);$j++){

				if($i == $j || $j == 0)
					$this->sql .= " ".$this->insert_fields[$j];
				else
					$this->sql .= ", ".$this->insert_fields[$j];

			}// End for

			$this->sql .= " )  VALUES (";
			$j = 1;
			foreach ($this->insert_values as $insert_key1 => $insert_val1) {
			$i = 1;

				foreach ($insert_val1 as $insert_key2 => $insert_val2) {
						//echo count($insert_val1);
						if($i == count($insert_val1) )
							$this->sql .= "'".$insert_val2."'";
						else {
							$this->sql .= "'".$insert_val2."',";
						} // End else
						$i++;
				} // End inner foreach
				$this->sql .= ")";
				//echo $j;
				if($j < count($this->insert_values))
					$this->sql .= ",(";
				$j++;
			} // End foreach
				//echo $this->sql;
			//die();
			$res = $this->Exec();
			//echo $this->sql;
			return $res;



		} // End function Insert

		  function Update(){

			$this->sql = "UPDATE ".$this->table_name." set ";
			for($i=0,$j=1;$i<count($this->update_fields);$i++,$j++){
				if($i < count($this->update_fields))
					if($this->update_fields[$j] != "")
						$t = ",";
				else
					$t = "";
				$this->sql .= $this->update_fields[$i]." = '".$this->update_values[$i]."'".$t;
			}
			if($this->update_condition != "")
				$this->sql .= " WHERE ".$this->update_condition;
			//echo  $this->sql;

			//die();
			return $res = $this->Exec();
		}

      /*New Function added to redirect to any specified page*/
	  function redirect($filename) {
         if (!headers_sent())
             header('Location: '.$filename);
         else {
             echo '<script type="text/javascript">';
             echo 'window.location.href="'.$filename.'";';
             echo '</script>';
             echo '<noscript>';
             echo '<meta http-equiv="refresh" content="0;url='.$filename.'" />';
             echo '</noscript>';
         }
      }


    /*New Function added to Generate Drop Down Dates*/
	function ReturnDate($mname='',$dname='',$yname='',$month='',$date='',$year='',$startYear=2000,$endYear=2020,$rev='')
	{
		$dtDate="";
		if($mname!=''){
			$dtDate.=	"<select name='$mname' id='$mname'>
			";
			if($month == 0)
				$dtDate.= "<option value='' selected>MM</option>
				";
			for ($i=1;$i<13;$i++)
			{
				$i=($i<10)?'0'.$i:$i;

				if($i == $month)
					$dtDate.= "<option value='$i' selected>$i</option>
					";
				else
				$dtDate.= "<option value='$i'>$i</option>
				";
			}
			$dtDate.= "</select>";
		}
		if($dname!=''){
			$dtDate.=	"<select name='$dname' id='$dname'>
			";
			if($date == 0)
				$dtDate.= "<option value='' selected>DD</option>
				";
			for ($i=1;$i<32;$i++)
			{
				$i=($i<10)?'0'.$i:$i;

				if($i == $date)
					$dtDate.= "<option value='$i' selected>$i</option>
					";
				else
				$dtDate.= "<option value='$i'>$i</option>
				";
			}
			$dtDate.= "</select>";
		}
		if($yname!=''){
			$dtDate.= "<select name='$yname' id='$yname'>
			";
			if($year == 0)
				$dtDate.= "<option value='' selected>YYYY</option>
				";

			if($rev!='')
			{
				for ($i=$endYear;$i>$startYear;$i--)
				{
					$i=($i<10)?'0'.$i:$i;

					if($i == $year)
						$dtDate.= "<option value='$i' selected>$i</option>
						";
					else
					$dtDate.= "<option value='$i'>$i</option>
					";
				}
			}
			else
			{
				for ($i=$startYear;$i<$endYear;$i++)
				{
					$i=($i<10)?'0'.$i:$i;

					if($i == $year)
						$dtDate.= "<option value='$i' selected>$i</option>
						";
					else
					$dtDate.= "<option value='$i'>$i</option>
					";
				}
			}
			$dtDate.= "</select>";
		}
		return $dtDate;
	}

	function fetchtop5(){
/*
			$this->sql="SELECT t1 . * , t2 . *
			FROM tbl_auction t1
			LEFT JOIN tbl_nurse_user t2 ON t1.auction_nurse_id = t2.user_id
			ORDER BY auction_amount DESC
			LIMIT 5 ";
*/

			$this->sql="SELECT t1. * , t2. *
			FROM tbl_bid t1
			LEFT JOIN tbl_nurse_user t2 ON t1.bid_nurse_id = t2.user_id
			ORDER BY bid_amount DESC
			LIMIT 5 ";

			$this->res_array = array("user_id","user_first_name","user_second_name","user_license_number","career_exp_mon","career_exp_yr","career_exprt","career_career_level","career_skills","user_city","user_state","user_country","bid_amount");
			$res = $this->Exec();
			$this->resultArray = $this->Fetch($res);
			/*echo '<pre>';
			print_r($this->resultArray);*/
			return $this->resultArray;
	}

		function hourlyWageIncresetop5(){
			$this->sql="SELECT t1.user_first_name, t1.user_second_name, t2.career_avg_pre_wage, max( t3.bid_amount )
			FROM tbl_nurse_user t1
			LEFT JOIN tbl_nurse_career t2 ON t1.user_id = t2.career_nurse_id
			LEFT JOIN tbl_bid t3 ON t3.bid_nurse_id = t2.career_nurse_id
			GROUP BY t1.user_id
			ORDER BY t3.bid_amount
			LIMIT 5";

			$this->res_array = array("user_first_name","user_second_name","career_avg_pre_wage","max(bid_amount)");
			$res = $this->Exec();
			$this->resultArray = $this->Fetch($res);
		/*  echo '<pre>';
			print_r($this->resultArray);*/
			return $this->resultArray;
	}


/*	function to return a array with the associative records for compatibility with the older functions
	returns --associative array containing the records in key=>value format		*/
	function dbFetchAll($strSql,$rec_pp=0,$parameters="")
	{
			if($rec_pp)				//If pagination
			{
				return $this->dbFetchAll_page($strSql,$rec_pp,$parameters);
			}
			else
			{
				$res = mysqli_query($this->con, $strSql) or die(mysqli_error($this->con));
				while($r = mysqli_fetch_array($res))
				{
					$result[] = $r;
				}
				return $result;
			}
	}/*End of function dbfetchall*/

/*	$rec_pp -> Records per page*/
	function dbFetchAll_page($strSql, $rec_pp=0,$parameters="")
	{
		if($parameters == "")
			$parameters = (isset($this->parameters)) ? $this->parameters : "";

		$rec_pp=($rec_pp>0) ? $rec_pp : $this->rec_pp;

		$res = mysqli_query($this->con, $strSql) or die(mysqli_error($this->con));
		while($r = mysqli_fetch_array($res))
		{
			$result[] = $r;
		}

		$p=new Pager;
		if($result)
		{
			if(count($result))
			{
				$num=count($result);
				//if($custPage==0)
				//{
				if(!isset($_REQUEST["page"]) || ($_REQUEST["page"]=="") )
					$_REQUEST["page"]=1;
				//}
				/*else
				{
				$_REQUEST["page"]=$custPage;
				$_GET["page"]=$custPage;
				}*/
				$limit =$rec_pp;
				$getpages=$p->findPages($num,$limit);
				if(isset($_REQUEST["page"]) and $_REQUEST["page"]>$getpages)
				{
					$_REQUEST["page"]=$getpages;
					$_GET["page"]=$getpages;
				}

				/* store page in temp session variable*/
				//$_SESSION['tempPage']=$_GET["page"];

				$start = $p->findStart($limit);
				$resultPaging=$this->dbFetchAll($strSql." Limit $start,$limit");
			}
		}
		$arrMain=array();
		/*if($maintainSearch)
		{
			if($condition!=null)
			   $arrMain[0]=$p->pageList($_REQUEST['page'],$getpages,"action=".$action."&srchString=".$condition);
		}
		else */
		$arrMain[0]=($getpages>1)?$p->pageList($_REQUEST['page'],$getpages,$parameters) : "";

		$arrMain[1]=$resultPaging;
		$arrMain[2]=count($result);

		//myPrintR($arrMain);
		return $arrMain;
		/*$arrMain[0]	-- 	string of pagination
		  $arrMain[1]	-- 	Array of records	*/

	}/*End of function dbfetchall_page*/

	function getValueByField($tblname,$element,$field,$fieldvalue){

			if(!(isset($fieldvalue)))
			{
				return "";
			}
			if($fieldvalue == "")
			{
				return "";
			}
			$this->sql="SELECT * FROM ".$tblname." WHERE ".$field."  = ".$fieldvalue;
			//echo $this->sql;
			$res=mysqli_query($this->con, $this->sql);
			$numberofres=mysqli_num_rows($res);
			if($numberofres==0)
			{
				return "";
			}
			$row=mysqli_fetch_array($res);

			return $row[$element];
	}



	} // End class



?>