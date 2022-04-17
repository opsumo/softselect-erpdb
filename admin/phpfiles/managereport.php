<?php
require_once 'sanitize.php';
function getImage($id,$lvl)
{
	if($id>0 && ($lvl >= 1 && $lvl <=3))
		return '<IMG src="level'.$lvl.'.gif">';
	else
		return '';
}
?>
<script type="text/javascript">
function delete_confirm(id)
{
var r=confirm("Delete This Report Details?");
if (r==true)
  {
  	var url = 'index.php?module=Report&mode=Delete&id='+id;
	window.location=url;
  }
else
  {
	return false;
  }
}
</script>
<p>&nbsp;</p>
<?php
$email = isset($_REQUEST['txt_email'])?stripper($_REQUEST['txt_email']):'';
$dfrom = isset($_REQUEST['txt_from'])?$_REQUEST['txt_from']:'';
$tfrom = isset($_REQUEST['txt_to'])?$_REQUEST['txt_to']:'';
?>
<table cellspacing="0" cellpadding="5" align="center" style="font-size:75%" class="reporttable">
	<tr height="60" bgcolor="#FFFFFF">
    	<td align="center" colspan="12" style="font-size:small;">
        	<form method="post" action="index.php?module=Report&mode=ManageReport" onSubmit="return valSubmit()">
            	<label><strong>Email</strong>&nbsp;</label>
            	<input type="text" name="txt_email" id="txt_email" value="<?php echo $email;?>"/>&nbsp;
                <strong>Query Date from</strong>&nbsp;
                <input type="text" name="txt_from" id="txt_from" value="<?php echo $dfrom;?>" readonly/>&nbsp;
                <strong>To</strong>&nbsp;
                <input type="text" name="txt_to" id="txt_to" value="<?php echo $tfrom;?>" readonly/>&nbsp;
                <input type="submit" name="btn_report" value="Go" id="btn_report"/>
            </form>
        </td>
    </tr>
</table>
<?php
	if(isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		$conn = mysqli_connect(HOST_NAME, USER_NAME, PASS, DB_NAME);
		if(!$conn) echo mysqli_error($conn)."<br>Failed to connect to database!";
		// $status = mysqli_select_db(DB_NAME, $conn);
//		if(!$status) echo mysqli_error($conn)."<br>Failed to select database!";

		$data = array();
		$dataheader = array();
		$databody = array();
		$i = 0;
		$x = 0;

		if(!empty($conn))
		{
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
			$res_head = mysqli_query($conn, $sql) or die(mysqli_error($conn));
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
                                    CASE WHEN INSTR(v.www, \'://\') > 0 THEN v.www ELSE CONCAT(\'http://\', v.www) END AS website,
																		p.mtco AS MultiTenant,
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
			$result = mysqli_query($conn, $sqlresult) or die(mysqli_error($conn));

			while($rows = mysqli_fetch_array($result))
			{
				//echo $rows[0].','.$rows[1].','.$rows[2].','.$rows[3].','.$rows[4].','.$rows[5].'<br/>';
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

		}

		$data['header'] = $dataheader;
		$data['body'] = $databody;
		$data['id'] = $id;

	$imgpath ="<img style='vertical-align:middle;' height='11px' width='11px' src=\"images/";
	if(!empty($data['header']) && !empty($data['body']))
	{?>
	<div style="float:right;margin-top:-10px;">
		<table width="100%">
			<tr>
				<td style="text-align:center"><center><button id="reportprintbtn1" style="font-size:.7em;font-weight:bold;" onclick="printdata('<?php echo $id;?>')"></button></center></td>
			</tr>
		</table>
	</div>
	<center>
	<table border="0" cellpadding="0" cellspacing="0" id="query-result-table" width="100%" style="font-family:arial;font-size:8pt;">
		<tr>
		<th>Vendor</th>
		<th>Product Name</th>
		<th>Web Site</th>
		<th>Multi-Tenant Only</th>
		<th>Company Size:<br/><?php echo $data['header'][0][3];?></th>
		<th>Primary<br/> Industry:<br/><?php echo $data['header'][0][5];?></th>
		<th>Secondary<br/> Industry:<br/><?php echo $data['header'][0][7];?></th>
		</tr>
	<?php foreach($data['body'] as $key=>$rows): ?>
		<tr>
			<td><?php echo !empty($rows[0])?$rows[0]:'';?></td>
			<td><?php echo !empty($rows[1])?$rows[1]:'';?></td>
			<td><a href="<?php echo !empty($rows[3])?$rows[3]:'#';?>" target="_blank"><?php echo !empty($rows[2])?$rows[2]:'';?></a></td>
			<td><?php echo !empty($rows[1])?$rows[4]:'';?></td>
			<td><?php echo (!empty($rows[5]) && strlen($rows[5]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[5])."\"/>":"";?></td>
			<td><?php echo (!empty($rows[6]) && strlen($rows[6]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[6])."\"/>":"";?></td>
			<td><?php echo (!empty($rows[7]) && strlen($rows[7]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[7])."\"/>":"";?></td>
		</tr>
	<?php endforeach; ?>
	</table>
    </center>
	<?php
	}
	else
	{?>
		<span id="nodata">This query has no data.</span>
	<?php
	}
	}
	else {
?>
<table cellspacing="0" cellpadding="0" align="center" style="font-family:verdana;font-size:3pt;" id="sorter" class="sortable1" width="100%">
<?php
	include('ps_pagination.php');
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if(!$conn) echo mysqli_error($conn)."<br>Failed to connect to database!";
//    $status = mysqli_select_db(DB_NAME, $conn);
//	if(!isset($status)||!$status) echo mysqli_error($conn)."<br>Failed to select database!";

	if(isset($_REQUEST['txt_email']))
		$email = stripper($_REQUEST['txt_email']);
	else if(isset($_REQUEST['txt_report']))
		$email = stripper($_REQUEST['txt_report']);
	else
		$email = "";

    $defaultTimezone = new DateTimeZone(date_default_timezone_get());
    if (empty($_REQUEST['txt_to'])) {
        $date = new DateTime();
        $to = $date->format('Y-m-d H:i:s');
    } else {
        $date = new DateTime($_REQUEST['txt_to'], new DateTimeZone('America/Los_Angeles'));
        $date->add(new DateInterval('P1D'));
        $date->setTimezone($defaultTimezone);
        $to = $date->format('Y-m-d H:i:s');
    }
    if (empty($_REQUEST['txt_from'])) {
        $date = new DateTime();
        $date->add(date_interval_create_from_date_string('-3 months'));
        $from = $date->format('Y-m-d H:i:s');
    } else {
        $date = new DateTime($_REQUEST['txt_from'], new DateTimeZone('America/Los_Angeles'));
        $date->setTimezone($defaultTimezone);
        $from = $date->format('Y-m-d H:i:s');
    }



//    $to = isset($_REQUEST['txt_to'])?stripper($_REQUEST['txt_to']):"";
//    if ($to == "")
//        $to_date = new DateTime();
//    else
//        $to_date = new DateTime($to);
//
//    $from = isset($_REQUEST['txt_from'])?stripper($_REQUEST['txt_from']):"";
//    if ($from == "")
//        $from_date = date_add($to_date, date_interval_create_from_date_string('-3 months'));
//    else
//        $from_date = new DateTime($from);

    $qs = "module=Report&mode=ManageReport&txt_from=".$from."&txt_to=".$to;

//    $fromsql = $from_date->format('Y-m-d');
//    $tosql = $to_date->format('Y-m-d');
    $sql = "SELECT	q.query_id,
            q.query_date,
            u.email_address,
            q.ip_address,
            u.firm_type,
            u.geo_location,
            'ERP' AS CategoryDsc,
            r.descript AS CostRangeDsc,
            m1.market_description AS MarketDsc1,
            m2.market_description AS MarketDsc2,
            q.printed
            FROM query q
            JOIN user u ON u.user_id = q.user_id
            JOIN cost_range r ON r.cost_range_id = q.cost_range_id
            JOIN target_market m1 ON m1.market_id = q.market_id1
            JOIN target_market m2 ON m2.market_id = q.market_id2
            WHERE q.query_date BETWEEN '$from' AND '$to'
            ";

	if($email!="")
	{
		$sql = $sql."AND u.email_address = '".$email."'
		";
		$qs .= "&txt_email=".$email;
	}

    $sql = $sql."ORDER BY q.query_date DESC";
//    $rs = mysqli_query($conn, $sql);
	$pager = new PS_Pagination($conn, $sql, 10, 20, $qs);
	$pager->setDebug(true);
	$rs = $pager->paginate();

	if($rs && mysqli_num_rows($rs)>0)
	{
?>

  <tr>

	<th>ID</th>
		<th>Date/Time</th>
		<th>Email</th>
		<th>IP Address</th>
		<!-- <th>Firm Type</th>-->
		<th>Location</th>
		<!-- <th>Category</th>-->
		<th>Company Size</th>
		<th>Primary Industry</th>
		<th>Secondary Industry</th>
		<th>Printed</th>
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
  	<?php
  		$emailadd = "<a href='mailto:".$row[2]."'>".$row[2]."</a>";
  		$ipadd = "<a target='_blank' href='http://whois.arin.net/rest/ip/".$row[3]."'>".$row[3]."</a>";
  	?>
  	<td style="font-size:6pt;"><a href="index.php?module=Report&mode=ManageReport&id=<?php echo $row[0]; ?>"><?php echo isset($row[0])?$row[0]:''; ?></a></td>
  	<td style="font-size:6pt;"><?php echo isset($row[1])?date("n/d/Y h:i:s",strtotime($row[1])):''; ?></td>
  	<td style="font-size:6pt;"><?php echo isset($emailadd)?$emailadd:''; ?></td>
  	<td style="font-size:6pt;"><?php echo isset($ipadd)?$ipadd:''; ?></td>
  	<!-- <td><?php //echo isset($row[4])?$row[4]:''; ?></td>-->
  	<td style="font-size:6pt;"><?php echo isset($row[5])?$row[5]:''; ?></td>
  	<!-- <td style="font-size:6pt;"><?php echo isset($row[6])?$row[6]:''; ?></td>-->
  	<td style="font-size:6pt;"><?php echo isset($row[7])?$row[7]:''; ?></td>
  	<td style="font-size:6pt;"><?php echo isset($row[8])?$row[8]:''; ?></td>
  	<td style="font-size:6pt;"><?php echo isset($row[9])?$row[9]:''; ?></td>
  	<!--<td><?php //echo isset($row[10])?$row[10]:''; ?></td>
  	<td style="font-size:6pt;"><?php //echo isset($row[11])?$row[11]:''; ?></td>-->
  	<td style="font-size:6pt;text-align:center"><?php echo (isset($row[10]) && $row[10] == 1)?'YES':'NO'; ?></td>
  </tr>

<?php
			$j++;
		}
?>
</table>
<input type="hidden" id="selfrom" value="<?php echo $dfrom;?>"/>
<input type="hidden" id="selto" value="<?php echo $tfrom;?>"/>
<script type="text/javascript">
	//var sorter=new table.sorter("sorter");
	//sorter.init("sorter",0);
</script>
<table cellspacing="0" cellpadding="5" align="center" style="font-size:75%" class="reporttable">
  <tr bgcolor="#FFFFFF">
    <td colspan="12" align="center">
        <?php echo $pager->renderFullNav(); ?>
    </td>
  </tr>
 </table>
<?php
	}
	else
	{
		echo "<center><br/><table><tr><td align='center' colspan='12'><strong>There is no data.</strong></td></tr></table></center>";
	}
	}
?>
