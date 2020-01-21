<?php
include_once('includes/session.php');
include_once('includes/functionsdb.php');
include_once('includes/sanitize.php');
session_start();

if(empty($_SESSION['userid'])) die();
//db instance

$id = !empty($_REQUEST['id'])?stripper($_REQUEST['id']):'';
$db = new softselect();
$result = $db->getReportForPrint($id);
$imgpath ="<img src=\"images/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	font: 100% arial, Helvetica, sans-serif;
	font-size: 80.5%;
	margin: 0;
	padding: 0;
	color: #000000;
	background-color: #ffffff;
	background-repeat: repeat-x;
}
table {
	border-collapse:collapse;
	font: 100% arial, Helvetica, sans-serif;
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
			<td style="font-weight:bold; padding-bottom: 0.7em;" colspan="2" >
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
      			&nbsp;<img src="images/legend_level1.gif" height="15" width="15"/>&nbsp;&nbsp;Significant activity<br />
     			&nbsp;<img src="images/legend_level2.gif" height="15" width="15"/>&nbsp;&nbsp;Relevant activity<br />
      			&nbsp;<img src="images/legend_level3.gif" height="15" width="15"/>&nbsp;&nbsp;Minor activity<br />
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
		<?php $in = ((empty($rows[5]) || empty($rows[6]) || (empty($rows[7])) && !empty($result['header'][0][7]) > 0) || $in == 1)?1:0;?>
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
            <td style="font-family:arial;font-size:9pt;padding-top:.7em;">
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
