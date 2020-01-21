 <center>
	 <table border="0" cellpadding="1" cellspacing="2" style="font-family:Arial, Helvetica, sans-serif;font-size:small;">
	 	<tr>   
	    	<td>Email:<input type="text" name="email" id="email" size=30 /></td>
	    	<td>Registered:<input type="text" name="datefrom" id="datefrom" size=10 /></td>
	    	<td>to</td>
	    	<td><input type="text" name="dateto" id="dateto" size=10 /></td>
			<td>Activated: <input type="checkbox" name="activatedyes" id="activatedyes" value="Y"/></td><td>Yes</td> 
	    	<td><input type="checkbox" name="activatedno" id="activatedno" value="N"/>No</td>
            <td><input type="button" id="userBtnSubmit" value="Submit"/></td>
            <td><input type="button" id="userBtnNew" value="New"/></td>
	    	<td><div id="loader" style="display:none"><img src="images/ajaxloader.gif"/></td>
	    </tr>
	</table>
</center>
<hr/>
<div id="result">&nbsp;</div>