<script type="text/javascript">
function delete_confirm(id)
{
var r=confirm("Delete This Product Details?");
if (r==true)
  {
  	var url = 'index.php?module=Product&mode=Delete&id='+id;
	   window.location=url;
  }
else
  {
	return false;
  }
}
</script>
<script language="javaScript">
$(document).ready(function(){

$('#message').dialog({
			autoOpen: false,
			width: 500,
			draggable: false,
			resizable: false
		});//$('#dialog').dialog({

$('#dialog').dialog({
			autoOpen: false,
			width: 500,
			draggable: false,
			resizable: false,
			buttons: {
				  "Cancel": function() {
						$(this).dialog("close");
						//$('#login-form').submit();
						},
				  "Save": function() {

					var current_id = $('#product_id').val();
					var product_name = $('#product').val();

					var url = '';

					if(current_id<=0)
					{
					url = "module=Product&mode=NewProduct&name="+product_name;
					}
					else
					{
						url = "module=Product&mode=NewProduct&id="+current_id+"&name="+product_name;
					}
					//alert(url);

					 $.ajax({
					   type: "GET",
					   url: "ajax.php",
					   data: url,
					   success: function(msg){
							//alert( "Data Saved: " + msg );
							window.location.reload();
							$('#message').dialog('open');
					   }//function(msg)
					 });

					$(this).dialog("close");
					}
				}//"Save": function()

		});//$('#dialog').dialog({

});

function ShowDelete(id,name)
{
	var conf = confirm("Are you sure you want to delete, " +name);

	if(conf==true)
	{
		var url="module=Product&mode=Delete&id="+id;
//alert(url);
					 $.ajax({
					   type: "GET",
					   url: "ajax.php",
					   data: url,
					   success: function(msg){
							   window.location.reload();
					   }//function(msg)
					 });
	}
}

function AddNewLocation()
{
	$('#product_id').val(0);
	$('#product').val('');
	$('#new-product-dialog').dialog('open');
}


function EditLocation(id)
{
	$('#product').val('');
	$.getJSON("phpfiles/product_ajax.php",{ ajaxaction:"GETRECORDS", id:+id}, function(jsondata)
	{
		if("SUCCESS"==jsondata.message) //if correct login detail
		{
			var id = jsondata.resultdata.product_id;
			var product_name = jsondata.resultdata.product_name;
			$('#product_id').val(id);
			$('#product').val(product_name);
			//alert(id+"Success!"+product_name);
		}
		else
		{
			switch(jsondata.message)
			{
				case "NODATA":
					msg = "There is no data for this Edit.";
					break;
				default:
					msg = "Unknown error in processing: " + jsondata.message;
					break;
			}
			alert(msg);
		}
	});
	$('#dialog').dialog('open');
}

function deactivate_confirm(id)
{

var r=confirm("Deactivate This Product?");
if (r==true)
  {
	var url="module=Product&mode=Deactivate&id="+id;
	$.ajax({
		type: "GET",
		url: "ajax.php",
		data: url,
		success: function(msg){
			   window.location.reload();
		}
	});

  }
else
  {
	return false;
  }
}

function activate_confirm(id)
{
var r=confirm("Activate This Product?");
if (r==true)
  {
	var url="module=Product&mode=Activate&id="+id;
	$.ajax({
		type: "GET",
		url: "ajax.php",
		data: url,
		success: function(msg){
			  window.location.reload();
		}
	});
  }
else
  {
	return false;
  }
}

</script>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="5" align="center" width="95%" id="product-table">
	<tr height="60" bgcolor="#FFFFFF">
    	<td colspan="6" align="center" >
		   	<form method="get" action="">
            	<input type="hidden" name="module" value="Product" />
                <input type="hidden" name="mode" value="ManageProduct" />
            	<label><strong>Search Product by Name</strong>&nbsp;</label>
            	<input type="text" size="50" name="txt_Product" id="txt_Product" />&nbsp;
                <input type="submit" name="btn_Product" value="Go" id="btn_Product"/>
            </form>
        </td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td colspan="3">
		<p align="left">
            Filter status:
            <select id="istatus" onchange="reloadproduct()">
                <option value="all" <?php echo (isset($_REQUEST['stat']) && $_REQUEST['stat']=='all')?'selected':'';?>>All</option>
                <option value="active" <?php echo (isset($_REQUEST['stat']) && $_REQUEST['stat']=='active')?'selected':'';?>>Active</option>
                <option value="inactive" <?php echo (isset($_REQUEST['stat']) && $_REQUEST['stat']=='inactive')?'selected':'';?>>Inactive</option>
            </select>
            Sort:
            <select id="isort" onchange="reloadproduct()">
                <option value="product" <?php echo (isset($_REQUEST['sort']) && $_REQUEST['sort']=='product')?'selected':'';?>>Product</option>
                <option value="vendor" <?php echo (isset($_REQUEST['sort']) && $_REQUEST['sort']=='vendor')?'selected':'';?>>Vendor</option>
            </select><?php echo $order_by; ?>
		</p>
		</td>
		<td colspan="3">
        <p align="right">
        	<input type="button" id="newproduct" value="New Product" onclick="editproduct('');"/>
        </p>
        </td>
    </tr>

<?php
	include('ps_pagination.php');

	$conn = mysqli_connect(HOST_NAME, USER_NAME, PASS, DB_NAME);
	if(!$conn) echo mysqli_error($conn)."<br>Failed to connect to database!";
	// $status = mysqli_select_db(DB_NAME, $conn);
//	if(!$status) echo mysqli_error($conn)."<br>Failed to select database!";

//if(isset($_REQUEST['sort'])?$_REQUEST['sort']:'product' == 'vendor')
  if(isset($_REQUEST['sort']))
  {
    $sort = $_REQUEST['sort'];
    if($_REQUEST['sort'] == 'vendor')
      $order_by = 'v.vendor_name, p.product_name';
    else
      $order_by = 'p.product_name';
  } else $order_by = 'p.product_name';


	if(isset($_REQUEST['txt_Product']))
		$Product = $_REQUEST['txt_Product'];
	else
		$Product = "";
	if($Product!="")
	{
		$sql = "SELECT p.* FROM product p JOIN vendor v on v.vendor_id = p.vendor_id WHERE p.product_name LIKE '%$Product%' ORDER BY ".$order_by." ASC";
		$qs = "module=Product&mode=ManageProduct&txt_Product=".$Product;
	}
	else if(isset($_REQUEST['stat']))
	{
		$stat = $_REQUEST['stat'];
		if($stat === 'all') {
			$sql = "SELECT p.* FROM product p JOIN vendor v on v.vendor_id = p.vendor_id ORDER BY ".$order_by." ASC";
		    $qs = "module=Product&mode=ManageProduct&stat=".$stat."&sort=".$sort;
		}
		else if($stat === 'active') {
			$sql = "SELECT p.* FROM product p JOIN vendor v on v.vendor_id = p.vendor_id where p.status = 1 and v.status = 1 ORDER BY ".$order_by." ASC";
			$qs = "module=Product&mode=ManageProduct&stat=".$stat."&sort=".$sort;
		}
    else if($stat === 'inactive') {
			$sql = "SELECT p.* FROM product p JOIN vendor v on v.vendor_id = p.vendor_id where p.status = 0 and v.status = 0 ORDER BY ".$order_by." ASC";
			$qs = "module=Product&mode=ManageProduct&stat=".$stat."&sort=".$sort;
		}

	}
	else {
		$sql = "SELECT p.* FROM product p JOIN vendor v on v.vendor_id = p.vendor_id ORDER BY ".$order_by." ASC";
		$qs = "module=Product&mode=ManageProduct";
	}
	$pager = new PS_Pagination($conn, $sql, 30, 20, $qs);
	$pager->setDebug(true);
	$rs = $pager->paginate();
	if($rs && mysqli_num_rows($rs)>0)
	{
?>
  <tr>
  	<th style="padding-left:0px;">Vendor Name</th>
  	<th style="padding-left:0px;">Vendor URL</th>
  	<th style="padding-left:0px;">Product Name</th>
    <th>Status</th>
    <th>Edit</th>
    <th>Delete</th>
  </tr>
<?php
		$j = 0;
		while($row=mysqli_fetch_array($rs))
		{
			$sql1 = "SELECT * FROM vendor WHERE vendor_id='".$row['vendor_id']."'";
			$res1 = mysqli_query($conn, $sql1);
			$row1 = mysqli_fetch_array($res1);
            $url = $row1['www'];

            if (strpos($url, '://') === FALSE && $url ) {
                $url = 'http://'.$url;
            }

			if(($j%2)==0)
				$bgcolor = "#FFFFFF";
			else
				$bgcolor = "#E6E6E6";
?>
  <tr bgcolor="<?php echo $bgcolor; ?>">
  	<td><?php echo $row1['vendor_name']; ?></td>
  	<td><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></td>
  	<td><?php echo $row['product_name']; ?></td>
    <td align="center">
	<?php
        if($row['status']==1)
        {
    ?>
    	<a style="cursor:pointer;" onclick="deactivate_confirm(<?php echo $row['product_id']; ?>)"><img src="images/button_green.gif" alt="Active" border="0" /></a>
    <?php
        }
        else
        {
    ?>
    	<a style="cursor:pointer;" onclick="activate_confirm(<?php echo $row['product_id']; ?>)"><img src="images/button_red.gif" alt="Inactive" border="0" /></a>
    <?php
        }
    ?>
    </td>
    <td align="center"><a style="cursor:pointer;" onclick="editproduct(<?php echo $row['product_id'];?>);"><img src="images/b_edit.png" border="0" alt="Edit" /></a></td>
    <td align="center"><a style="cursor:pointer;" onClick="ShowDelete(<?php echo $row['product_id'];?>,'<?php echo $row['product_name'];?>');"><img src="images/deleted.png" border="0" alt="Delete" /></a></td>
  </tr>
<?php
			$j++;
		}
?>
  <tr bgcolor="#FFFFFF">
    <td colspan="6" align="center">
        <?php echo $pager->renderFullNav(); ?>
    </td>
  </tr>
<?php
	}
	else
	{
		echo "<tr><td colspan='5'><strong>No Products</strong></td></tr>";
	}
?>
</table>
</form>
<script>
function reloadproduct(){
	//alert('here');
	var e = document.getElementById('istatus');
    var stat = e.options[e.selectedIndex].value;
    var e = document.getElementById('isort');
    var sort = e.options[e.selectedIndex].value;

	window.location='index.php?module=Product&mode=ManageProduct&stat='+stat+'&sort='+sort;

}
</script>
<div id="product-dialog" title="Edit Product">
	<div id="product-edit"></div>
</div>
<!--
<div id="new-product-dialog" title="New Product">
	<div id="new-product-edit"></div>
</div>
-->
<div id="message" title="Message">
	<div align="center" id="message_space">Product Updated</div>
</div>
<script>
	$(document).ready(function(){

		/*
		$("#newproduct").click(function(){
			$('#new-product-dialog').dialog('open');
			var data = "phpfiles/newproduct.php";
			$.ajax({
				url: data,
				type: "POST",
				cache: false,
				success: function (html) {
					$('#new-product-edit').html(html);
					$('#new-product-edit').fadeIn('slow');
				}
			});
		});
		*/

		$('#product-dialog').css('height','auto');
		//$('#new-product-dialog').css('height','auto');
		$('#product-dialog').dialog({
				autoOpen: false,
				width: 950,
				height: 700,
				modal: true,
				draggable: true,
				resizable: true,
				buttons: {
				"Save Changes": function(evt) {
					//get Cost Range data
					var cnt = $("#prodcostrange tr").length;
					var pid = $("#productid").val();
					var product_name = $("#product_name").val();
					var vendor_id = $("#vendors").val();
					var review_date = $("#review_date").val();
          var www = $("#www").val();
          var notes = $("#notes").val();
          var mtco = $("#mtxo").val(); console.log("MTXO::>>%s",mtco);

					//review_date = $.datepicker.formatDate('yy-dd-mm', review_date);

					if(cnt > 1)
					{
						var procost = "{";
						var id = "";
						var ida = "";
						var value = "";
						var i = 0;
						$("#prodcostrange tr").each(function(){
							id = $(this).attr("id").substr(3);
							ida = 	$(this).attr("id");
							//value = $("[id='"+ida+"']:radio:checked").val();
							value = $('input[name='+ida+']:checked').val();

							if(value!=undefined && value!='undefined' && ida !="")
							{
								if(i==(cnt-1))
								{
									procost+=id+":"+value;
								}
								else
								{
									procost+=id+":"+value+",";
								}
							}
							i++;
						});
						procost+="}";
					}
					//Get Product Market Data
					var cnt2 = $("#prodmarket tr").length;
					var prodmarket = Array();
					if(cnt2 > 1)
					{
						var id1 = "";
						var id1a = "";
						var value1 = "";
						var promarket = "{";
						var x = 0;
						$("#prodmarket tr").each(function(){
							id1 = $(this).attr("id").substr(3);
							id1a = 	$(this).attr("id");
							//value1 = $("[id='"+id1a+"']:radio:checked").val();
							value1 = $('input[name='+id1a+']:checked').val();

							if(value1!=undefined && value1!='undefined' && id1a !="")
							{
								if(x==(cnt2-1))
								{
									promarket+=id1+":"+value1;
								}
								else
								{
									promarket+=id1+":"+value1+",";
								}
							}
							x++;
						});
						promarket+="}";
					}

					var url = "phpfiles/saveproductschanges.php";
					//var post_data_obj = {id:pid, prod_name:prod_name, vendor_id:vendor_id, review_date:review_date, notes:notes}; //, 'procost[]':procost, 'promarket[]':promarket};
					var post_data = {
                    id:pid,
										prod_name:product_name,
										vendor_id:vendor_id,
										review_date:review_date,
                    www:www,
                    notes:notes,
                    mtco:mtco,
										'procost[]':procost,
										'promarket[]':promarket
									}; //, 'procost[]':procost, 'promarket[]':promarket};
                    var post_data_qry = $.param(post_data);
                    // show hourglass while saving
                    $(document.body).css({'cursor' : 'wait'});
                    // get DOM element for button
                    var buttonDomElement = evt.target;
                    // Disable the button
                    $(buttonDomElement).attr('disabled', true);

                    $.post("phpfiles/saveproductschanges.php", post_data,
						function(response) {
        					$('#product-dialog').dialog( "close" );
        					$("#btn_Product").click();
                            // show hourglass while saving
                            $(document.body).css({'cursor' : 'default'});
    				});

				},
				'Cancel': function() {
					$( this ).dialog( "close" );
				}
			}
		});
		//New Product Dialog Box
		$('#new-product-dialog').dialog({
				autoOpen: false,
				width:850,
				height: 600,
				modal: true,
				draggable: true,
				resizable: true,
				buttons: {
				"Save Changes": function(evt) {
				var prodname = $("#prodname").val();
				var vendorid = $("#vendosel").val();

				if(prodname != "")
				{
					var cnt = $("#prodcostrange tr").length;
					if(cnt > 1)
					{
						var procost = "{";
						var id = "";
						var ida = "";
						var value = "";
						var i = 0;
						var tdid = "";
						$("#prodcostrange tr").each(function(){
							ida = $(this).attr("id");
							id = ida.substr(3);
							//value = $("[id="+ida+"]:radio:checked").val();
							value = $('input[name='+ida+']:checked').val();

							if(value!=undefined && value!='undefined' && ida !="")
							{
								if(i==(cnt-1))
								{
									procost+=id+":"+value;
								}
								else
								{
									procost+=id+":"+value+",";
								}
							}
							i++;

						});
						procost+="}";
					}

					//Get Product Market Data
					var cnt2 = $("#prodmarket tr").length;
					var prodmarket = Array();
					if(cnt2 > 1)
					{
						var id1 = "";
						var id1a = "";
						var value1 = "";
						var promarket = "{";
						var x = 0;
						$("#prodmarket tr").each(function(){
							id1a = 	$(this).attr("id");
							id1 = id1a.substr(3);
							//value1 = $("[id='"+id1a+"']:radio:checked").val();
							value1 = $('input[name='+id1a+']:checked').val();
							if(value1!=undefined && value1!='undefined' && id1a !="")
							{
								if(x==(cnt2-1))
								{
									promarket+=id1+":"+value1;
								}
								else
								{
									promarket+=id1+":"+value1+",";
								}
							}
							x++;
						});
						promarket+="}";
					}

					var url = "phpfiles/savenewproduct.php?prodname="+prodname+"&vendorid="+vendorid;
                    // show hourglass while saving
                    $(document.body).css({'cursor' : 'wait'});
                    // get DOM element for button
                    var buttonDomElement = evt.target;
                    // Disable the button
                    $(buttonDomElement).attr('disabled', true);
                    console.log('post data:', post_data);
                    $.post(url,{'procost[]':procost,'promarket[]':promarket}, function(response) {
        					/*$("#save-result").html(response);*/
        					$('#new-product-dialog').dialog( "close" );
        					$("#btn_Product").click();
                            $(document.body).css({'cursor' : 'default'});
                    });
				}
				else
				{
					alert("Empty Product Name.");
				}
				},
				'Close': function() {
					$( this ).dialog( "close" );
				}
			}
		});

	});
</script>
