<?php
	if($_SESSION['admin_login']!="true")
	{
        echo $_SESSION['admin_login'];
//		echo "<script>menuButtonClick('Admin', 'Logout');</script>";
	}
	if(isset($_REQUEST['btn_go']) && $_REQUEST['period']!="")
		$reportDays = $_REQUEST['period'];
	else
		$reportDays = 10;
	
	function subtractTime($months=0, $days=0, $years=0)
	{
		$totalMonths = date("m")-$months;
		$totalDays = date("d")-$days;
		$totalYears = date("Y")-$years;
		$timeStamp = mktime(0,0,0, $totalMonths, $totalDays, $totalYears);
		$myTime = date("Y-m-d", $timeStamp);
		return $myTime;
	}
	
	$datePeriod = subtractTime(0,$reportDays,0);
	
	$sql1 = "SELECT * FROM `user` WHERE register_date > '$datePeriod'";
	$res1 = mysqli_query($this->con, $sql1);
	$num1 = mysqli_num_rows($res1);
	if(mysqli_num_rows($res1))
		$num1 = mysqli_num_rows($res1);
	else
		$num1 = 0;
	
	$sql2 = "SELECT * FROM query WHERE query_date > '$datePeriod'";
	$res2 = mysqli_query($this->con, $sql2);
	if(mysqli_num_rows($res2))
		$num2 = mysqli_num_rows($res2);
	else
		$num2 = 0;
	
	$sql3 = "SELECT * FROM `user` WHERE last_login > '$datePeriod'";
	$res3 = mysqli_query($this->con, $sql3);
	$num3 = mysqli_num_rows($res3);
	if(mysqli_num_rows($res3))
		$num3 = mysqli_num_rows($res3);
	else
		$num3 = 0;
?>
<p>&nbsp;</p>
<table align="center" cellpadding="5" width="60%" id="home-table">
	<tr>
    	<td colspan="2" align="center" style="border-bottom:1px solid #ccc;background:#0060AA;color:#ffffff;">
        	<h2 align="center">Welcome to Dashboard</h2>        
        </td>
    </tr>
    <tr bgcolor="#FFFFFF">
    	<td colspan="2" align="center" style="border-bottom:1px solid #ccc;">
        	<h3>
            	Activities from <?php echo $datePeriod; ?> to <?php echo date("Y-m-d"); ?>
            </h3>
        </td>
    </tr>
    <tr>
      <form method="post">
    	<td colspan="2" align="right" style="border-bottom:1px solid #ccc;">
        	<strong>Activities within last</strong>&nbsp;
        	<input type="text" size="1" name="period" value="<?php echo $reportDays;?>" />&nbsp;<strong>days</strong>&nbsp;<input type="submit" value="Go" name="btn_go" id="btn_go"/>
        </td>
      </form>
    </tr>
    <tr bgcolor="#FFFFFF" style="border-bottom:1px solid #ccc;">
    	<td>
        	Number of users who have registered within the last <?php echo $reportDays; ?> days
        </td>
        <td>
        	<?php echo $num1; ?>
        </td>
    </tr>
    <tr style="border-bottom:1px solid #ccc;">
    	<td>
        	Number of queries that have been run within the last <?php echo $reportDays; ?> days
        </td>
        <td>
        	<?php echo $num2; ?>
        </td>
    </tr>
    <tr bgcolor="#FFFFFF">
    	<td>
        	Number of distinct logins within the last <?php echo $reportDays; ?> days
        </td>
        <td>
        	<?php echo $num3; ?>
        </td>
    </tr>
</table>