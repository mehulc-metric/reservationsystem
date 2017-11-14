<div class="modal-dialog modal-sm">
        <div class="modal-content">
         <form ENCTYPE="multipart/form-data" id="Timeshedule" data-parsley-validate method="post"
            >
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
                                    <div id="display_start_time"><?=!empty($hourlyData[0]['start_time'])?displaydateformat($hourlyData[0]['date']).' '.$hourlyData[0]['start_time']:''?></div>
                                </div>
                                <div class="form-group clearfix ">
                                    <label for="name"><?=lang('end_time')?> : </label>
                                   <div id="display_end_time"><?=!empty($hourlyData[0]['end_time'])?displaydateformat($hourlyData[0]['date']).' '.$hourlyData[0]['end_time']:''?></div>

                                </div>
                                <div class="form-group clearfix ">
                                    <label for="name"><?=lang('select_as_reservable')?><span class="viewtimehide">*</span></label>
                                  <input  data-parsley-required-message="<?=lang('select_as_reservable')?>" <?=(!empty($hourlyData[0]['is_reservable']) && $hourlyData[0]['is_reservable'] == 1)?'checked':''?> type="radio" name="slot_type" value="1" required> <?=lang('reservable')?>
                                  <input type="radio" <?=(!empty($hourlyData) && $hourlyData[0]['is_reservable'] == 0)?'checked':''?> name="slot_type" value="0" required> <?=lang('non_reservable')?>
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
                     <input type="hidden" name="date" id="date" value="<?=!empty($hourlyData[0]['date'])?$hourlyData[0]['date']:''?>">
                     <input type="hidden" name="hourly_ts_id" id="hourly_ts_id" value="<?=!empty($hourlyData[0]['hourly_ts_id'])?$hourlyData[0]['hourly_ts_id']:''?>">
                     <input type="hidden" name="start_time" id="start_time" value="<?=!empty($hourlyData[0]['start_time'])?$hourlyData[0]['start_time']:''?>">
                     <input type="hidden" name="end_time" id="end_time" value="<?=!empty($hourlyData[0]['end_time'])?$hourlyData[0]['end_time']:''?>">
                     <input type="hidden" name="total_minute" id="total_minute" value="">
                     <input type="hidden" name="total_duration" id="total_duration" value="">
                    <input class="btn btn-primary" id="submitButton"  type="submit"
                           value="<?= lang ('submit') ?>"/>
                    <input type="button" data-dismiss="modal" aria-label="Close" class="btn btn-primary" name="cancel"
                     id="cancel" value="<?= lang ('cancel') ?>">
                     <?php if(checkAdminPermission('Timeshedule','delete')){ ?>
                     <?php if(!empty($hourlyData)) { ?>
                       <input type="button" data-dismiss="modal" onclick="return deletepopup(<?=!empty($hourlyData[0]['hourly_ts_id'])?$hourlyData[0]['hourly_ts_id']:''?>)" aria-label="Close" class="btn btn-primary" name="cancel"
                             id="delete" value="<?= lang ('delete') ?>">
                      <?php } } ?>
                </div>
            </div>
        </form>
    </div>