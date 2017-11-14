<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <!-- start: Meta -->
    <meta charset="utf-8">
    <title><?php echo lang("tzoh"); ?></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <!-- end: Meta -->
    <!-- start: Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- end: Mobile Specific -->
    <!-- start: CSS -->
      <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,700" rel="stylesheet">
    <link id="bootstrap-style" href="<?= base_url() ?>uploads/assets/frontend/css/bootstrap.min.css" rel="stylesheet">

    
<!--     <link id="base-style-responsive" href="<?= base_url() ?>uploads/assets/frontend/css/style-responsive.css" rel="stylesheet"> -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
    <!-- end: CSS -->
    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <link id="ie-style" href="<?= base_url() ?>uploads/assets/frontend/css/ie.css" rel="stylesheet">
    <![endif]-->

    <!--[if IE 9]>
    <link id="ie9style" href="<?= base_url() ?>uploads/assets/frontend/css/ie9.css" rel="stylesheet">
    <![endif]-->
     <!-- Confirm -->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>uploads/assets/css/admin/jquery-confirm.css"/>
    <!-- Parsley -->
     <link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/parsley.css" typet="text/css">

    <!-- end: Favicon -->
    <script src="<?= base_url() ?>uploads/assets/frontend/js/jquery-1.11.0.min.js"></script>
    <script src="<?= base_url() ?>uploads/assets/frontend/js/jquery.slimscroll.min.js"></script>
    <style type="text/css">
        <?php if(!$this->session->has_userdata('LOGGED_IN')){?>
            body { background: #020923; overflow-x: hidden; }
<?php }?>
    </style>

<?php

if (isset($headerCss) && count($headerCss) > 0) {
    foreach ($headerCss as $css) {
        ?>
        <link href="<?php echo $css; ?>" rel="stylesheet" type="text/css" />
        <?php
    }
}
?>
          
          <link id="base-style" href="<?= base_url() ?>uploads/assets/frontend/css/style.css" rel="stylesheet">
            