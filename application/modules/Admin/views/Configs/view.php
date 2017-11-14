<?php
/*
    @Description : Profile edit
    @Author      : Niral Patel
    @Date        : 23-10-2015

*/
 
$head_action = !empty($editRecord)?"":"Add";
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $this->viewname.'/'.$formAction;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo lang('profile'); ?>

        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url($this->type.'/dashboard')?>"><i class="fa fa-dashboard"></i><?php echo lang('home'); ?></a></li>
            <li class="active"><?=$head_action?><?php echo lang('profile'); ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=$head_action?><?php echo lang('profile'); ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form">
                  <div class="box-body">
                  <?php if(!empty($msg)){ ?>
                    <div class="col-sm-12 text-center alert alert-info" id="div_msg">
                      <?php echo $msg;?>
                    </div>
                    <?php } ?>
                   <div class="form-group">
                      <label><?php echo lang('name'); ?> :</label>
                      <?=!empty($editRecord[0]['firstname'])?$editRecord[0]['firstname']:''?> <?=!empty($editRecord[0]['lastname'])?$editRecord[0]['lastname']:''?>
                    </div>
                    <div class="form-group">
                      <label><?php echo lang('emails'); ?> :</label>
                      <?=!empty($editRecord[0]['email'])?$editRecord[0]['email']:''?>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <a class="btn btn-primary" href="<?=base_url($this->type.'/'.$this->viewname.'/edit_profile')?>"><?php echo lang('edit_profile'); ?></a>
                  </div>
                </form>
              </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
 
<script type="text/javascript">

function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}
 

</script>
