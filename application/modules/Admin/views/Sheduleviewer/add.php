<?php
$admin_session = $this->session->userdata('reservation_admin_session');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             <?=lang('sheduleviewer')?>           
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i><?php echo lang('home'); ?></a></li>
            <li class="active"> <?=lang('sheduleviewer')?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?=lang('sheduleviewer')?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                    
                        <div id='calendar'></div>
                        
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- Main row -->
        <div class="row">
            
        </div><!-- /.row (main row) -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
 <div id="ajaxModal" class="modal">

    <?=$this->load->view($this->type.'/'.$this->viewname.'/ajax_add','',true);?>
    <!-- /.content-wrapper -->
</div>                             
</div><!-- /.box-body -->
<script type="text/javascript">
    var accessEdit = false;
    var accessAdd = false;
    var slot_duration = <?=$slot_duration?>;
    var no_of_people_per_hour = <?=$no_of_people_per_hour?>;  
    <?php if(checkAdminPermission('Sheduleviewer','edit')){ ?>
        accessEdit = true;
    <?php } ?>
    <?php if(checkAdminPermission('Sheduleviewer','add')){ ?>
        accessAdd = true;
    <?php } ?>
</script>
