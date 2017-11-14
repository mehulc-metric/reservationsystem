<script type="text/javascript">
    $(document).ready(function () {
        
        //serch by enter
         $('#searchtext').keyup(function(event) 
         {
                if (event.keyCode == 13) {
                        data_search('changesearch');
                }
        
        });
    });

    //Search data
    function data_search(allflag)
    {
		
		
        var uri_segment = $("#uri_segment").val();
        
        /* Start Added By Sanket*/
        var request_url = '';
        if(uri_segment == 0)
        {
            request_url = '<?php echo $this->config->item('base_url').'/'.$this->viewname?>/index/'+uri_segment;
        }else
        {
            request_url = '<?php echo $this->config->item('base_url').'/'.$this->viewname?>/'+uri_segment;
        }
         /* End Added By Sanket*/

         
        $.ajax({
            type: "POST",
            url: request_url,
            data: {
            result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag
        },
        success: function(html){
           
                $("#common_div").html(html);
            }
        });
        return false;
    }
    function reset_data()
    {
        $("#searchtext").val("");
        apply_sorting('','');
        data_search('all');
    }

    function reset_data_list(data)
    {
        $("#searchtext").val(data);
        apply_sorting('','');
        data_search('all');
    }

    function changepages()
    {
        data_search(''); 
    }
    
    function apply_sorting(sortfilter,sorttype)
    {
		

        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search('changesorting');
    }
    //pagination
    $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type: 'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
            },
            /*
                beforeSend: function () {
                $('#common_div').block({message: 'Loading...'});
            },
            */
            success: function (html) {
                $("#common_div").html(html);
            //    $.unblockUI();
            }
        });
        return false;

    });
    //check box for delete all
    $('body').on('click','#selecctall',function(e){
     if(this.checked) { // check select status
         $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });        
        }
    });

    //check box for delete all
    $('body').on('click','#selecctall_user',function(e){
        if(this.checked) { // check select status
            $('.checkbox_user').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('.checkbox_user').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });

    function deletepopup(id,name)
    {
            if(id == '0')
            {
                var boxes = $('input[name="check[]"]:checked');
                if(boxes.length == '0')
                  {
                        $.alert({
                            title: 'Alert!',
                            //backgroundDismiss: false,
                            content: "<strong> Please select record(s) to delete.<strong>",
                            confirm: function(){
                            }
                        });
                        return false;

                  }
            }
            if(id == '0')
            {
                var msg = 'Are you sure want to delete Record(s)';
            }
            else
            {
                var msg = 'Are you sure want to delete '+name+'?';
            }
            $.confirm({
                    title: 'Confirm!',
                    content: "<strong> "+msg+" "+"<strong>",
                    confirm: function(){
                        delete_all_multipal('delete',id);
                    },
                    cancel: function(){

                    }
                });
    } 
    //Delete multiple
    function delete_all_multipal(val,id)
    {
          var boxes = $('input[name="check[]"]:checked');

          var myarray = new Array;
            var i=0;

            if(id != '0')
            {
                var single_remove_id = id;
            }
            else
            {
                var boxes = $('input[name="check[]"]:checked');
                $(boxes).each(function(){
                      myarray[i]=this.value;
                      i++;
                });
            }

          var url = "<?php echo $this->config->item('admin_base_url').$this->viewname.'/ajax_delete_all';?>";


          $.ajax({

          type: "POST",
          url: url,
          //dataType: 'json',
          data: {'myarray':myarray,'single_remove_id':id},
          success: function(data){
            //alert(data);
            $.ajax({
              type: "POST",
              url: "<?php echo $this->config->item('admin_base_url').$this->viewname?>/index/"+data,
              data: {
              result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
            },
            beforeSend: function() {
                  $('#common_div').block({ message: 'Loading...' }); 
                  },
              success: function(html){
                $("#common_div").html(html);
                $('#common_div').unblock(); 
              }
            });
            return false;
          }
        });
    }
    function statuspopup(id,status,name)
    {
        alert(status);
            if(status == 1)
            {
                var url =  "<?php echo $this->config->item('base_url').$this->viewname.'/publish_record';?>";
                if(name !=''){val ='publish';}else{val ='unpublish';};
            }
            else
            {
                var url =  "<?php echo base_url().$this->viewname.'/unpublish_record';?>";
                if(name !=''){val ='unpublish';}else{val ='publish';};
            }
            if(id == '0')
            {
                var boxes = $('input[name="check[]"]:checked');
                if(boxes.length == '0')
                  {

                       alert('Please select record(s)');
                        /*$.alert({
                            title: 'Alert!',
                            //backgroundDismiss: false,
                            content: "<strong> Please select record(s) to "+val+".<strong>",
                            confirm: function(){
                            }
                        });
                        */
                        return false;

                  }
            }
            if(id == '0')
            {
                var msg = 'Are you sure want to '+val+' Record(s)';
            }
            else
            {
                var msg = 'Are you sure want to '+val+' '+name+'?';
            }
            $.confirm({
                    title: 'Confirm!',
                    content: "<strong> "+msg+" "+"<strong>",
                    confirm: function(){
                        status_change_ajax(id,status);
                    },
                    cancel: function(){

                    }
                });
    }
    //Change status
    function status_change_ajax(id,status)
    {

        if(status == 1)
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/publish_record';?>";
            val ='unpublish';
        }
        else
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/unpublish_record';?>";
            val ='publish';
        }
        if(id != '0')
        {
            var id = id;
        }
        else
        {
            var boxes = $('input[name="check[]"]:checked');
            var myarray = new Array;
            var i=0;
            $(boxes).each(function(){
            myarray[i]=this.value;
            i++;
            });

        }
         $.ajax({
                type: "post",
                data: {'id':id,'status' : status,'myarray':myarray },
                url: url,
                 success: function(data){
            $.ajax({
              type: "POST",
              url: "<?php echo $this->config->item('admin_base_url').$this->viewname?>/index/"+data,
              data: {
              result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
            },
            beforeSend: function() {
                  $('#common_div').block({ message: 'Loading...' });
                  },
              success: function(html){
                $("#common_div").html(html);
                $('#common_div').unblock();
              }
            });
            return false;
          }
            });
    }


    function activatpopup(id,status,name)
    {
        if(status == 'active')
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/publish_record';?>";
            if(name !=''){val ='publish';}else{val ='unpublish';};
        }
        else
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/unpublish_record';?>";
            if(name !=''){val ='unpublish';}else{val ='publish';};
        }
        if(id == '0')
        {
            var boxes = $('input[name="check[]"]:checked');
            if(boxes.length == '0')
            {
                alert('Please select record(s) to');
                /*$.alert({
                    title: 'Alert!',
                    //backgroundDismiss: false,
                    content: "<strong> Please select record(s) to "+val+".<strong>",
                    confirm: function(){
                    }
                });
                */
                return false;

            }
        }
        if(id == '0')
        {
            var msg = 'Are you sure want to '+val+' Record(s)';
        }
        else
        {
            var msg = 'Are you sure want to '+val+' '+name+'?';
        }
        $.confirm({
            title: 'Confirm!',
            content: "<strong> "+msg+" "+"<strong>",
            confirm: function(){
                activat_change_ajax(id,status);
            },
            cancel: function(){

            }
        });
    }
    //Change status
    function activat_change_ajax(id,status)
    {

        if(status == 'active')
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/publish_record';?>";
            val ='unpublish';
        }
        else
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/unpublish_record';?>";
            val ='publish';
        }
        if(id != '0')
        {
            var id = id;
        }
        else
        {
            var boxes = $('input[name="check[]"]:checked');
            var myarray = new Array;
            var i=0;
            $(boxes).each(function(){
                myarray[i]=this.value;
                i++;
            });

        }
        $.ajax({
            type: "post",
            data: {'id':id,'activated' : status,'myarray':myarray },
            url: url,
            success: function(data){
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->config->item('admin_base_url').$this->viewname?>/index/"+data,
                    data: {
                        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
                    },
                    beforeSend: function() {
                        $('#common_div').block({ message: 'Loading...' });
                    },
                    success: function(html){
                        $("#common_div").html(html);
                        $('#common_div').unblock();
                    }
                });
                return false;
            }
        });
    }

    function activat_campaign_archive(link)
    {
            var url =  "<?php echo base_url().'Campaignarchive/campaign_archive';?>";
            var boxes = $('input[name="check[]"]:checked');
            var myarray = new Array;
            var i=0;
            $(boxes).each(function(){
                myarray[i]=this.value;
                i++;
            });
        $.ajax({
            type: "post",
            data: {'myarray':myarray },
            url: url,
            success: function(data){
                window.location.href = link;
                //$("#common_div").html(data);
                return true;
            }
        });
    }

    /*on and off button function */
    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked')) $(className).show();
        else $(className).hide();
    }

    function toggle_show_requirement(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked')){
            $(className).show();
        }else {
            $(className).val("");
            $(className).hide();

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
</script>
