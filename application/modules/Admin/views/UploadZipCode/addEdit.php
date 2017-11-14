<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="content-wrapper">
    
      <section class="content-header">
        <h1><?php echo ($screenType == 'edit') ? lang('edit_postal_code') : ''; ?></h1>
      </section>
  <div class="content">
		<div clas="row">
            <div class="col-md-12 error-list">
				
                <?= (isset($validation) && !empty($validation)) ? '<div class="alert alert-danger text-center">'.$validation.'<div>' : ''; ?>
            </div>
        </div>
        <?php
        $attributes = array("name" => "customer_add_edit", "id" => "customer_add_edit", "data-parsley-validate" => "", "class" => "form-horizontal", 'novalidate' => '');
        echo form_open_multipart($form_action_path, $attributes);
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                               <?php echo lang("uploaded_data_information"); ?>
                            </a>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">

                            <div class="panel-body">
                              <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("zip_code"); ?>:</label>
                                        <div class="col-sm-8">
                                            <input type="text" id='zip_code' name='zip_code' class="form-control" placeholder="<?php echo lang("zip_code"); ?>" value="<?php echo set_value('zip_code', (isset($editRecord[0]['zip_code'])) ? $editRecord[0]['zip_code'] : ''); ?>" data-parsley-minlength="4" data-parsley-minlength-message ='Min. 4 Characters are allowed.' data-parsley-maxlength="10" data-parsley-maxlength-message ='Max. 10 Characters are allowed.' required='true' data-parsley-type="digits" data-parsley-trigger="keyup" data-parsley-zipcode autocomplete="off" />
                                        </div>
                                    </div>
                                </div>

								<div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("population"); ?>:</label>
                                        <div class="col-sm-8">
                                            <input type="text" id='population' name='population' class="form-control" placeholder="<?php echo lang("population"); ?>" value="<?php echo set_value('population', (isset($editRecord[0]['population'])) ? $editRecord[0]['population'] : ''); ?>" required='true' data-parsley-maxlength="50" data-parsley-maxlength-message ='Max. 50 Characters are allowed.' data-parsley-type="alphanum" data-parsley-trigger="keyup" />
                                        </div>
                                    </div>
                                </div>
								
								<div class="clearfix"></div>
								
								<div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("province"); ?>:</label>
                                        <div class="col-sm-8">
                                            <input type="text" id='province' name='province' class="form-control" placeholder="<?php echo lang("province"); ?>" value="<?php echo set_value('province', (isset($editRecord[0]['province'])) ? $editRecord[0]['province'] : ''); ?>" required='true' data-parsley-maxlength="50" data-parsley-maxlength-message ='Max. 50 Characters are allowed.' data-parsley-type="alphanum" data-parsley-trigger="keyup" />
                                        </div>
                                    </div>
                                </div>
								
                                <!--<div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("contract_code"); ?>:</label>
                                        <div class="col-sm-8">
                                            <input type="text" id='contract_code' name='contract_code' class="form-control" placeholder="<?php echo lang("contract_code"); ?>" value="<?php echo set_value('contract_code', (isset($editRecord[0]['contract_code']) ? $editRecord[0]['contract_code'] : '')) ?>" data-parsley-minlength="4" data-parsley-maxlength="30" data-parsley-maxlength-message ='Max. 30 Characters are allowed.' required='true' data-parsley-trigger="keyup"/>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="clearfix"></div>
								
                                <div class="col-sm-12 text-center">
                                    <div class="bottom-buttons">
                                        <input class='btn btn-primary' type='submit' name='add_save' id='add_save' value='<?php echo ($screenType == 'edit') ? lang('update_btn') : 'Add' ?>' />
                                        <a href="<?php echo base_url($crnt_view) ?>" class="btn btn-default"><?php echo lang("COMMON_LABEL_CANCEL"); ?></a>
                                    </div>
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
</div>
<script type="text/javascript">
var checkduplicateZipcodeURL = "<?php echo base_url('Admin/UploadZipCode/checkDuplicateZipcode'); ?>";
var editId = "<?php echo $editRecord[0]['id'] ?>";
var duplicateErrorMessage = "<?php echo lang('zip_code_dupliate_error_msg') ?>";
</script>