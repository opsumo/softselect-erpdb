<script type="text/javascript">
function delete_confirm(id)
{
var r=confirm("Delete This Blocked IP Details?");
if (r==true)
  {
  	var url = 'index.php?module=BlockIP&mode=Delete&id='+id;
	window.location=url;
  }
else
  {
	return false;
  }
}
</script>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="5" align="center" id="block-table">
	<tr bgcolor="#FFFFFF">
    	<td colspan="5" align="right">
        	<p align="right"><a style="color:#000000; font-weight:bolder; text-decoration:none;" href="index.php?module=BlockIP&mode=NewBlockIP">New IP to Block</a></p>
        </td>
    </tr>

<?php
	include('ps_pagination.php');
	$conn = mysqli_connect(HOST_NAME, USER_NAME, PASS, DB_NAME);
	if(!$conn) echo mysqli_error($conn)."<br>Failed to connect to database!";
	// $status = mysqli_select_db(DB_NAME, $conn);
//	if(!$status) echo mysqli_error($conn)."<br>Failed to select database!";
	$sql = "SELECT * FROM blacklist ORDER BY blacklist_date desc";
	$pager = new PS_Pagination($conn, $sql, 10, 20, "module=BlockIP&mode=ManageBlockedIP");
	$pager->setDebug(true);
	$rs = $pager->paginate();
	if($rs && mysqli_num_rows($rs)>0)
	{
?>
  <tr>
	<th>Blocked IP Address</th>
    <th>IP Blocked Date</th>
    <th>Last Login Attempt</th>
    <th>Edit</th>
    <th>Delete</th>
  </tr>
<?php
		$j = 0;
		while($row=mysqli_fetch_array($rs))
		{
			if(($j%2)==0)
				$bgcolor = "#FFFFFF";
			else
				$bgcolor = "#E6E6E6";
?>
  <tr bgcolor="<?php echo $bgcolor; ?>">
  	<td><?php echo $row['blacklist_ip']; ?></td>
    <td><?php echo $row['blacklist_date']; ?></td>
    <td><?php echo $row['blacklist_last_log']; ?></td>
    <td align="center"><a href="index.php?module=BlockIP&mode=NewBlockIP&id=<?php echo $row['blacklist_id']; ?>"><img src="images/b_edit.png" border="0" alt="Edit" /></a></td>
    <td align="center"><a onClick="delete_confirm(<?php echo $row['blacklist_id'];?>)"><img src="images/deleted.png" border="0" alt="Delete" /></a></td>
  </tr>
<?php		
			$j++;	
		}
?>
  <tr>
    <td colspan="5" align="center">
        <?php echo $pager->renderFullNav(); ?>
    </td>
  </tr>
<?php
	}
	else
	{
		echo "<tr><td colspan='5'><strong>No Blocked IP</strong></td></tr>";
	}
?>
</table>