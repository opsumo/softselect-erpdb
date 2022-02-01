<?php
include_once('includes/session.php');
include_once('includes/functionsdb.php');
include_once('includes/sanitize.php');

// let's put the Drip check here. Or maybe in JS?
// session_start();

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
//$userid = !empty($_SESSION['userid'])?$_SESSION['userid']:'1';
$email = !empty(['email'])?stripper($_REQUEST['email']):"anonymous@unknown.com";

$db = new softselect();
//$result = $db->getReport($userid,$ip_addr,$comsize,$pri,$sec,$package);
$result = $db->getReport($email,$ip_addr,$comsize,$pri,$sec,$package);

$imgpath ="<img style='vertical-align:middle;' height='11px' width='11px' src=\"images/";
if(!empty($result['header']) && !empty($result['body']))
{?>
	<div style="float:right;margin-top:-10px;">
		<table width="100%">
			<tr>
				<td style="text-align:center"><center><button id="reportprintbtn1" style="font-size:.7em;font-weight:bold;" onclick="printdata('<?php echo $result['id'];?>')"></button></center></td>
			</tr>
		</table>
	</div>
	<table width="100%">
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
		<th style="white-space:none;overflow:hidden;width:63px;vertical-align:top;">Company<br/> Size:<br/><span style="color:#272626;"><?php echo $result['header'][0][3];?></span></th>
		<th style="white-space:none;overflow:hidden;width:75px;vertical-align:top;">Primary<br/> Industry:<br/><span style="color:#272626;"><?php echo $result['header'][0][5];?></span></th>
		<th style="white-space:none;overflow:hidden;width:75px;vertical-align:top;">Secondary<br/> Industry:<br/><span style="color:#272626;"><?php echo $result['header'][0][7];?></span></th>
		</tr>
		</thead>
		<tbody>
		<?php $c=0; ?>
	<?php foreach($result['body'] as $key=>$rows): ?>
    <?php if (!isset($forced_in)) { $forced_in = 0;} ?>
    <?php $forced_in = ((empty($rows[5]) || empty($rows[6]) || (empty($rows[7])) && $sec > 0) || $forced_in == 1)?1:0;?>
		<tr <?php echo ($c%2==0)?'bgcolor="#ffffff"':'bgcolor="#eeeeee"';?>>
			<td style="white-space:none;overflow:hidden;"><?php echo !empty($rows[0])?$rows[0]:'';?></td>
			<td style="white-space:none;overflow:hidden;"><?php echo !empty($rows[1])?$rows[1]:'';?></td>
			<td style='vertical-align:middle;white-space:none;overflow:hidden;'><a href="<?php echo !empty($rows[3])?$rows[3]:'#';?>" target="_blank"><?php  echo !empty($rows[2])?$rows[2]:'';?></a></td>
			<td align="center" style='vertical-align:middle;'><?php echo (!empty($rows[5]) && strlen($rows[5]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[5])."\"/>":"";?></td>
			<td align="center" style='vertical-align:middle;'><?php echo (!empty($rows[6]) && strlen($rows[6]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[6])."\"/>":"";?></td>
			<td align="center" style='vertical-align:middle;'><?php echo (!empty($rows[7]) && strlen($rows[7]) > 4 )?$imgpath.str_replace(array("<IMG src=\"",">"),'',$rows[7])."\"/>":"";?></td>
		</tr>
		<?php $c++;?>
	<?php endforeach; ?>
	</tbody>
	</table>
	<table width="98%">
		<tr>
            <td style="font-family:arial;font-size:.7em;padding-left:8px;padding-top: 5px;text-align:justify;">
				<?php if($forced_in): ?>
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
    <br/>
	    <table width="98%">
	    	<tr>
	    		<td style="text-align:center"><center><button id="reportprintbtn" style="font-size:.7em;font-weight:bold;" onclick="printdata('<?php echo $result['id'];?>')"></button></center></td>
	    	</tr>
	    </table>
	    <form id="print_form" name="print_form" action="SoftSelect_ERP_Database_Report.php" target="_blank" method="post">
			<input type="hidden" name="id" value="<?php echo $result['id'];?>" />
		</form>
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
