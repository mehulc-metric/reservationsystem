$(document).ready(function () {
	
    $('.chosen-select-roleType').chosen();
    $('#customer_add_edit').parsley();
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
    $('#search_form').parsley();
      
    $('body').delegate('[data-toggle="ajaxModal"]', 'click',
        function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
                var $this = $(this)
                    , $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href')
                    , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                    $('body').append($modal);
                    $modal.modal();
                    var url=$remote;                   
                    $modal.load(url);
        }
    );


    
});

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
        var start_date = $("#start_date").val();
        var start_time = $("#start_time").val();
        var end_date = $("#end_date").val();
        var end_time = $("#end_time").val();
        
        var controller_url = exportData+ "?searchtext="+searchtext+"&start_date="+start_date+"&start_time="+start_time+"&end_date="+end_date+"&end_time="+end_time;   
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

// Searching
function data_search_user(allflag)
{
    var start_date = $("#start_date").val();
     var end_date = $("#end_date").val();
    if ($('#search_form').parsley().validate() == true) {
        var uri_segment = $("#uri_segment").val();

        $.ajax({
            type: "POST",
            url: window.location.href + '\\index\\' + uri_segment,
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), start_date: start_date,end_date: end_date, start_time: $("#start_time").val(), end_time: $("#end_time").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: allflag
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

// Reset filter data
function reset_user_data()
{
   $("#searchtext").val("");
   $("#start_date").val("");
   $("#start_time").val("");
   $("#end_date").val("");
   $("#end_time").val("");
   apply_sorting('','');
   data_search('all');
   $("#error_end_time").hide();
   $("#error_start_time").hide();
}

// Delete teh customer
function delete_customers(id){
	
	if(id != ""){                
                
            var delete_url = deleteRecord+"?id=" + id;
            var delete_meg = delete_selected_data;
            BootstrapDialog.show(
                    {
                        title: 'Delete confirm',
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
        }else{
		
			BootstrapDialog.show(
                    {
                        title: delete_confirm,
                        message: please_select_data,
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


// Check duplicate zipcode
window.Parsley.addValidator('zipcode', function (value, requirement) {

    var response = false;
    var currentZipcode = $("#zip_code").val();

    $.ajax({
        type: "POST",
        url: checkduplicateZipcodeURL,
        data: {zipcode: currentZipcode, editId: editId}, // <--- THIS IS THE CHANGE
        async: false,
		beforeSend: function () {
            $('#common_div').block({message: loading});
        },
        success: function (result) {

            //response = result;
            if (result == 1) {
                response = false;
            } else {
                response = true;
            }
        },
        error: function () {
            // alert("Error posting feed.");
            var error_msg = "";
            BootstrapDialog.show(
                    {
                        title: 'Information',
                        message: error_msg,
                        buttons: [{
                                label: 'ok',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                    });
        }
    });
    return response;
}, 32).addMessage('en', 'zipcode', duplicateErrorMessage);

function zipcode_bulk_delete(){
		
        var allVals = [];
		$('#customer_ids :checked').each(function() {
		  allVals.push($(this).val());
		});
            
        if(allVals != ""){
                
            var delete_meg = confirm_zip_code_delete_msg;
            BootstrapDialog.show(
                    {
                        title: 'Zip Code Delete Confirm',
                        message: delete_meg,
                        buttons: [{
                                label: 'Cancel',
                                action: function (dialog) {
                                    dialog.close();

                                }
                            }, {
                                label: 'Ok',
                                action: function (dialog) {
                                    //window.location.href = delete_url;
                                    dialog.close();
									
									$.ajax({
										type: "POST",
										url: zipCodeDeleteURL,
										data: {
											deletedIds: allVals
										},
										beforeSend: function () {
											$('#common_div').block({message: 'Loading...'});
										},
										success: function (data) {
											$('#common_div').unblock();
											var obj = jQuery.parseJSON(data);
											location.href = obj.redirecturl;
										}
									});
                                }

                            }]
                    });
        }else{
		
			BootstrapDialog.show(
                    {
                        title: 'Zipcode Delete Confirm',
                        message: "Please Select Zipcode",
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