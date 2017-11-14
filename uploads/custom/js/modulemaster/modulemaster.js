$(document).ready(function () {
    $('#moduleform').parsley();
});


function delete_request(CatId){
    var delete_meg ="Are You Sure Want to Delete This Module from list ?";
    BootstrapDialog.show(
        {
            title: 'Information',
            message: delete_meg,
            buttons: [{
                label: 'Cancel',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: 'ok',
                action: function(dialog) {
                    window.location.href = baseurl +'/ModuleMaster/deleteModuleData/' + CatId;
                    dialog.close();
                }
            }]
        });
}