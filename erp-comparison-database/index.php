<?php
include_once('includes/functionsdb.php');
include_once('includes/cookies.php');

$db = new softselect();
$comsize = $db->getCompanySize();//get company size
$industries = $db->getIndustries();//get industries
// $mfgtype = $db->getMpgEnv();
$db->dbclose();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<!-- saved from url=(0053)http://www.softselect.com/services/ERP-selection.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
	<title>Tools for ERP Comparisons including free ERP Comparison Database.</title>
	<link rel="stylesheet" href="css/jquery.ui.all.css"> 
	<link rel="stylesheet" href="css/style1.css">	 
	<script type="text/javascript" src="js/jquery-1.5.1.js"></script>
	<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
	<script type="text/javascript" src="js/jquery.ui.button.js"></script>
	<script type="text/javascript" src="js/jquery.ui.draggable.js"></script>
	<script type="text/javascript" src="js/jquery.ui.position.js"></script>
	<script type="text/javascript" src="js/jquery.ui.resizable.js"></script>
	<script type="text/javascript" src="js/jquery.ui.dialog.js"></script>
	<script type="text/javascript" src="js/jquery.effects.core.js"></script>
	<script type="text/javascript" src="js/jquery.ui.tabs.js"></script>
	<script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>

<!--todo: remember to re-enable minified version -->
<!--    <script-->
<!--            src="https://code.jquery.com/jquery-1.12.4.min.js"-->
<!--            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="-->
<!--            crossorigin="anonymous"></script>-->
<!--    <script-->
<!--            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"-->
<!--            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="-->
<!--            crossorigin="anonymous"></script>-->

<!--    <script-->
<!--            src="https://code.jquery.com/jquery-1.12.4.js"-->
<!--            integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="-->
<!--            crossorigin="anonymous"></script>-->
<!--    <script-->
<!--            src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"-->
<!--            integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="-->
<!--            crossorigin="anonymous"></script>-->
<!---->
<!--    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>-->

<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<style type="text/css">
html,body {
	font-family: arial, Helvetica, sans-serif;	
	margin: 0;
	padding: 0;
}
table {
	border-collapse:collapse;
	font-family: 100% arial, Helvetica, sans-serif;
}
#pagecontainer {		
	width:700px;
	margin-left:auto;
  	margin-right:auto;
  	display:block;
  	height:100%;
  	min-height:500px;
 	background-color: #EFF4FC;
}
#top-banner {
	background-color: #0060a9;
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
#query-result-table,#historytable,#userlist-result-table,#query-result-table1 {
	border:1px solid #0060a9;
	border-collapse:collapse;
}
#query-result-table td
{
	border:1px solid #657f93;
	padding:3px 7px 2px 7px;
}
#query-result-table th,#query-result-table1 td, #query-result-table1 th, #historytable td,#historytable th ,#userlist-result-table td,#userlist-result-table th
{

	border:1px solid #ededed;
	padding:3px 7px 2px 7px;
}
#query-result-table th 
{
	text-align:left;
	border:1px solid #657f93;
	padding-top:5px;
	padding-bottom:4px;
	background-color:#d8d8d8;
	color:#000000;
	font-weight: bold;
	font-size: 1.1em;
}
#query-result-table1 th,#historytable th,#userlist-result-table th
{
	text-align:left;
	padding-top:5px;
	padding-bottom:4px;
	background-color:#0060a9;
	color:#ffffff;
}
#pagefooter {
	clear: both;
	background-color: #0060a9;
	text-decoration: none;
	font-size: 60%;
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
	background-color: #0060a9;
	border-left-width: 1px;
	border-left-style: dotted;
	border-left-color: #ffffff;
	padding-top: 0px;
	margin-top: 6px;
}
#specific-pack-table {
	margin: .5em 0; border-collapse: collapse;
	padding: 5px;
}
.ui-widget-content {
	border: 0;
}
.userlisttable {
	background: #eeeeee;
}
.inputbtn {
	height:28px;
	border: 1px solid #ddd;
	background:#eee;
	font-size: 1em;
	cursor:pointer;
}
/*.ui-print-icon {
	background-image: url(images/print-icon.png) !important;
	height: 16px;
	width: 16px;
}*/
#reportprintbtn {	
	height:28px;
	border: 1px solid #ddd;
	background:#eee;
	font-size: 1em;
	cursor:pointer;
}
.logout
{
	height:28px;
	background:#0060A9;
	font-size: .7em;
	font-weight:bold;
	color:#ffffff;
	cursor:pointer;
	border:0;
}
#logout {
	position:relative;
	float:right;
	margin-right:1px;
	margin-top:2px;
}
#report-title {	
	margin-left: 0;	
	float:left;
	width: 100%;
}
.breadcrumb{
	font-size: 80%;
	text-decoration: none;
	line-height: 150%;
	float:left;
}
.breadcrumb a{
	text-decoration: none;
	padding-right: 2px; /*adjust bullet image padding*/
	color: #666666;
	margin-left:9px;
}
.breadcrumb a:visited, .breadcrumb a:active{
	color: #666666;
	text-decoration: none;
}
.breadcrumb a:hover{
	color: #666666;
	text-decoration: none;
}
.report-title-name {
	font-size: 1.4em;
	font-weight: bold;
	color:#0060a9;
	width: 400px;
}
#query-form {
	padding:1px;
}
#query-table select {
	font-family:arial;
	font-size:.8em
}
#pagebody {
  	min-height:500px;
}
#query-result {
	padding:0;
	margin:0;
	float:left;
}
#showpackage {
	display:none;
}
#specificpackage td {
	font-size: small;
}
#nodata {
	width:700px;
	color:#ff0000;
	text-align:center;
	margin-left:0px;
	font-size:.8em;
	font-weight:bold;
}
#loadingpackage {
	display:none;
}
.instruction-text {
	font-family: verdana;
	font-size: .7em;
	color: #332424;
}
#his-legend {
	padding-left:2px;
	margin:0;
}
#his-legend li{
	list-style-type: none;	
}
</style>
</head>
<body onload="hideloader()">
	<input type="hidden" id="ifsubmitted" value="0"/> 	
	<div id="pagecontainer">
		<div id="pagebody">			
				<div id="report-title" style="border-bottom:solid 1px #7eaed4;">
					<div class="report-title-name" style="float:left">ERP Comparison Database</div>&nbsp;
					<div id="top-buttons" style="float:right;font-size:.6em;font-weight:bold;">
						<button id="u-btn">Usage Information</button>
						<button id="e-btn">ERP Cost Info</button>
					</div>
				</div>
				<br/>				
				<div id="query" style="width:100%;float:left;padding-top:.3em;padding-left:.1em;">
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
												<input type="button" id="submitquery" value="Create ERP List" style="font-size:.7em;font-weight:bold;"><div id="loading"><img src="images/ajaxloader.gif"/></div>
											</td>
										</tr>
									</table>
								</td> <!-- left -->
									<!-- right -->
								<td valign="top">
								<div id="query-title-instruction" style="display:block;float:right;margin-top:-5px;padding:2px;">
									<p class="instruction-text">To develop your ERP list the company size value is mandatory and one or both industry values are optional.</p>
								</div>
								<div id="showpackage" style="font-size:.9em;width:260px;margin-right:7px;margin-top:-2px;float:right;">
									<table border="0" cellpadding="0" cellspacing="0" id="specific-pack-table" width="100%">
											<tr style="background-color:#7eaed4;height:25px;">
												<td style="font-weight:bold;color:#ffffff;font-size:.8em;">&nbsp;&nbsp;Add Specific ERP to Report</td>
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
					<div id="query-result">&nbsp;</div>
				</div>	
		</div>
	</div>
	<div id="venpac-dialog-form" title="Select up to 3 ERP to include" style="font-size:80%">
		<table border="0" cellspacing="0" cellpadding="1" width="90%">
			<tr>
				<td style="font-weight:bold;font-size:.8em;">Vendor Name:</td>
				<td style="font-size:.8em;"><input type="text" value="" id="txtboxvname"/></td>
				<td style="font-weight:bold;font-size:.8em;">Product Name:</td>
				<td style="font-size:.8em;"><input type="text" value="" id="txtboxpname"/></td>
				<td style="font-weight:bold;font-size:.8em;"><input type="button" class="inputbtn" id="vpbtn" value="Search"/><span id="loadingpackage" style="margin-left:4px;"><img src="images/ajaxloader.gif"/></span></td>
			</tr>
		</table>
		<div id="venpac-result" style="padding-top:6px">&nbsp;</div>
	</div>

    <div id="usage-information">
        <div class="oneColFixCtrHdr" id="topspantext">
            <h2 class="style2"><em>Understanding the ERP  Comparison Data</em></h2>
        </div>
        <!-- This clearing element should immediately follow the #mainContent div in order to force the #container div to contain all child floats -->

        <div class="oneColFixCtrHdr" id="colspan2to4">
            <p>The report produced by the ERP comparison database shows ERP software that have a <em>history of  use</em> in a particular <em>type of company</em>.  Criteria to describe a <em>type of company</em> are (1) company size (in yearly gross sales), and optionally (2) one or two industry types. For criteria selected the <em>history of use</em> information is shown by the following strength  measures:</p>
            <p><img src="images/legend.jpg" alt="History Of Use Legend" /></p>
            <p><img src="images/Chart_SampleERPData.jpg" alt="Sample Data ERP Search" width="500" height="238" /></p>
            <h2>Procedures  to produce an ERP Comparison list: </h2>
            <ol>
                <li>Disable any pop-up  blockers for this website—as needed.</li>
                <li>Key  criterion: Using the ERP comparison database query form, select a company size  range for your business (projected 3 to 5 year company size). This step is  required to enable results to be generated.</li>
                <li>Optional  criteria: Select other optional values for primary industry and secondary  industry as is relevant.</li>
                <li>Adding an ERP: If the ERP list does not include an ERP for which you have an interest, select the control ‘Add Specific ERP to report’ and then search for the ERP to add to the list. Once this form is closed, the new ERP will be added to the existing list. Three additional ERP may be added.</li>
                <li>Printing the report: Select one of the printer icons and follow the instructions.</li></ol>
            <p>This data <strong>is not</strong> designed for use in making final decisions on the suitability of particular ERP software for a specific company seeking ERP. The only way to understand true suitability of candidate software solutions is to carefully and consistently measure them against your organization's most important functional and non-functional priorities.</p>
            <p>&nbsp;</p>
            <p><strong>Cloud versus on site ERP:</strong> The ERP database measures cloud-based (software as a service [SAAS]) the same as on-premise ERP. The attributes that have differentiated cloud-based versus on-premise are not relevant to the comparison database criteria. These differentiating factors are becoming less relevant as on-premise sellers have ways to offer their software remotely, have the software be hosted, and lower the large upfront costs.</p>
            <p>&nbsp;</p>
            <p><strong>Industry-specific add-on software:</strong> Some ERP sellers partner with independent software developers that build industry-specific vertical solutions that are integrated to the original ERP. For a variety of reasons it is not practical to list multiple instances of the ERP with all of the various industry-specific add-on software—although there are a few exceptions in the database. The SoftSelect ERP database attempts to reasonable reflect these industry-specific addons with the <em>industry type</em> criterion.</p>
            <p>&nbsp;</p>
            <p><strong>Open Source ERP:</strong> There are a few open source ERP in the ERP comparison database—however others were excluded based on general risks with depending on such a software for critical business process support.</p>
            <p>&nbsp;</p>
            <p><strong>ERP Database Usage Terms:</strong> This online ERP software database is the copyrighted and intellectual property of Engleman Associates, Inc. and can only be used for enterprise software projects for which you are directly involved.</p>
        </div>
    </div>

    <!-- Drip snippet -->
    <script type="text/javascript">
        var _dcq = _dcq || [];
        var _dcs = _dcs || {};
        _dcs.account = '6466134';

        (function() {
            var dc = document.createElement('script');
            dc.type = 'text/javascript'; dc.async = true;
            dc.src = '//tag.getdrip.com/6466134.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(dc, s);
        })();
    </script>

</body>
</html>