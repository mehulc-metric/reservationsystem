<?php
$admin_session = $this->session->userdata('reservation_admin_session');
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo lang("dashboard");?>		
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i><?php echo lang("home");?></a></li>
            <li class="active"><?php echo lang("dashboard");?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo lang("reports");?></h3>
                    </div><!-- /.box-header -->
                  <div class="row">
                    <div class="box-body">                       
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="whitebox">
                                <div class="clr"></div>
                                    <div class="text-right pull-right">
                                        <form class="navbar-form navbar-left">
                                            <div class="form-group">
                                              <div class="input-group">
                                                <select class="form-control chosen-select" id="spectators" name="spectators">
                                                    <option value="">Select</option>  
                                                    <option value="Weekly" ><?php echo $this->lang->line('weekly'); ?></option>
                                                    <option value="Monthly" ><?php echo $this->lang->line('monthly'); ?></option>                                                    
                                                </select>
                                                <div class="input-group-btn">
                                                  <button class="btn btn-default" type="button" onclick="filterSpectators();"><i class="fa fa-search fa-x"></i></button>
                                                </div>
                                              </div>
                                                
                                                <div id="customFilter1" class="filteroption" style="display: none;">
                                                    <div class="input-group date" id="start_date1" style="max-width: 150px;">
                                                        <input type="text" class="form-control"  placeholder="Start Date" id="start_date1" name="start_date1" onkeydown="return false">
                                                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                                                    </div>
                                                    <div class="input-group date" id="end_date1" style="max-width: 150px;">
                                                        <input type="text" class="form-control" placeholder="End Date" id="end_date1" name="end_date1" onkeydown="return false">
                                                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </form>
                                    </div>
                                    <div class="clr"></div>           
                                <div class=""><!-- Here Chart is load -->
                                    <div id="container"></div>   
                                </div>
                                <div class="clr"></div>
                            </div>                             
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="whitebox">
                                <div class="clr"></div>
                                    <div class="text-right pull-right">
                                        <form class="navbar-form navbar-left">
                                            <div class="form-group">
                                              <div class="input-group">
                                                <select class="form-control chosen-select" id="expected_Afluence" name="expected_Afluence">
                                                    <option value="">Select</option>  
                                                    <option value="Weekly" ><?php echo $this->lang->line('weekly'); ?></option>
                                                    <option value="Monthly" ><?php echo $this->lang->line('monthly'); ?></option>
                                                </select>
                                                <div class="input-group-btn">
                                                  <button class="btn btn-default" type="button" onclick="filterExpectedAfluence();"><i class="fa fa-search fa-x"></i></button>
                                                </div>
                                              </div>
                                                
                                                <div id="customFilter1" class="filteroption" style="display: none;">
                                                    <div class="input-group date" id="start_date1" style="max-width: 150px;">
                                                        <input type="text" class="form-control"  placeholder="Start Date" id="start_date1" name="start_date1" onkeydown="return false">
                                                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                                                    </div>
                                                    <div class="input-group date" id="end_date1" style="max-width: 150px;">
                                                        <input type="text" class="form-control" placeholder="End Date" id="end_date1" name="end_date1" onkeydown="return false">
                                                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </form>
                                    </div>
                                    <div class="clr"></div>           
                                <div class="">
                                    <div id="container1"></div>   
                                </div>
                                <div class="clr"></div>
                            </div>                           
                        </div>                        
                    </div><!-- /.box-body -->
                  </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!-- Main row -->
        <div class="row">
        </div><!-- /.row (main row) -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
var spectators_data =<?php echo json_encode($spectators_data); ?>;
var hours = <?php echo json_encode($hours_data);?>;
var reserved_data = <?php echo json_encode($reserved_data); ?>;
var base_url_filter = "<?php echo base_url('Admin'); ?>";
var spectators_title = "<?php echo $spectators_title; ?>";
var spectators_X_lebal = "<?php echo $spectators_X_lebal; ?>";
var afluence_title = "<?php echo $afluence_title; ?>";
var afluence_X_lebal = "<?php echo $afluence_X_lebal; ?>";
</script>