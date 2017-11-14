<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$disable = "";
?>
<script>
    var checkEmailDuplicateURL = "<?php echo base_url($crnt_view.'/isDuplicateEmail'); ?>";
    var customer_id = "<?php echo ($screenType == 'edit') ? trim($editCustomerId) : '' ?> "; 
</script>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <?php if($screenType == 'edit') {
                            echo lang("edit_user");
                          }else{
                            echo lang("add_user");
                         }
                    ?>
    </h1>
  </section>
    <div class="content">
        <div clas="row">
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
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
                                <?php echo lang("user_information"); ?>
                            </a>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">

                            <div class="panel-body">
                              <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 required"><?php echo lang("firstname"); ?></label>
                                        <div class="col-sm-9">
                                            <input type="text" id='first_name' name='first_name' class="form-control" placeholder="<?php echo lang("firstname"); ?>" value="<?php echo set_value('first_name', (isset($editFirstName) ? $editFirstName : '')) ?>" required='true' data-parsley-required-message="Please Enter First Name"   data-parsley-minlength="4" data-parsley-maxlength="30" data-parsley-maxlength-message ='Max. 30 Characters are allowed.' data-parsley-pattern="^[a-zA-Z ]+$" data-parsley-trigger="keyup"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 required"><?php echo lang("lastname"); ?></label>
                                        <div class="col-sm-9">
                                            <input type="text" id='last_name' name='last_name' class="form-control" placeholder="<?php echo lang("lastname"); ?>" value="<?php echo set_value('last_name', (isset($editLastName) ? $editLastName : '')) ?>" required='true' data-parsley-required-message="Please Enter Last Name"   data-parsley-minlength="4" data-parsley-maxlength="30" data-parsley-maxlength-message ='Max. 30 Characters are allowed.' data-parsley-pattern="^[a-zA-Z ]+$" data-parsley-trigger="keyup"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 required"><?php echo lang("emails"); ?></label>
                                        <div class="col-sm-9">
                                            <input type="text" id='email' name='email' class="form-control" placeholder="<?php echo lang("emails"); ?>" value="<?php echo set_value('email', (isset($editEmail) ? $editEmail : '')) ?>" required='true' data-parsley-required-message="Please Enter Email"  data-parsley-type="email"  data-parsley-type-message='Please Enter Valid Email' data-parsley-maxlength="100" data-parsley-maxlength-message ='Max. 100 Characters are allowed.' data-parsley-trigger="keyup" data-parsley-email />
                                        </div>
                                    </div>
                                </div>                               
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3 required"><?php echo lang("usertype"); ?></label>
                                        <div class="col-sm-9">
                                            <?php if((isset($editRoleType)) && ($editRoleType == $this->config->item('super_admin_role_id'))){ ?>
						<p class="form-control-static" ><?php if($roleName != ""){ echo $roleName; }else{ echo "";} ?> </p>
                                                <input hidden="usertype" name="usertype" id="usertype" value="<?php echo $editRoleType; ?> ">
                                            <?php }else{ ?>
                                            <select class="chosen-select-roleType form-control" data-parsley-errors-container=".usertype_error" placeholder="<?=$this->lang->line('usertype')?>"  name="usertype" id="usertype" required <?php echo $disable; ?> >
                                                <option value="">
                                                <?= $this->lang->line('usertype_select') ?>
                                                </option>
                                                <?php $salutions_id = $editRoleType;?>
                                                <?php foreach($roleType as $row){ 
                                                          if($salutions_id == $row['role_id']){?>
                                                <option selected value="<?php echo $row['role_id'];?>"><?php echo $row['role_name'];?></option>
                                                <?php }else{?>
                                                <option value="<?php echo $row['role_id'];?>"><?php echo $row['role_name'];?></option>
                                                <?php }}?>
                                            </select> 
                                            <?php }?>
                                            <span class="usertype_error err"></span>
                                        </div>
                                         
                                    </div>
                                </div>    
                                <div class="clearfix"></div>

                                <div class="col-sm-12 text-center">
                                    <div class="bottom-buttons">
                                        <input class='btn btn-primary' type='submit' name='add_save' id='add_save' value='<?php echo ($screenType == 'edit') ? 'Update' : 'Add' ?>' />
                                        <a href="<?php echo base_url($crnt_view) ?>" class="btn btn-default">Cancel</a>
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