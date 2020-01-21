<script type="text/javascript">
function delete_confirm(id)
{
var r=confirm("Delete This Company Details?");
if (r==true)
  {
  	var url = 'index.php?module=Company&mode=Delete&id='+id;
	window.location=url;
  }
else
  {
	return false;
  }
}
</script>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="5" align="center" id="company-table">
	<tr bgcolor="#FFFFFF">
    	<td colspan="4" align="right">
        	<p align="right"><a style="color:#000000; font-weight:bolder; text-decoration:none;" href="index.php?module=Company&mode=NewCompany">New Company</a></p>
        </td>
    </tr>

<?php
	include('ps_pagination.php');
	$conn = mysqli_connect(HOST_NAME, USER_NAME, PASS, DB_NAME);
	if(!$conn) echo mysqli_error($conn)."<br>Failed to connect to database!";
	// $status = mysqli_select_db(DB_NAME, $conn);
	if(!$status) echo mysqli_error($conn)."<br>Failed to select database!";
	$sql = "SELECT * FROM company ORDER BY company_size ASC";
	$pager = new PS_Pagination($conn, $sql, 10, 20, "module=Company&mode=ManageCompanies");
	$pager->setDebug(true);
	$rs = $pager->paginate();
	if($rs && mysqli_num_rows($rs)>0)
	{
?>
  <tr>
	<th>Company Size Range</th>
    <th>Company Description</th>
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
  	<td><?php echo $row['company_size']; ?></td>
    <td><?php echo $row['company_description']; ?></td>
    <td align="center"><a href="index.php?module=Company&mode=NewCompany&id=<?php echo $row['company_id']; ?>"><img src="images/b_edit.png" border="0" alt="Edit" /></a></td>
    <td align="center"><a onClick="delete_confirm(<?php echo $row['company_id'];?>)"><img src="images/deleted.png" border="0" alt="Delete" /></a></td>
  </tr>
<?php		
			$j++;	
		}
?>
  <tr>
    <td colspan="4" align="center">
        <?php echo $pager->renderFullNav(); ?>
    </td>
  </tr>
<?php
	}
	else
	{
		echo "<tr><td colspan='4'><strong>No Companies</strong></td></tr>";
	}
?>
</table>