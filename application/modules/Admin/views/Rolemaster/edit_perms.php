<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertAssginPerms/';
$path = $crnt_view . '/' . $formAction;
?>
<script>
    var url ='<?php echo base_url($path); ?>';
</script>

<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
    </h1>
  </section>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?PHP if($formAction == "insertAssginPerms"){ ?><?=$this->lang->line('assigned_perms_list')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
                    </div>
                    <div class="panel-body">
                        <form id="assignPermission" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform">
                            <div class="">
                                <div class="form-group">
                                    <label for="usertype"><?= $this->lang->line('user_name') ?>:</label>
                                    <?php
                                    //$options = array('1'=>"Super Admin",'2'=>"Admin");
                                    $options1 = array();
                                    $options2 = array();
                                    $selected = "";
                                    foreach ($userType as $key => $value) {
                                        if ($this->uri->segment(4) == $value['role_id']) {
                                            echo $value['role_name'];
                                            echo "<input type='hidden' name='usertype' value='".$value['role_id']."'>";
                                        }
                                    }

                                    ?>
                                    <span class="text-danger"><?php echo form_error('user_name'); ?></span>
                                </div>

                                <div class="form-group" id="edit_CRM_LIST" >
                                  <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <th><?php echo lang('module_list') ?></th>
                                        <?php
                                        if (count($getPermList) > 0) {
                                            foreach ($getPermList as $perm) {
                                                ?>

                                                <th><label class="checkbox-inline"><input type="checkbox" class="edit_CRM_LIST_parent_horizontal_checkbox" data-tag="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-box="box_edit_CRM_LIST_<?php echo $perm['name']; ?>" /> <?php echo lang($perm['name']); ?></label></th>

                                                <?php
                                            }
                                        }
                                        ?>
                                        <th><label class="checkbox-inline"><input type="checkbox"  class="edit_CRM_LIST_parent_horizontal_checkbox_All" data-tag="parent_edit_CRM_LIST_<?php echo $perm['name']; ?>"/><?php echo lang('all_perm'); ?></label></th>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (count($CRM_module_list) > 0) {
                                            foreach ($CRM_module_list as $modObj) {
                                                $counter = 0;
                                                ?>
                                                <tr>

                                                    <td><?php echo $modObj['module_name']; ?></td>
                                                    <?php
                                                    if (count($getPermList) > 0) {
                                                        foreach ($getPermList as $perm) {
                                                            ?>

                                                            <td><input type="checkbox" name="checkbox<?php echo $modObj['module_id'] . '_' . $perm['id']; ?>"
                                                                    <?php
                                                                    foreach ($view_perms_to_role_list as $assignData) {
                                                                        $checked = '';
                                                                        if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                            echo $checked = "checked=true";
                                                                            $counter++;
                                                                        } else {
                                                                            echo $checked = "";
                                                                        }
                                                                    }
                                                                    ?>

                                                                       class="child <?php echo $modObj['module_unique_name']; ?> child_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-parent="child_edit_CRM_LIST_<?php echo $perm['name']; ?>" ></td>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                    <td><input type="checkbox" <?php echo ($counter==4)?'checked':'';?> class="parent <?php echo $modObj['module_unique_name']; ?> parent_edit_CRM_LIST_<?php echo $perm['name']; ?>" data-attr="<?php echo $modObj['module_unique_name']; ?>" data-all="edit_all_CRM_LIST" ></td>
                                                    <!--  <td><input type="checkbox" name=""></td> -->
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                  </div>

                                </div>

                            </div>
                            <div class="modal-footer">
                                <center>
                                    <input type="hidden" name="id" value="<?php echo $this->uri->segment(4); ?>">
                                    <input type="hidden" name="editPerm" value="Edit Permissions">
                                    <?php if($formAction == "insertAssginPerms"){?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Assign Permission" />
                                        <a href="<?php echo base_url($crnt_view) ?>" class="btn btn-default">Cancel</a>
                                    <?php }else{?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('edit_perms')?>" />
                                        <a href="<?php echo base_url($crnt_view) ?>" class="btn btn-default">Cancel</a>
                                    <?php }?>


                                    <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>

                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>