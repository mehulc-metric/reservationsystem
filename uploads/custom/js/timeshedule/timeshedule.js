// load calandar on prev/next button
$('body').delegate('button.fc-prev-button,button.fc-next-button', 'click',
        function (e) {
            var view = $('#calendar').fullCalendar('getView');
            if (view.name == 'agendaWeek')
            {
                var calendar = $('#calendar').fullCalendar('getCalendar');
                var view = calendar.view;
                var week_start_date = getFormattedYearDate(view.start._d);
                var week_end_time = getFormattedYearDate(view.end._d);
                $.ajax({
                    url: base_url + 'Timeshedule/getTimeSlot',
                    data: {
                        week_start_date: week_start_date
                    },
                    type: "POST",
                    success: function (slot) {
                        $('#calendar').fullCalendar('destroy');
                        calnderload(slot, week_start_date);
                    }
                });
            }

        });
//load calandar
$(document).ready(function () {
    var week_start_date = getFormattedYearDate(new Date());
    calnderload(slot_duration, week_start_date);
});
//calandar option
function calnderload(duration, week_start_date)
{


    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'agendaWeek'
        },
        defaultDate: week_start_date,
        slotDuration: "00:" + duration + ":00",
        allDaySlot: false,
        navLinks: true, // can click day/week names to navigate views
        selectable: true,
        selectHelper: true,
        defaultView: 'agendaWeek',

        eventLimit: false, // allow "more" link when too many events
        //eventColor: '#ff0000',

        //disableDragging: true,
        //editable: false,
        //expandThrough: false,
        //droppable: false,
        //draggable: false,
        eventOverlap: false,
        selectOverlap: false,
        displayEventTime: false,
        events: {
            url: base_url + 'Timeshedule/getEvents',
            type: 'POST',
            beforeSend: function () {

                $('div#calendar').block({
                    message: '<h5>Please wait...</h5>'
                });
            },
            success: function (data) {
                $('div#calendar').unblock();
            },
            error: function () {
            }
        },
        eventRender: function (event, element) {
            element.attr("data-toggle", "tooltip");
            element.attr("data-placement", "top");
            element.attr("data-container", "body");
            element.attr("data-title", event.description);

            var strt_date = getFormattedTimeDate(new Date(event.start_date));
            currentDate = getFormattedTimeDate(new Date());

            if (accessEdit == true)
            {

                if (event.totaluser > 0)
                {
                    element.removeAttr("href");
                } else
                {
                    if (currentDate <= strt_date) {
                        element.attr("data-model", "ajaxModal");
                        var hr = element.attr('href');
                        element.removeAttr("href");
                        element.attr("data-href", hr);
                    } else {
                        element.removeAttr("href");
                    }

                }
            } else {
                element.removeAttr("href");
            }
        },
        dayClick: function (date, allDay, jsEvent, view) {
          
            var singleClick = date.format();
           // var datestart = $.fullCalendar.formatDate(date, "MM/DD/YYYY HH:mm");
            var currentDate = getFormattedTimeDate(new Date());
            var minutes = $('#total_minute').val();
            var datestart = date.format("MM/DD/YYYY HH:mm");
            if (datestart >= currentDate)
            {
                if (accessAdd == true)
                {
                    $('#ajaxModal').modal('show');
                    // return false;
                } 
            } else
            {
                calendar.fullCalendar('unselect');
            }

        },
        select: function (start, end, jsEvent) {
            
            $("body .tooltip").remove();
            var minutes = end.diff(start, "minutes");
            
            currentDate = getFormattedTimeDate(new Date());
            datestart = $.fullCalendar.formatDate(start, "MM/DD/YYYY");
            start = $.fullCalendar.formatDate(start, "HH:mm");
            end = $.fullCalendar.formatDate(end, "HH:mm");
            if (datestart + ' ' + start >= currentDate)
            {
                /*if(minutes == duration)
                 {*/


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
                    $('#ajaxModal').modal('show');
                }
                /*}
                 else
                 {
                 calendar.fullCalendar('unselect');
                 }*/
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
/*Submit time slot*/
$('body').delegate('#submitButton', 'click',
        function (e) {
            if ($('#Timeshedule').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                e.preventDefault();
                doSubmit();
            }

        });
/*Insert/update code for time slot*/
function doSubmit() {
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
    if (hourly_ts_id != '')
    {
        $.ajax({
            url: base_url + 'Timeshedule/update',
            data: {
                hourly_ts_id: hourly_ts_id, slot_type: slot_type
            },
            type: "POST",
            success: function (data) {

                if (data != 1)
                {
                    alert('Something went wrong');
                    calendar.fullCalendar('unselect');
                } else
                {
                    $("body .tooltip").remove();
                    $("#calendar").fullCalendar("refetchEvents");

                }
            }
        });
    } else //Insert
    {

        $.ajax({
            url: base_url + 'Timeshedule/insert',
            data: {
                date: datestart, start_time: start, end_time: end, week_start_date: week_start_date, week_end_date: week_end_time, slot_type: slot_type,
                total_minute: total_minute, total_duration: total_duration
            },
            type: "POST",
            success: function (data) {
                if (data == 1)
                {
                    $("body .tooltip").remove();
                    $("#ajaxModal input:radio").attr("checked", false);
                    $("#calendar").fullCalendar("refetchEvents");

                } else {
                    $.alert({
                        title: 'Alert!',
                        //backgroundDismiss: false,
                        content: "<strong> Something went wrong.<strong>",
                        confirm: function () {
                        }
                    });
                    $("body .tooltip").remove();
                    calendar.fullCalendar('unselect');

                }

            }
        });
    }

}

//convert to date format
function getFormattedDate(date, sign) {
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return month + '/' + day + '/' + year;
}
//convert to date format
function getFormattedYearDate(date, sign) {
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
//Delete time slot
function deletepopup(id)
{
    BootstrapDialog.show(
            {
                title: 'Confirm!',
                message: "<strong> Are you sure want to delete this time slot ? <strong>",
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
                                url: base_url + 'Timeshedule/deleteEvent',
                                //dataType: 'json',
                                data: {'id': id},
                                success: function (data) {
                                    if (data == 1)
                                    {
                                        $("#calendar").fullCalendar("refetchEvents");
                                    } else {
                                        $.alert({
                                            title: 'Alert!',
                                            //backgroundDismiss: false,
                                            content: "<strong> Something went wrong.<strong>",
                                            confirm: function () {
                                            }
                                        });
                                        calendar.fullCalendar('unselect');
                                    }
                                }
                            });
                            dialog.close();
                        }

                    }]
            });
} 