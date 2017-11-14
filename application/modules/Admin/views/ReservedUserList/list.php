<?php
$admin_session = $this->session->userdata('reseeit_admin_session');
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo lang("reserved_user_list"); ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i><?php echo lang("home");?></a></li>
            <li class="active"><?php echo lang("reserved_user_list"); ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap">
                            <?php if (($this->session->flashdata('message_session'))) { ?>
                                <div class="col-sm-12 text-center" id="div_msg">
                                    <?php echo $this->session->flashdata('message_session'); ?>
                                </div>
                            <?php } ?>                            
                            <div class="row">   
                                <form id="search_form" name="search_form" method="post">
                                    <div class="">
                                        <div class="col-md-6 col-sm-12 col-xs-12 pull-left">
                                          <div class="row">
                                          <div id="example1_filter" >
                                                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
												<div class="col-sm-6">
                          <div class="form-group">
                              <input type="text" name="searchtext" id="searchtext" class="form-control input-sm" placeholder="<?php echo lang('search'); ?>" aria-controls="example1" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                          </div>
												</div>
												<div class="col-sm-6">
                          <div class="form-group">
                              <input type="text" name="daterange" id="daterange" class="form-control input-sm " value="" placeholder="<?php echo lang('select_date_range'); ?>" />
                          </div>
												</div>
												
                                                <?php /*<label class="col-sm-1 control-label" style="padding-top: 5px;">OR</label>
                                                <div class="col-sm-2"><input style="max-width: 100%;" type="text" id='start_date' name='start_date' class="form-control" value="" placeholder="<?php echo lang('start_date'); ?>" /></div>
												<div class="col-sm-2"><input style="max-width: 100%;" type="text" id='end_date' name='end_date' class="form-control" value="" placeholder="<?php echo lang('end_date'); ?>" /></div>
                                                <div class="col-sm-2"><input style="max-width: 100%;" type="text" id='start_time' data-parsley-gteqtx="#start_time" data-parsley-errors-container="#error_start_time" name='start_time' placeholder="<?php echo lang('start_time'); ?>" class="form-control" value="" /></div>
                                                <div class="col-sm-2"><input style="max-width: 100%;" type="text" id='end_time' data-parsley-gteqt="#end_time" data-parsley-errors-container="#error_end_time" name='end_time' class="form-control" placeholder="<?php echo lang('end_time'); ?>" value="" /></div>
												*/ ?>
                        </div>
                                            </div> 
                                        </div>
                                        <div class="col-md-6 col-sm-12 pull-right text-right">
                                          <div class="form-group">
                                            <button  type="button" onclick="data_search_user('changesearch')" class="btn btn-primary howler"  title="Search"><?php echo lang("search"); ?></button>
                                            <button  type="button" class="btn btn-primary howler flt" title="Reset" onclick="reset_user_data()" title="Reset"><?php echo lang("reset"); ?></button>
                                            <?php if (checkAdminPermission('ReservedUserList', 'add')) { ?>
                                                <button  type="button" class="btn btn-primary howler flt" title="ResendEmail" onclick="resendEmails();" ><?php echo lang("re_send_email"); ?></button>
                                            <?php } ?>
                                            <?php if (checkAdminPermission('ReservedUserList', 'add')) { ?>
                                                <button type="button" class="btn btn-primary howler flt"  onclick="exportExcel();" title="Export"><?php echo lang("exportExcel"); ?></button>
                                            <?php } ?>
                                          </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="error_end_time"></div>
									<div id="error_start_time"></div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <?php echo $this->session->flashdata('msg'); ?>
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="common_div">
                                        <?= $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', '', true); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?= $this->load->view('/Common/add', '', true); ?>
<?= $this->load->view($this->type . '/common/common', '', true); ?>
<script type="text/javascript">
$(function() {
    
	// Load datereange picker
	$('input[name="daterange"]').daterangepicker({
        timePicker: true,
        timePickerIncrement: 04,
        locale: {
            format: 'MM/DD/YYYY HH:mm:ss'
        }
    }).attr('readonly', 'readonly');
	
	$('input[name="daterange"]').val(''); // Default blank value set
	
	// Blank the value on hide the daterange popup
	$('input[name="daterange"]').on('hide.daterangepicker', function (ev, picker) {
		$('input[name="daterange"]').val('');
	});
	
	// Set value ince apply
	$('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
		var startDate = picker.startDate;
		var endDate = picker.endDate;  
    
		$('input[name="daterange"]').val(startDate.format('MM/DD/YYYY HH:mm:ss') + ' - ' + endDate.format('MM/DD/YYYY HH:mm:ss'));
	});
	
});
</script>
