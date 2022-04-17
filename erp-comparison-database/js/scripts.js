var dripEmail = "anonymous@unknown.com";
var DRIP_PRINT_TAG = "Print ERP Comparison Database Report";

$(document).ready(function () {

    _dcq.push(["identify", {
        // pick up email from the cookie... maybe later check for querystring parm
        success: function (response) {
            if (response.email) {
                dripEmail = response.email;
            }
        }
    }]);

    // $('#usage-information').hide();
    $('#usage-information').dialog({
        autoOpen: false,
        height: 400,
        width: 740,
        modal: true,
        draggable: true,
        resizable: true,
        open : function() {
            $('body').scrollTop(0);
        }
    });

    // $('#venpac-dialog-form').hide();
    $('#venpac-dialog-form').dialog({
        autoOpen: false,
        height: 400,
        width: 740,
        modal: true,
        draggable: true,
        resizable: true
    });
    // $('#venpac-dialog-form').dialog('destroy');

    // $("#tabs").tabs();

    $(function () {
        $("#startdate").datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true
        });
        $("#enddate").datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true
        });
    });
    //$( "#top-buttons").buttonset();
    $("#u-btn").button({
        icons: {primary: "ui-icon-info"}
    }).click(function () {

        // move this out of the iframe
        $('#usage-information').dialog('open').scrollTop("0");

        // window.open('../../packages/exERP-Comparison-Database-Interpretation.html', '_blank');
    });
    $("#e-btn").button({
        icons: {primary: "ui-icon-info"}
    }).click(function () {
        window.open('/erp-software-cost-control', '_blank');
    });
    //get report
    $('#submitquery').button().click(function () {
        var comsize = $('#companysize').val();
        var primindustry = $('#primindustry').val();
        var secindustry = $('#secindustry').val();
        var ifsubmitted = $('#ifsubmitted').val();
        if (primindustry == secindustry && (primindustry != -1)) {
            alert($("#primindustry option:selected").text() + " already selected, please select another industry.");
            return;
        }
        //var prienv = $('#prienvironment').val();
        //var secenv = $('#secenvironment').val();
        var specificpck = getvals();

        if (comsize < 0) {
            alert("Please Select Company Size Range.");
        }
        else {
            var data = "query.php?email=" + dripEmail + "&comsize=" + comsize + "&pri=" + primindustry + "&sec=" + secindustry + "&spec=" + specificpck;
            if (ifsubmitted == 0) {
                $("#showpackage").css("display", "block");
                $("#query-title-instruction").css("display", "none");
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
                    $('#query-result').html(html);
                    $('#query-result').fadeIn('slow');
                }
            });
        }

    });
    //get history
    $('#submithistory').click(function () {
        var tsdate = $('#startdate').val();
        var tedate = $('#enddate').val();
        var sdate = new Array();
        var edate = new Array();

        //for validation if start date is greater than end date
        var valstart = new Date(sdate);
        var valend = new Date(edate);
        if (valstart > valend) {
            alert('Invalid Date Range');
        }
        else {
            var data = 'history.php?startdate=' + tsdate + '&enddate=' + tedate;
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
    $('#userlist').click(function () {

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
    $('#showsearch').click(function () {
        $('#txtboxvname').val('');
        $('#txtboxpname').val('');
        $('#venpac-result').html('&nbsp;');
        $('#venpac-dialog-form').dialog('open');
    });


    $('#vpbtn').click(function () {
        var vname = $('#txtboxvname').val();
        var pname = $('#txtboxpname').val();
        //show loading graphic

        var data1 = 'package.php';
        if (vname == '' && pname == '') {
            alert("Please enter a value for Vendor or Product field.");
            return;
        }
        else if (vname != '' && pname == '') {
            data1 += '?vname=' + vname;
        }
        else if (pname != '' && vname == '') {
            data1 += '?pname=' + pname;
        }
        else if (vname != '' && pname != '') {
            data1 += '?vname=' + vname + '&pname=' + pname;
        }
        $("#loadingpackage").show();
        $.ajax({
            url: data1,
            type: "POST",
            cache: false,
            success: function (html) {
                $("#loadingpackage").hide();
                $('#venpac-result').html(html);
                $('#venpac-result').fadeIn('slow');
            }
        });
    });

});