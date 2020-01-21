<?php
include_once '../classes/config.php';
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$rad = array(1=>"H",2=>"M",3=>"L",0=>"N");
//get the product details
$product_id = !empty($_REQUEST['id'])?$_REQUEST['id']:'';

if (!empty($product_id)) {
	$sql = "select product_name, vendor_id, review_date, www, notes from product where product_id = ".$product_id;
	$res = mysqli_query($con, $sql) or die(mysqli_error($con));
	while($row = mysqli_fetch_array($res))
	{
		$productname = $row['product_name'];
		$vendor_id = $row['vendor_id'];
		$review_date = (''==$row['review_date']||empty($row['review_date']))?'':date('m/d/Y', strtotime($row['review_date']));
        $www = $row['www'];
        $notes = $row['notes'];
	}
	mysqli_free_result($res);
} else {
	$productname = '';
	$vendor_id = '';
	$review_date = '';
	$www = '';
	$notes = '';
}
//getting the cost range
$i = 0;
$prodcostrange = array();
if (!empty($product_id)) {
	$sql = "select c.cost_range_id,c.descript,p.focus_level from cost_range c
			 inner join product_cost_range p on p.cost_range_id=c.cost_range_id 
			 where c.cost_range_id <> -1 and p.product_id=".$product_id;
} else {
	$sql = "select c.cost_range_id,c.descript,0 focus_level from cost_range c where c.cost_range_id <> -1";
}
$res1 = mysqli_query($con, $sql) or die(mysqli_error($con));
while($row1 = mysqli_fetch_array($res1))
{
	$prodcostrange[$i]['costrangeid'] = $row1['cost_range_id'];
	$prodcostrange[$i]['description'] = $row1['descript'];
	$prodcostrange[$i]['focuslevel'] = $row1['focus_level'];
	$i++;
}
mysqli_free_result($res1);

//getting the market
$x = 0;
$prodmarket = array();
if (!empty($product_id)) {
	$sql = "select m.market_id,m.market_description,p.focus_level from target_market m
			inner join product_market p on p.market_id=m.market_id where 
			m.market_id <> -1 and m.status = 1 and p.product_id=".$product_id." order by market_description asc";
} else {
	$sql = "select m.market_id,m.market_description,0 focus_level from target_market m 
			where m.market_id <> -1 and m.status = 1 order by market_description asc";
}		
$res2 = mysqli_query($con, $sql) or die(mysqli_error($con));
while($row2 = mysqli_fetch_array($res2))
{
	$prodmarket[$row2['market_id']]['marketid'] = $row2['market_id'];
	$prodmarket[$row2['market_id']]['marketdes'] = $row2['market_description'];
	$prodmarket[$row2['market_id']]['focuslevel'] = $row2['focus_level'];
}
mysqli_free_result($res2);
//verndors
$vendors = array();
$y = 0;
$sql = "select vendor_id, vendor_name from vendor order by vendor_name asc";
$resven = mysqli_query($con, $sql) or die(mysqli_error($con));
while($rowsven = mysqli_fetch_array($resven))
{
	$vendors[$y]['vendorid'] = $rowsven['vendor_id'];
	$vendors[$y]['vendorname'] = $rowsven['vendor_name'];
	$y++;
}
mysqli_free_result($resven);

/*
//product market
$marketlist = array();
$sql = "select market_id, market_description from target_market where market_id <> -1 and status = 1 order by market_description asc";
$resultmarket = mysqli_query($con, $sql) or die(mysqli_error($con));
$marcnt = 0;
while($marketrow=mysqli_fetch_array($resultmarket))
{
	$marketlist[$marcnt]['marketid'] = $marketrow['market_id'] ;
	$marketlist[$marcnt]['marketdes'] = $marketrow['market_description'] ;
	$marcnt++;
}
mysqli_free_result($resultmarket);
*/

//cost range
$costrangelist = array();
$sql = "select cost_range_id, descript from cost_range where cost_range_id <> -1";
$rescostrange = mysqli_query($con, $sql) or die(mysqli_error($con));
$coscnt = 0;
while($cosrow=mysqli_fetch_array($rescostrange))
{
	$costrangelist[$coscnt]['costrangeid'] = $cosrow['cost_range_id'];
	$costrangelist[$coscnt]['description'] = $cosrow['descript'];
	$coscnt++;
}
mysqli_free_result($rescostrange);

//list of industries
$target_market_list = array();
$sql = "select market_id,market_description
		from target_market where status=1
		and market_id <> -1 order by market_description";
$tcount = 0;
$restml = mysqli_query($con, $sql) or die(mysqli_error($con));
while($trow = mysqli_fetch_array($restml))
{
	$target_market_list[$tcount]['mid'] = $trow['market_id'];
	$target_market_list[$tcount]['md'] = $trow['market_description'];
	$target_market_list[$tcount]['fl'] = isset($prodmarket[$trow['market_id']])?$prodmarket[$trow['market_id']]['focuslevel']:0;
	$tcount++;
}
mysqli_free_result($restml);
?>
<input type="hidden" id="productid" value="<?php echo $product_id;?>"/>
<table style="font-size:8pt;" width="800px" border="0" cellpadding="2" cellspacing="2">
	<tr>
		<td><strong>Product Name:&nbsp;</strong>
			<input type="text" size="44" name="product_name" id="product_name" value="<?php echo $productname;?>" />
		</td>
		<td><strong>Vendor:&nbsp;</strong>
			<select id="vendors">
				<?php 
					foreach($vendors as $kven=>$valven):
						$option_selected = ($valven['vendorid']==$vendor_id)?'selected="selected" ':'';
						echo '<option '.$option_selected.'value="'.$valven['vendorid'].'">'.$valven['vendorname'].'</option>';
					endforeach; 
				?>
			</select>
		</td>
    </tr>
    <tr>
        <td colspan="2">URL: <input type="text" size="108" name="www" id="www" value="<?php echo $www;?>" /><hr /></td>
    </tr>
	<tr>
		<td valign="top">
			<!--Cost range table-->
			<table id="prodcostrange" border="0" width="100%">
				<tr>
					<td nowrap>Cost Range:</td>
					<td colspan="3">
						<select id="costrangelist">
							<?php foreach($costrangelist as $key=>$values):?>
							<option value="<?php echo $values['costrangeid'];?>"><?php echo $values['description'];?></option>
							<?php endforeach;?>
						</select>
					</td>
					<td><input type="button" id="costrangeadd" value="Add"/></td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<th>H</th>
					<th>M</th>
					<th>L</th>
					<th>N/A</th>
					<th>&nbsp;</th>	
				</tr>
				<?php foreach ($prodcostrange as $key=>$values):?>
				<tr id="cos<?php echo $values['costrangeid'];?>">
					<td nowrap><?php echo $values['description'];?></td>					
						<?php foreach($rad as $k=>$v): ?>
							<td align="center">
								<input type="radio" name="cos<?php echo $values['costrangeid'];?>" id="cos<?php echo $values['costrangeid'];?>" value="<?php echo $k;?>" <?php echo ($k==$values['focuslevel'])?'checked':'unchecked';?>/>
							</td>
						<?php endforeach;?>
						<td><input type="button" value="X" onclick="delprodcostrange(<?php echo $values['costrangeid'];?>)" alt="remove"/></td>			
				</tr>
				<?php endforeach;?>
				<tr><td colspan="6">&nbsp;</td></tr>
				<tr><th colspan="6">&nbsp;</th></tr>
				<tr><td colspan="6">&nbsp;</td></tr>
				<!--End Cost Range table-->
                <tr><td colspan="6">Review Date: <input type="text" name="review_date" id="review_date" value="<?php echo $review_date;?>" /></td></tr>
                <tr><td colspan="6">&nbsp;</td></tr>
				<tr><td colspan="6">Notes:<br />
				<textarea rows="21" cols="58" name="notes" id="notes"><?php echo $notes;?></textarea></td></tr>
			</table>
		</td>
		<td valign="top">
			<!--Market-->
			<table id="prodmarket" border="0" width="100%">
				<tr>
					<td>Industry:</td>
					<td colspan="3">
						<select id="productmarketlist">
							<?php foreach($target_market_list as $key=>$values):?>
							<option value="<?php echo $values['mid'];?>"><?php echo $values['md'];?></option>
							<?php endforeach;?>
						</select>
					</td>
					<td><input type="button" id="addnewmarket" value="Add"/></td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<th>H</th>
					<th>M</th>
					<th>L</th>
					<th>N/A</th>
					<th>&nbsp;</th>					
				</tr>
				<?php foreach ($target_market_list as $key=>$values):?>
				<tr id="tar<?php echo $values['mid'];?>">
					<td nowrap><?php echo $values['md'];?></td>
					<?php foreach($rad as $k=>$v): ?>
							<td align="center">
								<input type="radio" name="tar<?php echo $values['mid'];?>" id="tar<?php echo $values['mid'];?>" value="<?php echo $k;?>" <?php echo ($k==$values['fl'])?'checked':'unchecked';?>/>
							</td>
						<?php endforeach;?>
						<td><input type="button" value="X" alt="remove" onclick="delproductmarket(<?php echo $values['mid'];?>)"/></td>	
				</tr>
				<?php endforeach;?>
			</table>
			<!--End Market-->
		</td>
	</tr>
</table>
<script>
//add product market
		$("#addnewmarket").click(function(){
			var tmarketid = $("#productmarketlist option:selected").val();
			var tmarketname = $("#productmarketlist option:selected").text();
			var productid = $("#productid").val();
			
			if(!findIdMarket('tar'+tmarketid))
			{
				var row = "<tr id='tar" +tmarketid+"'>";
				row += "<td nowrap>"+tmarketname+"</td>";
				row += "<td align=center><input type='radio' name='tar"+tmarketid+"' id='tar"+tmarketid+"' value='1'/>";
				row += "<td align=center><input type='radio' name='tar"+tmarketid+"' id='tar"+tmarketid+"' value='2'/>";
				row += "<td align=center><input type='radio' name='tar"+tmarketid+"' id='tar"+tmarketid+"' value='3'/>";
				row += "<td align=center><input type='radio' name='tar"+tmarketid+"' id='tar"+tmarketid+"' value='0' checked/>";
				row += "<td><input type='button' value='X' onclick='delproductmarket("+tmarketid+")'/></td>";
				row +="</tr>";
				
				/*$.getJSON("phpfiles/addproductmarket.php",{prodid:productid,marketid:tmarketid},function(jsondata){
					if("SUCCESS"==jsondata.message)
					{
						$("#prodmarket").append(row);
					}
					else 
					{
							alert("There was a problem adding new market, please try again.");
					}
				});*/
				var data = 'phpfiles/addproductmarket.php?prodid='+productid+'&marketid='+tmarketid;
				$.get(data, function(data){
					if(data == "SUCCESS") {
						$("#prodmarket").append(row);
					}
					else{
						alert("There was a problem adding new market, please try again.");
					}
				});						
			}
			else
			{
				alert(tmarketname + " already exist, please select another market.");
			}
		});
		$("#costrangeadd").click(function(){
			
			var costid = $("#costrangelist option:selected").val();
			var costtname = $("#costrangelist option:selected").text();
			var productid = $("#productid").val();
			
			if(!findIdCostRange('cos'+costid))
			{
				var row = "<tr id='cos"+costid+"'>";
					row += "<td nowrap>"+costtname+"</td>";
					row += "<td align=center><input type='radio' name='cos"+costid+"' id='cos"+costid+"' value='1'/>";
					row += "<td align=center><input type='radio' name='cos"+costid+"' id='cos"+costid+"' value='2'/>";
					row += "<td align=center><input type='radio' name='cos"+costid+"' id='cos"+costid+"' value='3'/>";
					row += "<td align=center><input type='radio' name='cos"+costid+"' id='cos"+costid+"' value='0' checked/>";
					row += "<td><input type='button' value='X' onclick='delprodcostrange("+costid+")'/></td>";
					row +="</tr>";
					
					/*$.getJSON("phpfiles/addprodcostrange.php",{prodid:productid,costid:costid},function(jsondata){
						if('SUCCESS'==jsondata.message)
						{
							$("#prodcostrange").append(row);
						}
						else
						{
							alert("There was a problem adding new Cost Range, please try again.");
						}
					});	*/
					var data = 'phpfiles/addprodcostrange.php?prodid='+productid+'&costid='+costid;
					$.get(data, function(data){
					if(data == "SUCCESS") {
						$("#prodcostrange").append(row);
					}
					else{
						alert("There was a problem adding new Cost Range, please try again.");
					}
			});		
			}
			else
			{
				alert(costtname + " already exist, please select another Cost Range.");
			}
		});
		//change the vendor assigned for a product
		$("#showvendor").click(function(){
			var vendorid = $("#vendors option:selected").val();
			var productid = $("#productid").val();
			/*$.getJSON("phpfiles/updateprodvendor.php",{prodid:productid,vendorid:vendorid},function(jsondata){
					if("SUCCESS"==jsondata.message)
					{
						$("#product-dialog").dialog("close");
						
						$("#product-dialog").dialog("open");
						var data = 'phpfiles/productdetails.php?id='+productid;
						
						$.ajax({
								url: data,  
								type: "POST", 
								cache: false,
								success: function (html) {
									$('#product-edit').html(html);
									$('#product-edit').fadeIn('slow');       
								}       
							});		
					}
					else 
					{
						alert("There was a problem updating this product, please try again.");
					}
				});*/
			var data = 'phpfiles/updateprodvendor.php?prodid='+productid+'&vendorid='+vendorid;
			$.get(data, function(data){
					if(data == "SUCCESS") {
						$("#product-dialog").dialog("close");
						
						$("#product-dialog").dialog("open");
						var data = 'phpfiles/productdetails.php?id='+productid;
						
						$.ajax({
								url: data,  
								type: "POST", 
								cache: false,
								success: function (html) {
									$('#product-edit').html(html);
									$('#product-edit').fadeIn('slow');       
								}       
							});
					}
					else{
						alert("There was a problem updating this product, please try again.");
					}
			});	
		});
		
		//will remove a selected product target_market from db as well as in the screen
		function delproductmarket(marketid)
		{
			var marketid = marketid;
			var productid = $("#productid").val();
			
			var id="#tar"+marketid;
			
			/*$.getJSON("phpfiles/delproductmarket.php",{prodid:productid,marketid:marketid},function(jsondata){
					if("SUCCESS"==jsondata.message)
					{
						$(id).remove();
					}
					else 
					{
						alert("There was a problem removing new market, please try again.");
					}
				});*/
			var data = 'phpfiles/delproductmarket.php?prodid='+productid+'&marketid='+marketid;
			$.get(data, function(data){
					if(data == "SUCCESS") {
						$(id).remove();
					}
					else{
						alert("There was a problem removing new market, please try again.");
					}
			});		
		}
		
		//will remove a selected product cost_range from db as well as in the screen
		function delprodcostrange(costid)
		{
			var costid = costid;
			var productid = $("#productid").val();
			var id="#cos"+costid;
			
			/*$.getJSON("phpfiles/delprodcostrange.php",{prodid:productid,costid:costid},function(jsondata){
						if('SUCCESS'==jsondata.message)
						{
							$(id).remove();
						}
						else
						{
							alert("There was a problem removing new Cost Range, please try again.");
						}
					});*/
			var data = 'phpfiles/delprodcostrange.php?prodid='+productid+'&costid='+costid;
			$.get(data, function(data){
					if(data == "SUCCESS") {
						$(id).remove();
					}
					else{
						alert("There was a problem removing new Cost Range, please try again.");
					}
			});					
		}
		
		//this function will check if a market already selected or added,will return true if existing
		function findIdMarket(id)
		{
			var existing = false;
			$('#prodmarket  tr').each(function(){
				if($(this).attr('id') == id)
				{
					existing = true;			
				}			
			});
			return existing;
		}
		
		//this function will check if a Cost Range already selected or added,will return true if existing
		function findIdCostRange(id)
		{
			var existing = false;
			
			$('#prodcostrange  tr').each(function(){
				if($(this).attr('id') == id)
				{
					existing = true;			
				}			
			});
			return existing;
		}
</script>
<div id="save-result"></div>