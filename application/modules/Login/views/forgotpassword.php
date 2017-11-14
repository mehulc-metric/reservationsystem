<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path = 'Login/resetpassword';
?>
<div class="row-fluid">
    <div class="login-box">

        <h2>Forgot Password</h2>
        <?php echo $this->session->flashdata('msg'); ?>
        <?php
        $attributes = array("name" => "resetpassword", "id" => "resetpassword", "data-parsley-validate" => "");
        echo form_open_multipart($path, $attributes);
        ?>
        <div class="input-prepend" title="Username">
            <span class="add-on"><i class="halflings-icon user"></i></span>
            <input class="input-large span10" name="email" placeholder="<?php echo "Email"; ?>" data-parsley-trigger="change" required="" type="email" data-parsley-email />
        </div>
        <div class="clearfix"></div>
        <div class="button-login">
            <button name="submit_btn" type="submit" class="btn btn-primary"><?php echo 'Submit'; ?></button>
        </div>
        <div class="clearfix"></div>
        <?php echo form_close(); ?>
    </div><!--/span-->
</div><!--/row-->