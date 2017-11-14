$(document).ready(function () {
	
    $('.chosen-select-roleType').chosen();
    
	$('#customer_add_edit').parsley();
	$('#search_form').parsley();
	
	$('.input-daterange').datepicker({});
    
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
	
});

// Cancel the reservation click event
$(document).on("click", ".cancel_reservation", function() {

	var res_email = $(this).attr('data-email');
	var res_code = $(this).attr('data-code');
	var res_user_id = $(this).attr('data-id');
	
	var current_element  = $(this);
	BootstrapDialog.show(
	{
		title: cancel_reservation_confirmation,
		message: cancel_reservation_confirmation_message,
		buttons: [{
				label: 'Cancel',
				action: function (dialog) {
					dialog.close();
				}
			}, {
				label: 'Ok',
				action: function (dialog) {
					dialog.close();
					cancelReservationAjax(res_user_id, res_code, dialog); // Ajax call for cancel the reservation
					//dialog.close();
					//location.reload();
					//current_element.parent().parent().remove();
				}
			}]
	});
});

// Cancel Reservation function
function cancelReservationAjax(res_user_id, res_code, dialog){

	$.ajax({
		type: "POST",
		url: cancel_reservation_URL,
		data: {
			user_id: res_user_id, code: res_code
		},
		async : true,
		beforeSend: function () {
			dialog.close();
			$('#common_div').block({message: loading});
		},
		success: function (html) {
			dialog.close();
			$('#common_div').unblock();
			location.reload();
			/*$.alert({
					title: successfully,
					//backgroundDismiss: false,
					content: "<strong> " + cancel_reservation_successfully_msg + "<strong>",
					confirm: function () {
					}
			});*/
			
		}
	});
}

function resendEmails() {

    var allVals = [];
    $('#customer_ids :checked').each(function () {
        allVals.push($(this).val());
    });

    if (allVals != "") {

        var delete_url = sendEmailToSelectedUser + "?customerid=" + allVals;
        var delete_meg = are_you_sure_re_send_email_to_selected_user;
        BootstrapDialog.show(
                {
                    title: re_send_email_confirmation,
                    message: delete_meg,
                    buttons: [{
                            label: 'Cancel',
                            action: function (dialog) {
                                dialog.close();

                            }
                        }, {
                            label: 'Ok',
                            action: function (dialog) {
                                window.location.href = delete_url;
                                dialog.close();
                            }

                        }]
                });
    } else {

        BootstrapDialog.show(
                {
                    title: re_send_email_confirmation,
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


function exportExcel() {
	
	var delete_meg = export_message;
	
	var searchtext = $("#searchtext").val();
	var daterange = $("#daterange").val();
	
	var checkedAll =  $('.selecctall:checked').val();
	
	var appendSelectedIds = '';
    var allVals = [];
    $('#customer_ids :checked').each(function () {
        allVals.push("'"+$(this).val()+"'");
    });  
    
    //if (allVals != "") {
		//var appendSelectedIds = "&customerid=" + allVals;
    //}
	
	var controller_url = exportData + "?customerid=" + allVals +"&searchtext="+ searchtext +"&daterange="+ daterange;
	
	BootstrapDialog.show({
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
	
}

function data_search_user(allflag)
{

	var daterange = $("#daterange").val();
	
    if ($('#search_form').parsley().validate() == true) {
        var uri_segment = $("#uri_segment").val();

        $.ajax({
            type: "POST",
            url: window.location.href + '\\index\\' + uri_segment,
            data: {
                //result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), start_date: start_date,end_date: end_date, start_time: $("#start_time").val(), end_time: $("#end_time").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: allflag
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), daterange: daterange, sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: allflag
            },
            beforeSend: function () {
                $('#common_div').block({message: loading});
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
   
   apply_sorting('','');
   data_search('all');
   
   $("#error_end_time").hide();
   $("#error_start_time").hide();
}