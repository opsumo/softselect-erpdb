<?php
	$user_id = $_REQUEST['id'];
	$sql3 = "SELECT * FROM user WHERE user_id = '$user_id'";
	$res3 = mysqli_query($con, $sql3);
	if(!$res3)
		echo mysqli_error($con);
	$row3 = mysqli_fetch_array($res3);
?>
<form action="index.php?module=Users&mode=Update" method="post">
	<table align="center" cellpadding="5">
    	<tr>
        	<td colspan="2" align="center">
            
            </td>
        </tr>
        <tr>
        	<td>Email Address</td>
            <td><input type="text" value="<?php echo $row3['email_address']; ?>" name="txt_email" id="txt_email" /></td>
        </tr>
        <tr>
        	<td>Firm Type</td>
            <?php
				$sql = "select firm_type_idn, firm_type_nme from firm_type order by firm_type_idn;";
				$rs = mysqli_query($con, $sql);
				$firmTypeOptions = "";
                while($row = mysqli_fetch_array($rs))
				{
                    $firmId = $row['firm_type_idn'];
                    $firmNme= $row['firm_type_nme'];  
					if($firmId==$row3['firm_type'])  
                    	$firmTypeOptions .= "<option selected='selected' value=\"$firmId\">$firmNme</option>";
					else  
                    	$firmTypeOptions .= "<option value=\"$firmId\">$firmNme</option>";
                }
				mysqli_free_result($rs);
			?>
            <td>
            	<select name="firm_type" id="firm_type">
					<?php echo $firmTypeOptions; ?>
                </select>
            </td>
        </tr>
        <tr>
        	<td>Geo Location</td>
            <?php
                $sql = "select geo_location_idn, geo_location_nme from geo_location order by geo_location_idn;";
				$rs = mysqli_query($con, $sql);
				$geoLocationOptions   = "";
                while ( $row  = mysqli_fetch_array( $rs ) ) {
	                $geoId  = $row['geo_location_idn'];
	                $geoNme = $row['geo_location_nme'];
					if($geoId==$row3['geo_location'])
						$geoLocationOptions .= "<option selected='selected' value=\"$geoId\">$geoNme</option>";
	                else
						$geoLocationOptions .= "<option value=\"$geoId\">$geoNme</option>";
                }
                mysqli_free_result( $rs );
            ?>
            <td>
            	<select name="location" id="location">
                	<?php echo $geoLocationOptions; ?>
                </select>
            </td>
        </tr>
        <tr>
        	<td>Activation</td>
            <td><input type="text" value="<?php echo $row3['activated']; ?>" name="txt_activation" id="txt_activation" /></td>
        </tr>
        <tr>
        	<td>User Type Code</td>
            <td><input type="text" value="<?php echo $row3['user_type_code']; ?>" name="user_code" id="user_code" /></td>
        </tr>
    	<tr>
        	<td colspan="2" align="center">
            	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" id="user_id" />
            	<input type="submit" name="btn_submit" value="Update" />
            </td>
        </tr>
    </table>
</form>