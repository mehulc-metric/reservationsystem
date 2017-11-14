<?php
/*
  @Description : Module Add/edit
  @Author      : Mehul Patel
  @Date        : 08-06-2017

 */
$this->type = ADMIN_SITE;
$formAction = !empty($editRecord)?'edit':'add';
$path = $form_action_path;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           <?PHP if($formAction == "add"){ ?><?=$this->lang->line('add_module')?><?php }else{ ?><?=$this->lang->line('edit_module')?><?php }?>

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url($this->type . '/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?PHP if($formAction == "add"){ ?><?=$this->lang->line('add_module')?><?php }else{ ?><?=$this->lang->line('edit_module')?><?php }?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?PHP if($formAction == "add"){ ?><?=$this->lang->line('add_module')?><?php }else{ ?><?=$this->lang->line('edit_module')?><?php }?></h3>
                        <a class="btn btn-primary pull-right" onclick="history.go(-1)" href="javascript:void(0)">Back</a> 
                         <?= isset($validation) ? $validation : ''; ?>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="<?= $this->viewname ?>" class="form-horizontal" data-parsley-validate method="post" action="<?= base_url($path) ?>"  ENCTYPE="multipart/form-data">
                        <div class="box-body">

                            <div class="col-sm-6">
                            <div class="form-group">
                                <label for="component_name" class="control-label col-sm-4 required"><?=$this->lang->line('component_name')?></label>
                                <div class="col-sm-8">
                                    <?php
                                    $options = array('ReservationSystem'=>"ReservationSystem");
                                    $name = "component_name";
                                    if($formAction == "add"){
                                        $selected = 1;
                                    }else{
                                        $selected = $editModuleRecord[0]['component_name'];
                                    }
                                    echo dropdown( $name, $options, $selected );
                                    ?>
                                    <span class="text-danger"><?php echo form_error('component_name'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="module_name" class="control-label col-sm-4 required"><?=$this->lang->line('module_name')?></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="module_name" placeholder="<?=$this->lang->line('module_name')?>" type="text" value="<?=!empty($editModuleRecord[0]['module_name'])?$editModuleRecord[0]['module_name']:''?>" data-parsley-pattern="/^\S*$/" required="" />
                                    <span class="text-danger"><?php echo form_error('module_name'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="controller_name" class="control-label col-sm-4 required"><?=$this->lang->line('controller_name')?></label>
                                <div class="col-sm-8">
                                    <input class="form-control" name="controller_name" placeholder="<?=$this->lang->line('controller_name')?>" type="text" value="<?=!empty($editModuleRecord[0]['controller_name'])?$editModuleRecord[0]['controller_name']:''?>" data-parsley-pattern="/^\S*$/" required="" />
                                    <span class="text-danger"><?php echo form_error('controller_name'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="module_status" class="control-label col-sm-4 required"><?=$this->lang->line('module_status')?></label>
                                <div class="col-sm-8">
                                    <?php
                                    $options = array('1'=>"Active",'0'=>"InActive");
                                    $name = "module_status";
                                    if(isset($editModuleRecord[0]['status']) && $editModuleRecord[0]['status'] == '1'){
                                        $selected = $editModuleRecord[0]['status'];
                                        
                                    }else if(isset($editModuleRecord[0]['status']) && $editModuleRecord[0]['status'] == '0'){
                                        $selected = $editModuleRecord[0]['status'];
                                    }else{
                                        $selected = 1;    
                                    }
                                    echo dropdown( $name, $options, $selected );
                                    ?>
                                    <span class="text-danger"><?php echo form_error('module_status'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                            <div class="col-sm-12 text-center">
                                <div class="bottom-buttons">
                                    <input name="module_id" type="hidden" value="<?=!empty($editModuleRecord[0]['module_id'])?$editModuleRecord[0]['module_id']:''?>" />
                                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                                    <?php if($formAction == "add"){?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_module')?>" />
                                    <?php }else{?>
                                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_module')?>" />
                                    <?php }?>
                                    <input type="reset"  class="btn btn-info" name="remove"  value="Reset" />
                                    <a href="<?php echo base_url()?>/ModuleMaster" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </form>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
