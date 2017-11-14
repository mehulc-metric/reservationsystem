<?php 
//if(isset($flag_is_group) || $flag_is_group != ""){
//    $flag_is_group = $flag_is_group;
//} else {
//    $flag_is_group = 1;
//} ?>
<div class="modal-dialog">
    <div class="modal-content">         
        <div class="modal-header">     
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4><b><?= lang('new_reservation') ?></b>&nbsp;&nbsp;Starting on <span id="display_start_time"><?= !empty($hourlyData[0]['start_time']) ? displaydateformat($hourlyData[0]['date']) . ' ' . $hourlyData[0]['start_time'] : '' ?></span></h4>
        </div>
        <div class="modal-body">
            <!-- Main content -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab"><?= lang('single_slot_reservation') ?></a>
                                </li>
                                <li>
                                    <a href="#tab_default_2" data-toggle="tab"><?= lang('group_reservation') ?></a>
                                </li>						
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">
                                    <div class="col-xs-12 col-lg-12">
                                        <form ENCTYPE="multipart/form-data" id="reserve_single_slot" data-parsley-validate method="post" action="" class="form-horizontal" novalidate>
                                            <div class="form-group ">
                                                <label for="zip_code" class="control-label col-sm-5"><?= lang('enter_zip_code') ?> : </label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control postal-code" placeholder="<?= lang('enter_zip_code') ?>" name="zip_code" id="zip_code" data-parsley-errors-container=".zipCodeError" data-parsley-minlength="4" data-parsley-minlength-message ='Min. 4 Characters are allowed.' data-parsley-maxlength="10" data-parsley-maxlength-message ='Max. 10 Characters are allowed.' data-parsley-type="digits" data-parsley-required="true" data-parsley-trigger="keyup" data-parsley-zip_code onkeyup="checkZipCode();"autocomplete="off" />
                                                    <i class="msg postal-code-msg"><?= lang('as_an_AGBAR_customer') ?><?= lang('you_have_access_up_to_4_people') ?></i>
                                                    <span class="zipCodeError err" ></span>
                                                    <div class="clearfix"></div>
                                                </div>                                                 
                                            </div>
                                            <?php //if($flag_is_group){?>
                                            <div class="form-group is-grpbox " <?php echo ((isset($flag_is_group)) && $flag_is_group<=0)?'style="display:none"':'';?> >
                                                <label class="control-label col-sm-5"></label>
                                                <div class="col-sm-6">
                                                    <input class="styled-checkbox" type="checkbox" value="" namme="is-group" id="is-group" onchange="is_group(this);" />
                                                    <label for="is-group"><?= lang('large_family') ?></label>                                                  
                                                </div>                                                
                                            </div> 
                                            <?php// } ?>
                                            <div class="form-group">
                                                <label for="name" class="control-label col-sm-5"><?= lang('select_no_user') ?> : </label>
                                                <div class="col-sm-6">
                                                    <select name="select_no_user" id="select_no_user" class="form-control" required onchange="getSelectedPeople();">                                               
                                                        <option value=""><?= lang('select_no_user') ?></option>
                                                        <?php if((isset($no_of_people_per_hour) && $no_of_people_per_hour != "") || isset($available_people)){ if(!empty($available_people)){$no_of_people_per_hour=$available_people;}else{$available_people = $available_people;} ?>
                                                            <?php for($i=1; $i<=$no_of_people_per_hour; $i++ ){?>
                                                                <option value="<?= $i ?>"><?= $i ?></option>
                                                            <?php } ?>
                                                        <?php } ?>                                                           
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <label for="name" class="control-label col-sm-5"><?= lang('email_address') ?> : </label>
                                                <div class="col-sm-6">
                                                    <input  type="email" placeholder="<?= lang('email_address') ?>" data-parsley-required-message="<?= lang('please_enter_email_id') ?>" name="email_address" id="email_address" value=""  class="form-control"  data-parsley-trigger="keyup" data-parsley-email_address data-parsley-type="email"  autocomplete="off" required >
                                                    <span class="emailError err" ></span>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>    
                                            <div class="form-group  ">
                                                <label for="name" class="control-label col-sm-5"><?= lang('confirm_email') ?> : </label>
                                                <div class="col-sm-6">
                                                    <input  type="email" placeholder="<?= lang('confirm_email') ?>" data-parsley-required-message="<?= lang('confirm_email') ?>" name="confirm_email" id="confirm_email" value="" data-parsley-equalto="#email_address" autocomplete="off" class="form-control" required >
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label for="is-free" class="control-label col-sm-5"><?= lang('allow_free_ticket') ?></label>
                                                <div class="col-sm-6">
                                                  <label class="checkbox-inline">
                                                    <input type="checkbox" value="1" namme="is-free" id="is-free" onchange="is_free_entry(this);" /> &nbsp;
                                                  </label>
                                                </div>
                                            </div>
                                          <div class="form-group">
                                            <label class="control-label col-sm-5">
                                              <input type="hidden" name="is_agbar_customer" id="is_agbar_customer">
                                                    <input type="hidden" name="is_free" id="is_free">
                                                    <input type="hidden" name="config_amount" id="config_amount" value="<?= !empty($config_amount) ? $config_amount : '' ?>">
                                                    <input type="hidden" name="config_vat" id="config_vat" value="<?= !empty($config_vat) ? $config_vat : '' ?>">
                                                    <input type="hidden" name="final_amount_set" id="final_amount_set">
                                                    <input type="hidden" name="email_id" id="email_id">
                                                    <?= lang('final_price') ?>
                                            </label>
                                            <label class="control-label col-sm-6 text-left">
                                                        <i class="no-of-tickets">0</i>
                                                        <i>X</i>
                                                        <i class="ticket-price" id="price"><?php echo ((isset($config_amount)))? $config_amount:'';?>€</i>
                                                        <i>+</i>
                                                        <i class="ticket-price"><?= lang('vat') ?></i>
                                                        <i class="ticket-price" id="vat"><?php echo ((isset($config_vat)))? $config_vat:'';?>%</i>
                                                        <span class="bar">|</span>
                                                        <i>
                                                            <b class="total-price" id="total-price">0</b>
                                                            <b>€</b>
                                                        </i>
                                            </label>
                                          </div>
                                            <div class="text-center">
                                                <input type="hidden" name="date" id="date" value="<?= !empty($hourlyData[0]['date']) ? $hourlyData[0]['date'] : '' ?>">
                                                <input type="hidden" name="hourly_ts_id" id="hourly_ts_id" value="<?= !empty($hourlyData[0]['hourly_ts_id']) ? $hourlyData[0]['hourly_ts_id'] : '' ?>">
                                                <input type="hidden" name="start_time" id="start_time" value="<?= !empty($hourlyData[0]['start_time']) ? $hourlyData[0]['start_time'] : '' ?>">
                                                <input type="hidden" name="end_time" id="end_time" value="<?= !empty($hourlyData[0]['end_time']) ? $hourlyData[0]['end_time'] : '' ?>">
                                                <input type="hidden" name="total_minute" id="total_minute" value="">
                                                <input type="hidden" name="total_duration" id="total_duration" value="">
                                                <input class="btn btn-primary" id="submitSingleSlotButton"  type="submit" value="<?= lang('submit') ?>"/>
                                                <input type="button" data-dismiss="modal" aria-label="Close" class="btn btn-primary" name="cancel" id="cancel" value="<?= lang('cancel') ?>">

                                            </div>
                                        </form> 
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_2">
                                    <div class="col-xs-12 col-lg-12">
                                        <form ENCTYPE="multipart/form-data" id="reserve_group" data-parsley-validate method="post" class="form-horizontal" novalidate>
                                            <div class="form-group ">
                                                <label for="group_zip_code" class="control-label col-sm-5"><?= lang('enter_zip_code') ?> : </label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control postal-code" placeholder="<?= lang('enter_zip_code') ?>" name="group_zip_code" id="group_zip_code" data-parsley-errors-container=".zipCodeGroupError" data-parsley-minlength="4" data-parsley-minlength-message ='Min. 4 Characters are allowed.' data-parsley-maxlength="10" data-parsley-maxlength-message ='Max. 10 Characters are allowed.' data-parsley-type="digits" data-parsley-required="true" autocomplete="off" />                                                    
                                                    <span class="zipCodeGroupError err" ></span>
                                                    <div class="clearfix"></div>
                                                </div>                                                 
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-5 control-label"><?= lang('group_name') ?> : </label>
                                                <div class="col-sm-6">
                                                    <input  type="text" placeholder="<?= lang('group_name') ?>" name="group_name" id="group_name" value=""  class="form-control" data-parsley-minlength="4" data-parsley-maxlength="30"  data-parsley-pattern="/^[-@./#&+\w\s]*$/"  required autocomplete="off" >
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label for="name" class="col-sm-5 control-label"><?= lang('number_of_user') ?> : </label>
                                                <div class="col-sm-6">
                                                    <input  type="text" placeholder="<?= lang('number_of_user') ?>" name="number_of_user" id="number_of_user" value=""  class="form-control" min="4" max="1440" step="100" 
                                                            data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
                                                            data-parsley-type="digits" required  autocomplete="off" >
                                                </div>
                                            </div> 
                                            <div class="form-group ">
                                                <label for="name" class="control-label col-sm-5"><?= lang('email_address') ?> : </label>
                                                <div class="col-sm-6">
                                                    <input  type="email" placeholder="<?= lang('email_address') ?>" data-parsley-required-message="<?= lang('please_enter_email_id') ?>" name="group_email_address" id="group_email_address" value=""  class="form-control" data-parsley-trigger="keyup" data-parsley-group_email_address data-parsley-type="group_email_address" required autocomplete="off" >
                                                </div>
                                            </div>    
                                            <div class="form-group  ">
                                                <label for="name" class="control-label col-sm-5"><?= lang('confirm_email') ?> : </label>
                                                <div class="col-sm-6">
                                                    <input  type="email" placeholder="<?= lang('confirm_email') ?>" data-parsley-required-message="<?= lang('confirm_email') ?>" name="group_confirm_email" id="group_confirm_email" value="" data-parsley-equalto="#group_email_address"  class="form-control" required autocomplete="off" >
                                                </div>
                                            </div> 
                                            <div class="text-center">
                                                <input type="hidden" name="date" id="date" value="<?= !empty($hourlyData[0]['date']) ? $hourlyData[0]['date'] : '' ?>">
                                                <input type="hidden" name="hourly_ts_id" id="hourly_ts_id" value="<?= !empty($hourlyData[0]['hourly_ts_id']) ? $hourlyData[0]['hourly_ts_id'] : '' ?>">
                                                <input type="hidden" name="start_time" id="start_time" value="<?= !empty($hourlyData[0]['start_time']) ? $hourlyData[0]['start_time'] : '' ?>">
                                                <input type="hidden" name="end_time" id="end_time" value="<?= !empty($hourlyData[0]['end_time']) ? $hourlyData[0]['end_time'] : '' ?>">
                                                <input type="hidden" name="total_minute" id="total_minute" value="">
                                                <input type="hidden" name="total_duration" id="total_duration" value="">
                                                <input class="btn btn-primary" id="submitGroupButton"  type="submit" value="<?= lang('submit') ?>"/>
                                                <input type="button" data-dismiss="modal" aria-label="Close" class="btn btn-primary" name="cancel" id="cancel" value="<?= lang('cancel') ?>">
                                            </div>
                                        </form> 
                                    </div>    
                                </div>						
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>

    </div>
    <script>
    var reservation_successfully_done = "<?php echo lang('reservation_successfully_done'); ?>";
    var somthing_went_wrong = "<?php echo lang('somthing_went_wrong'); ?>";
    var please_wait = "<?php echo lang('please_wait'); ?>";
    var successfully = "<?php echo lang('successfully'); ?>";
    var group_reservation_done = "<?php echo lang('group_reservation_done'); ?>";
    var group_reservation_not_possible = "<?php echo lang('group_reservation_not_possible'); ?>";
    var reservation_not_possible = "<?php echo lang('reservation_not_possible'); ?>";
    var config_amount = "<?php echo getCofigAmount();?>";
    var config_vat = "<?php echo getConfigVat();?>";
    var email_has_been_used = "<?php echo lang('email_has_been_used'); ?>";
    $('.postal-code-msg').hide();    
    </script>