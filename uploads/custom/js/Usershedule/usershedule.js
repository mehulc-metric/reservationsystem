$(document).ready(function(){
	
	$('.btn-group label').removeClass('focus');
	$('#userform4').parsley();
	$('#userform1').parsley();
	$('#is-group').click(function () {
		if ($(this).prop("checked") == true) {
			$('.btn-group.no-of-persons .btn.btn-primary.display-checked').show();
			$('.btn-group.no-of-persons span.display-checked').show();
		}
		else if ($(this).prop("checked") == false) {
			$('.btn-group.no-of-persons .btn.btn-primary.display-checked').hide();
			$('.btn-group.no-of-persons span.display-checked').hide();
		}
	});
	
});

window.onload = function() {
	
    $('input[type=radio]').blur();
	$('.postal-code-msg').hide();
	$('.email-address-msg').hide();
};

$(window).on('load resize', function () {
    $('#content').css('min-height', $(window).height() - 102);
});
$(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            nextBtn1 = $('.nextBtn1');
            nextBtn2 = $('.nextBtn2');
            nextBtn3 = $('.nextBtn3');
            nextBtn4 = $('.nextBtn4');
            

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });
    //next button 1
    nextBtn1.click(function(){
       var no_of_people = $("input[name='no_of_people']:checked").val();
        if ($('#userform1').parsley().validate() === true){
                var email_id = $('#email').val();
                if(email_id){
                    var is_email_id = $("input[name=email_id]:hidden");
                    is_email_id.val(email_id);
                }
                if (no_of_people) {
                var curStep = $(this).closest(".setup-content"),
                        curStepBtn = curStep.attr("id"),
                        nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                        curInputs = curStep.find("input[type='text'],input[type='url']"),
                        isValid = true;

                if (isValid)
                    nextStepWizard.trigger('click');

                var week_start_date = getFormattedYearDate(new Date());
                $('#calendar').fullCalendar('destroy');
                calnderload(week_start_date, 'month');
            } else {
                $.alert({
                    title: alert_title_msg,
                    //backgroundDismiss: false,
                    content: "<strong>"+select_no_of_people_msg+"<strong>",
                    confirm: function () {
                    }
                });
            }
        }     
    });
    //next button 2
    nextBtn2.click(function () {
       
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;
            resDate =  $('#reservation_date').val();
            if(resDate != '')
            {
                var checkAvaiblity = $('#d'+resDate).attr('color-type');
                if(checkAvaiblity != undefined)
                {
                    if(checkAvaiblity == 3)
                    {
                        isValid = false;
                        $.alert({
                            title: alert_title_msg,
                            //backgroundDismiss: false,
                            content: "<strong>"+date_already_full_msg+"<strong>",
                            confirm: function(){
                            }
                        });
                        
                        $('.fc-day').removeClass('select-date');
                    }
                    if (isValid)
                    {
                        $.ajax({
                            type: "POST",
                            url: base_url+'Usershedule/getTimeslots',
                            data: {
                            reservation_date:resDate,colortype:checkAvaiblity,no_of_people: $("input:radio[name=no_of_people]:checked").val(),current_time:getDefaultTimeDate(new Date())
                        },
                        success: function(html){
                            $("#hoursSlot").html(html);
                            $('.slots').slimScroll({
                                height: '270px',
                                color: '#fff',
                                alwaysVisible: true
                            });
                            }
                        });
                        nextStepWizard.trigger('click'); 
                    }
                }
                else
                {
                    $.alert({
                        title: alert_title_msg,
                        //backgroundDismiss: false,
                        content: "<strong>"+valid_date_select_msg+"<strong>",
                        confirm: function(){
                        }
                    });
                }
            }
            else
            {
                $.alert({
                    title: alert_title_msg,
                    //backgroundDismiss: false,
                    content: "<strong>"+date_select_msg+"<strong>",
                    confirm: function(){
                    }
                });
                
            }
    });
    //next button 3
    nextBtn3.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            is_email_id = $("#email_id").val(),
            isValid = true;
            resHour =  $('#weekly_ts_hour').val();
            if(resHour != '')
            {
                var checkAvaiblity = $('#hr-'+resHour).attr('data-color');
                if(checkAvaiblity == 3)
                {
                    isValid = false;
                    $.alert({
                            title: alert_title_msg,
                            //backgroundDismiss: false,
                            content: "<strong>"+hour_already_full_msg+"<strong>",
                            confirm: function(){
                            }
                        });
                    $('#hoursSlot li').removeClass('selected');
                }
                if (isValid)
                {
                    $.ajax({
                        type: "POST",
                        dataType:"json",
                        url: base_url+'Usershedule/getAvailableSlot',
                        data: {
                        reservation_date:$('#reservation_date').val(),colortype:checkAvaiblity,no_of_people: $("input:radio[name=no_of_people]:checked").val(),selected_email: is_email_id,
                        weekly_ts_id:resHour,current_time:getDefaultTimeDate(new Date())
                    },
                    success: function(data){
                            if(data != 0)
                            {   
                                $('#confirm_date').html(data[0].date+ 'at ' +data[0].start_time);
                                $('#hourly_ts_id').val(data[0].hourly_ts_id);
                                $('#confirm_date').val(data[0].date);
                                $('#confirm_time').val(data[0].start_time);
                                $('#no_of_people_selected').text(data[0].no_of_people);
                                $('#confirm_email').val(data[0].selected_email);
                                nextStepWizard.trigger('click'); 
                            }
                            else
                            {
                                $.alert({
                                    title: alert_title_msg,
                                    //backgrou  dDismiss: false,
                                    content: "<strong>"+no_time_slot_available_msg+"<strong>",
                                    confirm: function(){
                                    }
                                });
                            }
                        }
                    });
                    
                }
            }
            else
            {
                $.alert({
                    title: alert_title_msg,
                    //backgroundDismiss: false,
                    content: "<strong>"+please_select_hour_msg+"<strong>",
                    confirm: function(){
                    }
                });
            }
    });
    //next button 4
    nextBtn4.click(function(){
        
		var is_agbar_customer = $("input[name=is_agbar_customer]:hidden");
		
		if(is_agbar_customer.val() == '0') {
			$.alert({
					title: alert_title_msg,			
					content: "<strong>"+payment_btn_click_msg+"<strong>"
			});
		}else{
			var curStep = $(this).closest(".setup-content"),
				curStepBtn = curStep.attr("id"),
				nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
				curInputs = curStep.find("input[type='text'],input[type='url']"),
				zip_code = $('#zip_code').val();
				isValid = true;
				 var val = [];             
				  $("input[name='notice[]']:checked").each(function(i){                
					val[i] = $(this).val();             
				  });
				 
				 if ($('#userform4').parsley().isValid()) {
				   
					 if(val.length === 4){
						 $.ajax({
							type: "POST",
							dataType:"json",
							url: base_url+'Usershedule/insertUserSlot',
							data: {
							hourly_ts_id:$('#hourly_ts_id').val(),weekly_ts_id:$('#weekly_ts_hour').val(),no_of_people: $("input:radio[name=no_of_people]:checked").val(),zip_code: zip_code,
							email:$('#confirm_email').val(),current_time:getDefaultTimeDate(new Date())
						},
						beforeSend: function() {
							$('div#step-4 .prevBtn').addClass('disabled');
							$('div#step-4 .disabled').removeClass('prevBtn');
							$('div#step-4').block({ 
								message: '<h5>'+please_wait_msg+'</h5>', 
							   /* css: { border: '3px solid #a00' } */
							}); 
						},
						success: function(data){
							
							   var dataset = data.result.length;
								$('div#step-4').unblock(); 
								var i;
								if(data.result){
								   for(i=0; i<=dataset-1; i++){                                   
									  if(data.result[i].status == 1){
											//$('#display-qrcode').append('<a href="'+base_url+'/uploads/pdf/'+data.result[i].pdf+'" download=""><img width="100" height="100" src="'+base_url+'/uploads/images/download_ticket.png" class="downloadable"/></a>&nbsp;&nbsp;&nbsp;&nbsp;');
											$('#display-qrcode').append('<a href="'+base_url+'Usershedule/download?file='+base_url+'uploads/pdf/'+data.result[i].pdf+'" ><img width="100" height="100" src="'+base_url+'/uploads/images/download_ticket.png" class="downloadable"/></a>&nbsp;&nbsp;&nbsp;&nbsp;');
									   }else{
										   $.alert({
												title: alert_title_msg,
												//backgroundDismiss: false,
												content: "<strong>"+data.message+"<strong>"
											});
									   }
									}                        
									$('div#step-4').unblock(); 
									$('#reservation_date').val('');
									$('#weekly_ts_hour').val('');
									$('#hourly_ts_id').val('');
									$('#step-4 input').val('');
									$("input:radio[name=no_of_people]").prop('checked', false);
									nextStepWizard.trigger('click'); 
								}else
								{
									$.alert({
										title: alert_title_msg,
										//backgroundDismiss: false,
										content: "<strong>"+data.message+"<strong>"
									});
								}
							}
						});
					 }else{
						$.alert({
							title: alert_title_msg,
							//backgroundDismiss: false,
							content: "<strong>"+terms_cond_error_msg+"<strong>"
						});
					 }  
				 }
			}
    });
	
    $(document).on("click", ".prevBtn", function () {
        var curStep = $(this).closest(".setup-content"),
         curStepBtn = curStep.attr("id"),
         prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");
        prevStepWizard.trigger('click');

    });
    $('div.setup-panel div a.btn-primary').trigger('click');

});
$(document).ready(function() {
      $('body').delegate('#hoursSlot li', 'click',
    function (e) {
        currentDate = getFormattedTimeDate(new Date());
        resDate =  $('#reservation_date').val();
        var time = $(this).attr('end-time');
       
        if(getFormattedDate(resDate)+' '+time >= currentDate)
        {
            $('#hoursSlot li').removeClass('selected');
            $(this).addClass('selected');
            $('#weekly_ts_hour').val($(this).attr('data-id'));
        }
    });
        
});
//calandar option
function calnderload(week_start_date,viewname)
{
    
    $('#calendar').fullCalendar({
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            defaultDate: week_start_date,
            slotDuration: "00:60:00",
            allDaySlot: false,
            navLinks: true, // can click day/week names to navigate views
           
            defaultView: viewname,
            lazyFetching:false,
            eventLimit: true, // allow "more" link when too many events
            eventColor: '#FFFFFF',
            eventBorderColor:'#cccccc',
            disableDragging: true,
            editable: false,
            expandThrough: false,
            droppable: false,
            draggable: false,
            eventOverlap: false,
            selectOverlap: false,
            displayEventTime: false,
            dayClick: function(date, jsEvent, view) {
                currentDate = getFormattedYearDate(new Date());
                if(getFormattedYearDate(date) >= currentDate)
                {
                    $('.fc-day').removeClass('select-date');
                    $(this).addClass('select-date');
                    $('#reservation_date').val(getFormattedYearDate(date));
                }

            },
            /*eventClick: function(event) {
                currentDate = getFormattedYearDate(new Date());
                if(getFormattedYearDate(event.date) >= currentDate)
                {
                    $('.fc-day').removeClass('select-date');
                    $('.fc-bg').find("[data-date='" + event.date + "']").addClass('select-date');;
                    $('#reservation_date').val(getFormattedYearDate(event.date));
                }
            },*/
            events: {
                url: base_url+'Usershedule/getEvents',
                type: 'POST',
                data:{no_of_people: $("input:radio[name=no_of_people]:checked").val(),current_time:getDefaultTimeDate(new Date())},
                success: function (data) {
                },
                error: function () {
                }
            },
            eventRender: function (event, element) {
                //$(element).css("display", "none");
                element.attr("data-toggle", "tooltip");
                element.attr("data-placement", "top");
                element.attr("data-container", "body");
                element.attr("color-type", event.colortype);
                if(event.colortype == 1){var backcolor = 'green';}
                else if(event.colortype == 2){var backcolor = 'yellow';}
                else if(event.colortype == 3){var backcolor = 'red';}
                $(".fc-bg td[data-date='"+event.date+"']").addClass(backcolor);
                element.attr("id",'d'+event.description);
                //$(element).find('.fc-title').html(event.title + ', ' + event.morecontent);
                //element.attr("data-model", "ajaxModal");
                $(element).find('.fc-title').html(event.title);
                var hr = element.attr('href');
                element.removeAttr("href");
                element.attr("data-href", hr);
               
            },
            
            
      
            selectConstraint:{
              start: '00:00', 
              end: '24:00', 
            },
        });
    $('.fc-month-button').trigger('click');
}


 //convert to date format
 function getFormattedDate(date) {
    var date = new Date(date);
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;
    
    return month + '/' + day + '/' + year;
  }
  //convert to date format
 function getFormattedYearDate(date) {
    var date = new Date(date);
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;
    
    return year + '-' + month + '-' + day;
  }
  function getFormattedTimeDate(date,sign) {
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
      
      return month + '/' + day + '/' + year+ ' '+hour+':'+min;
    }
    function getDefaultTimeDate(date,sign) {
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
      
      return year + '-' + month + '-' + day+ ' '+hour+':'+min;
    }
    
    function getZipCode(){        
        var zip_code = $("#zip_code").val();
        if(zip_code.length > 4){
                $.ajax({
                type: "POST",
                url: base_url+'Usershedule/checkZipCode',
                data: {zip_code: zip_code},
                async: false,
                success: function (result) {
                    alert(result);
                }
            });
        }        
    }
    
    window.Parsley.addValidator('email', function (value, requirement) {
   
    var response = false;
    var currentEmailName = $("#email").val();
	
    $.ajax({
        type: "POST",
        url: base_url+'Usershedule/isDuplicateEmail',
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
}, 32).addMessage('en', 'email', email_has_been_used_msg);

 window.Parsley.addValidator('zip_code', function (value, requirement) {
    var zip_code = $("#zip_code").val();
	if(zip_code.length > 3){
        $.ajax({
        type: "POST",
        url: base_url+'Usershedule/checkZipCode',
        data: {zip_code: zip_code},
        async: false,
        success: function (result) {
           var is_agbar_customer = $("input[name=is_agbar_customer]:hidden");
            if (result == 1 || result == '1') {
                is_agbar_customer.val('1');
                $('.postal-code-msg').show();   

				$("#payment_btn").hide(); // Hide if zipcode is not arbitory group
				//$('.nextBtn4').show();
					
            } else {
                //var test = $("input[name=is_agbar_customer]:hidden");
                is_agbar_customer.val('0');
                $('.postal-code-msg').hide();
				
				$("#payment_btn").show(); // Show if zipcode is not arbitory group
				//$('.nextBtn4').hide();
            }
            
            // Update the value Based on the zip code
            if($("input[name='no_of_people']").is(":checked")) {
				getValue($('input[name=no_of_people]:checked').val()); // update the final payment amount
            }
        }        
    });
   }
  
   
});

function getValue(val){
    var configAmount = $('#config_amount').val();
    var configVat = $('#config_vat').val();
    var is_agbar_customer = $("#is_agbar_customer").val();   
    var final_amount_set = $("input[name=final_amount_set]:hidden");
    if(is_agbar_customer == 1){
        var final = 0;
        $('.no-of-tickets').text(0);  
        $('.total-price').text(0);
        final_amount_set.val(final);
    }else{
        var getVal = val * configAmount;
        // var get_val_personatage = getVal / configVat;
        var get_val_personatage = (getVal * configVat) / 100;
        var final = getVal + get_val_personatage;
        var final_amount = final.toFixed(2)
        $('.no-of-tickets').text(val);
        $('.total-price').text(final_amount);
        final_amount_set.val(final_amount);
        
    }       
}

// Paypal payment
$(document).ready(function(){
	
	// Payment Btn click event
	$("#payment_btn").on("click", function(){
		
		var val = [];
		$("input[name='notice[]']:checked").each(function(i){                
			val[i] = $(this).val();             
		});
		
		if(val.length == 4) {
			
			var final_payment_amount = $('#final_amount_set').val();
			$.ajax({
				type: "POST",
				url: base_url+'Usershedule/payment',
				data: {payment_amount:final_payment_amount, hourly_ts_id:$('#hourly_ts_id').val(), weekly_ts_id:$('#weekly_ts_hour').val(),
				no_of_people: $("input:radio[name=no_of_people]:checked").val(), zip_code: $('#zip_code').val(), email:$('#confirm_email').val(),
				current_time:getDefaultTimeDate(new Date())},
				async: false,
				beforeSend: function() {
					//console.log('before send');
					$('div#step-4 .prevBtn').addClass('disabled');
					$('div#step-4 .disabled').removeClass('prevBtn');
					$('div#step-4').block({ 
						message: '<h5>'+please_wait_msg+'</h5>'
					}); 
				},
				success: function(data) {
					
					//console.log(data); return false;
					$('#payment_btn_div').append(data); // Append Paypal form
					//return false;
					document.forms['paypal_auto_form'].submit(); // Submit the Paypal Form
					
				},
				error: function(xhr, status, error) {
					var err = eval("(" + xhr.responseText + ")");
					
					$.alert({
						title: alert_title_msg,
						content: "<strong>"+err.Message+"<strong>"
					});
				}
			});
		}else{
			$.alert({
				title: alert_title_msg,
				//backgroundDismiss: false,
				content: "<strong>"+terms_cond_error_msg+"<strong>"
            });
		}
			
    });
	
});