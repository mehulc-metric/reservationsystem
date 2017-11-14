$(document).ready(function () {
    $('#customer_add_edit').parsley();
});

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
}, 32).addMessage('en', 'email', 'Entered Email is already exist.');