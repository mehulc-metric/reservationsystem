<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; <?php echo date('Y');?> </strong> All rights reserved.
</footer>


<!-- Bootstrap 3.3.5 -->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/bootstrap.min.js"></script>
<!-- Added By Mehul Patel BootStrap Dialog box Start-->
<link href="<?php echo base_url('uploads/custom/css/bootstrap-dialog.css'); ?>" rel="stylesheet" type="text/css" />

<script src="<?php echo base_url('uploads/custom/js/bootstrap-dialog-min.js'); ?>"></script>

<!-- Added By Mehul Patel BootStrap Dialog box End-->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-confirm.js"></script>
<link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">
<script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script>
<!-- AdminLTE App -->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/app.min.js"></script>

<!-- Block ui-->
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery.blockUI.js"></script>
<script src="<?= base_url() ?>uploads/assets/js/admin/common.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        setTimeout(function () {
            $('.alert').fadeOut('5000');
        }, 8000);

    });
</script>
<?=$this->load->view('/Common/add','',true);?>
<?=$this->load->view($this->type.'/common/common','',true);?>

<?php
if (isset($footerCss) && count($footerCss) > 0) {
    foreach ($footerCss as $css) {
        ?>
        <link rel="stylesheet" href="<?php echo $css; ?>" >
        <?php
    }
}
?>

<?php
if (isset($footerJs) && count($footerJs) > 0) {
    foreach ($footerJs as $js) {
        ?>
        <script src="<?php echo $js; ?>" ></script>
        <?php
    }
}
?>
</body>
</html>