<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= SITE_NAME ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/bootstrap.min.css" typet="text/css">
        <link type="text/css" href="<?= base_url ('uploads/assets/css/admin/jquery.datetimepicker.css'); ?>" rel="stylesheet"/>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url() ?>uploads/font-awesome-4.5.0/css/font-awesome.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/AdminLTE.min.css" typet="text/css">
        <!-- Defult style -->
        
        <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/_all-skins.min.css" typet="text/css">
        <!-- Confirm -->
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>uploads/assets/css/admin/jquery-confirm.css"/>
        <!-- Parsley -->
        <link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/parsley.css" typet="text/css">

        <!-- DataTables -->
        <link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/dataTables.bootstrap.css">
		<link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/style.css" typet="text/css">


        <!-- jQuery 2.1.4 -->
        <script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-3.1.1.min.js"></script><!-- jQuery UI 1.11.4 -->
         <script src="<?= base_url ('uploads/assets/js/admin/jquery.datetimepicker.full.min.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-ui.min.js"></script><!-- jQuery UI 1.11.4 -->
        <!-- DataTables -->

        
        <!-- end-->
    </head>
    <script type="text/javascript">
         var base_url = "<?php echo base_url().ADMIN_SITE.'/'; ?>";
         var next_month_date = "<?php echo date('Y-m-d', strtotime('+1 month', time())); ?>";
    </script>
    <?php
    $this->type = ADMIN_SITE;
    $admin_session = $this->session->userdata('reservation_admin_session');
    if (empty($admin_session)) {
        redirect('/Admin');
    }
    ?>
    <body class="hold-transition sidebar-mini skin-blue">
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="<?= base_url('Admin/Dashboard') ?>" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b><?= SITE_NAME_SMALL ?></b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b><?= SITE_NAME ?></b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu lang">
                                <a class="cancel-res dropdown-toggle" type="button" data-toggle="dropdown"><?php echo lang('LANGUAGE_HEADER_MENU_LABEL'); ?>
                <span class="caret"></span></a>
                                <ul class="dropdown-menu"> 
                <?php $lang_data = getLanguages();
                foreach ($lang_data as $data) { ?>
                    <li><a href="<?php echo base_url('Set_language?lang=' . $data['language_name']); ?>"><?= $data['name'] ?></a></li>
                <?php } ?>
            </ul>
                            </li>
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php /*if (!empty($admin_session['admin_image'])) { ?> 
                                        <img src="<?= $this->config->item('admin_user_small_img_url') . $admin_session['admin_image'] ?>" class="user-image" alt="User Image">
                                    <?php } else { ?>
                                        <img src="<?= base_url("uploads/admin_user/small/") ?>noimage.jpg" class="user-image" alt="User Image">
                                    <?php } */?>
                                    <span class=""><?= !empty($admin_session['name']) ? $admin_session['name'] : '' ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <?php /*if (!empty($admin_session['admin_image'])) { ?> 
                                            <img src="<?= $this->config->item('admin_user_small_img_url') . $admin_session['admin_image'] ?>" class="user-image" alt="User Image">
                                        <?php } else { ?>
                                            <img src="<?= base_url("uploads/admin_user/small/") ?>noimage.jpg" class="user-image" alt="User Image">
                                        <?php } */?>
                                        <p>
                                            <?= !empty($admin_session['name']) ? $admin_session['name'] : '' ?>
                                            <!-- <small>Member since Nov. 2012</small> -->
                                        </p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <!-- <a href="<?= base_url($this->type . '/profile') ?>" class="btn btn-default btn-flat">Profile</a> -->
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?= base_url($this->type . '/logout') ?>" class="btn btn-default btn-flat"><?php echo lang('sign_out'); ?></a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <!-- <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li> -->
                        </ul>
                    </div>
                </nav>
            </header>
