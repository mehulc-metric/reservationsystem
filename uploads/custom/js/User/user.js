$(document).ready(function () {
	$('.chosen-select-roleType').chosen(); 
	$('#customer_add_edit').parsley();
	var totalcheckbox;
	$('.selecctall').click(function(e){
		totalcheckbox = $('input[type="checkbox"]').length;
	});
	$('.checkbox1').click(function(e){
		var totalcheckbox1 = $('.checkbox1:checkbox:checked').length;
		
		if(totalcheckbox-1 != totalcheckbox1){
		
			$(".selecctall").prop('checked', false);
		}else{ 		
		
			$(".selecctall").prop('checked', true);
		}

	});
	
});
function customer_bulk_delete(){

        var allVals = [];
            $('#customer_ids :checked').each(function() {
              allVals.push($(this).val());
            });
            
            if(allVals != ""){                
                
            var delete_url = customerDeleteurl+"?customerid=" + allVals;
            var delete_meg = "Are you sure you want to delete selected Users ?";
            BootstrapDialog.show(
                    {
                        title: 'User Delete Confirm',
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
                        title: 'User Delete Confirm',
                        message: "Please Select User",
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
function delete_customers(id){
	if(id != ""){                
                
            var delete_url = customerDeleteurl+"?customerid=" + id;
            var delete_meg = "Are you sure you want to delete selected User ?";
            BootstrapDialog.show(
                    {
                        title: 'User Delete confirm',
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
                        title: 'User Delete confirm',
                        message: "Please Select User",
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
window.Parsley.addValidator('email', function (value, requirement) {
   
    var response = false;
    var currentEmailName = $("#email").val();
	
    $.ajax({
        type: "POST",
        url: checkEmailDuplicateURL,
        data: {email: currentEmailName, customer_id: customer_id},
        async: false,
        success: function (result) {
            
            //response = result;
            if (result == 1) {
                response = false;
            } else {
                response = true;
            }
        }
        /*error: function () {
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
        }*/
    });
    return response;
}, 32).addMessage('en', 'email', 'Entered Email is already exist.');
