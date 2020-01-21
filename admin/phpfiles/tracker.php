<?php
include '../classes/config.php';
include('ps_pagination.php');
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$from = !empty($_REQUEST['from'])?date("Y-m-d",strtotime($_REQUEST['from'])):'';
$to = !empty($_REQUEST['to'])?date("Y-m-d",strtotime($_REQUEST['to'])):'';
if($from == '' && $to == '')
	$sql = "select url, ip, dte from tracker order by dte desc limit 0,100";
else
	$sql = "select url, ip, dte from tracker where dte >= '{$from} 00:00:01' and dte <= '{$to} 59:59:59'order by dte desc";
$qs = "module=Tracker&mode=ManageTracker";
echo $_SERVER['PHP_SELF'];
/*$data = array();
$i = 0;
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
while($rows = mysqli_fetch_array($result))
{
	$data[$i]["url"] = $rows["url"];
	$data[$i]["ip"] = "<a target='_blank' href='http://whois.arin.net/rest/ip/".$rows["ip"]."'>".$rows["ip"]."</a>";
	$data[$i]["dte"] = $rows["dte"];
	$i++;
}*/
$pager = new PS_Pagination($con, $sql, 30, 20, $qs);
$pager->setDebug(true);
$rs = $pager->paginate();
if($rs && mysqli_num_rows($rs) > 0)
{?>
	<br/>
	<center>
	<table width="900" id="tracker-table" cellpadding="1" cellspacing="1">
		<tr>
			<th>File</th>
			<th>IP Address</th>
			<th>Date</th>
		</tr>
		<?php 
		$i = 0;
		while ($row=mysqli_fetch_array($rs)) {
			$back = ($i%2==0)?$back="bgcolor='#ffffff'":$back="bgcolor='#E6E6E6'";
			?>
			<tr <?php echo $back;?>>
				<td><?php echo isset($row["url"])?$row["url"]:'';?></td>
				<td><?php echo isset($row["ip"])?<a target='_blank' href='http://whois.arin.net/rest/ip/".$row['ip']."'>".$row['ip']."</a>:'';?></td>
				<td><?php echo isset($row["dte"])?$row["dte"]:'';?></td>
			</tr>
		<?php $i++; } ?>
		<tr bgcolor="#FFFFFF">
    <td colspan="3" align="center">
        <?php echo $pager->renderFullNav(); ?>
    </td>
  </tr>
	</table>
	</center>	
<?php
}
else {
	echo "<center>No result for this date selection.</center>";
}
?>