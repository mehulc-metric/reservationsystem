<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord) ? 'updatedata' : 'view_perms_to_role_list';
?>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <?= $this->lang->line('viewPerms') ?>
    </h1>
  </section>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= $this->lang->line('viewPerms') ?>
                    </div>
                    <div class="panel-body">

                        <?php
                        $attributes = array("name" => "permissionform");
                        echo form_open(base_url($path));
                        ?>
                        <!--   <form id="viewPermission" accept-charset="utf-8" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate name="permissionform"> -->
                        <div class="">
                            <div class="form-group">
                                <label id="datatable1" class="table table-striped" for="usertype"><?= $this->lang->line('user_name') ?>:
                                    <?php
                                    //$options = array('1'=>"Super Admin",'2'=>"Admin");
                                    $options1 = array();
                                    $options2 = array();
                                    $selected = "";
                                    foreach ($userType as $key => $value) {
                                        if ($value['role_id'] == $this->uri->segment(4)) {
                                            echo $value['role_name'];
                                        }
                                    }
                                    ?>
                                    <span class="text-danger"><?php echo form_error('username'); ?></span>

                                </label>
                            </div>
                            <div class="form-group" id="CRM_LIST">
                              <div class="table-responsive">
                                <table class="table" >
                                    <thead>

                                    <th><?php echo lang('module_list') ?></th>
                                    <?php
                                    if (count($getPermList) > 0) {
                                        foreach ($getPermList as $perm) {
                                            ?>
                                            <th><?php echo lang($perm['name']) ; ?></th>
                                            <?php
                                        }
                                    }
                                    ?>
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

                                                        <td>
                                                            <?php
                                                            foreach ($view_perms_to_role_list as $assignData) {
                                                                $checked = '';
                                                                if ($assignData['module_id'] == $modObj['module_id'] && $assignData['perm_id'] == $perm['id']) {
                                                                    echo $checked = '<i class="fa fa-check"></i>';
                                                                } else {
                                                                    echo $checked = "";
                                                                }
                                                            }
                                                            ?>

                                                        </td>
                                                        <?php
                                                    }
                                                }
                                                ?>
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
                        <!--  <div class="modal-footer">
             <center>
             <input type="hidden" name="id" value="<?php echo $this->uri->segment(4); ?>">
             <input type="hidden" name="editPerm" value="Edit Permissions">
            <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Save" />

                                     <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>

         </div> -->
                        <div class="modal-footer">
                            <center>
                              <a href="<?php echo base_url($crnt_view) ?>" class="btn btn-default">Cancel</a>
                            </center>
                        </div>
                        <?php echo form_close(); ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>