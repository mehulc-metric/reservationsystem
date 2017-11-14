$(document).ready(function () {
    $('.chosen-select-roleType').chosen();
    $('#customer_add_edit').parsley();
    $('#monthbox').hide();
    $('.input-daterange').datepicker({
    });
});
/*intialize datepicker*/
$(function () {
    $('#start_date').datetimepicker({
        format: 'Y-m-d',
        minDate: new Date(),
        timelistScroll: false,
        scrollInput: false,
        //maxDate:next_month_date,
        onShow: function (ct) {
            /*var from = $("#end_date").val().split("/");
             var dt = new Date(from[2], from[1] -1 , from[0]);*/
            var dt = $("#end_date").val();
            if (dt != '')
            {
                this.setOptions({
                    maxDate: dt ? dt : false
                })
            }
        },
        timepicker: false
    });
    $('#end_date').datetimepicker({
        format: 'Y-m-d',
        //minDate: '0',
        //maxDate: new Date(),
        timelistScroll: false,
        scrollInput: false,
        // maxDate:next_month_date,
        onShow: function (ct) {
            /* var from = $("#start_date").val().split("/");
             var dt = new Date(from[2], from[1] -1 , from[0]);*/
            var dt = $("#start_date").val();
            this.setOptions({

                minDate: dt ? dt : false
            })
        },
        timepicker: false
    });
    $('#start_time').datetimepicker({
        datepicker: false,
        pickTime: true,
        step: 60,
        format: 'H:i',
        /*onShow: function (ct) {
         
         
         var dt = $("#end_time").val();
         if(dt !='')
         {this.setOptions({
         maxTime: dt ? dt : false
         })
         }
         },*/
    });
    $('#end_time').datetimepicker({
        datepicker: false,
        pickTime: false,
        step: 60,
        format: 'H:i',
        /*onShow: function (ct) {
         
         var dt = $("#start_time").val();
         //alert(dt);
         this.setOptions({
         
         minTime: dt ? dt : false
         })
         }*/
    });

});

window.Parsley.addValidator('gteqt',
        function (value, requirement) {
            return $('#end_time').val() >= $('#start_time').val();
        }, 32)
        .addMessage('en', 'gteqt', value_should_be_greater_then_start_time);

window.Parsley.addValidator('gteqtx',
        function (value, requirement) {
            var start_date = $('#start_date').val();
            var start_time = $('#start_time').val();
            var current_date = getCurrentDate();
            var current_time = getCurrentTime();
            
            if ($('#type').val() != "monthly") {
                if (Date.parse(start_date) == Date.parse(current_date)) {
                    return (start_time >= current_time);
                } else if (Date.parse(start_date) > Date.parse(current_date)) {
                    return true;
                } else
                {
                    return false;
                }
            }else{
                return true;
            }


        }, 31)
        .addMessage('en', 'gteqtx', value_greater_or_equal_to_current_time);

//change repetly
function changeRepeat(obj)
{
    if (obj.value == 'monthly')
    {
        $('#weekbox').hide();
        $('#monthbox').show();
        $('#weekbox input').removeAttr('required');
        $('#monthbox select').attr('required', true);

    } else
    {
        $('#weekbox').show();
        $('#weekbox input').attr('required', true);
        $('#monthbox').hide();
        $('#monthbox select').removeAttr('required');

    }
}
// change next prev
function changeWeek(date, np)
{
    $.ajax({
        url: base_url + 'Setuphours',
        data: {
            date: date, nextPrev: np
        },
        type: "POST",
        success: function (html) {
            $('#common_div').html(html);
        }
    });
}
//update hour
function updateHour()
{
    $.ajax({
        url: base_url + 'Setuphours/updateHour',
        data: {
            weekly_ts_id: $('#weekly_ts_id').val(), is_open: $('input[name=is_open]:checked').val()
        },
        type: "POST",
        success: function (html) {
            changeWeek($('#currentweek').val(), 'next');
            $('#ajaxModal').modal('hide');
        }
    });
}
//Delete time slot
function deletepopup(id)
{
    BootstrapDialog.show(
            {
                title: 'Confirm!',
                message: "<strong> " + delete_hour + " <strong>",
                buttons: [{
                        label: 'Cancel',
                        action: function (dialog) {
                            dialog.close();

                        }
                    }, {
                        label: 'Ok',
                        action: function (dialog) {
                            $.ajax({

                                type: "POST",
                                url: base_url + 'Setuphours/deleteHour',
                                //dataType: 'json',
                                data: {weekly_ts_id: $('#weekly_ts_id').val()},
                                success: function (data) {
                                    changeWeek($('#currentweek').val(), 'next');
                                    $('#ajaxModal').modal('hide');
                                }
                            });
                            dialog.close();
                        }

                    }]
            });
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