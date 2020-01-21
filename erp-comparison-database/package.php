<?php
//include_once('includes/session.php');
include_once('includes/functionsdb.php');
include_once('includes/sanitize.php');
//session_start();

//if(empty($_SESSION['userid'])) die();
//db instance
$db = new softselect();

//get passed variables
$vname = !empty($_REQUEST['vname'])?stripper($_REQUEST['vname']):'';
$pname = !empty($_REQUEST['pname'])?stripper($_REQUEST['pname']):'';
$package = $db->getPackage($vname,$pname);
$db->dbclose();
if(!empty($package))
{?>
	<table border="0" cellpadding="0" cellspacing="0" id="query-result-table1" class="tablesorter">
		<thead>
			<tr>
				<th style="font-size:8pt">Vendor</th>
				<th style="font-size:8pt">Product Name</th>
				<th style="font-size:8pt">Web Site</th>		
				<th style="font-size:8pt">Add</th>		
			</tr>
		</thead>
		<tbody>
	<?php foreach($package as $key=>$values):?>
		<tr>
			<td style="font-size:8pt"><?php echo $values['vendor_name'];?></td>
			<td style="font-size:8pt"><?php echo $values['product_name'];?></td>
			<td style="font-size:8pt"><?php echo $values['www'];?></td>			
			<td align="center" style="cursor:pointer;"><img src="images/plus.png" onclick="addpck('<?php echo str_replace("'","",$values['product_name']);?>','<?php echo $values['product_id'];?>')" title="Click to add"/></td>			
		</tr>		
	<?php endforeach; ?>
		</tbody>
	</table>
<?php
}
else
{?>
	<span id="nodata">0 results for this ERP keyword.</span>
<?php	
}
?>
<script>
	$(document).ready(function(){		
		$("#query-result-table1").tablesorter
		({
			headers: 
			{
				2: {sorter:false},
				3: {sorter:false}
			}
		});
	});	
</script>