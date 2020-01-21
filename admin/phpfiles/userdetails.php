<?php
include_once '../classes/config.php';
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$id = !empty($_REQUEST['id'])?$_REQUEST['id']:'';

if ($id !== '') {
//if(empty($_SESSION['userid'])) die();
//db instance
    $result = array();
    $sql = "select * from user where user_id=" . $id;
    $res = mysqli_query($con, $sql) or die(mysqli_error($con));
    while ($rows = mysqli_fetch_array($res)) {
        $result[0]["email"] = $rows["email_address"];
        $result[0]["firmtype"] = $rows["firm_type"];
        $result[0]["geo"] = $rows["geo_location"];
        $result[0]["actexp"] = $rows["activation_expire_date"];
        $result[0]["act"] = $rows["activated"];
        $result[0]["usertypecode"] = $rows["user_type_code"];
        $result[0]["referer"] = $rows["referer"];
        $result[0]["sourcename"] = $rows["source_id"];
        $result[0]["actcode"] = $rows["activation_code"];
    }

    mysqli_free_result($res);
}

if(empty($result)) {
    $result[0]["email"] ='';
    $result[0]["firmtype"] = 'Please Select';
    $result[0]["geo"] = 'North America';
    $result[0]["actexp"] = '';
    $result[0]["act"] = 'Y';
    $result[0]["usertypecode"] = 0;
    $result[0]["referer"] = '';
    $result[0]["sourcename"] = '';
    $result[0]["actcode"] = '';
}

$usertypecode = array('1'=>"Internal",'0'=>"External");
$acti = array("N","Y");
$firm = array("Manufacturing","Distribution","Professional Services","Software Vendor","Government","Retail","Other");
$geo = array("North America","UK","Australia","Other");

if(!empty($result))
{
?>
<input type="hidden" value="<?php echo $id;?>" id="userid"/>
<fieldset>
	<legend>User ID: <?php echo $id;?> </legend>
	<table>
		<tr>
			<td align="right">Email:</td><td><input type="text" value="<?php echo !empty($result[0]['email'])?$result[0]['email']:'';?>" id="emailadd" size="30"/></td>
			<td align="right">Activation:</td><td><input type="text" value="<?php echo !empty($result[0]['actcode'])?$result[0]['actcode']:'';?>" id="cctivation"/></td>
		</tr>
		<tr>
			<td align="right">Firm Type:</td>
			<td>
				<select id="firmtype">
					<option value="">Please Select</option>
					<?php foreach($firm as $key=>$val):?>
						<?php $selfirm = ($val == $result[0]['firmtype'])?"selected='selected'":'';?>
						<option value="<?php echo $val;?>" <?php echo $selfirm;?>><?php echo $val;?></option>
					<?php endforeach;?>
				</select>
			</td>
			<td align="right">Geo Location:</td>
			<td>
				<select id="geeloc">
					<option value="">Please Select</option>
					<?php foreach($geo as $key=>$val1): ?>
					<?php $selgeo = ($val1 == $result[0]['geo'])?"selected='selected'":'';?>
						<option value="<?php echo $val1;?>" <?php echo $selgeo;?>><?php echo $val1;?></option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Act. Expiration:</td><td><input type="text" value="<?php echo !empty($result[0]['actexp'])?date('Y-m-d',strtotime($result[0]['actexp'])):'';?>" id="actexpire"/></td>
			<td align="right">Activated:</td>
			<td>
				<table>
					<tr>
						<td>
							<select id="activated">
								<?php foreach ($acti as $key=>$val2): ?>
								<?php $selact = ($val2==$result[0]['act'])?"selected='selected'":'';?>
								<option value="<?php echo $val2;?>" <?php echo $selact;?>><?php echo $val2;?></option>
								<?php endforeach;?>
							</select>
						</td>
						<td align="right">
							Type:
						</td>
						<td>
							<select id="usertypecode">
								<?php foreach ($usertypecode as $key=>$val3): ?>
								<?php $selcode = ($key==$result[0]['usertypecode'])?"selected='selected'":'';?>
								<option value="<?php echo $key;?>" <?php echo $selcode;?>><?php echo $val3;?></option>
								<?php endforeach;?>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
        <tr>
            <td align="right">Referer:</td><td><input type="text" value="<?php echo !empty($result[0]['referer'])?$result[0]['referer']:'';?>" id="referer"/></td>
            <td align="right">Source:</td><td><input type="text" value="<?php echo !empty($result[0]['sourcename'])?$result[0]['sourcename']:'';?>" id="source"/></td>
        </tr>
        <tr>
            <td align="right">Password:</td><td><input type="password" name="password" id="password"/></td>
            <td align="right">Confirm:</td><td><input type="password" name="confirm_pw" id="confirm_pw"/></td>
        </tr>
		<tr>
			<td colspan="2" align="left"><div id="errorchange"></div></td>
		</tr>	
	</table>
</fieldset>
<?php } ?>
<script>
	$(document).ready(function(){

		$("#actexpire").datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: 'yy-mm-dd'
		});
	});
</script>