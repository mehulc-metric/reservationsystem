<?php
$this->type = ADMIN_SITE;
$admin_session = $this->session->userdata('reservation_admin_session');
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <?php /* <div class="pull-left image">
                <?php if (!empty($admin_session['admin_image'])) { ?> 
                    <img src="<?= $this->config->item('admin_user_small_img_url') . $admin_session['admin_image'] ?>" class="user-image" alt="User Image">
                <?php } else { ?>
                    <img src="<?= $this->config->item('admin_image_path') ?>images.png" class="user-image" alt="User Image">
                <?php } ?>
            </div> */?>
            <div class="pull-left info">
                <p><?= !empty($admin_session['name']) ? $admin_session['name'] : '' ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li <?php if ($this->uri->segment(2) == 'Dashboard') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Dashboard') ?>"><i class="fa fa-circle-o"></i> <span><?= "DashBoard" ?></span></a></li>
             <?php if(checkAdminPermission('Setuphours','view')){ ?>
            <li <?php if ($this->uri->segment(2) == 'Setuphours') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Setuphours') ?>"><i class="fa fa-calendar"></i> <span><?=lang('opening_and_closing_hours')?></span></a></li>
            <?php } ?>  
            <?php if(checkAdminPermission('Timeshedule','view')){ ?>
            <li <?php if ($this->uri->segment(2) == 'Timeshedule') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Timeshedule') ?>"><i class="fa fa-calendar"></i> <span><?=lang('set_time_shedule')?></span></a></li>
            <?php } ?>  
             <?php if(checkAdminPermission('Sheduleviewer','view')){ ?>
            <li <?php if ($this->uri->segment(2) == 'Sheduleviewer') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Sheduleviewer') ?>"><i class="fa fa-calendar"></i> <span><?=lang('sheduleviewer')?></span></a></li>
            <?php } ?>            
			<?php if(checkAdminPermission('ReservedUserList','view')){ ?>
            <li <?php if ($this->uri->segment(2) == 'ReservedUserList') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/ReservedUserList') ?>"><i class="fa fa-users"></i> <span><?php echo "Reserved User List"; ?></span></a></li>
            <?php } ?>	
			<?php if(checkAdminPermission('CancelledReservedList','view')){ ?>
            <li <?php if ($this->uri->segment(2) == 'CancelledReservedList') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/CancelledReservedList') ?>"><i class="fa fa-users"></i> <span><?php echo lang('cancelled_reserved_list'); ?></span></a></li>
            <?php } ?>	
            <?php if(checkAdminPermission('UploadZipCode','view')){ ?>
            <li <?php if ($this->uri->segment(2) == 'UploadZipCode') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/UploadZipCode') ?>"><i class="fa fa-upload"></i> <span><?=lang('upload_zip_code')?></span></a></li>
            <?php } ?>
			
			<?php if(checkAdminPermission('User','view')){ ?>
            <li <?php if ($this->uri->segment(2) == 'User') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/User') ?>"><i class="fa fa-users"></i> <span><?php echo lang('user_management'); ?></span></a></li>
            <?php } ?>
			<?php if(checkAdminPermission('Rolemaster','view')){ ?>
			<li <?php if ($this->uri->segment(2) == 'Rolemaster') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Rolemaster') ?>"><i class="fa fa-list-alt"></i> <span><?=lang('role_management')?></span></a></li>
			<?php } ?>
			<?php if(checkAdminPermission('Configs','view')){ ?>
            <li <?php if ($this->uri->segment(2) == 'Configs') { ?> class="active" <?php } ?>><a href="<?= base_url($this->type . '/Configs') ?>"><i class="fa fa-wrench"></i> <span><?=lang('config_module_title')?></span></a></li>
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>