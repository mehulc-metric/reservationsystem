<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<footer>
    <p>
        <span style="">Para consultas technicas <b>supporte@tzoh.com</b></span>
    </p>
</footer>

<script src="<?= base_url() ?>uploads/assets/js/tether.min.js"></script>
<script src="<?= base_url() ?>uploads/assets/frontend/js/bootstrap.min.js"></script>
<script src='<?= base_url() ?>uploads/assets/frontend/js/jquery.dataTables.min.js'></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-confirm.js"></script>

<!-- Block ui-->
<script type="text/javascript" src="<?= base_url() ?>uploads/custom/js/jquery.blockUI.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        setTimeout(function () {
            $('.alert').fadeOut('5000');
        }, 8000);

    });
</script>