<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$disable = "";
?>

<div class="content-wrapper">
  <section class="content-header">
    <div clas="row">
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
            </div>
        </div>    
    <h1 class="page-head-line">
                    <?=lang('opening_and_closing_hours')?>
                </h1>
    </section>
  <section class="content">
    <div class="">
        <?php
        $attributes = array("name" => "customer_add_edit", "id" => "customer_add_edit", "data-parsley-validate" => "", "class" => "form-horizontal", 'novalidate' => '');
        $form_action_path =ADMIN_SITE.'/Setuphours/insert';
        echo form_open_multipart($form_action_path, $attributes);
        ?>
        <div class="row">
            <?php if(($this->session->flashdata('msg'))){ ?>
            <div class="col-sm-12 text-center" id="div_msg">
                <?php echo $this->session->flashdata('msg');?>
            </div>
            <?php } ?>
            <div class="col-md-12">
                <div class="panel-group">
                    <div class="panel panel-default">
                        
                        <div id="collapseOne" class="panel-collapse collapse in">

                            <div class="panel-body">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-2 required"><?php echo lang('repeatedly'); ?></label>
                                        <div class="col-sm-8 col-md-10">
                                            <select class="chosen-select-roleType form-control" data-parsley-errors-container="#usertype_error" onchange="changeRepeat(this)"  name="type" id="type" required >
                                                <option value="weekly">
                                                    <?php echo lang('weekly'); ?>
                                                </option>
                                                <option value="monthly">
                                                    <?php echo lang('monthly'); ?>
                                                </option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" >
                                  <div id="weekbox">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-2 required"><?php echo lang('start_date'); ?></label>
                                        <div class="col-sm-8 col-md-10">
                                            <input type="text" id='start_date' name='start_date' class="form-control" value="" required='true'/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-2 required"><?php echo lang('end_date'); ?></label>
                                        <div class="col-sm-8 col-md-10">
                                            <input type="text" id='end_date' name='end_date' class="form-control" value="" required='true'/>
                                        </div>
                                    </div>
                                  </div>
                                  <div id="monthbox">
                                      <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-2 required"><?php echo lang('select_month'); ?></label>
                                        <div class="col-sm-8 col-md-10">
                                        <?php $month = array('January','February','March','April','May','June','July','August','Septmber','Octomber','November','December');
                                          $currMonthNo = date("m", time()); ?>
                                            <select class="chosen-select-roleType form-control" data-parsley-errors-container="#month_error"  name="month" id="month">
                                              <option value="">Select month</option>
                                              <?php 
                                              
                                                for($i=0;$i<count($month);$i++)
                                                {
                                                  if($i+1 >= $currMonthNo)
                                                  {
                                                    ?>
                                                        <option value="<?=$i+1?>"><?=$month[$i]?></option>     
                                                    <?php
                                                  }

                                                }
                                                if($currMonthNo == 12)
                                                {
                                                    for($i=0;$i<count($month)-1;$i++)
                                                    {
                                                      ?>
                                                            <option value="<?=$i+1?>"><?=$month[$i]?></option>     
                                                      <?php
                                                    }
                                                }
                                              ?>

                                            </select> 
                                            <div id="month_error"></div>
                                        </div>

                                      </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-2 required"><?php echo lang('start_time'); ?></label>
                                        <div class="col-sm-8 col-md-10">
                                            <input type="text" id='start_time' data-parsley-gteqtx="#start_time"  name='start_time' class="form-control" value="" required='true'/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-2 required"><?php echo lang('end_time'); ?></label>
                                        <div class="col-sm-8 col-md-10">
                                            <input type="text" id='end_time' data-parsley-gteqt="#end_time" name='end_time' class="form-control" value="" required='true'/>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-2 required"><?php echo lang('select_day'); ?></label>
                                        <div class="col-sm-8 col-md-10">
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="weekday[]" value="Monday" data-parsley-errors-container="#ch_error" /><?php echo lang('monday'); ?>
                                          </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="weekday[]" value="Tuesday" /> <?php echo lang('tuesday'); ?>
                                            </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="weekday[]" value="Wednesday" /> <?php echo lang('wednesday'); ?>
                                            </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="weekday[]" value="Thursday" /> <?php echo lang('thursday'); ?>
                                            </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="weekday[]" value="Friday" /> <?php echo lang('friday'); ?>
                                            </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="weekday[]" value="Saturday" /> <?php echo lang('saturday'); ?>
                                            </label>
                                          <label class="checkbox-inline">
                                            <input type="checkbox" name="weekday[]" value="Sunday" /> <?php echo lang('sunday'); ?>  
                                            </label>
                                          <div id="ch_error"></div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 col-md-2 required"><?php echo lang('open_close'); ?></label>
                                        <div class="col-sm-8 col-md-10">
                                          <label class="radio-inline">
                                            <input type="radio" name="is_open" checked value="1" required /><?php echo lang('open'); ?>
                                          </label>
                                          <label class="radio-inline">
                                            <input type="radio" name="is_open" value="0" required /><?php echo lang('close'); ?>
                                          </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-sm-12 text-center">
                                    <div class="bottom-buttons">
                                        <input class='btn btn-primary' type='submit' name='add_save' id='add_save' value='Save' />
                                        <a href="<?php echo base_url(ADMIN_SITE.'/Setuphours') ?>" class="btn btn-default"><?php echo lang('cancel'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    </section>
</div>
<script>
    var value_should_be_greater_then_start_time = "<?php echo lang('value_should_be_greater_then_start_time'); ?>";
</script>