var DRIP_PRINT_TAG = "Print ERP Comparison Database Report";

function addpck(pname,pid)
	{
		var cnt = $('#specificpackage tr').length;

		//check if selected package id already selected
		if(!findIdSpecific(pid))
		{
			if(cnt<3)
			{
				if(cnt%2==0)
					var back= "#C8E3F9";
				else
					var back= "#eeeeee";
				var row = "<tr id="+pid+" style=\"background-color:"+back+";text-align:left;\"><td>"+pname+"</td><td style=\"text-align:right;cursor:pointer;\" title=\"Click to remove\"><img src=\"images/minus.gif\" onclick=\"rempck('"+pid+"')\"/></td></tr>";
				$('#specificpackage').append(row);
				$('#venpac-dialog-form').dialog( "close" );
				//this will automatically reload the query result,as per Mikes change 20110918
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
					var data = "query.php?email=" + dripEmail + "&comsize="+comsize+"&pri="+primindustry+"&sec="+secindustry+"&spec="+specificpck;
					
					$("#loading").show();
					$.ajax({
							url: data,  
							type: "POST", 
							cache: false,
							success: function (html) {
								$("#loading").hide();
								$('#query-result').html(html);
								$('#query-result').fadeIn('slow');       
							}       
						});
				}
			}
			else
			{
				alert('You have already selected three products');
				$('#venpac-dialog-form').dialog( "close" );
			}
		}
		else
		{
			alert('Package already selected');
		}
	}
	//search for package if already selected
	function findIdSpecific(id)
	{
		var existing = false;
			
		$('#specificpackage  tr').each(function(){
		if($(this).attr('id') == id)
		{
			existing = true;			
		}			
	 });
	return existing;
	}
	
	//function to remove row from table ,remove selected package
	function rempck(pid)
	{
		var id="#"+pid;
		$(id).remove();
		//this will automatically reload the query result,as per Mikes change 20110918
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
					var data = "query.php?email=" + dripEmail + "&comsize="+comsize+"&pri="+primindustry+"&sec="+secindustry+"&spec="+specificpck;
					
					$("#loading").show();
					$.ajax({
							url: data,  
							type: "POST", 
							cache: false,
							success: function (html) {
								$("#loading").hide();
								$('#query-result').html(html);
								$('#query-result').fadeIn('slow');       
							}       
						});
				}
	}
	//function to retrive ids(value) from each table,these are the selected package
	function getvals()
	{
		var ids = '';
		$('#specificpackage  tr').each(function(){
			if(ids=='')
			{
				ids += $(this).attr('id');
			}
			else
			{
				ids +=','+$(this).attr('id');
			}
		});
		return ids;
	}
	function printdata(id)
	{
        if (_dcq && DRIP_PRINT_TAG) {
            console.log('Setting report printed tag in Drip');
            _dcq.push(["identify", {tags: [DRIP_PRINT_TAG]}]);
		}
        else {
            console.log('no Print update to drip - vars not set');
		}

        // window.open("printdata.php?id="+id,'printwidow','width=1000,height=500');
		document.print_form.submit();
	}
	//date picker for History selection
	
	//function to add table row dynamically,add specific package	
	function hideloader()
	{
		$("#loading").hide();
	}
	
	function changepage()
	{
		var page = $('#pagenum').val();
		var data = 'userlist.php?page='+page;
		$.ajax({
				url: data,  
				type: "POST", 
				cache: false,
				success: function (html) {
					$('#usercrud-list').html(html);
					$('#usercrud-list').fadeIn('slow');       
				}       
			});	
	}