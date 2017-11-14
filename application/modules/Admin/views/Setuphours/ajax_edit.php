<div class="modal-dialog modal-sm">
        <div class="modal-content">
         
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <h4><b><?=lang('set_time_shedule')?></b></h4>
            </div>
            <div class="modal-body">
                <!-- Main content -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-primary">
                            <?= isset($validation) ? $validation : ''; ?>

                            <div class="box-body">
                                <div class="form-group clearfix ">
                                    <label for="name"><?=lang('start_time')?> :</label>
                                    <div id="display_start_time"><?=!empty($weeklyData[0]['start_time'])?displaydateformat($weeklyData[0]['date']).' '.$weeklyData[0]['start_time']:''?></div>

                                </div>
                                <div class="form-group clearfix ">
                                    <label for="name"><?=lang('end_time')?> : </label>
                                   <div id="display_end_time"><?=!empty($weeklyData[0]['end_time'])?displaydateformat($weeklyData[0]['date']).' '.$weeklyData[0]['end_time']:''?></div>

                                </div>
                                <div class="form-group clearfix ">
                                    <label for="name">Select open/close<span class="viewtimehide">*</span></label>
                                  <input  data-parsley-required-message="Select open/close" <?=(!empty($weeklyData[0]['is_open']) && $weeklyData[0]['is_open'] == 1)?'checked':''?> type="radio" name="is_open" value="1" required><?php echo lang('open'); ?>
                                  <input type="radio" <?=(!empty($weeklyData) && $weeklyData[0]['is_open'] == 0)?'checked':''?> name="is_open" value="0" required><?php echo lang('close'); ?>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
               <!-- /.row -->
            </div>
            <div class="modal-footer">
                <div class="box-footer text-center">
                    <input type="hidden" name="weekly_ts_id" id="weekly_ts_id" value="<?=!empty($weeklyData[0]['weekly_ts_id'])?$weeklyData[0]['weekly_ts_id']:''?>">
                    <?php if(checkAdminPermission('Setuphours','edit')){ ?>
                    <input class="btn btn-primary" id="submitButton"  type="button" onclick="updateHour()" value="<?= lang ('submit') ?>"/>
                    <?php } ?>
                    <input type="button" data-dismiss="modal" aria-label="Close" class="btn btn-primary" name="cancel"
                     id="cancel" value="<?= lang ('cancel') ?>">
                     <?php if(checkAdminPermission('Setuphours','add')){ ?>
                    
                       <input type="button" data-dismiss="modal" onclick="return deletepopup(<?=!empty($weeklyData[0]['hourly_ts_id'])?$weeklyData[0]['hourly_ts_id']:''?>)" aria-label="Close" class="btn btn-primary" name="cancel"
                             id="delete" value="<?= lang ('delete') ?>">
                      <?php } ?>
                </div>
            </div>
        
    </div>
    <script>
    var value_should_be_greater_then_start_time = "<?php echo lang('value_should_be_greater_then_start_time'); ?>";
    var value_greater_or_equal_to_current_time = "<?php echo lang('value_greater_or_equal_to_current_time'); ?>";
    var delete_hour = "<?php echo lang('delete_hour'); ?>";
</script>