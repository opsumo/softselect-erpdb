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

function validate_form(thisform)
{
with (thisform)
  {
	  if (validate_required(txt_uname,"User Name must be filled out!")==false)
	  	{txt_uname.focus();return false;}
	  if (validate_required(txt_pword,"Password must be filled out!")==false)
	  	{txt_pword.focus();return false;}
  }
}
</script>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
	<form action="index.php?module=Admin&mode=Login" method="post" onsubmit="return validate_form(this)">
    <table align="center" style="border:1px solid #ddd;border-collapse:collapse;background:#ffffff;font-family:Arial,Verdana;">
    	<tr>
    	<td><img src="images/key.jpg" width="140px" height="140px"/></td>
    	<td>
			<table align="center" cellpadding="2" border="0">
	        	<tr>
	            	<td colspan="2" align="center" style="color:#0060AA;" valign="bottom"><h2>Admin Login</h2></td>
	            </tr>
	            <tr>
	            	<td align="right"><label>User Name:</label></td>
	                <td><input type="text" name="txt_uname" id="txt_uname" size="25"/></td>
	            </tr>
	            <tr>
	            	<td align="right"><label>Password:</label></td>
	                <td><input type="password" name="txt_pword" id="txt_pword" size="25"/></td>
	            </tr>
	            <tr>
	            	<td>&nbsp;</td>
	            	<td align="left">
	                	<input type="submit" value="Login" name="btn_submit" />
	                </td>
	            </tr>
	            <?php if(isset($_REQUEST['error']) && $_REQUEST['error']==1){?>
	            <tr>
	            	<td>&nbsp;</td>
	            	<td style="color:#ff0000;font-size:8pt;">Invalid username & password, please try again</td>
	            </tr>
	            <?php }
	            	else if(isset($_REQUEST['err']) && $_REQUEST['err']==='expire') {
	            ?>
	            <tr>
	            	<td>&nbsp;</td>
	            	<td style="color:#ff0000;font-size:8pt;">Session has expired, please log in again.</td>
	            </tr>
	            <?php } ?>
	        </table>
	      </td>
        </tr>
    </table>	
    </form>