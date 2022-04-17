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
      strength, ERP technology status, and the ERP's ability to meet a company's specific functional priorities.
      ‘Multi-tenant’ ERP is directly controlled by the ERP seller and customers share various resources.
  </div>

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
    <h4>Purpose and Limitations – of the ERP Comparison Database</h4>
		<p><b>ERP Database Design Approach</b>
		<ol>
			  <li><b>Main Assessment Criteria:</b>&nbsp;ERP in the database are assessed and
					attributed based on the sizes (in revenue) and industry types of customers using
					the ERP product. These attributes don’t change rapidly, and are used for creating
					ERP lists for a particular user.</li>
			  <li><b>ERP Not Listed:</b>&nbsp;Some ERP software products are not included in this
					ERP list as they are or were:
					<ol type="A">
						  <li>Not significantly suitable for use in manufacturing and/or distribution firms.</li>
						  <li>Overall considered weak in market presence or significantly dated technology.</li>
						  <li>Not effectively supported in the North American market.</li>
							<li>An add-on solution to a base ERP that attempts to make the base ERP suitable for a particular vertical industry.</li>
					</ol>
				</li>
			  <li><b>Cloud ERP Status:</b>&nbsp;Most ERP in this ERP database can be offered remotely or 'in the Cloud'
					through various means. Accessing ERP remotely relieves the buyer from managing hardware and software,
					which is usually good. However, everything else about remote access ERP is largely designed to benefit
					the ERP seller unilaterally. At the top of the list are unbounded options to increase fees to customers
					who cannot change ERP without great cost and risk. Here's a <a href="https://www.softselect.com/downloads/Cloud_ERP_Whitepaper-from_EAI-SoftSelect.pdf">link to our white paper</a> on truly
					important details to understand when arranging access to Cloud ERP.</li>
		</ol>
		<p><b>ERP Database Usage Terms</b>
		<ol>
			  <li><b>Copyright:</b>&nbsp;This online ERP database and resultant reports are the copyrighted and intellectual property of Engleman Associates, Inc., and can only be used for enterprise software planning activities for which the user is directly involved. Users may not repackage or resell this ERP system data for other purposes.</li>
			  <li><b>Conditional Advice:</b>&nbsp;This ERP data and listing process is not designed for making final decisions on the suitability of particular ERP for a specific company seeking ERP. The only way to understand actual suitability of candidate ERP solutions is to carefully, consistently and directly measure them against your organization's most important functional and non-functional priorities.</li>
			  <li><b>Acknowledgement:</b>&nbsp;By using the ERP comparison database you agree to these terms and acknowledge the ERP comparison database purpose and limitations stated herein.</li>
		</ol>
		<p><u>Why This Data is Made Available</u>:&nbsp;This ERP software vendor directory is a summary version of the EAI-SoftSelect total ERP software data and insight.
			Our company makes this summary ERP software data available to create awareness of its broader business software insight and ERP services.
			This ERP application database has been managed and enhanced since 2001 and includes links to all listed ERP software vendors from North America.
		<p>
		<p>Please <a href="mailto:administration@softselect.com?__s=4p951hebzbm690n3ca83">contact us</a> if you have any questions.<br>
		Engleman Associates, Inc.<br>
		<a href="www.softselect.com">www.softselect.com</a><br>
		360-699-6150<br>
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
    // Require composer autoload
    require_once __DIR__ . '/vendor/autoload.php';

    // Create class instances
    $mpdf = new \Mpdf\Mpdf([
        mode => 'en-US',
        format => 'Letter-L',
        default_font_size => 0,
        default_font => 'Helvetica'
    ]);

    $stylesheet = file_get_contents('css/report-mpdf.css');
    $mpdf->WriteHTML($stylesheet,1);

    $mpdf->SetHTMLFooter('<div class="footer"><div class="footer-text">ERP Comparison Database from Engleman Associates, Inc. / SoftSelect  -  www.softselect.com  888-421-8372&nbsp;&nbsp;© Copyright '.$year.' - All rights reserved.</div><div class="footer-pagenum">{PAGENO}</div></div>');

    $mpdf->WriteHTML($rpt_html, 2);

    header('Content-disposition: attachment; filename="SoftSelect%20ERP%20Database%20Report.pdf"');

    // Output the PDF file:
    $mpdf->Output('SoftSelect ERP Database Report.pdf', 'I');
}
?>
