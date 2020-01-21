<script type="text/javascript">
function delete_confirm(id)
{
var r=confirm("Delete This Vendor Details?");
if (r==true)
  {
  	var url = 'index.php?module=Vendor&mode=Delete&id='+id;
	 window.location=url;

  }
else
  {
	return false;
  }
}
function deactivate_confirm1(id)
{
var r=confirm("Deactivate This Vendor?");
if (r==true)
  {
	var url="phpfiles/vendor_actdct.php?mode=deactivate&id="+id;
	$.post(url,
		function(data)
		{
			if(data === 'SUCCESS') {
				window.location.reload();
			}
			else {
				alert('There was an error updating this Vendor, please try again.');
			}
		});
	
  }
else
  {
	return false;
  }
}

function activate_confirm1(id)
{
var r=confirm("Activate This Vendor?");
if (r==true)
  {
	var url="phpfiles/vendor_actdct.php?mode=activate&id="+id;
	$.post(url,
		function(data)
		{
			if(data === 'SUCCESS') {
				window.location.reload();
			}
			else {
				alert('There was an error updating this Vendor, please try again.');
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
<?php
$vendor = isset($_REQUEST['txt_vendor'])?$_REQUEST['txt_vendor']:'';
?>
<table cellspacing="0" cellpadding="5" align="center" id="vendor-table" boder="1">	
	<tr height="60" bgcolor="#FFFFFF">
    	<td align="center" colspan="5">
			<form method="post" action="">		
            	<label><strong>Search Vendor by Name</strong>&nbsp;</label>
            	<input type="text" size="50" name="txt_vendor" id="txt_vendor" value="<?php echo $vendor;?>"/>&nbsp;
                <input type="submit" name="btn_vendor" value="Go" id="btn_vendor"/>
			</form>				
        </td>        
	</tr>
	<tr bgcolor="#FFFFFF">
		<td>Filter status:
			<select id="vstatus" onchange="reloadvendor()">
				<option value="all" <?php echo (isset($_REQUEST['stat']) && $_REQUEST['stat']=='all')?'selected':'';?>>All</option>
				<option value="active" <?php echo (isset($_REQUEST['stat']) && $_REQUEST['stat']=='active')?'selected':'';?>>Active</option>
			</select>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="center">
        	<a style="color:#000000; font-weight:bolder; text-decoration:none;" href="index.php?module=Vendor&mode=NewVendor">New Vendor</a>
        </td>
	</tr>	
<?php
	/*
	include_once('../../includes/config.inc');
	define("USER_NAME",$sqlUsr);
	define("PASS",$sqlPwd);
    define("HOST_NAME",$sqlSrvr );
	define("DB_NAME",$sqlDb);
	*/
	include('ps_pagination.php');
	//print(HOST_NAME.'-'. USER_NAME.'-'. PASS.'\n\n');
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if(!$conn) echo mysqli_error($conn)."<br>Failed to connect to database!";
    $status = mysqli_select_db($conn,DB_NAME);
	if(!$status) echo mysqli_error($conn)."<br>Failed to select database!";

	$sql = "SELECT v.*, (SELECT COUNT(*) FROM product p WHERE p.vendor_id = v.vendor_id) product_count FROM vendor v";
	if($vendor!="")
	{
		$sql = $sql." WHERE v.vendor_name LIKE '$vendor%' ORDER BY v.vendor_name ASC";
		$qs = "module=Vendor&mode=ManageVendor&txt_vendor=".$vendor;
	}
	else if(isset($_REQUEST['stat'])) {
		$status = isset($_REQUEST['stat'])?$_REQUEST['stat']:'';
		switch($status){
			case "active":
				$sql = $sql." WHERE status=1 order by vendor_name asc";
			break;
			default:
				$sql = $sql." ORDER BY vendor_name ASC";
			break;
				
		}
		$qs = "module=Vendor&mode=ManageVendor&stat=".$status;
	}
	else
	{
		$sql =  $sql." ORDER BY vendor_name ASC";
		$qs = "module=Vendor&mode=ManageVendor";
	}	
	$pager = new PS_Pagination($conn, $sql, 30, 20, $qs);
	$pager->setDebug(true);
	$rs = $pager->paginate();
	if($rs && mysqli_num_rows($rs)>0)
	{
?>
  <tr>
	<th style="padding-left:0px;">Vendor Name</th>
    <th style="padding-left:0px;">Vendor Website</th>
    <th>Products</th>
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

            $url = $row['www'];

            if (strpos($url, '://') === FALSE && $url ) {
                $url = 'http://'.$url;
            }
?>
  <tr bgcolor="<?php echo $bgcolor; ?>">
  	<td><?php echo $row['vendor_name']; ?></td>
    <td><a target="_blank" href="<?php echo $url; ?>"><?php echo $url; ?></td></a>
    <td align="center"><?php echo $row['product_count']; ?></td>
    <td align="center">
	<?php
        if($row['status']==1)
        {
    ?>
    	<a style="cursor:pointer;" onclick="deactivate_confirm1(<?php echo $row['vendor_id']; ?>)">
    		<img src="images/button_green.gif" alt="Active" border="0" />
    	</a>
    <?php    
        }
        else
        {
    ?>
    	<a style="cursor:pointer;" onclick="activate_confirm1(<?php echo $row['vendor_id']; ?>)">
    		<img src="images/button_red.gif" alt="Inactive" border="0" />
    	</a>
    <?php    
        }
    ?>
    </td> 
    <td align="center"><a href="index.php?module=Vendor&mode=NewVendor&id=<?php echo $row['vendor_id']; ?>"><img src="images/b_edit.png" border="0" alt="Edit" /></a></td>
    <td align="center"><a onClick="delete_confirm(<?php echo $row['vendor_id'];?>)"><img src="images/deleted.png" border="0" alt="Delete" /></a></td>
  </tr>
<?php		
			$j++;	
		}
?>
  <tr bgcolor="#FFFFFF">
    <td colspan="5" align="center">
        <?php echo $pager->renderFullNav(); ?>
    </td>
  </tr>
<?php
	}
	else
	{
		echo "<tr><td colspan='4'><strong>No Vendors</strong></td></tr>";
	}
?>
</table>
<script>
	function reloadvendor(){
	//alert('here');
	var e = document.getElementById('vstatus');
	var sel = e.options[e.selectedIndex].value;
	
	window.location='index.php?module=Vendor&mode=ManageVendor&stat='+sel;
	
	}
</script>