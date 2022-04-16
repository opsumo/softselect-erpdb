<?php
require_once("DB.Class.php");
if($_SESSION['admin_login']!="true")
{
	echo "<script>menuButtonClick('Admin', 'Logout');</script>";
}
class BlockIP extends DBCon
	{
function BlockIP()
	{
	parent::DBCon();
	switch($_REQUEST['mode'])
		{
		case "ManageBlockedIP":
			$this->ManageBlockedIP();
		break;
		
		case "NewBlockIP":
			$this->NewBlockIP();
		break;
		
		case "Create":
			$this->Create();
		break;
		
		case "Update":
			$this->Update();
		break;
		
		case "Delete":
			$this->Delete();
		break;
				
		default:
			$this->ManageBlockedIP();
		}
	}
	
 	function ManageBlockedIP()
	{
		$this->template="phpfiles/manageblockedip.php";
		$this->LoadFile();
	}
	
 	function NewBlockIP()
	{
		$mode = "Create";
		if(isset($_REQUEST['id']) && ($_REQUEST['id']!=""))
		{
			$mode = "Update";
			$blacklist_id = $_REQUEST['id'];
			$sql = "SELECT * FROM blacklist WHERE blacklist_id = '$blacklist_id'";
			$res = mysqli_query($this->con, $sql);
			if(!$res)
				echo mysqli_error($this->con);
			$row = mysqli_fetch_array($res);
		}
?>
<script>
	function validate()
	{
		var isvalid = true;
		var IPvalue = $("#blacklist_ip").val();
		
		if(!fnValidateIPAddress(IPvalue))
		{
			alert("Empty or Invalid ip address field.");
			
			return false;
		}
		else {
		return true;
		}
	}
	function fnValidateIPAddress(ipaddr) {
    //Remember, this function will validate only Class C IP.
    //change to other IP Classes as you need
    ipaddr = ipaddr.replace( /\s/g, "") //remove spaces for checking
    var re = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/; //regex. check for digits and in
                                          //all 4 quadrants of the IP
    if (re.test(ipaddr)) {
        //split into units with dots "."
        var parts = ipaddr.split(".");
        //if the first unit/quadrant of the IP is zero
        if (parseInt(parseFloat(parts[0])) == 0) {
            return false;
        }
        //if the fourth unit/quadrant of the IP is zero
        if (parseInt(parseFloat(parts[3])) == 0) {
            return false;
        }
        //if any part is greater than 255
        for (var i=0; i<parts.length; i++) {
            if (parseInt(parseFloat(parts[i])) > 255){
                return false;
            }
        }
        return true;
    } else {
        return false;
    }
}
</script>
<form action="index.php?module=BlockIP&mode=<?php echo $mode; ?>" method="post" onsubmit="return validate()">
	<table align="center" width="50%">
    	<tr>
        	<td colspan="2" align="center">
            	<?php if($blacklist_id) {?>
                <h2>Edit Blocked IP Details</h2>
                <?php } else { ?>
                <h2>New IP to Block</h2>
                <?php } ?>
            </td>
        </tr>
        <tr>
        	<td align="right"><strong>IP Address to Block:</strong></td>
            <td>
            	<input type="text" name="blacklist_ip" id="blacklist_ip" <?php if($blacklist_id) echo "value='".$row['blacklist_ip']."'"; ?> />
            </td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td align="left">
			<?php if($blacklist_id) { ?>
                <input type="hidden" name="blacklist_id" id="blacklist_id" value="<?php echo $row['blacklist_id']; ?>" />
            <?php } ?>
                <input type="submit" name="btn_<?php echo $mode; ?>" id="btn_<?php echo $mode; ?>" value="<?php echo $mode; ?>" />
        	</td>
        </tr>
    </table>
</form>
<?php
	}
	
 	function Create()
	{
		$ip = $_POST['blacklist_ip'];
		$sql = "INSERT INTO blacklist SET blacklist_ip='$ip', blacklist_date='".date("Y-m-d H:i:s")."'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		echo "<script>menuButtonClick('BlockIP', 'ManageBlockedIP');</script>";
	}
	
 	function Update()
	{
		$blacklist_id = $_REQUEST['blacklist_id'];
		$ip = $_POST['blacklist_ip'];
		$sql = "UPDATE blacklist SET blacklist_ip='$ip' WHERE blacklist_id = '$blacklist_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		echo "<script>menuButtonClick('BlockIP', 'ManageBlockedIP');</script>";
	}
	
	function Delete()
	{
		$blacklist_id = $_REQUEST['id'];
		$sql = "DELETE FROM blacklist WHERE blacklist_id = '$blacklist_id'";
		$res = mysqli_query($this->con, $sql);
		if(!$res)
			echo mysqli_error($this->con);
		echo "<script>menuButtonClick('BlockIP', 'ManageBlockedIP');</script>";
	}
}
?>