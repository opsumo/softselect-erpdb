<?php
	if($_SESSION['admin_login']!="true")
	{
		echo "<script>menuButtonClick('Admin', 'Logout');</script>";
	}
?>
<script type="text/javascript">
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
  }
}

function validate_compare(field1, field2, alerttxt)
{
  if (field1.value!=field2.value)
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
}

function validate_form(thisform)
{
with (thisform)
  {
  	if (validate_required(txt_pword1,"Current Password must be filled out!")==false)
  		{txt_pword1.focus();return false;}
	if (validate_required(txt_pword2,"New Password must be filled out!")==false)
  		{txt_pword2.focus();return false;}
	if (validate_required(txt_pword3,"Confirm Password must be filled out!")==false)
  		{txt_pword3.focus();return false;}
	if (validate_compare(txt_pword2,txt_pword3,"New Password and Confirm Password must be the same!")==false)
  		{txt_pword3.focus();return false;}
  }
}
</script>

	<form action="index.php?module=Admin&mode=UpdatePassword" onsubmit="return validate_form(this)" method="post">
    	<table align="center" cellpadding="10">
        	<tr>
            	<td colspan="2" align="center"><h2 align="center">Change Password</h2></td>
            </tr>
            <tr>
            	<td><label>Current Password</label></td>
                <td><input type="password" name="txt_pword1" id="txt_pword1" /></td>
            </tr>
            <tr>
            	<td><label>New Password</label></td>
                <td><input type="password" name="txt_pword2" id="txt_pword2" /></td>
            </tr>
            <tr>
            	<td><label>Confirm Password</label></td>
                <td><input type="password" name="txt_pword3" id="txt_pword3" /></td>
            </tr>
            <tr>
            	<td colspan="2" align="center">
                	<input type="submit" value="Update Password" name="btn_update" />
                </td>
            </tr>
        </table>
    </form>