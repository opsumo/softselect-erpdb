<?php
include_once '../classes/config.php';
require_once '../sanitize.php';
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

//get passed variables
$vname = !empty($_REQUEST['vname'])?stripper($_REQUEST['vname']):'';
$pname = !empty($_REQUEST['pname'])?stripper($_REQUEST['pname']):'';
$package = array();
$sql = 'select v.vendor_name as vendor_name, p.product_name as product_name, v.www, p.product_id as product_id from vendor v join product p on p.vendor_id = v.vendor_id ';

		if(!empty($vname) && empty($pname))
		{
			$sql .= "where v.vendor_name like '%".$vname."%' ";
		}
		if(!empty($pname) && empty($vname))
		{
			$sql .= "where p.product_name like '%".$pname."%' ";
		}
		if(!empty($vname) && !empty($pname))
		{
			$sql .= "where v.vendor_name like '%".$vname."%' or p.product_name like '%".$pname."%' ";
		}
		$sql .= " and v.vendor_id <> 0 and p.product_id <> 0  and v.status = 1 and p.status = 1 order by v.vendor_name, p.product_name";

		
		$i = 0;
		$result = mysqli_query($con, $sql) or die(mysqli_error($con));
			while($rows = mysqli_fetch_array($result))
			{
				$package[$i]['vendor_name'] = $rows['vendor_name'];
				$package[$i]['product_name'] = $rows['product_name'];
				$package[$i]['www'] = $rows['www'];
				$package[$i]['product_id'] = $rows['product_id'];
				$i++;
			}
			mysqli_free_result($result);

if(!empty($package))
{?>
	<table cellpadding="0" cellspacing="0" width="100%" id="query-result-table">
		<tr>
			<th style="font-size:1em">Vendor</th>
			<th style="font-size:1em">Product Name</th>
			<th style="font-size:1em">Web Site</th>		
			<th style="font-size:1em">Add</th>		
		</tr>
<?php $j=0;?>		
	<?php foreach($package as $key=>$values):?>
	<?php
		
		if(($j%2)==0)
				$bgcolor = "#FFFFFF";
			else
				$bgcolor = "#E6E6E6";
	?>
		<tr bgcolor="<?php echo $bgcolor;?>">
			<td style="font-size:8pt"><?php echo $values['vendor_name'];?></td>
			<td style="font-size:8pt"><?php echo $values['product_name'];?></td>
			<td style="font-size:8pt"><?php echo $values['www'];?></td>			
			<td align="center" style="cursor:pointer;"><img src="images/plus.png" onclick="addpck('<?php echo str_replace("'","",$values['product_name']);?>','<?php echo $values['product_id'];?>')" title="Click to add"/></td>			
		</tr>
	<?php endforeach; ?>
	</table>
<?php
}
else
{?>
	<span id="nodata">0 results for this ERP keyword.</span>
<?php	
}
