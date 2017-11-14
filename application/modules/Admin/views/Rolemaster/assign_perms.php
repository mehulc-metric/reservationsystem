<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertAssginPerms';
$path = $crnt_view . '/' . $formAction;
?>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">
                    <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
                    </div>
                    <div class="panel-body">
                        <form id="assignPermission" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform">
                        <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="usertype"><?= $this->lang->line('user_name') ?>:</label>
                                <?php
                                $options1 = array();
                                $options2 = array();
                                $selected = "";
                                foreach ($userType as $key => $value) {
                                    array_push($options1, $value['role_id']);
                                    array_push($options2, $value['role_name']);
                                }
                                $options = array_combine($options1, $options2);
                                $name = "usertype";
                                if ($formAction == "insertdata") {
                                    $selected = 1;
                                } else {
                                    $selected = $roleId;
                                }
                                echo dropdown($name, $options, $selected);
                                ?>
                                <span class="text-danger"><?php echo form_error('usertype'); ?></span>
                            </div>
                        </div>
                        </div>
                            <div class="form-group" id="CRM_LIST">
                              <div class="table-responsive">
                                <table class="table">
                                    <thead>

                                    <th><?php echo lang('module_list') ?></th>
                                    <?php
                                    if (count($getPermList) > 0) {
                                        foreach ($getPermList as $perm) {
                                            ?>
                                            <th><label class="checkbox-inline"><input type="checkbox" class="CRM_LIST_parent_horizontal_checkbox" data-tag="child_CRM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></label>

                                            </th>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <th><label class="checkbox-inline"><input type="checkbox"  class="CRM_LIST_parent_horizontal_checkbox_All" data-tag="parent_CRM_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></label></th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (count($CRM_module_list) > 0) {
                                        foreach ($CRM_module_list as $modObj) {
                                            ?>
                                            <tr>

                                                <td><?php echo $modObj['module_name']; ?></td>
                                                <?php
                                                if (count($getPermList) > 0) {
                                                    foreach ($getPermList as $perm) {
                                                        ?>
                                                        <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id'] ; ?>" class="child <?php echo $modObj['module_unique_name']; ?> child_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_CRM_LIST_<?php echo $perm['name']; ?>"></td>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <td><input type="checkbox" class="parent <?php echo $modObj['module_unique_name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="all_CRM_LIST"></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                        <div class="clearfix"></div>
                        <div class="">
                            <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <div class="bottom-buttons">

                                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Assign Permission" />

                                    <input type="reset" class="btn btn-info" name="reset" id="reset" value="Reset" />
                                    <a href="<?php echo base_url('Admin/Rolemaster') ?>" class="btn btn-default"><?php echo lang("COMMON_LABEL_CANCEL"); ?></a>

                                </div>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>