<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="css/tab.css" />
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.5.custom.css" />
<link rel="stylesheet" type="text/css" href="css/style1.css" />
<link rel="stylesheet" type="text/css" href="css/sort.css" />
<!--<script type="text/javascript" src="js/script.js"></script>-->	
<script type="text/javascript" src="js/jquery-1.5.1.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>  
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="js/jquery.ui.button.js"></script>
<script type="text/javascript" src="js/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="js/jquery.ui.position.js"></script>
<script type="text/javascript" src="js/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="js/jquery.ui.dialog.js"></script> 	
<script type="text/javascript" src="js/jquery.effects.core.js"></script>	
<script type="text/javascript" src="js/jquery.ui.tabs.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>	
<script type="text/javascript" src="js/jquery.metadata.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script>
	function blockthis(ip)
	{
		var x=confirm("Are you sure you want to blacklist this IP address " +ip + "?");
		
		if(x) {
			var data = 'phpfiles/blockipfromuserlist.php?ip='+ip;
			$.ajax({
					url: data,  
					type: "POST", 
					cache: false,
					success: function (html) {
						$("#userBtnSubmit").click();      
					}       
				});
		}
	}
	
	function unblockthis(ip)
	{
		var x=confirm("Are you sure you want to remove this IP address " +ip + " from the blacklist?");
		
		if(x) {
			var data = 'phpfiles/unblockipfromuserlist.php?ip='+ip;
			$.ajax({
					url: data,  
					type: "POST", 
					cache: false,
					success: function (html) {
						$("#userBtnSubmit").click();      
					}       
				});
		}
	}

    function showuser(id)
    {
        $("#user-detail-form").dialog("open");

        var data = 'phpfiles/userdetails.php?id='+id;
        $.ajax({
            url: data,
            type: "POST",
            cache: false,
            success: function (html) {
                $('#userdetails').html(html);
                $('#userdetails').fadeIn('slow');
            }
        });
    }
	function deleteuser(id,adminid)
	{
		var x = confirm("Are you sure you want to delete this User?");
		if(x) {
			var data = 'phpfiles/userdelete.php?id='+id+'&adminid='+adminid;
			$.get(data, function(data){
				if(data == "OKAY") {
					$("#userBtnSubmit").click();
				}
			});
		}
	}
	function blackuser(id,adminid)
	{
		var x = confirm("Are you sure you want to blacklist this User?");
		if(x) {
			var data = 'phpfiles/userblack.php?id='+id+'&adminid='+adminid;
			$.get(data, function(data){
				if(data == "OKAY") {
					$("#userBtnSubmit").click();
				}
			});
		}
	}
	function editproduct(id)
	{
		$("#product-dialog").dialog("open");

		var data = 'phpfiles/productdetails.php?id='+id;
		$.ajax({
				url: data,  
				type: "POST", 
				cache: false,
				success: function (html) {
					$('#product-edit').html(html);
					$('#product-edit').fadeIn('slow');
                    $(function() {
                        $( "#review_date" ).datepicker({
                            showOn: "button",
                            buttonImage: "images/calendar.gif",
                            buttonImageOnly: true
                        });
                    });
                }
			});
		
			
	}
	function showSelReport(id)
	{
		var data = "phpfiles/showselreport.php?id="+id;
		$.ajax({
			url: data,  
			type: "POST", 
			cache: false,
			success: function (html) {
				$('#product-edit').html(html);
				$('#product-edit').fadeIn('slow');       
			}       
		});
	}
	function printdata(id)
	{
		//window.open("phpfiles/printdata.php?id="+id,'printwidow','width=1000,height=500');
		document.print_form.submit();
	}
</script>
<script>
	$(document).ready(function(){
	/**
	report/query
	*/
	$('#venpac-dialog-form').dialog('destroy');
		$('#venpac-dialog-form').dialog({		
			autoOpen: false,
			height: 400,
			width: 740,
			modal: true,
			draggable: true,
			resizable: true
	});
	$('#showsearch').click(function(){
		$('#txtboxvname').val('');
		$('#txtboxpname').val('');
		$('#venpac-dialog-form').dialog('open');
	});
	$('#submitquery').button().click(function(){
		var comsize = $('#companysize').val();
		var primindustry = $('#primindustry').val();
		var secindustry = $('#secindustry').val();
		var ifsubmitted = $('#ifsubmitted').val();
		if(primindustry == secindustry && (primindustry != -1))
		{
			alert($("#primindustry option:selected").text() + " already selected, please select another industry.");
			return;
		}
		//var prienv = $('#prienvironment').val();
		//var secenv = $('#secenvironment').val();
		var specificpck = getvals();
		
		if(comsize < 0)
		{
			alert("Please Select Company Size Range.");
		}
		else
		{
			var data = "phpfiles/query.php?comsize="+comsize+"&pri="+primindustry+"&sec="+secindustry+"&spec="+specificpck;
			if(ifsubmitted == 0){
				$("#showpackage").css("display","block");
				$("#query-title-instruction").css("display","none");
				$("#submitquery").val("Update ERP List");
			}
			else {
				$("#ifsubmitted").val("1");
			}
			$("#loading").show();
			$.ajax({
					url: data,  
					type: "POST", 
					cache: false,
					success: function (html) {
						$("#loading").hide();
						$('#result').html(html);
						$('#result').fadeIn('slow');       
					}       
				});
		}
		
	});
	$('#vpbtn').click(function(){
		var vname = $('#txtboxvname').val();
		var pname = $('#txtboxpname').val();
		
		var data1 = 'phpfiles/package.php';
		
		if(vname == '' && pname == '') {
			alert("Please enter a value for Vendor or Product field.");
			return;
		}
		else if(vname != '' && pname == '')
		{
			data1 += '?vname='+vname;
		}
		else if(pname != '' && vname == '')
		{
			data1 += '?pname='+pname;
		}
		else if(vname != '' && pname != '')
		{
			data1 += '?vname='+vname+'&pname='+pname;
		}		
		$.ajax({
				url: data1,  
				type: "POST", 
				cache: false,
				success: function (html) {
					$('#venpac-result').html(html);
					$('#venpac-result').fadeIn('slow');       
				}       
		});
	});	
	
	$('#showsearch').click(function(){
		$('#txtboxvname').val('');
		$('#txtboxpname').val('');
		$('#venpac-dialog-form').dialog('open');
	});
	//function to remove row from table ,remove selected package
	
	//end report/query
		
	var vDate = new Date();
	var nMonth = vDate.getMonth() + 1;
	
	$("#dateto").val(nMonth + '/' + vDate.getDate() + '/' + vDate.getFullYear());
	
	if($("#selto").val() == ''){
		$("#txt_to").val(nMonth + '/' + vDate.getDate() + '/' + vDate.getFullYear());
	}
	else {
		$("#txt_to").val($("#selto").val());
	}
	vDate.setMonth(vDate.getMonth() - 3);
	vDate.setDate(vDate.getDate() + 1);
	var nMonth = vDate.getMonth() + 1;
	$(function() {
		$( "#datefrom" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true
		});
		$( "#dateto" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			 maxDate: 'd'
		});
		$( "#from-date" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true
		});
		$( "#to-date" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			 maxDate: 'd'
		});
	});	
	$("#datefrom").val(nMonth + '/' + vDate.getDate() + '/' + vDate.getFullYear());
	if($("#selfrom").val() =='') {
		$("#txt_from").val(nMonth + '/' + vDate.getDate() + '/' + vDate.getFullYear());
	}
	else {
		$("#txt_from").val($("#selfrom").val());
    }
	$("#userBtnSubmit").click(function(){
		var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		var activated = "";
		if ($('#activatedyes').is(':checked'))
				activated = "Y";
		if ($('#activatedno').is(':checked'))
				activated = "N";
		var dfrom = $("#datefrom").val();
		var dto = $("#dateto").val();
		var email = $("#email").val();
		///used for verifying date comparison
		var dfromtemp = new Date(dfrom);
		var dtotemp = new Date(dto);
		if(email != '' && email.search(emailRegEx) == -1)
		{
			alert("Please provide a valid email address.");
			return;
		}
		if(dfromtemp > dtotemp) alert("Invalid date selection, From date is greater than To date.");
		else {
		var data = 'phpfiles/usersresult.php?email='+email+'&datefrom='+dfrom+'&dateto='+dto+'&activated='+activated;
		$("#loader").show();
		$.ajax({
				url: data,  
				type: "POST", 
				cache: false,
				success: function (html) {
					$("#loader").hide();
					$('#result').html(html);
					$('#result').fadeIn('slow');       
				}       
			});
		}
	});
    $("#userBtnNew").click(function() {
        {
            $("#user-detail-form").dialog("open");

            var data = 'phpfiles/userdetails.php';
            $.ajax({
                url: data,
                type: "POST",
                cache: false,
                success: function (html) {
                    $('#userdetails').html(html);
                    $('#userdetails').fadeIn('slow');
                }
            });
        }
    });
	$(function() {
		$( "#txt_from" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true
		});
		$( "#txt_to" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			maxDate: 'd'
		});
	});
	$("input[id=email]").focus(function(){
		$("#datefrom").val("");
		$("#dateto").val("");
	});
	$("input[id=activatedyes]").click(function(){
		$("input[id=activatedno]").attr("checked",false);
	});
	$("input[id=activatedno]").click(function(){
		$("input[id=activatedyes]").attr("checked",false);
	});
	$("#submit-trackerbtn").click(function(){
		var from = $("#from-date").val();
		var to = $("#to-date").val();
		var tfrom = new Date(from);
		var tto = new Date(to);
		
		if(tfrom > tto) {
			alert("Invalid date selection, From date is greater than To date.");
			return;
		}
		else {
			var data = 'phpfiles/tracker.php?from='+from+'&to='+to;
			$.ajax({
				url: data,  
				type: "POST", 
				cache: false,
				success: function (html) {					
					$('#result').html(html);
					$('#result').fadeIn('slow');       
				}       
			});
		}
	});
        $("#menu_home").click(function(e){
            $("#menu_form_module").val('Admin');
            $("#menu_form_mode").val('Home');

        });
        $("#menu_users").click(function(e){
            $("#menu_form_module").val('Users');
            $("#menu_form_mode").val('ManageUsers');

        });
        $("#menu_history").click(function(e){
            $("#menu_form_module").val('Report');
            $("#menu_form_mode").val('ManageReport');

        });
        $("#menu_report").click(function(e){
            $("#menu_form_module").val('Query');
            $("#menu_form_mode").val('QueryReport');

        });
        $("#menu_blacklist").click(function(e){
            $("#menu_form_module").val('BlockIP');
            $("#menu_form_mode").val('ManageBlockedIP');

        });
        $("#menu_logout").click(function(e){
            $("#menu_form_module").val('Admin');
            $("#menu_form_mode").val('Logout');

        });
        $("#menu_industry").click(function(e){
            $("#menu_form_module").val('Industry');
            $("#menu_form_mode").val('ManageIndustries');
        });
        $("#menu_vendor").click(function(e){
            $("#menu_form_module").val('Vendor');
            $("#menu_form_mode").val('ManageVendor');
        });
        $("#menu_product").click(function(e){
            $("#menu_form_module").val('Product');
            $("#menu_form_mode").val('ManageProduct');
        });
    $(function() {
        $( "#vendor_review_date" ).datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true,
            maxDate: 'd'
        });
    });
});

function valSubmit()
{
	var valid = true;
	var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var email = $("#txt_email").val();
	var dfrom = $("#txt_from").val();
	var dto = $("#txt_to").val();
	var dfromtmp = new Date(dfrom);
	var dtotmp = new Date(dto);
	if(email != '' && email.search(emailRegEx)==-1)
	{
		valid = false;
		alert("Please provide a valid email address.");
	}
	if(dfromtmp > dtotmp)
	{
		valid = false;
		alert("Invalid date selection, From date is greater than To date.");
	}
	return valid;
}
    function menuButtonClick(module, mode) {
        $("#menu_form_module").val(module);
        $("#menu_form_mode").val(mode);
        $("#menu_form").submit()
    }
</script>
<style type="text/css">
	.ui-widget {
		font-size: 0.8em;
	}
	#editbtn,#userBtnSubmit,#userBtnNew,#btn_report,#btn_go,#btn_vendor,#btn_Product{
		height:28px;
		border: 1px solid #fff;
		background:#277DB0;
		font-size: 1em;
		font-weight:bold;
		cursor:pointer;
		color:#fff;
	}
	#editbtn {
		height:28px;
		border: 1px solid #fff;
		background:#277DB0;
		font-size: .8em;
		font-weight:bold;
		cursor:pointer;
		color:#fff;
	}
	#delbtn,#blkbtn{
		height:28px;
		background:#277DB0;
		font-size: .8em;
		font-weight:bold;
		cursor:pointer;
		color:#fff;
		padding:0px;
	}
	#newproduct {
		height:28px;
		border: 1px solid #fff;
		background:#277DB0;
		font-size: 1em;
		font-weight:bold;
		cursor:pointer;
		color:#fff;
	}
	#pagecontainer {
		margin: 0 auto;
    	padding: 0;
    	width: 908px;
    	display: block;
	}
	#loading {display:none}
	#showpackage {display:none;}
	#loadingpackage {display:none;}
	.inputbtn {
		height:28px;
		border: 1px solid #ddd;
		background:#eee;
		font-size: 1em;
		cursor:pointer;
	}
	#his-legend {
		padding-left:2px;
		margin:0;
	}
	#his-legend li{
		list-style-type: none;	
	}
	.instruction-text {
		font-family: verdana;
		font-size: .7em;
		color: #332424;
	}
	html,body {
		font-family: Verdana, Arial, Helvetica, sans-serif;	
	}
</style>
</head>
<body>
<div id="wrapper">
        <div id="header">
            <div id="logo"></div>
         </div>
    <form id="menu_form" method="post">
        <input type="hidden" id="menu_form_module" name="module"/>
        <input type="hidden" id="menu_form_mode" name="mode"/>
<?php
if(isset($_SESSION['admin_login']) && $_SESSION['admin_login']==='true')
{
?>
         <div id="menu">
                 <ul>
                     <li><input type="submit" id="menu_home" value="Home" class="menu_link"/></li>
                     <li><input type="submit" id="menu_users" value="Users" class="menu_link"/></li>
                     <li><input type="submit" id="menu_history" value="History" class="menu_link"/></li>
                     <li><input type="submit" id="menu_report" value="Report" class="menu_link"/></li>
                     <li><input type="submit" id="menu_blacklist" value="IP Blacklist" class="menu_link"/></li>
                     <li><input type="submit" id="menu_vendor" value="Vendor" class="menu_link"/></li>
                     <li><input type="submit" id="menu_product" value="Product" class="menu_link"/></li>
                     <li><input type="submit" id="menu_industry" value="Industry" class="menu_link"/></li>
                     <li><input type="submit" id="menu_logout" value="Logout" class="menu_link"/></li>
                 </ul>
          </div>
<?php
	}

?>
    </form>

           <div id="content">
               <!--<div id="min_height">-->
<?php
require_once("classes/DB.Class.php");
$Obj = new DBCon();

if((isset($_SESSION['admin_login']) && $_SESSION['admin_login'] == "true") || (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "Login")) {
	if(isset($_REQUEST['module']) && $_REQUEST['module'] != ""){
		$Class_Name = $_REQUEST['module'];
		$file_name = "classes/".$Class_Name.".Class.php";
		if (file_exists($file_name)) {
			require_once($file_name); // Include that file
			$Class_Obj = new $Class_Name; // Create an instance for that class
		} else {
				$err_msg = "<center> Module not found </center>";
				echo $err_msg;
		}
	} else {
        echo "<script>menuButtonClick('Admin', 'Home')</script>";
    }
} else {
    require_once('phpfiles/login.php');
}
$year = (new DateTime())->format('Y');
?>
			<!--</div>-->
         </div>        
</div>
</div>
<div id="venpac-dialog-form" title="Select up to 3 ERP to include">
		<table border="0" cellspacing="0" cellpadding="1" width="90%" style="font-family: Verdana, Arial, Helvetica, Sans Serif;font-size:.9em;">
			<tr>
				<td style="font-weight:bold;">Vendor Name:</td>
				<td><input type="text" value="" id="txtboxvname"/></td>
				<td style="font-weight:bold;">Product Name:</td>
				<td><input type="text" value="" id="txtboxpname"/></td>
				<td style="font-weight:bold;"><input type="button" class="inputbtn" id="vpbtn" value="Search"/>
					<span id="loadingpackage" style="margin-left:4px;"><img src="images/ajaxloader.gif"/></span></td>
			</tr>			
		</table>
		<div id="venpac-result" style="padding-top:6px">&nbsp;</div>
	</div>
<div id="footer" class="white_11">
    	&copy; Copyright <?php echo $year ?> EAI - All rights reserved.
        </div>
</div>
</body>
</html>