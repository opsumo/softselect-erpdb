<form action="index.php?module=Report&mode=SelectDetails" method="post">
	<table align="center" width="40%" cellpadding="5">
    	<tr>
        	<td colspan="2" align="center"><h2>Report Page</h2></td>
        </tr>
        <tr>
        	<td>Company Size</td>
        	<td>
            <?php
				$sql1 = "SELECT * FROM company";
				$res1 = mysqli_query($con, $sql1);
				if(!$res1)
                    echo mysqli_error($con);
			?>
           		<select name="company_size" id="company_size">
                	<option value="">--- Please Select ---</option>
            <?php
                while($row1 = mysqli_fetch_array($res1))
                {
                    echo "<option value='".$row1['company_id']."'>".$row1['company_size']."</option>";
                }
            ?>
            	</select>
            </td>
        </tr>
        <tr>
        	<td>Primary Industry</td>
        	<td>
            <?php
				$sql2 = "SELECT * FROM industry";
				$res2 = mysqli_query($con, $sql2);
				if(!$res2)
                    echo mysqli_error($con);
			?>
           		<select name="primary_industry" id="primary_industry">
                	<option value="">--- Please Select ---</option>
            <?php
                while($row2 = mysqli_fetch_array($res2))
                {
                    echo "<option value='".$row2['industry_id']."'>".$row2['industry_name']."</option>";
                }
            ?>
            	</select>
            </td>
        </tr>
        <tr>
        	<td>Secondary Industry</td>
        	<td>
            <?php
				$sql3 = "SELECT * FROM industry";
				$res3 = mysqli_query($con, $sql3);
				if(!$res3)
                    echo mysqli_error($con);
			?>
           		<select name="secondary_industry" id="secondary_industry">
                	<option value="">--- Please Select ---</option>
            <?php
                while($row3 = mysqli_fetch_array($res3))
                {
                    echo "<option value='".$row3['industry_id']."'>".$row3['industry_name']."</option>";
                }
            ?>
            	</select>
            </td>
        </tr>
        <tr>
        	<td>Packages to add</td>
        	<td></td>
        </tr>
        <tr>
        	<td colspan="2" align="center">
            	<input type="submit" name="btn_submit" value="Get Report" />
            </td>
        </tr>
    </table>
</form>