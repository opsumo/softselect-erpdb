$(document).ready(function () {
	$( "#tabs" ).tabs();
	$(function() {
		$( "#startdate" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true
		});
		$( "#enddate" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true
		});
	});
	$('#venpac-dialog-form').dialog('destroy');
		//get report
	$('#submitquery').button().click(function(){
		var comsize = $('#companysize').val();
		var primindustry = $('#primindustry').val();
		var secindustry = $('#secindustry').val();
		var specificpck = getvals();
		
		if(comsize < 0)
		{
			alert("Please Select Company Size Range.");
		}
		else
		{
			var data = "query.php?comsize="+comsize+"&pri="+primindustry+"&sec="+secindustry+"&spec="+specificpck;
			$.ajax({
					url: data,  
					type: "POST", 
					cache: false,
					success: function (html) {
						$('#query-result').html(html);
						$('#query-result').fadeIn('slow');       
					}       
				});
		}
		
	});
	//get history
	$('#submithistory').click(function(){
		var tsdate = $('#startdate').val();
		var tedate = $('#enddate').val();
		var sdate = new Array();
		var edate = new Array();
		
		//for validation if start date is greater than end date
		var valstart = new Date(sdate);
		var valend = new Date(edate);
		if(valstart > valend)
		{
			alert('Invalid Date Range');
		}
		else
		{
			var data = 'history.php?startdate='+tsdate+'&enddate='+tedate;
			$.ajax({
					url: data,  
					type: "POST", 
					cache: false,
					success: function (html) {
						$('#history-result').html(html);
						$('#history-result').fadeIn('slow');       
					}       
				});
		}
	});
	//get userslist
	$('#userlist').click(function(){
		
		var data = 'userlist.php';
		$.ajax({
				url: data,  
				type: "POST", 
				cache: false,
				success: function (html) {
					$('#usercrud-list').html(html);
					$('#usercrud-list').fadeIn('slow');       
				}       
			});		
	});
	$('#showsearch').click(function(){
		$('#txtboxvname').val('');
		$('#txtboxpname').val('');
		$('#venpac-dialog-form').dialog('open');
	});
	$('#venpac-dialog-form').dialog({
		
		autoOpen: false,
		height: 400,
		width: 740,
		modal: true,
		draggable: true,
		resizable: true
	});
	$('#vpbtn').click(function(){
		var vname = $('#txtboxvname').val();
		var pname = $('#txtboxpname').val();
		
		var data1 = 'package.php';
		if(vname != '' && pname == '')
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
});