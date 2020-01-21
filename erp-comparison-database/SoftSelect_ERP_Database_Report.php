<?php
include_once('includes/functionsdb.php');
include_once('includes/sanitize.php');

// start buffering output for PDF generation
ob_start();

// check if user is logged in
// todo - align to admin side login process 

// get input values from GET/POST
$id = !empty($_REQUEST['id'])?stripper($_REQUEST['id']):'';
$debug = !empty($_REQUEST['debug'])?stripper($_REQUEST['debug']):'';

$db = new softselect();

$data = $db->getReportData($id);

$year = (new DateTime())->format('Y');

//logs if the user click print
$result = $db->getReportForPrint($id);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php if('y'==$debug): ?>
		<title>SoftSelect ERP Database</title>
		<link href="css/report-mpdf.css" rel="stylesheet" type="text/css" />
	<?php endif; ?>
</head>
<body>
<div id="container">
  <div id="intro">
  <h3>ERP Comparison Database Results - North American ERP Market</h3>
      This report shows ERP that EAI has determined has discernible usage history in companies matching the
      criteria in the three right columns. This data does not reflect key selection factors such as ERP vendor
      strength, ERP technology status, and the ERP's ability to meet a company's specific functional priorities.</div>

<div id="legend">
	<strong>History of Use Legend</strong><p />
    <img src="./images/level1.png">&nbsp;&nbsp;Significant activity<br>
    <img src="./images/level2.png">&nbsp;&nbsp;Relevant activity<br>
    <img src="./images/level3.png">&nbsp;&nbsp;Minor activity
</div>
<p></p>
<table>
<thead>		
	<tr>
		<th>Vendor</th>
		<th>Product Name</th>
		<th>Web Site</th>
		<th>Company Size:<br/><?php echo $data['cost_range'];?></th>
		<th>Primary Industry:<br/><?php echo $data['primary_industry'];?></th>
		<th>Secondary Industry:<br/><?php echo $data['secondary_industry'];?></th>		
	</tr>
</thead>
<tbody>		
<?php 
	foreach($data['body'] as $key=>$row):
		echo "<tr>\n<td>".$row['vendor']."</td>\n";
		echo "<td>".$row['product_name']."</td>\n";
		if ($row['weburl'] == '' || empty($row['weburl'])) {
			echo '<td />';
		}
		else {
		    $url = $row['weburl'];
            if (strpos($url, '://') === FALSE && $url ) {
                $url = 'http://'.$url;
            }
			echo "<td><a href=\"".$url."\" target=\"_blank\">".$url."</a></td>\n";
		}
		echo ($row['company_size'])?"<td align=\"center\"><img src=\"images/level".$row['company_size'].".png\" height=\"16px\" width=\"16px\" /></td>":"<td />\n";
		echo ($row['primary_industry'])?"<td align=\"center\"><img src=\"images/level".$row['primary_industry'].".png\" height=\"16px\" width=\"16px\" /></td>":"<td />\n";
		echo ($row['secondary_industry'])?"<td align=\"center\"><img src=\"images/level".$row['secondary_industry'].".png\" height=\"16px\" width=\"16px\" /></td>":"<td />\n";
		echo "</tr>\n";
	endforeach; 
?>
</tbody>
</table>
<?php if($data['added_product_flag']): ?>
<div class="asterisk">
	<p>* Listings that show no level of suitability for the criteria of (1) company size and/or 
	(2) Industries (primary and secondary) were added by the person configuring this report.</p>
</div>
<?php endif; ?>
    <br />
    <h4>ERP Database Purpose and Limitations</h4>
    <p>This ERP listing tool provides our team's observations of the industries
        and company sizes for which listed ERP has reasonable, or better, suitability. &nbsp;
        This free ERP system list can be used for:</p>

    <ul type=disc>
        <li>Creating initial ERP lists (long list)</li>
        <li>Challenging ERP short lists</li>
        <li>Validating current ERP</li>
    </ul>

    <p>This ERP data <b>is not</b> designed for use in making final decisions
        on the suitability of particular ERP packages for a specific company seeking ERP.
        The only way to understand actual suitability of candidate ERP solutions is to
        carefully and consistently and directly measure them against your organization's
        most important functional and non-functional priorities.</p>

    <p>Some ERP software vendors or ERP software products are not included
        in this ERP list as they are or were:</p>

    <ul type=disc>
        <li>Focused on other geographical markets and not properly
            supported in the North American market.</li>
        <li>Solely focused on low end manufacturers or
            distributors.</li>
        <li>An add-on solution to a base ERP that attempts to make
            the ERP suitable for a particular vertical industry.</li>
        <li>Overall weak in market presence or technology.</li>
        <li>Not significantly suitable for use in a manufacturing
            and distribution firms.</li>
    </ul>

    <p><b>Cloud ERP:</b>&nbsp; Cloud ERP is generally described by the following attributes:
        (1) ERP hosted by some entity (could be the user),
        (2) ERP maintenance and upgrades managed by another entity,
        (3) Remotely accessed by users (browser and other techniques), and
        (4) Pay as you go model (subscription). Once educated, buyers become aware of
        the pros and cons of details within these attributes and their options.
        Based on a buyer's point of view on 'Cloud ERP' many of the Cloud offerings
        will not be suitable, including many of the pure-play ERP Cloud offerings.
        Conversely some ERP will meet a particular buyer's 'Cloud ERP' objectives even though
        the ERP may normally be used on-premise. THEREFORE THIS ERP DATABASE DOES
        NOT LIST ERP AS EXPLICITLY  CLOUD—OR NOT.  Such a designation would be
        illegitimate and a disservice to the ERP database users.</p>

    <p><b><span >ERP Database Usage Terms:</b>&nbsp;
        This ERP data seeks to favor the ERP buyer's interest and in this light
        (1) does not seek to overstate the general suitability of any listed ERP, and
        (2) seeks to be diligent for not including some ERP in the database that are
        subject to one or more items in the list of issues directly above. &nbsp;
        Based on this approach, and the ever-changing ERP marketplace, the data
        is inherently 'high-level', is subject to alternative opinions by other
        parties, and could be inaccurate to some degree. &nbsp;This online enterprise
        software database is the copyrighted and intellectual property of Engleman Associates,
        Inc., and can only be used for enterprise software projects for which you are
        directly involved. Users cannot repackage or resell this ERP system review for
        other purposes. &nbsp;By using the ERP comparison database you agree to these terms
        and acknowledge this ERP data processing approach and potential limitations.&nbsp;</p>
</div>
</body>
</html>

<?php 
// capture output buffer into a variable
$rpt_html = ob_get_contents();
// flush the output buffer
ob_end_clean();

if ('y'==$debug) {
	// dump html stream to stdout
	echo $rpt_html;
} 
else {
	include_once('mpdf/mpdf.php');
		
	// Create class instances
	$mpdf=new mPDF('WinAnsi', 'Letter-L', 0, 'Helvetica', 10 ,10 ,10 ,18);

	//$mpdf->useOnlyCoreFonts = true;

	// $stylesheet = file_get_contents(sfConfig::get('sf_web_dir').'/css/mpdfstyletables.css');
	$stylesheet = file_get_contents('css/report-mpdf.css');
	$mpdf->WriteHTML($stylesheet,1);   // The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->SetHTMLFooter('<div class="footer"><div class="footer-text">ERP Comparison Database from Engleman Associates, Inc. / SoftSelect  -  www.softselect.com  888-421-8372&nbsp;&nbsp;© Copyright '.$year.' - All rights reserved.</div><div class="footer-pagenum">{PAGENO}</div></div>');

	$mpdf->WriteHTML($rpt_html, 2);
//    $mpdf->WriteHTML($rpt_html);

	header('Content-disposition: attachment; filename="SoftSelect%20ERP%20Database%20Report.pdf"');
	
	// Output the PDF file:
	$mpdf->Output('SoftSelect ERP Database Report.pdf', 'I');
}
?>
