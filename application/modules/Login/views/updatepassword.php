<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path ='Login/updatePasswords';
?>
<div class="row-fluid">
    <div class="login-box">

        <h2>Update Password</h2>
        <?php $attributes = array("name" => "updatepassword", "id" => "updatepassword", "data-parsley-validate" => "");
        echo form_open_multipart($path, $attributes);
        ?>

        <div class="input-prepend" title="Username">
            <span class="add-on"><i class="halflings-icon user"></i></span>
            <input class="input-large span10" id="password" name="password" placeholder="New Password" type="password" data-parsley-minlength="6" data-parsley-required="true"  />
            <span class="text-danger"><?php echo form_error('password'); ?></span>
        </div>
        <div class="clearfix"></div>

        <div class="input-prepend" title="Password">
            <span class="add-on"><i class="halflings-icon lock"></i></span>
            <input class="input-large span10" name="cpassword" placeholder="Confirm Password" type="password" data-parsley-equalto="#password" data-parsley-minlength="6" data-parsley-required="true" />
            <span class="text-danger"><?php echo form_error('cpassword'); ?></span>
        </div>
        <div class="clearfix"></div>
        <div class="button-login">
            <input type="hidden" id="tokenID" name="tokenID"  value="<?php echo $this->input->get('token');?>">
            <button name="submit_btn" type="submit" class="btn btn-primary">Submit</button>
        </div>

        <div class="clearfix"></div>
        <?php echo form_close(); ?>
    </div><!--/span-->
</div><!--/row-->