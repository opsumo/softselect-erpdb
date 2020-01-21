<?php
include_once '../classes/config.php';
require_once '../sanitize.php';
session_start();

if(empty($_SESSION)) {
	
	echo "<script>window.location='index.php?err=expire'</script>";
	exit;
}

$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$email = isset($_REQUEST['email'])?stripper($_REQUEST['email']):'';
$dateto = '';

if (empty($_REQUEST['datefrom'])) {
    $datefrom = '';
} else {
    $date = new DateTime($_REQUEST['datefrom'], new DateTimeZone('America/Los_Angeles'));
    $date->setTimezone(new DateTimeZone('UTC'));
    $datefrom = $date->format('Y-m-d H:i:s');
}

if (empty($_REQUEST['dateto'])) {
    $dateto = '';
} else {
    $date = new DateTime($_REQUEST['dateto'], new DateTimeZone('America/Los_Angeles'));
    $date->add(new DateInterval('P1D'));
    $date->setTimezone(new DateTimeZone('UTC'));
    $dateto = $date->format('Y-m-d H:i:s');
}

$activated = isset($_REQUEST['activated'])?$_REQUEST['activated']:'';


$sql = "select user_id, email_address, ip_address, firm_type, geo_location, register_date, activation_expire_date, activated
		from user";

$valid = false;
if(!empty($email))
{
	$sql .= " where email_address = '$email' ";
	//$valid = val_email($email);
}
if(!empty($datefrom))
{
	if(!empty($email)) $sql .= " and register_date >= '$datefrom' ";
	else $sql .= " where register_date >= '$datefrom' ";
}
if(!empty($dateto))
{
	if(!empty($email) || !empty($datefrom)) $sql .= " and register_date < '$dateto' ";
	else $sql .= " where register_date < '$dateto' ";
}
if(!empty($activated))	
{
	if(!empty($email) || (!empty($datefrom) && !empty($dateto))) $sql .= " and activated = '".$activated."'";
	else $sql .= "where activated ='".$activated."' ";
}
/*
if(!empty($email) || (!empty($datefrom) && !empty($dateto)) || !empty($activated))
	$sql .= " and user_id not in(".$_SESSION['user_id'].")
		order by register_date desc";
else 
	$sql .= " where user_id not in(".$_SESSION['user_id'].")
	order by register_date desc";
*/
$sql .= " order by register_date desc";

$data = array();
$i = 0;
$result = mysqli_query($con, $sql) or die(mysqli_error($con));
while($rows = mysqli_fetch_array($result))
{
	$data[$i]["user_id"] = $rows["user_id"];
	$data[$i]["email_address"] = "<a href='mailto:".$rows["email_address"]."'>".$rows["email_address"]."</a>";
	$data[$i]["ip_address"] = "<a target='_blank' href='http://whois.arin.net/rest/ip/".$rows["ip_address"]."'>".$rows["ip_address"]."</a>";
	$data[$i]["firm_type"] = $rows["firm_type"];
	$data[$i]["ip"] = $rows["ip_address"];
	$data[$i]["geo_location"] = $rows["geo_location"];
	$data[$i]["register_date"] = $rows["register_date"];
	$data[$i]["activation_expire_date"] = $rows["activation_expire_date"];
	$data[$i]["activated"] = $rows["activated"];
	//check if users ip address is in the blacklist table
	$s = "select blacklist_ip from blacklist where blacklist_ip = '{$rows["ip_address"]}'";
	$r = mysqli_query($con, $s) or die(mysqli_error($con));
	if(mysqli_num_rows($r) > 0)
		$data[$i]["blacklisted"] = "1";
	else 
		$data[$i]["blacklisted"] = "0";
	mysqli_free_result($r);
	$i++;
}
function val_email($email)
{
	return filter_val($email,FILTER_VALIDATE_EMAIL);
}
if(!empty($data))
{?>
	<center>
	<table width="900" id="user-result-table" cellpadding="0" cellspacing="0" class="tablesorter">
		<thead>
  		<tr>	  		
	  		<th>Email Address</th>
	  		<th>IP Address</th>
	  		<th>Firm Type</th>
	  		<th>Location</th>
	  		<th>Registered <br/>Date</th>
	  		<th>Activation <br/>Expires</th>
	  		<th>Activated</th>
	  		<th>&nbsp;</th>	  
	  		<th>&nbsp;</th>  
  		</tr>
  		</thead>
  		<tbody>		  	
  		<?php 
  			$i = 0;
  			foreach($data as $key=>$values){
  			$back = ($i%2!=0)?"bgcolor=#ffffff":'';
  				?>  		
  		<tr <?php echo $back;?> id="user_id<?php echo $values["user_id"];?>">  			
  			<td><?php echo isset($values["email_address"])?$values["email_address"]:'';?></td>
  			<td>
  				<?php echo isset($values["ip_address"])?$values["ip_address"]:'';?>
  				<?php
	  				if($values["blacklisted"]){
	  			?>
	  				<span style="float:right;cursor:pointer;"><img onclick="unblockthis('<?php echo $values["ip"];?>')" src="images/red.gif" alt="unblock this IP" title="unblock this IP"></span>
	  			<?php } 
	  				else {
	  			?>
	  				<span style="float:right;cursor:pointer;"><img onclick="blockthis('<?php echo $values["ip"];?>')" src="images/green.gif" alt="block this IP" title="block this IP"></span>
	  			<?php } ?>
  			</td>
  			<td><?php echo isset($values["firm_type"])?$values["firm_type"]:'';?></td>
  			<td><?php echo isset($values["geo_location"])?$values["geo_location"]:'';?></td>
  			<td><?php echo isset($values["register_date"])?date("Y-m-d",strtotime($values["register_date"])):'';?></td>
  			<td><?php echo isset($values["activation_expire_date"])?date("Y-m-d",strtotime($values["activation_expire_date"])):'';?></td>
  			<td><?php echo isset($values["activated"])?$values["activated"]:'';?></td>
  			<td style="padding:0px;"><button id="editbtn" onclick="showuser('<?php echo $values["user_id"];?>')">Edit</button></td>
  			<td style="padding:0px;"><button id="delbtn" onclick="deleteuser('<?php echo $values["user_id"];?>','<?php echo $_SESSION['user_id'];?>')">Delete</button></td>
  		</tr>  		  		
  		<?php 
  			$i++;	
  			}
  		?>
  		</tbody>
  </table>
  </center>
  <div id="user-detail-form" title="User Details">
	  <div id="userdetails"></div>
  </div>
<?php 
}
else {
	echo "   No user exist for this selection.";
}
?>
<script>
	$(document).ready(function(){
		$("#user-result-table").tablesorter({
			headers:{
				7: {sorter:false},
				8: {sorter:false},
				9: {sorter:false}
			}
		});
		$('#user-detail-form').dialog({		
				autoOpen: false,
				height: 285,
				width: 640,
				modal: true,
				draggable: true,
				resizable: true,
				buttons: {
				"Save Changes": function() {
                    var userid = $('#userid').val();
                    var email = $('#emailadd').val();
                    var cctivation = $('#cctivation').val();
                    var actexpire = $('#actexpire').val();
                    var referer = $('#referer').val();
                    var source = $('#source').val();
                    var firmtype = $('#firmtype option:selected').text();
                    var geeloc = $('#geeloc option:selected').text();
                    var activated = $('#activated option:selected').text();
                    var usertypecode = $('#usertypecode option:selected').val();
                    var data1 = "phpfiles/upuserdetail.php?userid=" + userid + "&email=" + email + "&cctivation=" + cctivation + "&actexpire=" + actexpire + "&referer=" + referer + "&source=" + source + "&firmtype=" + firmtype + "&geeloc=" + geeloc + "&activated=" + activated + "&usertypecode=" + usertypecode;

                    var password = $('#password').val();
                    var confirm_pw = $('#confirm_pw').val();
                    if (password === confirm_pw && password !== '')
                        data1 += "&password=" + password;
                    else {
                        $('#errorchange').html("Passwords don't match");
                        return;
                    }

                    $.get(data1,function(data){
                        $('#errorchange').html(data);
                    });
				},
				'Close': function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
</script>
<script type="text/javascript">
	//var sorter=new table.sorter("sorter");
	//sorter.init("sorter",0);
</script>