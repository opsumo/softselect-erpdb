<script type="text/javascript">
function delete_confirm(id)
{
var r=confirm("Delete This Industry Details?");
if (r==true)
  {
  	var url = 'index.php?module=Industry&mode=Delete&id='+id;
	window.location=url;
  }
else
  {
	return false;
  }
}
function deactivate_confirm2(id)
{
var r=confirm("Deactivate This Industry?");
if (r==true)
  {
	var url="phpfiles/industry_actdct.php?mode=deactivate&id="+id;
	$.post(url,
		function(data)
		{
			if(data === 'SUCCESS') {
				window.location.reload();
			}
			else {
				alert('There was an error updating this Industry, please try again.');
			}
		});

  }
else
  {
	return false;
  }
}

function activate_confirm2(id)
{

var r=confirm("Activate This Industry?");
if (r==true)
  {
	var url="phpfiles/industry_actdct.php?mode=activate&id="+id;
	$.post(url,
		function(data)
		{
			if(data === 'SUCCESS') {
				window.location.reload();
			}
			else {
				alert('There was an error updating this Industry, please try again.');
			}
		});
  }
else
  {
	return false;
  }
}
</script>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="5" align="center" id="industries-table">
	<tr bgcolor="#FFFFFF">
		<td>Filter status:
			<select id="istatus" onchange="reloadindustry()">
				<option value="all" <?php echo (isset($_REQUEST['stat']) && $_REQUEST['stat']=='all')?'selected':'';?>>All</option>
				<option value="active" <?php echo (isset($_REQUEST['stat']) && $_REQUEST['stat']=='active')?'selected':'';?>>Active</option>
			</select>
		</td>
    	<td colspan="3" align="right">
        	<p align="right"><a style="color:#000000; font-weight:bolder; text-decoration:none;" href="index.php?module=Industry&mode=NewIndustry">New Industry</a></p>
        </td>
    </tr>

<?php
	include('ps_pagination.php');
	$conn = mysqli_connect(HOST_NAME, USER_NAME, PASS, DB_NAME);
	if(!$conn) echo mysqli_error($conn)."<br>Failed to connect to database!";
	// $status = mysqli_select_db(DB_NAME, $conn);
//	if(!$status) echo mysqli_error($conn)."<br>Failed to select database!";
	if(isset($_REQUEST['stat'])) {

		if($_REQUEST['stat'] === 'active') {
			$sql="SELECT market_id as marketid, market_description as marketdescription, status
				FROM target_market
				WHERE market_id <> -1 and status <> 0
				ORDER BY CASE WHEN market_id = -1
				THEN '' ELSE market_description END";
			$m = 'module=Industry&mode=ManageIndustries&stat='.$_REQUEST['stat'];
		}
		else if($_REQUEST['stat'] === 'all'){
			$sql = "SELECT market_id as marketid, market_description as marketdescription, status
			FROM target_market
			WHERE market_id <> -1
			ORDER BY CASE WHEN market_id = -1
			THEN '' ELSE market_description END";
			$m = 'module=Industry&mode=ManageIndustries&stat='.$_REQUEST['stat'];
		}
	}
	else {
	$sql = "SELECT market_id as marketid, market_description as marketdescription, status
			FROM target_market
			WHERE market_id <> -1
			ORDER BY CASE WHEN market_id = -1
			THEN '' ELSE market_description END";
			$m = "module=Industry&mode=ManageIndustries";
	}
	$pager = new PS_Pagination($conn, $sql, 10, 20, $m);
	$pager->setDebug(true);
	$rs = $pager->paginate();
	if($rs && mysqli_num_rows($rs)>0)
	{
?>
  <tr>
	<th style="text-align:left;">Industry Name</th>
	<th>Status</th>
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
  	<td><?php echo $row['marketdescription']; ?></td>
<td align="center">
	<?php
        if($row['status']==1)
        {
    ?>
    	<a style="cursor:pointer;" onclick="deactivate_confirm2(<?php echo $row['marketid']; ?>)"><img src="images/button_green.gif" alt="Active" border="0" /></a>
    <?php
        }
        else
        {
    ?>
    	<a style="cursor:pointer;" onclick="activate_confirm2(<?php echo $row['marketid']; ?>)"><img src="images/button_red.gif" alt="Inactive" border="0" /></a>
    <?php
        }
    ?>
    </td>
    <td align="center"><a href="index.php?module=Industry&mode=NewIndustry&id=<?php echo $row['marketid']; ?>"><img src="images/b_edit.png" border="0" alt="Edit" /></a></td>
    <td align="center"><a onClick="delete_confirm(<?php echo $row['marketid'];?>)"><img src="images/deleted.png" border="0" alt="Delete" /></a></td>
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
		echo "<tr><td colspan='4'><strong>No Industries</strong></td></tr>";
	}
?>
</table>
<script>
	function reloadindustry(){
	//alert('here');
	var e = document.getElementById('istatus');
	var sel = e.options[e.selectedIndex].value;

	window.location='index.php?module=Industry&mode=ManageIndustries&stat='+sel;

	}
</script>
