/**
 * AdminLTE Demo Menu
 * ------------------
 * You should not use this file in production.
 * This file is for demo purposes only.
 */
$(document).ready(function(){
    $("#div_msg").fadeOut(4000); 
    //tooltip
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });

    $('form').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
      }
    });
});
//ajax popup open
$('body').delegate('[data-model="ajaxModal"]', 'click',
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
        if(viewpage == 1)
        {$(this).closest('table tr.unread').removeClass('unread');} 
    }
);

