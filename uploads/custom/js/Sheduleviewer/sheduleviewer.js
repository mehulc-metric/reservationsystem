// load calandar on prev/next button
$('body').delegate('button.fc-prev-button,button.fc-next-button', 'click',
        function (e) {
            var view = $('#calendar').fullCalendar('getView');
            if (view.name == 'agendaWeek' || view.name == 'agendaDay')
            {
                var calendar = $('#calendar').fullCalendar('getCalendar');
                var view = calendar.view;
                var week_start_date = getFormattedYearDate(view.start._d);
                var week_end_time = getFormattedYearDate(view.end._d);
                $.ajax({
                    url: base_url + 'Sheduleviewer/getTimeSlot',
                    data: {
                        week_start_date: week_start_date
                    },
                    type: "POST",
                    success: function (slot) {
                        $('#calendar').fullCalendar('destroy');
                        calnderload(slot, week_start_date, view.name);
                    }
                });
            }
        });
/*$('body').delegate('.fc-month-button,.fc-agendaWeek-button,fc-agendaDay-button', 'click',
 function (e) {
 
 $("#calendar").fullCalendar("refetchEvents");
 });*/
//load calandar
$(document).ready(function () {
    var week_start_date = getFormattedYearDate(new Date());

    calnderload(slot_duration, week_start_date, 'agendaWeek');
    

});
var clicks = 0;
var timer = null;
//calandar option
function calnderload(duration, week_start_date, viewname)
{
    var doubleClick = null;
    var clickTimer = null;
    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: week_start_date,
        slotDuration: "00:" + duration + ":00",
        allDaySlot: false,
        navLinks: true, // can click day/week names to navigate views

        defaultView: viewname,
        lazyFetching: false,
        eventLimit: true, // allow "more" link when too many events
        eventColor: '#FFFFFF',
        eventBorderColor: '#cccccc',
        disableDragging: true,
        editable: false,
        expandThrough: false,
        droppable: false,
        draggable: false,
        eventOverlap: false,
        selectOverlap: false,
        displayEventTime: false,
        selectable: true,
        selectHelper: false,
        events: {
            url: base_url + 'Sheduleviewer/getEvents',
            type: 'POST',
            beforeSend: function () {
                $('div#calendar').block({
                    message: '<h5>'+please_wait+'</h5>'
                });
            },
            cache: false,
            success: function (data) {
                $('div#calendar').unblock();
            },
            error: function () {
            }
        },
        eventRender: function (event, element) {

            if (event.calender_view_type === "week_view") {
                var isIE = /*@cc_on!@*/false || !!document.documentMode;

                /*element.attr("data-toggle", "tooltip");
                 element.attr("data-placement", "top");
                 element.attr("data-container", "body");
                 element.attr("data-title", event.description);
                 */
                //$(element).find('.fc-title').html(event.title + ', ' + event.morecontent);
                var edate = event.start_date;
                var evnt_date = edate.replace("-", "/");
                if (isIE) {
                    var strt_date = getFormattedTimeDate(new Date(evnt_date));
                } else {
                    var strt_date = getFormattedTimeDate(new Date(event.start_date));
                }

                // var strt_date = getFormattedTimeDate(new Date(evnt_date));
                var currentDate = getFormattedTimeDate(new Date());
                if (accessEdit == true) {
                    if ((currentDate <= strt_date) && event.remove_url == 0) {
                        element.attr("data-model_1", "ajaxModal");
                        var hr = element.attr('href');
                        element.removeAttr("href");
                        element.attr("data-href", hr);
                    } else {
                        element.removeAttr("href");
                    }
                } else {
                    element.removeAttr("href");
                }
                $(element).find('.fc-title').html(event.title);
            } else {
                element.attr("data-toggle", "tooltip");
                element.attr("data-placement", "top");
                element.attr("data-container", "body");
                element.attr("data-title", event.description);
                //$(element).find('.fc-title').html(event.title + ', ' + event.morecontent);
                //element.attr("data-model", "ajaxModal");
                $(element).find('.fc-title').html(event.title);
                var hr = element.attr('href');
                element.removeAttr("href");
                element.attr("data-href", hr);
            }

        },

        dayClick: function (date, allDay, jsEvent, view) {

            var singleClick = date.format();
            // alert(singleClick);

            if (doubleClick == singleClick) {

                var currentDate = getFormattedTimeDate(new Date());

                var minutes = $('#total_minute').val();
                //  var datestart = $('#display_start_time').text();
                var datestart = $.fullCalendar.formatDate(date, "MM/DD/YYYY HH:mm");

                if (datestart >= currentDate)
                {
                    if (minutes == duration)
                    {

                        if (accessAdd == true)
                        {

                            $('#ajaxModal').modal('show');
                        }
                    } else
                    {
                        calendar.fullCalendar('unselect');
                    }
                } else {
                    calendar.fullCalendar('unselect');
                }
                doubleClick = null;
            } else {
                doubleClick = date.format();
                clearInterval(clickTimer);
                clickTimer = setInterval(function () {
                    doubleClick = null;
                    clearInterval(clickTimer);
                }, 500);
            }
        },
        select: function (start, end, jsEvent) {

            
            $("body .tooltip").remove();
            $('#reserve_single_slot').parsley().reset();
            // Single slots fields
            $("#select_no_user").val('');
            $("#email_address").val('');
            $("#confirm_email").val('');
            // Group Reservation fields
            $('#reserve_group').parsley().reset();
            $('#group_email_address').val('');
            $('#group_confirm_email').val('');
            $('#number_of_user').val('');
            $('#group_name').val('');
            $('#price').empty();     
            $('#zip_code').val('');
            $('#group_zip_code').val(''); 
            $('.postal-code-msg').hide();
            $('#is-group').prop('checked', false); 
            $('#is-free').prop('checked', false); 
            $("input[name=is_free]:hidden").val('0');
            // Set price value based on config
            $('#price').text(config_amount+'€');
            $('#vat').empty();           
            $('#vat').text(config_vat+'%');
            $('#config_amount').val(config_amount);
            $('#config_vat').val(config_vat);
            
            
            var minutes = end.diff(start, "minutes");
            currentDate = getFormattedTimeDate(new Date());
            datestart = $.fullCalendar.formatDate(start, "MM/DD/YYYY");
            start = $.fullCalendar.formatDate(start, "HH:mm");
            end = $.fullCalendar.formatDate(end, "HH:mm");
            if (datestart + ' ' + start >= currentDate)
            {
                if (minutes == duration)
                {         $('.is-grpbox').show();
                    $('#ajaxModal #display_start_time').html(datestart + ' ' + start);
                    $('#ajaxModal #display_end_time').html(datestart + ' ' + end);
                    $('#ajaxModal #start_time').val(start);
                    $('#ajaxModal #end_time').val(end);
                    $('#ajaxModal #delete').remove();
                    $('#ajaxModal #date').val(datestart);
                    $('#ajaxModal #total_minute').val(minutes);
                    $('#ajaxModal #total_duration').val(duration);
                    $('#ajaxModal #hourly_ts_id').val('');
                    if (accessAdd == true)
                    {  
                        $("body input[name=slot_type]").prop("checked", false);
                        // $('#ajaxModal').modal('show');
                        $('#select_no_user').find('option').remove();
                        for (var i = 0; i <= no_of_people_per_hour; i++)
                        {
                            $('#select_no_user').append($('<option>',
                                    {
                                        value: (i === 0) ? "" : i,
                                        text: (i === 0) ? "Select No of user" : i,
                                    }));
                        }
                    }
                } else
                {
                    calendar.fullCalendar('unselect');
                }
            } else
            {
                calendar.fullCalendar('unselect');
            }

        },

        selectConstraint: {
            start: '00:00',
            end: '24:00',
        },
    });

}

//$('.fc-widget-content').bind('dblclick', function () {
//    alert('double click!');
//
//});
//convert to date format
function getFormattedDate(date, sign) {
    var date = new Date(date);
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return month + '/' + day + '/' + year;
}
//convert to date format
function getFormattedYearDate(date, sign) {
    var date = new Date(date);
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return year + '-' + month + '-' + day;
}
function getFormattedTimeDate(date, sign) {
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    var hour = date.getHours().toString();
    hour = hour.length > 1 ? hour : '0' + hour;

    var min = date.getMinutes().toString();
    min = min.length > 1 ? min : '0' + min;

    return month + '/' + day + '/' + year + ' ' + hour + ':' + min;
}
/*Submit Sigle Slot Reservation Form */
$('body').delegate('#submitSingleSlotButton', 'click',
        function (e) {
            if ($('#reserve_single_slot').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                e.preventDefault();
                singleSlotReservationSubmit();
            }
        });

/*Insert/update code for single Slot Reservation*/
function singleSlotReservationSubmit() {

    $("body .tooltip").remove();
    $('input[type="submit"]').removeAttr('disabled');
    $("#ajaxModal").modal('hide');
    //get current week dates
    var calendar = $('#calendar').fullCalendar('getCalendar');
    var view = calendar.view;
    var week_start_date = getFormattedDate(view.start._d);
    var week_end_time = getFormattedDate(view.end._d);

    var datestart = $('#date').val();
    var start = $('#start_time').val();
    var end = $('#end_time').val();
    var slot_type = $('input[name=slot_type]:checked').val();
    var hourly_ts_id = $('#hourly_ts_id').val();
    var total_minute = $('#total_minute').val();
    var total_duration = $('#total_duration').val();
    var email_id = $('#email_address').val();
    var selected_no_user = $('#select_no_user').val();
    
    var zip_code = $('#zip_code').val();
    var is_free_entry = $("input[name=is_free]:hidden").val();
    var is_agbar_customer = $("input[name=is_agbar_customer]:hidden").val();
    if (hourly_ts_id != '')
    {
        $.ajax({
            url: base_url + 'Sheduleviewer/update',
            data: {
                hourly_ts_id: hourly_ts_id, selected_no_user: selected_no_user, email_id: email_id, zip_code: zip_code, is_free_entry: is_free_entry, is_agbar_customer: is_agbar_customer, current_time: getDefaultTimeDate(new Date())
            },
            type: "POST",
            beforeSend: function () {
                $('div#calendar').block({
                    message: '<h5>' + please_wait + '</h5>'
                });
            },
            success: function (data) {
                $('div#calendar').unblock();
                if (data == 1)
                {
                    $("body .tooltip").remove();
                    $("#ajaxModal input:radio").attr("checked", false);
                    $("#calendar").fullCalendar("refetchEvents");
                    $.alert({
                        title: successfully,
                        //backgroundDismiss: false,
                        content: "<strong> " + reservation_successfully_done + "<strong>",
                        confirm: function () {
                        }
                    });
                } else {
                    $.alert({
                        title: 'Alert!',
                        //backgroundDismiss: false,
                        content: "<strong>" + somthing_went_wrong + "<strong>",
                        confirm: function () {
                        }
                    });
                    $("body .tooltip").remove();
                    //calendar.fullCalendar('unselect');
                    $("#calendar").fullCalendar("unselect");

                }
            }
        });
    } else //Insert
    {
        // Check Slot type is undefine then set as reservable
        if (slot_type === undefined) {
            slot_type = 1;
        } else {
            slot_type = slot_type;
        }
        var startDate = $('#date').val();
       
        $.ajax({
            url: base_url + 'Sheduleviewer/insert',
            data: {
                date: datestart, start_time: start, end_time: end, week_start_date: week_start_date, week_end_date: week_end_time, slot_type: slot_type, zip_code: zip_code, is_free_entry: is_free_entry, is_agbar_customer: is_agbar_customer,
                total_minute: total_minute, total_duration: total_duration, email_id: email_id, selected_no_user: selected_no_user, startDate: startDate, current_time: getDefaultTimeDate(new Date())
            },
            type: "POST",
            beforeSend: function () {

                $('div#calendar').block({
                    message: '<h5>' + please_wait + '</h5>'
                });
            },
            success: function (data) {
                $('div#calendar').unblock();
                if (data == 1)
                {
                    $("body .tooltip").remove();
                    $("#ajaxModal input:radio").attr("checked", false);
                    $("#calendar").fullCalendar("refetchEvents");
                    $.alert({
                        title: 'Successfully!',
                        //backgroundDismiss: false,
                        content: "<strong>" + reservation_successfully_done + "<strong>",
                        confirm: function () {
                        }
                    });
                } else {
                    $.alert({
                        title: 'Alert!',
                        //backgroundDismiss: false,
                        content: "<strong>" + reservation_not_possible + "<strong>",
                        confirm: function () {
                        }
                    });
                    $("body .tooltip").remove();
                    //calendar.fullCalendar('unselect');
                    $("#calendar").fullCalendar("unselect");

                }

            }
        });
    }

}
function getDefaultTimeDate(date, sign) {
    var date = new Date(date);
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    var hour = date.getHours().toString();
    hour = hour.length > 1 ? hour : '0' + hour;

    var min = date.getMinutes().toString();
    min = min.length > 1 ? min : '0' + min;

    return year + '-' + month + '-' + day + ' ' + hour + ':' + min;
}
/*Submit Group Reservation Form from Admin penal*/
$('body').delegate('#submitGroupButton', 'click',
        function (e) {
            if ($('#reserve_group').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                e.preventDefault();
                groupReservationSubmit();

            }
        });

/*Insert/update code for group Reservation from Admin*/
function groupReservationSubmit() {

    $("body .tooltip").remove();
    $('input[type="submit"]').removeAttr('disabled');
    $("#ajaxModal").modal('hide');
    //get current week dates
    var calendar = $('#calendar').fullCalendar('getCalendar');
    var view = calendar.view;
    var week_start_date = getFormattedDate(view.start._d);
    var week_end_time = getFormattedDate(view.end._d);
    var datestart = $('#date').val();
   
    var start = $('#start_time').val();
    var end = $('#end_time').val();
    var slot_type = $('input[name=slot_type]:checked').val();
    var hourly_ts_id = $('#hourly_ts_id').val();
    var total_minute = $('#total_minute').val();
    var total_duration = $('#total_duration').val();
    var email_id = $('#group_email_address').val();
    var number_of_user = $('#number_of_user').val();
    var group_name = $('#group_name').val();
    var group_zip_code = $('#group_zip_code').val(); 
    if (hourly_ts_id != '')
    {
        // Check Slot type is undefine then set as reservable
        if (slot_type === undefined) {
            slot_type = 1;
        } else {
            slot_type = slot_type;
        }
        hourly_ts_id = hourly_ts_id;
    } else {

        // Check Slot type is undefine then set as reservable
        if (slot_type === undefined) {
            slot_type = 1;
        } else {
            slot_type = slot_type;
        }

        hourly_ts_id = "";
    }
    // Check Slot Availbility from input No of user into Group
    checkSlotAvailbilityForGroup(datestart, start, number_of_user, total_minute, total_duration, slot_type, week_start_date, week_end_time, group_name, email_id, hourly_ts_id,group_zip_code);
}

function checkSlotAvailbilityForGroup(datestart, start, number_of_user, total_minute, total_duration, slot_type, week_start_date, week_end_time, group_name, email_id, hourly_ts_id,group_zip_code) {

    $.ajax({
        url: base_url + 'Sheduleviewer/checkSlotAvailbilityForGroup',
        data: {startDate: datestart, start_time: start, number_of_user: number_of_user, total_minute: total_minute, total_duration: total_duration, slot_type: slot_type, week_start_date: week_start_date, week_end_date: week_end_time, group_name: group_name,group_zip_code: group_zip_code},
        type: "POST",
        beforeSend: function () {
            $('div#calendar').block({
                message: '<h5>' + please_wait + '</h5>'
            });
        },
        success: function (data) {
            if (data == 1) {

                $.ajax({
                    url: base_url + 'Sheduleviewer/insertSlotsForGroup',
                    data: {date: datestart, start_time: start, number_of_user: number_of_user, total_minute: total_minute, total_duration: total_duration, slot_type: slot_type, week_start_date: week_start_date, week_end_date: week_end_time, group_name: group_name, email_id: email_id, hourly_ts_id: hourly_ts_id,group_zip_code: group_zip_code, current_time: getDefaultTimeDate(new Date())},
                    type: "POST",
                    success: function (data) {
                        $('div#calendar').unblock();
                        if (data == 1) {
                            $("body .tooltip").remove();
                            $("#ajaxModal input:radio").attr("checked", false);
                            $("#calendar").fullCalendar("refetchEvents");
                            $.alert({
                                title: successfully,
                                //backgroundDismiss: false,
                                content: "<strong>" + group_reservation_done + "<strong>",
                                confirm: function () {
                                }
                            });
                        } else {
                            $.alert({
                                title: 'Alert!',
                                //backgroundDismiss: false,
                                content: "<strong>" + group_reservation_not_possible + "<strong>",
                                confirm: function () {
                                }
                            });
                        }
                    }
                });
            } else {
                $('div#calendar').unblock();
                $.alert({
                    title: 'Alert!',
                    //backgroundDismiss: false,
                    content: "<strong>" + group_reservation_not_possible + "<strong>",
                    confirm: function () {
                    }
                });
            }
        }
    });
}

//ajax popup open
$('body').delegate('[data-model_1="ajaxModal"]', 'dblclick',
        function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
            var $this = $(this)
                    , $remote = $this.data('remote') || $this.attr('data-href')
                    , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal();
            $modal.load($remote);           
            var viewpage = $this.attr('data-view');
            if (viewpage == 1)
            {
                $(this).closest('table tr.unread').removeClass('unread');
            }
        }
);

// Check Zip Code belongs to AGBAR Group or not
function checkZipCode(){
      var zip_code = $("#zip_code").val();
        if(zip_code.length > 3){   
             $.ajax({
                type: "POST",
                url: base_url+'Sheduleviewer/checkZipCode',
                data: {zip_code: zip_code},
                async: false,
                success: function (result) {
                    if (result == 1 || result == '1') {               
                        var is_agbar_customer = $("input[name=is_agbar_customer]:hidden");
                        is_agbar_customer.val('1');                
                        $('.postal-code-msg').show();               
                    } else {
                        var test = $("input[name=is_agbar_customer]:hidden");
                        test.val('0');
                        $('.postal-code-msg').hide();
                    }

                    // update the value Based on the zip code 
                    if($('#select_no_user').val()) {
                     getSelectedPeople($('#select_no_user').val()); // update the final payment amount
                    }
                }        
            });
        }
}
// Check is Group more then 4 people
function is_group(obj){

    if ($(obj).is(":checked")) {
        for (var i = 5; i <= 6; i++)
        {
            $('#select_no_user').append($('<option>',
                    {
                        value: (i === 0) ? "" : i,
                        text: (i === 0) ? "Select No of user" : i,
                    }));
        }
    } else {
        $("#select_no_user option[value='5']").remove();
        $("#select_no_user option[value='6']").remove();
    }

}
// Get selcted Number of people and calculate amount 

function getSelectedPeople(){
 
    var selectedPeople = $('#select_no_user').val();
    var configAmount = $('#config_amount').val();
    var configVat = $('#config_vat').val();
    var is_agbar_customer = $("#is_agbar_customer").val();
    var final_amount_set = $("input[name=final_amount_set]:hidden");
    if (is_agbar_customer == 1) {
        var final = 0;
        $('.no-of-tickets').text(0);
        $('#total-price').text(0);
        final_amount_set.val(final);
    } else {
        var getVal = selectedPeople * configAmount;
        // var get_val_personatage = getVal / configVat;
        var get_val_personatage = (getVal * configVat) / 100;
        var final = getVal + get_val_personatage;
        var final_amount = final.toFixed(2);
        
        $('.no-of-tickets').text(selectedPeople);
        $('#total-price').text(final_amount);
        final_amount_set.val(final_amount);

    }

}

//check for free Entry 

function is_free_entry(obj){
   
    var is_free = $("input[name=is_free]:hidden");
    if ($(obj).is(":checked")) {
   
        is_free.val('1');
    }else{
   
        is_free.val('0');
    }
}


// Validation Uniqe Email Id allow only
    window.Parsley.addValidator('email_address', function (value, requirement) {
   
    var response = false;
    var currentEmailName = $("#email_address").val();
	
    $.ajax({
        type: "POST",
        url: base_url+'Sheduleviewer/isDuplicateEmail',
        data: {email: currentEmailName},
        async: false,
        success: function (result) {
            
            //response = result;
            if (result == 1) {
                response = false;
            } else {
                response = true;
            }
        }        
    });
    return response;
}, 32).addMessage('en', 'email_address', email_has_been_used);


// Validation Uniqe Email Id allow only for Group Reservation
    window.Parsley.addValidator('group_email_address', function (value, requirement) {
   
    var response = false;
    var currentEmailName = $("#group_email_address").val();
	
    $.ajax({
        type: "POST",
        url: base_url+'Sheduleviewer/isDuplicateEmail',
        data: {email: currentEmailName},
        async: false,
        success: function (result) {
            
            //response = result;
            if (result == 1) {
                response = false;
            } else {
                response = true;
            }
        }        
    });
    return response;
}, 32).addMessage('en', 'group_email_address', email_has_been_used);