<div class="section">
        <!--<img class="logo" src="<?= base_url('uploads/assets/frontend/img/logo.png') ?>" />-->
    <div class="logo"></div>
    <!-- <img class="logo" src="./uploads/assets/frontend/img/logo.png" /> -->
    <div class="stepwizard">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step">
                <a href="#step-1" type="button" class="btn btn-default btn-circle bt1" disabled="disabled"></a>            
            </div>
            <div class="stepwizard-step">
                <a href="#step-2" type="button" class="btn btn-default btn-circle bt2" disabled="disabled"></a>            
            </div>
            <div class="stepwizard-step">
                <a href="#step-3" type="button" class="btn btn-default btn-circle bt3" disabled="disabled"></a>            
            </div>
            <div class="stepwizard-step">
                <a href="#step-4" type="button" class="btn btn-default btn-circle bt4" disabled="disabled"></a>            
            </div>
            <div class="stepwizard-step">
                <a href="#step-5" type="button" class="btn btn-primary btn-default btn-circle bt5" disabled="disabled"></a>
                <!-- <p>Step 5</p> -->
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
	<?php if (($this->session->flashdata('session_msg'))) { ?>
		<div class="col-sm-12 text-center" id="div_msg">
			<?php echo $this->session->flashdata('session_msg'); ?>
		</div>
	<?php } ?>
    <div class="clearfix"></div>
    <div class="setup-content" id="step-1" style="display: none;">    
        <form id="userform1" data-parsley-validate method="post">
            <div class="left-margin-block">
                <div class="form-group">
                    <label><?= lang('enter_zip_code') ?></label>
                    <div>
                        <input type="text" class="form-control postal-code" name="zip_code" id="zip_code" data-parsley-required="true" data-parsley-required-message="<?= lang('postal_code_required');?>" data-parsley-errors-container=".zipCodeError" data-parsley-minlength="4" data-parsley-minlength-message ='<?= lang('postal_code_min_msg');?>' data-parsley-maxlength="10" data-parsley-maxlength-message ='<?= lang('postal_code_max_msg');?>' data-parsley-type="digits" data-parsley-type-message="<?= lang('postal_code_digit_msg');?>" data-parsley-trigger="keyup" data-parsley-zip_code autocomplete="off" />
                        <i class="msg postal-code-msg"><?= lang('as_an_AGBAR_customer') ?><?= lang('you_have_access_up_to_4_people') ?></i>
                    </div>
                   
                    <span class="zipCodeError err" ></span>
                    <div class="clearfix"></div>
                <p class="desc">
                  <i><?= lang('enter_message') ?></i>
                </p>
                </div>
              <div class="divider"></div>
                <div class="form-group email-1">
                    <label><?= lang('enter_email_id') ?></label>
                    <div>
                        <input type="email" class="form-control email-address" name="email" id="email" 
						data-parsley-errors-container=".emailError" 
						data-parsley-required="true" data-parsley-required-message="<?= lang('please_enter_email_id') ?>" 
						data-parsley-trigger="keyup" 
						data-parsley-email 
						data-parsley-type="email" data-parsley-type-message="<?= lang('Invalid_email_id') ?>"
						autocomplete="off"/>
                        <i class="msg email-address-msg"><?= lang('email_has_been_used') ?></i>
                    </div>
                   <div class="clearfix"></div>
                    <span class="emailError err" ></span>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group email-2">
                    <label><?= lang('check_email') ?></label>
                    <div>
                        <input type="email" class="form-control email-address" name="confrim_email" id="confrim_email" 
						data-parsley-errors-container=".confrim_emailError" 
						data-parsley-required="true" data-parsley-required-message="<?= lang('please_enter_email_id') ?>" 
						data-parsley-equalto="#email" data-parsley-equalto-message=<?= lang('confirm_email')?>
						autocomplete="off" 
						data-parsley-type="email" data-parsley-type-message="<?= lang('Invalid_email_id') ?>" />
                    </div>
                    <div class="clearfix"></div>
                    <span class="confrim_emailError err"></span>
                    <div class="clearfix"></div>
                </div>
              <div class="clearfix"></div>
              <div class="divider"></div>
                <div class="checkbox more">
                   
                        <input class="styled-checkbox" type="checkbox" value="" id="is-group" />
                      <label for="is-group">   <?= lang('number_of_family') ?>
                      <i><?= lang('must_show_card') ?></i></label>
                    
                </div>
              
			  <div class="checkbox more more-2">
                <input class="styled-checkbox" type="checkbox" id="is-group2" value=""
				data-parsley-required="true"
				data-parsley-errors-container=".age_checkbox"/>
                <label for="is-group2"><?= lang('age_message') ?>
					<i><?= lang('age_message1') ?></i>
				</label>
				<span class="age_checkbox err" ></span>
              </div>
			  
              <div class="clearfix"></div>
              <div class="divider"></div>
                <h2 class="first-tab-title"> <?= lang('select_no_of_people') ?></h2>
                <div class="btn-group no-of-persons" data-toggle="buttons">
                    <?php
                    if (!empty($userPerHour)) {
                        for ($i = 1; $i <= $userPerHour; $i++) {
                            ?>
                            <label class="btn btn-primary" onclick="getValue('<?= $i ?>');">
                                <input type="radio" name="no_of_people" 
								data-parsley-errors-container=".no_of_peopleError" 
								data-parsley-required="true" data-parsley-required-message ="<?= lang('no_of_people_required')?>"
								class="totalmember" id="no_of_people<?= $i ?>" value="<?= $i ?>" /> <?= $i ?> 
                            </label>
                            <?php
                        }
                    }
                    ?>
                    <span class="plus-sign display-checked">+</span>
                    <label class="btn btn-primary display-checked" onclick="getValue('5');" ><input type="radio" name="no_of_people" data-parsley-errors-container=".no_of_peopleError" class="totalmember" id="no_of_people5" value="5" />5</label>
                    <label class="btn btn-primary  display-checked" onclick="getValue('6');"><input type="radio" name="no_of_people" data-parsley-errors-container=".no_of_peopleError" class="totalmember" id="no_of_people6" value="6" />6</label>
                </div>
                <div class="clearfix"></div>
                <span class="no_of_peopleError err" ></span>
            <div class="form-group m-t-xs-30">
            <div class="pricing-details">
                <input type="hidden" name="is_agbar_customer" id="is_agbar_customer">
                <input type="hidden" name="config_amount" id="config_amount" value="<?= $config_amount ?>">
                <input type="hidden" name="config_vat" id="config_vat" value="<?= $config_vat ?>">
                <input type="hidden" name="final_amount_set" id="final_amount_set">
                <input type="hidden" name="email_id" id="email_id">
                <p class="title"><?= lang('final_price') ?></p>
                  </div>
              <div class="clearfix"></div>
              <div class="divider divider-pricing"></div>
              <div class="pricing-details">
                <p class="price">
                    <i class="no-of-tickets">0</i>
                    <i>X</i>
                    <i class="ticket-price"><?= $config_amount ?>€</i>
                    <i>+</i>
                    <i class="ticket-price"><?= lang('vat') ?></i>
                    <i class="ticket-price"><?= $config_vat ?>%</i>
                    <span class="bar">|</span>
                    <i>
                        <b class="total-price">0</b>
                        <b>€</b>
                    </i>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>
            </div>

        </form>
      <div class="form-group">
        <button class="btn btn-primary nextBtn1 btn-lg pull-right first-tab-next-btn  prev-next-btn-container " type="button" ><?= lang('next') ?>
                <span></span>
            </button>
        </div>
        <!--<div class="first-tab-desc">
            <?= lang('front_notice') ?>
        </div>-->
    </div>  
</div>
<div class="setup-content" id="step-2" style="display: none;">
    <div class="row p-lr-xs-15">
        <div class="clearfix"></div>
        <div class="col-xs-12">
            <h2 class="select-date"><?= lang('select_date') ?></h2>
        </div>
        <div class="clearfix"></div>
        <div class="box-body">
            <input id="reservation_date" name="reservation_date" value="" type="hidden">
            <div id='calendar'></div>

        </div>
        <div class="form-group prev-next-btn-container">
            <button class="btn btn-primary prevBtn btn-lg pull-left second-tab-prev-btn" type="button" ><span></span><?= lang('back') ?></button>
            <button class="btn btn-primary nextBtn2 btn-lg pull-right second-tab-next-btn" type="button" ><?= lang('next') ?><span></span></button>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="setup-content" id="step-3" style="display: none;">
    <div class="row">
        <!-- Display dynamic hour -->
        <div id="hoursSlot">

        </div>
        <div class="form-group prev-next-btn-container">
            <div class="p-lr-xs-15">
                <button class="btn btn-primary prevBtn btn-lg pull-left second-tab-prev-btn" type="button" ><span></span><?= lang('back') ?></button>
                <button class="btn btn-primary nextBtn3 btn-lg pull-right second-tab-next-btn" type="button" ><?= lang('next') ?><span></span></button>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="setup-content" id="step-4" style="display: none;">
    <form onsubmit="return false;" id="userform4" data-parsley-validate method="post" action="<?= base_url($path) ?>"  ENCTYPE="multipart/form-data">
        <div class="box-body text-center">

            <div class="container-500">
                <h3 class="time-assigned-label"> <?= lang('assigned_the_following_time') ?></h3>
                <h3 class="time-assigned-value"><span id="confirm_date"></span> + <span id="no_of_people_selected"></span> <?= lang('people') ?></h3>
            </div>

            <div class="container-500">
                <h6 class="ticket-time-note"><?= lang('please_be_aware') ?></h6>
                <div class="col-md-12 text-center">
                    <h6 class="ticket-time-note"><?= lang('reservation_cancellation_notice') ?></h6>
                    <!-- <input class="confirm-checkbox" data-parsley-errors-container="#is_confirm-errors" data-parsley-required-message="<?= lang('please_confirm_reservation') ?>" type="checkbox" name="is_confirm" required value="1"> -->                 
                    <!-- <span id="is_confirm-errors"></span> -->
                </div>
            </div>
        </div>
        <div class="left-margin-block">
            <div class="clearfix"></div>
            <div class="form-group ticket-checkboxes">
                <span><label class="confirm-msg" ><input type="checkbox" name="notice[]" id="notice_1" value="1" class="styled-checkbox" />  <label for="notice_1"><?= lang('notice_1') ?></label</label>></span></br>
                <span><label class="confirm-msg" ><input type="checkbox" name="notice[]" id="notice_2" value="2" class="styled-checkbox" /><label for="notice_2"> <?= lang('notice_2') ?></label></label></span></br>
                <span><label class="confirm-msg" ><input type="checkbox" name="notice[]" id="notice_3" value="3" class="styled-checkbox" /><label for="notice_3"> <?= lang('notice_3') ?></label></label></span></br>
                <span><label class="confirm-msg" ><input type="checkbox" name="notice[]" id="notice_4" value="4" class="styled-checkbox" /> <label for="notice_4"><?= lang('notice_4') ?></label></label></span></br>               
            </div>
          <div class="payment-btn pull-right" id="payment_btn_div">
					<button class="btn pull-right btn-lg payment-white-btn" type="button" name="payment_btn" id="payment_btn">CONFIRM</button>
				</div>
        <div class="payment-details">
                <p class="title"><?= lang('final_price') ?></p>
          </div>
              <div class="clearfix"></div>
              <div class="divider divider-pricing mar"></div>
              <div class="payment-details">
                <p class="price">
                    <i class="no-of-tickets">0</i>
                    <i>X</i>
                    <i class="ticket-price"><?= $config_amount ?>€</i>
                    <i>+</i>
                    <i class="ticket-price"><?= lang('vat') ?></i>
                    <i class="ticket-price"><?= $config_vat ?>%</i>
                    <span class="bar">|</span>
                    <i>
                        <b class="total-price">0</b>
                        <b>€</b>
                    </i>
                </p>
					<!-- code added by JS for payment button : end  -->
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-body text-center">
            <input type="hidden" name="confirm_email" id="confirm_email">
            <input id="hourly_ts_id" name="hourly_ts_id" value="" type="hidden">
            <!-- <div class="container-625">  
                 <input  type="email" placeholder="<?= lang('email_id') ?>" data-parsley-required-message="<?= lang('please_enter_email_id') ?>" name="confirm_email" id="confirm_email" value=""  class="form-control" required >
               <input id="hourly_ts_id" name="hourly_ts_id" value="" type="hidden">
           </div> -->
            <div class="form-group prev-next-btn-container">
                <div class="">
                    <button class="btn btn-primary nextBtn4 btn-lg pull-right second-tab-next-btn" type="submit" ><?= lang('next') ?><span></span>
                    </button>
                    <button class="btn btn-primary prevBtn btn-lg pull-left second-tab-prev-btn" type="button" ><span></span><?= lang('back') ?></button>
                </div>
				<!-- code added by JS for payment button  : start -->				
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </form>

</div>

<div class="setup-content" id="step-5">
    <div class="row text-center">
        <div class="form-group">
            <div class="container-500 p-lr-xs-15">
                <h3 class="pass-text"><?= lang('copy_of_your_exhibit_pass') ?></h3>
            </div>
        </div>
        <div id="display-qrcode">
		<?php if(is_array($dataList)){
				foreach($dataList as $qrDetails){
					if($qrDetails['status']) {
						$downloadLink = base_url('/uploads/pdf/').$qrDetails['pdf_file_name'];
						$QRCodeLink = base_url('/uploads/qr_codes/').$qrDetails['qr_code'];
		?>
			<a href="<?= base_url('Usershedule/download').'?file='.$downloadLink ?>" id="donwloadFile">
				<?php /* <a href="<?= $downloadLink ?>" target="_blank" download> <img width="100" height="100" src="<?= $QRCodeLink ?>" class="downloadable"/> */ ?>
				<img width="100" height="100" src="<?=base_url('uploads/images/download_ticket.png')?>" class="downloadable"/>
			</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php }
			}
		} ?>
        </div>
    <div class="info-box">
      <div class="info-icon"></div>
      <p class="desc m-t-10"><?= lang('pdf_worning_1') ?></p>
		<p class="desc"><?= lang('pdf_worning_2') ?></p>
		<p class="desc"><?= lang('pdf_worning_3') ?></p>
    </div>
</div>
</div>
