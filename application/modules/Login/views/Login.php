<div class="row-fluid">
    <div class="login-box">

        <h2>Login to your account</h2>
            <?php echo $this->session->flashdata('msgs'); ?>
            <?php if (isset($error) && !empty($error)) {
                ?>
                <?php echo $error; ?>
            <?php } ?>
            <?php
            $attributes = array("class" => "form-horizontal","name" => "frmlogin", "id" => "frmlogin", "data-parsley-validate" => "");
            echo form_open('Login/verifylogin', $attributes);
            ?>

                <div class="input-prepend" title="Username">
                    <span class="add-on"><i class="halflings-icon user"></i></span>
                    <input class="input-large span10" name="email" placeholder="type username" type="email" required />
                </div>
        <div class="clearfix"></div>

        <div class="input-prepend" title="Password">
            <span class="add-on"><i class="halflings-icon lock"></i></span>
            <input class="input-large span10" name="password" data-parsley-minlength="5" placeholder="type password" type="password" required />
        </div>
                <div class="clearfix"></div>

                <!-- <label class="remember" for="remember">
                    <input type="hidden" id="remove_session" name="session" value="<?php  //$session_id; ?>">
                    <input type="checkbox" class="checkbox" name="" id="remember"/>Remember me
                </label> -->
                <div class="button-login">
                    <input type="hidden" name="timezone" id="timezone">
                    <button name="lgnsubmit" type="submit" id="lgnsubmit" class="btn btn-primary">Login</button>
                </div>
                <div class="clearfix"></div>
        <?php echo form_close(); ?>
        <hr>
        <h3><a class="white-link" href="<?php echo base_url('Login/forgotpassword'); ?>" class="white-link">Forgot Password?</a></h3>        
    </div><!--/span-->
</div><!--/row-->