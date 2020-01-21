<?php
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);
$i = 0;
$comsize = array();
$sql = "select cost_range_id, descript from cost_range order by display_order";
$result = mysqli_query($con, $sql);
while($rows=mysqli_fetch_array($result))
{
	$comsize[$i]['cost_range_id'] = $rows['cost_range_id'];
	$comsize[$i]['descript'] = $rows['descript'];
	$i++;
}
mysqli_free_result($result);

$ii = 0;
$industries = array();
$sql = "SELECT market_id as marketid, market_description as marketdescription 
	    FROM target_market where status <> 0 ORDER BY CASE WHEN market_id = -1 
		THEN '' ELSE market_description END";
$result1 = mysqli_query($con, $sql) or die(mysqli_error($con));
while($rows=mysqli_fetch_array($result1))
{
	$industries[$ii]['marketid'] = $rows['marketid'];
	$industries[$ii]['marketdescription'] = $rows['marketdescription'];
	$ii++;
}
mysqli_free_result($result1);
?>
<input type="hidden" id="ifsubmitted" value="0"/
<div id="query" style="width:100%;font-family: Verdana, Arial, Helvetica, sans-serif;">					
		<table width="100%">
							<tr>
								<td>
									<table border="0" id="query-table" cellspacing="0" width="100%">																				
										<tr>
											<td style="text-align:right;color:#0060a9;font-size:.8em" nowrap>Company Size:</td>
											<td>
												<select id="companysize">
													<?php foreach($comsize as $key=>$values):?>
														<option value="<?php echo $values['cost_range_id']?>"><?php echo $values['descript'];?></option>
													<?php endforeach; ?>
												</select>
											</td>								
										</tr>
										<tr>
											<td style="text-align:right;color:#0060a9;font-size:.8em" nowrap>Primary industry:</td>
											<td>
												<select id="primindustry">
													<?php foreach($industries as $key=>$values):?>
														<option value="<?php echo $values['marketid'];?>"><?php echo $values['marketdescription'];?></option>
													<?php endforeach; ?>
												</select>
											</td>								
										</tr>
										<tr>
											<td style="text-align:right;color:#0060a9;font-size:.8em" nowrap>Secondary industry:</td>
											<td>
												<select id="secindustry">
													<?php foreach($industries as $key=>$values):?>
														<option value="<?php echo $values['marketid'];?>"><?php echo $values['marketdescription'];?></option>
													<?php endforeach; ?>
												</select>
											</td>								
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>
												<input type="button" id="submitquery" value="Create ERP List" style="font-size:.8em;font-weight:bold;"><div id="loading"><img src="images/ajaxloader.gif"/></div>
											</td>
										</tr>
									</table>
								</td> <!-- left -->
									<!-- right -->
								<td valign="top">
								<div id="query-title-instruction" style="display:block;float:right;margin-top:-5px;padding:2px;">
									<p class="instruction-text">To develop your ERP list the company size value is mandatory and one or both industry values are optional.</p>
								</div>
								<div id="showpackage" style="font-size:.8em;width:350px;margin-right:7px;margin-top:-2px;float:right;">
									<table border="0" cellpadding="0" cellspacing="0" id="specific-pack-table" width="100%">
											<tr style="background-color:#7eaed4;height:25px;">
												<td style="font-weight:bold;color:#ffffff;font-size:.9em;">&nbsp;&nbsp;Add Specific ERP to Report</td>
												<td style="text-align:right;cursor:pointer;">
													<img id="showsearch" src="images/plus.png" title="Click to search for specific package"/>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<table id="specificpackage" border="0" cellpadding="0" cellspacing="0" width="100%">
													</table>
												</td>
											</tr>
									</table>
								</td>
							</tr>
					</table>
				</div>
		<div id="result" style="margin-top:1px;">&nbsp;</div>
</div>
<script>
function addpck(pname,pid)
	{
		var cnt = $('#specificpackage tr').length;
		var idpack = pid;
		//check if selected package id already selected
		if(!findIdSpecific(pid))
		{
			if(cnt<3)
			{
				if(cnt%2==0)
					var back= "#C8E3F9";
				else
					var back= "#eeeeee";
				var row = "<tr id="+pid+" style=\"background-color:"+back+";text-align:left;\"><td>"+pname+"</td><td style=\"text-align:right;cursor:pointer;\" title=\"Click to remove\"><img src=\"images/minus.gif\" onclick=\"rempck('"+pid+"')\"/></td></tr>";
				$('#specificpackage').append(row);
				$('#venpac-dialog-form').dialog( "close" );
				$('#submitquery').click();
			}
			else
			{
				alert('You have already selected three products');
				$('#venpac-dialog-form').dialog( "close" );
			}
		}
		else
		{
			alert('Package already selected');
		}
		
	}
	//function to remove row from table ,remove selected package
	function rempck(pid)
	{
		var id="#"+pid;
		$(id).remove();
		//this will automatically reload the query result,as per Mikes change 20110918
				var comsize = $('#companysize').val();
				var primindustry = $('#primindustry').val();
				var secindustry = $('#secindustry').val();
				var specificpck = getvals();
				
				if(comsize < 0)
				{
					alert("Please Select Company Size Range.");
				}
				else
				{
					$('#submitquery').click();
				}
	}
	
	function findIdSpecific(id)
	{
		var existing = false;
			
		$('#specificpackage  tr').each(function(){
		if($(this).attr('id') == id)
		{
			existing = true;			
		}			
	 	});
		return existing;
	}
	//function to retrieve ids(value) from each table,these are the selected package
	function getvals()
	{
		var ids = '';
		$('#specificpackage  tr').each(function(){
			if(ids=='')
			{
				ids += $(this).attr('id');
			}
			else
			{
				ids +=','+$(this).attr('id');
			}
		});
		return ids;
	}	
</script>