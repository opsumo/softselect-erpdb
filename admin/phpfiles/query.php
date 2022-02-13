<?php
include_once '../classes/config.php';
require_once '../sanitize.php';

$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

//if(empty($_SESSION['userid'])) die();
//db instance

$comsize = ($_REQUEST['comsize'] > 0)?stripper($_REQUEST['comsize']):0;
$pri = ($_REQUEST['pri'] > 0)?stripper($_REQUEST['pri']):-1;
$sec = ($_REQUEST['sec'] > 0)?stripper($_REQUEST['sec']):-1;
//$package = !empty($_REQUEST['spec'])?explode(",",stripper($_REQUEST['spec'])):'';
$ip_addr = $_SERVER['REMOTE_ADDR'];
$package = !empty($_REQUEST['spec'])?$_REQUEST['spec']:'';
//$prienv = ($_REQUEST['prienv'] > 0)?stripper($_REQUEST['prienv']):-1;
//$secenv = ($_REQUEST['secenv'] > 0)?stripper($_REQUEST['secenv']):-1;
//$package[0] = !empty($package[0])?$package[0]:'NULL';
//$package[1] = !empty($package[1])?$package[1]:'NULL';
//$package[2] = !empty($package[2])?$package[2]:'NULL';

$user = !empty($_SESSION['user_id'])?$_SESSION['user_id']:'1';

function getImage($id,$lvl)
{
	if($id>0 && ($lvl >= 1 && $lvl <=3))
		return '<IMG src="level'.$lvl.'.gif">';
	else
		return '';
}
/**
 * generate report
 */

		$data = array();
		$dataheader = array();
		$databody = array();
		$id = '';
		$i = 0;
		$x = 0;

			// Save query

			$sql = "insert into query (user_id,query_date,ip_address,category_id,cost_range_id,market_id1,market_id2,focus_level1,focus_level2,focus_level3,package_id_string)
					values(".$user.",NOW(),'".$ip_addr."',1,".$comsize.",".$pri.",".$sec.",1,0,0,'".$package."')";

			$insertresult = mysqli_query($con, $sql) or die(mysqli_error($con));

			// this is to get the last insert id
			$id = mysqli_insert_id($con);

			// get the header

			$sql = "SELECT	'c.description' AS CategoryDsc,
					'c.spc_definition' AS SPCDefinition,
					'c.spc_instructions' AS SPCInstructions,
					r.descript AS CostRangeDsc,
					q.market_id1,
					m1.market_description AS MarketDsc1,
					q.market_id2,
					m2.market_description AS MarketDsc2,
					q.mfg_type_id1,
					q.focus_level1,
					q.focus_level2,
					q.focus_level3,
					0 as catCount
					FROM query q
					JOIN cost_range r ON r.cost_range_id = q.cost_range_id
					JOIN target_market m1 ON m1.market_id = q.market_id1
					JOIN target_market m2 ON m2.market_id = q.market_id2
					WHERE q.query_id = ".$id;
			$res_head = mysqli_query($con, $sql) or die(mysqli_error($con));
			while($rows1 = mysqli_fetch_row($res_head))
			{
				$dataheader[$x][0] = $rows1[0];
				$dataheader[$x][1] = !empty($rows1[1])?$rows1[1]:'SPC Definition not available';
				$dataheader[$x][2] = !empty($rows1[2])?$rows1[2]:'SPC Instructions not available';
				$dataheader[$x][3] = $rows1[3];
				$dataheader[$x][4] = $rows1[4];
				$dataheader[$x][5] = $rows1[5];
				$dataheader[$x][6] = $rows1[6];
				$dataheader[$x][7] = $rows1[7];
				$dataheader[$x][8] = $rows1[8];
				$dataheader[$x][9] = $rows1[9];
				$dataheader[$x][10] = $rows1[10];
				$dataheader[$x][11] = $rows1[11];
				$dataheader[$x][12] = $rows1[12];
				$dataheader[$x][13] = $rows1[13];
				$x++;
			}
			mysqli_free_result($res_head);

			// get the body
			$sqlresult = 'SELECT  v.vendor_name AS Vendor,
                                    p.product_name AS Product,
                                    CONCAT(CASE WHEN INSTR(COALESCE(NULLIF(p.www, \'\'), v.www), \'://\') = 0 THEN \'http://\' ELSE \'\' END, COALESCE(NULLIF(p.www, \'\'), v.www)) AS website,
																		p.mtco AS multi_tenant,
                                    s.focus_level AS company_size,
                                    i1.focus_level AS primary_industry,
                                    i2.focus_level AS secondary_industry
                            FROM  product p
                            JOIN  query q ON q.query_id = '.$id.'
                            JOIN  vendor v ON p.vendor_id = v.vendor_id
                            LEFT JOIN  product_cost_range s ON s.product_id = p.product_id AND s.cost_range_id = q.cost_range_id
                            LEFT JOIN  product_market i1 ON i1.product_id = p.product_id AND i1.market_id = q.market_id1
                            LEFT JOIN  product_market i2 ON i2.product_id = p.product_id AND i2.market_id = q.market_id2
                            WHERE p.product_id IN ( SELECT pq.product_id
                                                    FROM  product pq
                                                    JOIN query q ON q.query_id = '.$id.'
                                                    JOIN product_cost_range qs ON qs.cost_range_id = q.cost_range_id AND pq.product_id = qs.product_id AND qs.focus_level BETWEEN 1 AND 3
                                                    JOIN  product_market qi1 ON ((qi1.market_id = q.market_id1 AND qi1.focus_level BETWEEN 1 AND 3) OR q.market_id1 = -1) AND pq.product_id = qi1.product_id
                                                    JOIN  product_market qi2 ON ((qi2.market_id = q.market_id2 AND qi2.focus_level BETWEEN 1 AND 3) OR q.market_id2 = -1) AND pq.product_id = qi2.product_id
                                                    WHERE pq.status = 1)
                            OR FIND_IN_SET(p.product_id, q.package_id_string) > 0
                            ORDER BY Vendor, Product';

			//echo $sqlresult;
			//echo $sqlresult;exit();
			$result = mysqli_query($con, $sqlresult) or die(mysqli_error($con));
			while($rows = mysqli_fetch_array($result))
			{
				$databody[$i][0] = $rows[0];
				$databody[$i][1] = $rows[1];
				$databody[$i][2] = $rows[2];
				$databody[$i][3] = $rows[2];
				$databody[$i][4] = getImage(1,($rows[3]==1)?1:3);//$rows[3];//''; // was category
				$databody[$i][5] = getImage(1,$rows[4]);
				$databody[$i][6] = getImage(1,$rows[5]);
				$databody[$i][7] = getImage(1,$rows[6]);
				$i++;
			}


		$data['header'] = $dataheader;
		$data['body'] = $databody;
		$data['id'] = $id;

////////////////////////////////////////
$imgpath ="<img style='vertical-align:middle;' height='11px' width='11px' src=\"images/";

if(!empty($data['header']) && !empty($data['body']))
{?>
	<table width="98%" style="margin-top:-30px;margin-left:420px;">
	  	<tr>
	   		<td style="text-align:center"><center><button id="reportprintbtn1" style="font-size:.8em;font-weight:bold;" onclick="printdata('<?php echo $data['id'];?>')"></button></center></td>
	  	</tr>
	</table>
	<center>
	<table width="100%" style="border-collapse: collapse;">
		<!-- <tr>
			<td style="font-weight:bold;" colspan="2">
				<center>ERP Comparison Database Results<br />North American ERP Market</center>
			</td>
		</tr> -->
		<tr style="background-color:#fff">
			<td style="border-top:1px #657f93 solid;border-left:1px #657f93 solid;" valign="top">
		    <p style="text-align:justify;font-size:.8em;padding:2px;margin:0;">
                This report shows ERP that EAI has determined has discernible usage history in companies matching the
                criteria in the three right columns. This information does not reflect key selection factors such as ERP vendor
                strength, ERP technology status, and the ERP's ability to meet a company's specific functional priorities.
                ‘Multi-tenant’ ERP is directly controlled by the ERP seller and customers share various resources.
            </p>
 			</td>
 			<td style="width:230px;border-top:1px #657f93 solid;border-right:1px #657f93 solid;border-left:1px #657f93 solid;font-size:.8em;padding-left: 15px;vertical-align:middle;" nowrap>
 				<u><strong>History of Use Legend</strong></u>
				<ul id="his-legend">
					<li><img src="images/legend_level1.gif" height="22" width="20"/>&nbsp;&nbsp;&nbsp;Significant activity</li>
					<li><img src="images/legend_level2.gif" height="20" width="22"/>&nbsp;&nbsp;&nbsp;Relevant activity</li>
					<li><img src="images/legend_level3.gif" height="22" width="20"/>&nbsp;&nbsp;&nbsp;Minor activity</li>
				</ul>
  			</td>
		</tr>
	</table>
	<table border="0" cellpadding="0" cellspacing="0" id="query-result-table" style="font-family:arial;font-size:8pt;width:100%;table-layout: fixed;" class="tablesorter">
		<thead>
		<tr>
		<th style="white-space:none;overflow:hidden;">Vendor</th>
		<th style="white-space:none;overflow:hidden;">Product Name</th>
		<th style="white-space:none;overflow:hidden;">Web Site</th>

		<th style="white-space:none;overflow:hidden;width:75px;vertical-align:top;">Multi-Tenant<br/> Cloud Only:<br/></th>
		<th style="white-space:none;overflow:hidden;width:63px;vertical-align:top;">Company<br/> Size:<br/><span style="color:#272626;"><?php echo $dataheader[0][3];?></span></th>
		<th style="white-space:none;overflow:hidden;width:75px;vertical-align:top;">Primary<br/> Industry:<br/><span style="color:#272626;"><?php echo $dataheader[0][5];?></span></th>
		<th style="white-space:none;overflow:hidden;width:75px;vertical-align:top;">Secondary<br/> Industry:<br/><span style="color:#272626;"><?php echo $dataheader[0][7];?></span></th>
		</tr>
		</thead>
		<tbody>
		<?php $c=0; ?>
	<?php foreach($databody as $key=>$rows): ?>
	<?php $in = (empty($rows[5]) || empty($rows[6]) || (empty($rows[7]) && $sec > 0) || $in == 1)?1:0;?>
		<tr <?php echo ($c%2==0)?'bgcolor="#ffffff"':'bgcolor="#eeeeee"';?>>
			<td style="white-space:none;overflow:hidden;"><?php echo !empty($rows[0])?$rows[0]:'';?></td>
			<td style="white-space:none;overflow:hidden;"><?php echo !empty($rows[1])?$rows[1]:'';?></td>
			<td style='vertical-align:middle;white-space:none;overflow:hidden;'><a href="<?php echo !empty($rows[3])?$rows[3]:'#';?>" target="_blank"><?php  echo !empty($rows[2])?$rows[2]:'';?></a></td>
			<td align="center" style='vertical-align:middle;'><?php echo (!empty($rows[5]) && strlen($rows[4]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[4])."\"/>":"";?></td>
			<td align="center" style='vertical-align:middle;'><?php echo (!empty($rows[5]) && strlen($rows[5]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[5])."\"/>":"";?></td>
			<td align="center" style='vertical-align:middle;'><?php echo (!empty($rows[6]) && strlen($rows[6]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[6])."\"/>":"";?></td>
			<td align="center" style='vertical-align:middle;'><?php echo (!empty($rows[7]) && strlen($rows[7]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[7])."\"/>":"";?></td>
		</tr>
		<?php $c++;?>
	<?php endforeach; ?>
	</tbody>
	</table>
	<table width="98%"><tr><td style="font-family:arial;font-size:.7em;padding-left:8px;padding-top: 5px;text-align:justify;">
				<?php if($in):?>
				* Listings that show no level of suitability for the criteria of (1) company size and/or
				(2) Industries (primary and secondary) were added by the person configuring this report.<br/><br/>
				<?php endif;?>
  </td></tr></table>
	<br/>
	    <table width="98%">
	    	<tr>
	    		<td style="text-align:center"><center><button id="reportprintbtn" style="font-size:.7em;font-weight:bold;" onclick="printdata('<?php echo $data['id'];?>')"></button></center></td>
	    	</tr>
	    </table>
	    <form id="print_form" name="print_form" action="/legacy/erp-comparison-database/SoftSelect_ERP_Database_Report.php" target="_blank" method="post">
			<input type="hidden" name="id" value="<?php echo $data['id'];?>" />
		</form>
    </center>
<script>
	$(document).ready(function(){
		$("#query-result-table").tablesorter
		({
			headers:
			{
				3: {sorter:false},
				4: {sorter:false},
				5: {sorter:false}
			}
		});
		$("#reportprintbtn").button({
			label: 'Print',
  			icons: {primary: 'ui-icon-print'}
		});
		$("#reportprintbtn1").button({
			label: 'Print',
  			icons: {primary: 'ui-icon-print'}
		});
	});
</script>
	<br/>
<?php
}
else
{?>
<br/><br/>
<br/><br/>
	<div id="nodata">Listings that show no level of suitability for the criteria of
(1) company size and (2) product manufactured, were
added by the the person configuring this report.</div>
<?php
}
?>
