<?php
$admin_session = $this->session->userdata('reseeit_admin_session');
$this->type = ADMIN_SITE;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?=lang('opening_and_closing_hours')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url($this->type.'/dashboard')?>"><i class="fa fa-dashboard"></i><?php echo lang('home'); ?></a></li>
            <li class="active"><?=lang('opening_and_closing_hours')?></li>
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
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <?php if(($this->session->flashdata('message_session'))){ ?>
                            <div class="col-sm-12 text-center" id="div_msg">
                                <?php echo $this->session->flashdata('message_session');?>
                            </div>
                            <?php } ?>                            
                            <div class="row">                                
                                <div class="col-sm-12">
                                    <div id="example1_filter" class="dataTables_filter">
                                    <?php if(checkAdminPermission('Setuphours','add')){ ?>
                                        <label>
                                            <a class="btn btn-primary howler flt" title="<?=lang('opening_and_closing_hours')?>" href="<?=base_url(ADMIN_SITE.'/Setuphours/add')?>" ><?=lang('opening_and_closing_hours')?></a>
                                        </label>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="common_div">
                                        <?=$this->load->view($this->type.'/'.$this->viewname.'/ajax_list','',true);?>
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
<script>
    var value_should_be_greater_then_start_time = "<?php echo lang('value_should_be_greater_then_start_time'); ?>";
</script>
