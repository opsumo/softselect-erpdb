<?php
include_once '../classes/config.php';
require_once '../sanitize.php';
session_start();

if(empty($_SESSION['user_id'])) die();
//db instance
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$id = !empty($_REQUEST['id'])?stripper($_REQUEST['id']):'';

$result = getReportForPrint($id);
$imgpath ="<img style='vertical-align:middle;' height='11px' width='11px' src=\"../images/";
function getImage($id,$lvl)
{
	if($id>0 && ($lvl >= 1 && $lvl <=3))
		return '<IMG src="level'.$lvl.'.gif">';
	else
		return '';
}
function getReportForPrint($id)
	{
		$data = array();
		$dataheader = array();
		$databody = array();	
		$i = 0;
		$x = 0;
		
			/**
			 * update query,set printed column to true
			 */
			$sqlupdate = "update query set printed = 1 where query_id=".$id;
			mysqli_query($con, $sqlupdate) or die(mysqli_error($con));
			
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
                                    v.www AS website, 
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
				$databody[$i][4] = ''; // was category
				$databody[$i][5] = getImage(1,$rows[3]);
				$databody[$i][6] = getImage(1,$rows[4]);
				$databody[$i][7] = getImage(1,$rows[5]);	
				$i++;				
			}

		$data['header'] = $dataheader;
		$data['body'] = $databody;
		$data['id'] = $id;
		return $data;
	}
?>
<html>
	<head>
		<title>SoftSelect ERP Database</title>
	<script type="text/javascript">
		function alertForLandscape() {
	        alert("For best printing results, please adjust your printer properties to 'Landscape'.\n(Print dialogue box will open after selecting the 'OK' button).");
	        return;
	    }	
		function printme()
		{
			window.print();
		}
	</script>
	<style type="text/css">
body {
	font: 100% Arial, Helvetica, sans-serif;
	font-size: 80.5%;
	margin: 0;
	padding: 0;
	color: #000000;
	background-color: #ffffff;
	background-repeat: repeat-x;
}
table {
	border-collapse:collapse;
	font: 100% Arial, Helvetica, sans-serif;
}
#pagecontainer {
	float:left;
}
#top-banner,#pagebody {
	width: 100%;
	float:left;
}
#top-banner img {
	float:left;
	border: 0;
}
#query-table {
	padding: .6em;
}
#query-table td,#history-form-table td,#usercrud-table td{
	text-align: left;
}
.ui-tabs .ui-tabs-nav {
    margin: 0;
    padding: 0;
}
.ui-widget-header {
	background:url("images/v.gif") repeat-x scroll 50% 50% #CCCCCC;
}
#query-result,#history-result {
	padding-top: 10px;
}
#query-result-table,#historytable,#userlist-result-table {
	border:1px solid #000000;
}
#query-result-table td, #query-result-table th,#historytable td,#historytable th ,#userlist-result-table td,#userlist-result-table th
{

	border:1px solid #000000;
	padding:3px 7px 2px 7px;
}
#query-result-table th,#historytable th,#userlist-result-table th
{
	text-align:left;
	padding-top:5px;
	padding-bottom:4px;
	background-color:#000000;
	color:#ffffff;
}
#pagefooter {
	clear: both;
	background-color: #ffffff;
	text-decoration: none;	
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 5px;
	padding-left: 0px;
	margin-top: 18px;
	margin-right: 22px;
	margin-bottom: 0px;
	margin-left: 0px;
	width: 100%;
	height: 20px;
	font-size:9pt;
	color: #000000;
}
#pagefooter ul li {
	display: inline;
}
#pagefooter ul li a {
	text-decoration: none;
	float: left;
	padding-right: 10px;
	padding-left: 10px;
	color: #ffffff;
	background-color: #000000;
	border-left-width: 1px;
	border-left-style: dotted;
	border-left-color: #ffffff;
	padding-top: 0px;
	margin-top: 6px;
}
#specific-pack-table {
	margin: .5em 0; border-collapse: collapse; width: 100%;
	padding: 5px;
}
.ui-widget-content {
	border: 0;
}
</style>
	</head>
<body>
	<?php
if(!empty($result['header']))
{?>
	<table width="100%">
		<tr>
			<td style="font-weight:bold;" colspan="2">
				<center>ERP Comparison Database Results - North American ERP Market</center>
			</td>			
		</tr>
		<tr>
			<td style="border-top:1px #657f93 solid;border-left:1px #657f93 solid;" valign="top">
		    	<p style="text-align:justify;font-size:9pt;padding:2px;margin:0;">This report shows ERP that have a history of relevant usage in companies matching
				the criteria in the three right columns. Report data do not necessarily reflect key 
				selection factors, such as vendor strength, progressiveness of software architecture, 
				and ability to meet a company's specific functional priorities.
				</p>
 			</td>
 			<td style="width:270px;border:1px #333 solid;font-size:9pt;vertical-align:middle;padding-left:5px;" nowrap>
 				<u>History of Use Legend</u><br/>
      			&nbsp;<img src="../images/legend_level1.gif" height="15" width="15"/>&nbsp;&nbsp;Significant activity<br />
     			&nbsp;<img src="../images/legend_level2.gif" height="15" width="15"/>&nbsp;&nbsp;Relevant activity<br />
      			&nbsp;<img src="../images/legend_level3.gif" height="15" width="15"/>&nbsp;&nbsp;Minor activity<br />
  			</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" id="query-result-table" width="100%">		
		<tr>
			<th>Vendor</th>
			<th>Product Name</th>
			<th>Web Site</th>
			<th>Company<br/> Size:<br/><span><?php echo $result['header'][0][3];?></span></th>
			<th>Primary<br/> Industry:<br/><span><?php echo $result['header'][0][5];?></span></th>
			<th>Secondary<br/> Industry:<br/><span><?php echo $result['header'][0][7];?></span></th>		
		</tr>		
	<?php foreach($result['body'] as $key=>$rows): ?>
		<?php $in = (empty($rows[5]) || empty($rows[6]) || (empty($rows[7]) && "None selected" <> $result['header'][0][7]) || $in == 1)?1:0;?>
		<tr>
			<td style="border:1px #333 solid"><?php echo !empty($rows[0])?$rows[0]:'';?></td>
			<td><?php echo !empty($rows[1])?$rows[1]:'';?></td>
			<td style='vertical-align:middle;'><a href="<?php echo !empty($rows[3])?$rows[3]:'#';?>" target="_blank"><?php echo !empty($rows[2])?$rows[2]:'';?></a></td>			
			<td style='vertical-align:middle;'><?php echo (!empty($rows[5]) && strlen($rows[5]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[5])."\"/>":"";?></td>			
			<td style='vertical-align:middle;'><?php echo (!empty($rows[6]) && strlen($rows[6]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[6])."\"/>":"";?></td>			
			<td style='vertical-align:middle;'><?php echo (!empty($rows[7]) && strlen($rows[7]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[7])."\"/>":"";?></td>					
		</tr>
	<?php endforeach; ?>
	</table>
	<table width="100%">
		<tr>
            <td style="font-family:arial;font-size:9pt;">
   				<?php if($in): ?>
				* Listings that show no level of suitability for the criteria of (1) company size and/or 
				(2) Industries (primary and secondary) were added by the person configuring this report.<br/><br/>
				<?php endif; ?>

                <u>ERP Database Purpose and Limitations</u>: This database provides high-level information that shows software packages that
                have a track record in a particular type of company. This information is useful in:
                <ul>
                    <li>Creating initial ERP lists (long lists) </li>
                    <li>Challenging ERP short lists</li>
                    <li>Validating current ERP</li>
                </ul>
					This data is not designed for use in making final decisions on the suitability of particular software 
					packages for a specific company seeking ERP. The only way to understand true suitability of candidate 
					software solutions is to carefully and consistently measure them against your organization's most 
					important functional and non-functional priorities. Also ERP vendor viability is an increasingly relevant 
					topic and should be a key differentiating factor in any ERP search.<p />
					<u>ERP Database Usage Terms</u>: This online enterprise software database is the copyrighted and intellectual 
					property of Engleman Associates, Inc. and can only be used for enterprise software projects for which you 
					are directly involved. This ERP evaluation database has been available since 2001 and includes links 
					to all listed ERP software vendors from North America.
             </td>
        </tr>
    </table>
    <script>
    	alertForLandscape();
    	printme();
    </script>    
<?php
}
else
{?>
	<span id="nodata">This query report has no data.</span>
<?php	
}
?>
<div id="pagefooter">
	<center>ERP Comparison Database from Engleman Associates, Inc. / SoftSelect  -  www.softselect.com  888-421-8372 <br /> &copy; Copyright 2012 - All rights reserved.</center>
</div>
</body>
</html>
