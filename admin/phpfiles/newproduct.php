<?php
include_once '../classes/config.php';
session_start();
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

//get list of cost range
$costrange = array();
$i = 0;
$sql = "select * from cost_range where cost_range_id <> -1";
$res = mysqli_query($con, $sql) or die(mysqli_error($con));
while($row = mysqli_fetch_array($res))
{
	$costrange[$i]['costrangeid'] = $row['cost_range_id'];
	$costrange[$i]['description'] = $row['descript'];
	$i++;
}
mysqli_free_result($res);
//get target Market List
$tarmarket = array();
$x = 0;
$sql1 = "select * from target_market where market_id <> -1 order by market_description asc";
$res1 = mysqli_query($con, $sql1) or die(mysqli_error($con));
while($rows = mysqli_fetch_array($res1))
{
	$tarmarket[$x]['marketid'] = $rows['market_id'];
	$tarmarket[$x]['marketdes'] = $rows['market_description'];
	$x++;
}
mysqli_free_result($res1);
$vendors = array();
$y = 0;
$sql = "select * from vendor order by vendor_name asc";
$resven = mysqli_query($con, $sql) or die(mysqli_error($con));
while($rowsven = mysqli_fetch_array($resven))
{
	$vendors[$y]['vendorid'] = $rowsven['vendor_id'];
	$vendors[$y]['vendorname'] = $rowsven['vendor_name'];
	$y++;
}
mysqli_free_result($resven);
mysqli_close($con);
//get list of vendors
?>
<table>
	<tr>
		<td><strong>Product Name:</strong></td>
		<td><input type="text" id="prodname" size="40"/></td>	
	</tr>
	<tr>
		<td><strong>Vendor Name:</strong></td>
		<td>
			<select id="vendosel">
				<?php foreach ($vendors as $k=>$v): ?>
				<option value="<?php echo $v['vendorid'];?>"><?php echo $v['vendorname'];?></option>
				<?php endforeach;?>
			</select>
		</td>	
	</tr>	
	<tr>
		<td>&nbsp;</td>
		<td><input type="button" id="addnewproduct" value="Add Product"/></td>
	</tr>	
</table>
<input type="hidden" value="" id="productid"/>
<div id="radiocorner">
	<table width="800px" border="0" cellpadding="2" cellspacing="2">
		<!--<tr>
			<td nowrap>Cost Range:</td>
			<td>
				<select id="costrange">
					<?php #foreach ($costrange as $key=>$val):?>
					<option value="<?php #echo $val['costrangeid'];?>"><?php #echo $val['description'];?></option>
					<?php #endforeach;?>
				</select>
			</td>
			<td><input type="button" id="addcostrange" value="Add" onclick="addcostrange()"/></td>
			<td>&nbsp;</td>
			<td nowrap>Industry:</td>
			<td>
				<select id="targetmarket">
					<?php #foreach ($tarmarket as $key=>$val):?>
					<option value="<?php #echo $val['marketid'];?>"><?php #echo $val['marketdes'];?></option>
					<?php #endforeach;?>
				</select>
			</td>
			<td><input type="button" id="addtargetmarket" value="Add"/></td>
		</tr>-->
		<tr>
			<td colspan="3" valign="top">
				<table id="prodcostrange" width="100%">
					<tr>
						<th align="left">Cost Range</th>
						<th>H</th>
						<th>M</th>
						<th>L</th>
						<th>N/A</th>
					</tr>
					<?php foreach ($costrange as $key=>$val): ?>
						<tr id="cos<?php echo $val['costrangeid'];?>">
							<td><?php echo $val['description'];?></td>
							<td><input type="radio" name="cos<?php echo $val['costrangeid'];?>" id="cos<?php echo $val['costrangeid'];?>'" value="1"/></td>
							<td><input type="radio" name="cos<?php echo $val['costrangeid'];?>" id="cos<?php echo $val['costrangeid'];?>" value="2"/></td>
							<td><input type="radio" name="cos<?php echo $val['costrangeid'];?>" id="cos<?php echo $val['costrangeid'];?>" value="3"/></td>
							<td><input type="radio" name="cos<?php echo $val['costrangeid'];?>" id="cos<?php echo $val['costrangeid'];?>" value="0" checked/></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</td>
			<td>&nbsp;</td>
			<td colspan="3" valign="top">
				<table id="prodmarket"  width="100%">
					<tr>
						<th align="left">Industry</th>
						<th>H</th>
						<th>M</th>
						<th>L</th>
						<th>N/A</th>
					</tr>
					<?php foreach ($tarmarket as $key=>$val):?>
						<tr id="tar<?php echo $val['marketid'];?>">
							<td><?php echo $val['marketdes'];?></td>
							<td><input type="radio" name="tar<?php echo $val['marketid'];?>" id="tar<?php echo $val['marketid'];?>" value="1" unchecked/></td>
							<td><input type="radio" name="tar<?php echo $val['marketid'];?>" id="tar<?php echo $val['marketid'];?>" value="2" unchecked/></td>
							<td><input type="radio" name="tar<?php echo $val['marketid'];?>" id="tar<?php echo $val['marketid'];?>" value="3" unchecked/></td>
							<td><input type="radio" name="tar<?php echo $val['marketid'];?>" id="tar<?php echo $val['marketid'];?>" value="0" checked/></td>
						</tr>
					<?php endforeach;?>
				</table>
			</td>
		</tr>
	</table>
</div>
<div id="save-result"></div>
<script>
$(document).ready(function(){
	$("#radiocorner").hide();
	//add product button
	$("#addnewproduct").click(function(){
		var prodname = $("#prodname").val();
		var prodRegex = /^[\w\-./@ ]{1,100}$/;
		
		if(prodname.match(prodRegex))
		{
			var data = 'phpfiles/checkprodname.php?name='+prodname;
			
			$.get(data, function(data){
				if(data == "OKAY") {
					$("#radiocorner").show();
				}
				else if(data == "DUPLICATE"){
					alert("Product name already exist.");
				}
				else {
					alert("Invalid Product name.");
				}
			});			
		}
		else
		{
			alert("Invalid Product name.");
		}
	});
	
	//new product cost range
		$("#addcostrange").click(function(){
			var selected = 0;			
			var val = $("#costrange").val();
			var id = '';
			var costrangetext = $("#costrange option:selected").text();
			$("#prodcostrange tr").each(function(){
				id = $(this).attr('id');
				if(val == id)
				{
					selected = 1;
				}
			});
			
			if(selected != 1)
			{
				var rad = '<td><input type="radio" name="cost'+val+'" id="'+val+'" value="1" "unchecked"/></td>';
					rad += '<td><input type="radio" name="cost'+val+'" id="'+val+'" value="2" "unchecked"/></td>';
					rad += '<td><input type="radio" name="cost'+val+'" id="'+val+'" value="3" "unchecked"/></td>';
					rad += '<td><input type="radio" name="cost'+val+'" id="'+val+'" value="0" "unchecked"/></td>';
				var row = "<tr id="+val+"><td>"+costrangetext+"</td>"+rad+"</tr>";
			
				$("#prodcostrange").append(row);
			}
			else
			{
				alert("Cost Range already selected.");
			}
		});
		$("#addtargetmarket").click(function(){
			var selected = 0;
			var val = $("#targetmarket").val();
			var id = '';
			var targetmarkettext = $("#targetmarket option:selected").text();
			$("#prodmarket tr").each(function(){
				id = $(this).attr('id');
				if(val == id)
				{
					selected = 1;
				}
			});
			if(selected != 1)
			{
				var rad = '<td><input type="radio" name="tar'+val+'" id="'+val+'" value="1" "unchecked"/></td>';
					rad += '<td><input type="radio" name="tar'+val+'" id="'+val+'" value="2" "unchecked"/></td>';
					rad += '<td><input type="radio" name="tar'+val+'" id="'+val+'" value="3" "unchecked"/></td>';
					rad += '<td><input type="radio" name="tar'+val+'" id="'+val+'" value="0" "unchecked"/></td>';
				var row = '<tr id="'+val+'"><td>'+targetmarkettext+'</td>'+rad+'</tr>';
				
				$("#prodmarket").append(row);
			}
			else
			{
				alert("Industry already selected.");
			}
		});
		
});
</script>