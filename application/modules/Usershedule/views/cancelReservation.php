<div class="section">
    <div class="col-xs-12 col-lg-6 col-lg-offset-3">
       
            <?php if(($this->session->flashdata('session_msg'))){ ?>
            <div class="col-sm-12 text-center" id="div_msg">
                <?php echo $this->session->flashdata('session_msg');?>
            </div>
            <?php }?>
			
        <form id="cancelform" method="post" action="<?=base_url('Usershedule/cancelSchedule')?>" data-parsley-validate >
            <div class="form-group">
                <label for="name" class="text-center"><h3> <strong><?=lang('cancel_your_reservation')?></strong></h3></label>
                <label for="name" class="text-center"><h3> <?=lang('please_introduce_email')?></h3></label>
                <label for="name" class="text-center"><h3> <?=lang('cancelation_code_ticket')?></h3></label>
                
            </div>
            <div class="form-group">
                <label for="name"><?=lang('email_id')?></label>
                <input  type="email" class="form-control" name="email" id="email" value=""
				data-parsley-required="true" data-parsley-required-message="<?= lang ('please_enter_email_id') ?>"
				data-parsley-trigger="keyup" >
                
            </div>
            <div class="form-group">
                <label for="name"><?=lang('cancelation_code')?></label>
                <input  type="text" class="form-control" name="cancellation_code" id="cancellation_code" value=""
				data-parsley-required="true" data-parsley-required-message="<?= lang ('please_enter_cancelation_code') ?>" 
				data-parsley-trigger="keyup" >
            </div>
            <div class="form-group prev-next-btn-container text-center">
				<button class="btn btn-primary nextBtn1 btn-lg second-tab-next-btn" style="margin-left: auto; margin-right: auto;" id="submit_btn" name="submit_btn" type="submit" ><?=lang('submit')?>
              <span></span>
            </button>
            <?php /* if(($this->session->flashdata('sucess_msg'))){ ?>
                <?php  echo $this->session->flashdata('sucess_msg');?>
            <?php }else { ?>
                    <button class="btn btn-primary nextBtn4 btn-lg pull-right" type="submit" ><?=lang('submit')?></button>
             <?php }*/ ?>
                
            </div>
        </form>
    </div>
</div>
<div class="clearfix"></div>
<script>
$(document).ready(function(){
	$("#cancelform").on("submit", function(){
		 
		 if ($('#cancelform').parsley().validate() === true){
			$("#submit_btn").attr("disabled", true); // disable the btn			
		}
	});
});
</script>