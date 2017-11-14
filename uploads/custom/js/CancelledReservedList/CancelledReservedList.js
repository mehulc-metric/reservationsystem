$(document).ready(function () {
    
	$('.chosen-select-roleType').chosen();
    
	var totalcheckbox;
    
	$('.selecctall').click(function (e) {
        totalcheckbox = $('input[type="checkbox"]').length;
    });
	
    $('.checkbox1').click(function (e) {
        
		var totalcheckbox1 = $('.checkbox1:checkbox:checked').length;
        
		if (totalcheckbox - 1 != totalcheckbox1) {
            $(".selecctall").prop('checked', false);
        } else {
            $(".selecctall").prop('checked', true);
        }
		
    });
	
    $('.input-daterange').datepicker({});
	
	$('#customer_add_edit').parsley();
    $('#search_form').parsley();
});

// Export Excel file
function exportExcel() {

    var allVals = [];
    $('#customer_ids :checked').each(function () {
        allVals.push("'"+$(this).val()+"'");
    });  
    var checkedAll =  $('.selecctall:checked').val();
    
    if (allVals != "" && checkedAll !== "selecctall" ) {

        var controller_url = exportData + "?customerid=" + allVals;   
        var delete_meg = export_message;
        BootstrapDialog.show(
                {
                    title: export_data_confirmation,
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                window.location.href = controller_url;
                                dialog.close();
                            }

                        }]
                });
    }else if(checkedAll === "selecctall" && allVals != ""){ 
      
        var searchtext = $("#searchtext").val();
		var daterange = $("#daterange").val();
        //var start_date = $("#start_date").val();
        //var daterange = $("#start_time").val();
        //var end_date = $("#end_date").val();
        //var end_time = $("#end_time").val();
        
        //var controller_url = exportData+ "?searchtext="+searchtext+"&start_date="+start_date+"&start_time="+start_time+"&end_date="+end_date+"&end_time="+end_time;   
        var controller_url = exportData+ "?searchtext="+searchtext+"&daterange="+daterange;   
        var delete_meg = export_message;
       
        BootstrapDialog.show(
                {
                    title: export_data_confirmation,
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                window.location.href = controller_url;
                                dialog.close();
                            }

                        }]
                });      
    
    }else {
       
        BootstrapDialog.show(
                {
                    title: export_data_confirmation,
                    message: please_select_user,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();

                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {

                                dialog.close();
                            }

                        }]
                });
    }
}

function data_search_user(allflag)
{
    //var start_date = $("#start_date").val();
    //var end_date = $("#end_date").val();
    var daterange = $("#daterange").val();
	
    if ($('#search_form').parsley().validate() == true) {
        var uri_segment = $("#uri_segment").val();

        $.ajax({
            type: "POST",
            url: window.location.href + '\\index\\' + uri_segment,
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), daterange: daterange, sortfield:$("#sortfield").val(),sortby:$("#sortby").val(), allflag: allflag
            },
            beforeSend: function () {
                $('#common_div').block({message: 'Loading...'});
            },
            success: function (html) {
                $("#common_div").html(html);
                $('#common_div').unblock();
            }
        });
        return false;
    }
}

function apply_data_sorting(sortfilter,sorttype)
{
	$("#sortfield").val(sortfilter);
	$("#sortby").val(sorttype);
	data_search_user('changesorting');
}
	
function getCurrentTime() {
    var today = new Date();
    var hr = today.getHours();
    return hr + ':' + '00';
}

function getCurrentDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var today = yyyy + '-' + mm + '-' + dd;
    return today;
}

function reset_user_data()
{
   $("#searchtext").val("");
   $('input[name="daterange"]').val('');
   //$("#start_date").val("");
   //$("#start_time").val("");
   //$("#end_date").val("");
   //$("#end_time").val("");
   apply_sorting('','');
   data_search('all');
   $("#error_end_time").hide();
   $("#error_start_time").hide();
   
}