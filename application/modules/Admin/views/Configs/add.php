<?php
/*
  @Description : Config
  @Author      : Niral Patel
  @Date        : 23-10-2015

 */
$head_action = "Manage";
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
$formAction = !empty($editRecord) ? 'update_data' : 'update_data';
$paymentformAction = !empty($editRecord) ? 'update_payment_data' : 'update_payment_data';
$pathPaymentSettings = $this->type . '/' . $this->viewname . '/' . $paymentformAction;
$path = $this->type . '/' . $this->viewname . '/' . $formAction;

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $this->lang->line('config_module_title') ?>

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> <?php echo lang("home"); ?></a></li>
            <li class="active"><?= $head_action ?> <?= $this->lang->line('config_module_title') ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <?php if (($this->session->flashdata('msg1'))) { ?>
                    <div class="col-sm-12 text-center" id="div_msg">
                        <?php echo $this->session->flashdata('msg1'); ?>
                    </div>
                <?php } ?>
                <?php if (($this->session->flashdata('msg'))) { ?>
                    <div class="col-sm-12 text-center" id="div_msg">
                        <?php echo $this->session->flashdata('msg'); ?>
                    </div>
                <?php } ?>
                <div class="panel with-nav-tabs panel-default">
                    <div class="panel-body">
                      <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab"><?= $head_action ?> <?= $this->lang->line('config_module_title') ?></a></li>
                            <li><a href="#tab2default" data-toggle="tab"><?= $this->lang->line('paymentSettings') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">   
                                <form id="Configs" data-parsley-validate method="post" action="<?= base_url($path) ?>"  ENCTYPE="multipart/form-data" class="form-horizontal">
                                    <div class="box-body">
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                         <div class="form-group bd-form-group">
                                            <label for="name" class="col-sm-5 col-md-6 col-lg-4 control-label"><?= $this->lang->line('no_of_slot_per_hour') ?> <span class="viewtimehide">*</span></label>
                                           <div class="col-sm-6 col-xs-12">
                                            <input  class="form-control" data-parsley-type="integer" type="text" name="no_of_slot_per_hour" id="no_of_slot_per_hour" maxlength="2" value="<?= !empty($no_of_slot_per_hour[0]['value']) ? $no_of_slot_per_hour[0]['value'] : '' ?>"  class="form-control" onblur="return checkInput();" onkeypress="return isNumberKey(event)" required >
                                           </div>
                                        </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group bd-form-group">
                                            <label for="name" class="col-sm-5 col-md-6 col-lg-4 control-label"><?= $this->lang->line('no_of_people_per_hour') ?> <span class="viewtimehide">*</span></label>
                                            <div class="col-sm-6 col-xs-12">
                                            <input  class="form-control" type="number" min="2" name="no_of_people_per_hour" id="no_of_people_per_hour" maxlength="2" data-parsley-type="digits" value="<?= !empty($no_of_people_per_hour[0]['value']) ? $no_of_people_per_hour[0]['value'] : '' ?>" class="form-control" onkeypress="return isNumberKey(event)" required>
                                            </div>
                                        </div>
                                        </div>
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer text-center">
                                        <input class="btn btn-primary" id="submitConfigSettings" type="submit" value="<?= $this->lang->line('submit') ?>" />
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tab2default">
                                <form id="Configs" data-parsley-validate method="post" action="<?= base_url($pathPaymentSettings) ?>"  ENCTYPE="multipart/form-data">
                                    <div class="box-body form-horizontal">
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group bd-form-group">
                                                <label for="amount" class="col-sm-5 col-md-6 col-lg-4 control-label"><?= $this->lang->line('amount_per_user') ?><span class="viewtimehide">*</span></label>
                                                <div class="input-group col-sm-6">
                                                    <input  type="text" placeholder="<?= lang('amount_per_user') ?>" name="amount" id="number_of_user" value="<?php
                                                        if ($formAction == "insertdata") {
                                                            echo set_value('amount');
                                                            ?><?php } else { ?><?= !empty($amount[0]['value']) ? $amount[0]['value'] : '' ?><?php } ?>"  class="form-control" data-parsley-validation-threshold="1" data-parsley-required="true" data-parsley-maxlength="4" data-parsley-type="digits" data-parsley-errors-container=".amount" data-parsley-trigger="keyup">
															<span class="input-group-addon"><i class="fa fa-eur" aria-hidden="true"></i></span>
															 
                                                </div>
												<span class="amount err" ></span>
                                            </div>
                                        </div>
										
                                        <div class="col-xs-12 col-12-6 col-md-6 col-lg-6">
                                            <div class="form-group bd-form-group">
                                                <label for="vat" class="col-sm-5 col-md-6 col-lg-4 control-label"><?= $this->lang->line('vat') ?><span class="viewtimehide">*</span></label>
                                                <div class="input-group col-sm-6">                                               
													<input type="text" placeholder="<?= lang('vat') ?>" name="vat" id="number_of_user" value="<?php if ($formAction == "insertdata") { echo set_value('vat');?><?php } else { ?><?= !empty($vat[0]['value']) ? $vat[0]['value'] : '' ?><?php } ?>"  class="form-control" data-parsley-required="true" data-parsley-type="number" data-parsley-maxlength="4" data-parsley-errors-container=".vat" data-parsley-trigger="keyup">
													<span class="input-group-addon">%</span>
													
                                                </div>
                                            </div>
											<span class="vat err" ></span>
                                        </div>
                                      </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer text-center">
                                        <input class="btn btn-primary " type="submit" id="submitPaymentSettings" value="<?= $this->lang->line('submit') ?>" />
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="tab-pane fade" id="tab3default">Default 3</div>
                            <div class="tab-pane fade" id="tab4default">Default 4</div>
                            <div class="tab-pane fade" id="tab5default">Default 5</div> -->
                        </div>
                    </div>
                </div>               
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
