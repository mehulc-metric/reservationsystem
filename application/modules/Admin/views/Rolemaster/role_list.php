<?php
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           <?php echo lang("role_master_list"); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url($this->type.'/dashboard')?>"><i class="fa fa-dashboard"></i><?php echo lang("home"); ?></a></li>
            <li class="active"><?php echo lang("role_master_list"); ?></li>
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
                            <?php if(($this->session->flashdata('message_session'))){ ?>
                            <div class="col-sm-12 text-center" id="div_msg">
                                <?php echo $this->session->flashdata('message_session');?>
                            </div>
                            <?php } ?>
                            <div class="row">
                              <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                  <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                                  <input type="text" name="searchtext" id="searchtext" class="form-control input-sm" placeholder="Search" aria-controls="example1" value="<?=!empty($searchtext)?$searchtext:''?>">
                                </div>
                              </div>
                              <div class="col-sm-9 col-xs-12 text-right">
                                <div class="form-group">
                                <button onclick="data_search('changesearch')" class="btn btn-primary howler"  title="Search"><?php echo lang("search"); ?></button>
                                <button class="btn btn-primary howler flt" title="Reset" onclick="reset_data()" title="Reset"><?php echo lang("reset"); ?></button>
                                <?php if (checkAdminPermission('Rolemaster', 'add')) {?>
                                <a class="btn btn-primary howler" title="Add New" href="<?=base_url($this->type.'/'.$this->viewname.'/add');?>"><?php echo lang("add_new"); ?></a>
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
                                        <?=$this->load->view($this->type.'/'.$this->viewname.'/ajaxlist','',true);?>
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



