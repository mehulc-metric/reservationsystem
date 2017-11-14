function deleteRole_t(role_id){
    var delete_meg =delete_role;
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
                    window.location.href = baseurl + '/Rolemaster/deletedata/' + role_id;					
                    dialog.close();
                }
            }]
        });
}


function deleteRole(role_id){

    var delete_meg =role_delete_confrim_msg;
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
                    window.location.href = "Rolemaster/deletedata/" + role_id;
                    dialog.close();
                }
            }]
        });
}
function deleteAssignedPermission(perms_id){
    var delete_meg = delete_assign_permission;
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
                    window.location.href = "Rolemaster/deleteAssignperms/" + perms_id;
                    dialog.close();
                }
            }]
        });
}
function deleteModuleController(module_id){
    var delete_meg =delete_module_and_controller;
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
                    window.location.href = "Rolemaster/deleteModuleData/" + module_id;
                    dialog.close();
                }
            }]
        });
}

$(document).ready(function () {
    $('#addrole').parsley();
    
  /*  $('.chosen-select').chosen();
    $('.chosen-select-salution').chosen();
    $('.chosen-select-status').chosen();
*/	
/*
    $('form#addrole').submit(function(e) {
        var form = $(this);
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // <--- THIS IS THE CHANGE
            dataType: "html",
            success: function(data){

                $("#ajaxModal").html(data);
                //$('#feed-container').prepend(data);
            },
            error: function() {
                var delete_meg ="Error posting feed.";
                BootstrapDialog.show(
                    {
                        title: 'Information',
                        message: delete_meg,
                        buttons: [{
                            label: 'ok',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });
            }
        });

    });
*/

});


$(".CRM_LIST_parent_horizontal_checkbox").change(function () {
    var chkbx=$(this).attr('data-tag');
    $("."+chkbx).prop('checked', $(this).prop("checked"));
    if($(this).prop('checked')==false){
        $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
        $("input[data-all=all_CRM_LIST]").prop('checked',$(this).prop("checked"));
    }else{
        if($("#CRM_LIST .child").length==$("#CRM_LIST input.child:checked").length){
            $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
            $("input[data-all=all_CRM_LIST]").prop('checked',$(this).prop("checked"));
        }
    }
});

$(".CRM_LIST_parent_horizontal_checkbox_All").change(function () {
    //var chkbx=$(this).attr('data-tag');
    //$("."+chkbx).prop('checked', $(this).prop("checked"));
    $("#CRM_LIST input:checkbox").prop('checked', $(this).prop("checked"));

});


$("#CRM_LIST").show();
$("#CRM_LI").addClass("active");

// For Edit Permission

$(".edit_CRM_LIST_parent_horizontal_checkbox").change(function () {
    var chkbx=$(this).attr('data-tag');
    $("."+chkbx).prop('checked', $(this).prop("checked"));
    if($(this).prop('checked')==false){
        $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
        $("input[data-all=edit_all_CRM_LIST]").prop('checked',$(this).prop("checked"));
    }else{
        if($("#edit_CRM_LIST .child").length==$("#edit_CRM_LIST input.child:checked").length){
            $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
            $("input[data-all=edit_all_CRM_LIST]").prop('checked',$(this).prop("checked"));
        }
    }
});

$(".edit_CRM_LIST_parent_horizontal_checkbox_All").change(function () {
    $("#edit_CRM_LIST input:checkbox").prop('checked', $(this).prop("checked"));

});


$(document).ready(function() {

    $('.edit_CRM_LIST_parent_horizontal_checkbox').each(function(){
        var chkClass=$(this).attr('data-tag');
        var datachecked = $(this).attr('data-box');
        if($('.'+chkClass).length != 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
        {
            $("input[data-box="+datachecked+"]").prop('checked',true);

        }else{
            $("input[data-box="+datachecked+"]").prop('checked',false);
        }
    });

    // For All Checkbox

    $('.edit_CRM_LIST_parent_horizontal_checkbox_All').each(function(){
        var chkClass=$(this).attr('data-tag');
        if($('.'+chkClass).length!= 0 && ( $('.'+chkClass).length==$('.'+chkClass+':checked').length))
        {

            $('.edit_CRM_LIST_parent_horizontal_checkbox_All').prop('checked',true);

        }else{
            $('.edit_CRM_LIST_parent_horizontal_checkbox_All').prop('checked',false);
        }
    });
});

function permsTab(module){
    if(module == "CRM"){
        $("#CRM_LIST").show();
        $("#CRM_LI").addClass("active");

    }

}

$('.parent').click(function () {
    var child = $(this).attr('data-attr');

    if (this.checked) {
        $('.' + child).each(function () { //loop through each checkbox
            this.checked = true; //select all checkboxes with class "checkbox1"
        });

    } else {
        $('.' + child).each(function () { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "checkbox1"
        });
        // For Edit  sections
        $(".edit_CRM_LIST_parent_horizontal_checkbox").prop('checked',false);

        $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',false);

        // For assign sections
        $(".CRM_LIST_parent_horizontal_checkbox").prop('checked',false);

        $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',false);

    }
    // Edit sections
    if($("input[data-all=edit_all_CRM_LIST]").length==$("input[data-all=edit_all_CRM_LIST]:checked").length){

        $(".edit_CRM_LIST_parent_horizontal_checkbox").prop('checked',true);
        $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
    }
    //Assign sections
    if($("input[data-all=all_CRM_LIST]").length==$("input[data-all=all_CRM_LIST]:checked").length){

        $(".CRM_LIST_parent_horizontal_checkbox").prop('checked',true);
        $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
    }
});


$('.child').click(function () {
    var chk=$(this).attr("data-parent");
    if($(this).prop('checked')===false){
        // for Edit Section
        $("input[data-tag='"+chk+"']").prop('checked',false);
        $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',false);
        // For Assign section

        $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',false);
    }else{
        if ($("input[data-parent='"+chk+"']").length === $("input[data-parent='"+chk+"']:checked").length) {
            $("input[data-tag='"+chk+"']").prop('checked',true);
        }
        // for Edit Section
        if($("#edit_CRM_LIST .child").length==$("#edit_CRM_LIST input.child:checked").length){
            $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
        }
        // For Assign Section
        if($("#CRM_LIST .child").length==$("#CRM_LIST input.child:checked").length){
            $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
        }
    }
    var parentCbox=$(this).attr('data-parent');
//		alert(parentCbox);
    var child = $(this).attr('data-attr');
    if ($('.child.' + child).length === $('.child.' + child + ':checked').length) {
        $('.parent.' + child).prop('checked', true);
    }
    else{
        $('.parent.' + child).prop('checked', false);
    }

});

// Edit permission Js
$('.permView').click(function (e) {
    var link = $(this).attr('data-href');
    e.preventDefault();
    $('#permissionView .modal-body').load(link);
    $('#permissionView').modal('show');

});
