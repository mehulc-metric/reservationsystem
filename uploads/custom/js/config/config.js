function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
//On submit loading
function setdefaultdata()
{   
    if ($('#Configs').parsley().isValid()) {
        
       // $('input[type="submit"]').prop('disabled', true);
        $('#Configs').submit();
    }
}

   /* $('form#Configs').submit(function(e){
       e.preventDefault();
    });*/
function checkInput()
{

    slot = $('#no_of_slot_per_hour').val();
    if(slot != '')
    {
        var d = 60/slot;

        if(d % 1 != 0)
        {
            $.alert({
                title: 'Alert!',
                //backgroundDismiss: false,
                content: "<strong> Invalid time slot.<strong>",
                confirm: function(){
                    $('#no_of_slot_per_hour').val('');
                    $('#no_of_slot_per_hour').focus();
                }
            });
        }
        else
        {return false;}
    return false;
    }
    
}