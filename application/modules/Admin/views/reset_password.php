<?php
$this->viewname = $this->uri->segment(1);
?>
<!DOCTYPE html>
<!-- saved from url=(0078)file:///D:/rnd/admin-theme/admin-theme/gentelella-master/production/login.html -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?=SITE_NAME?> </title>
<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/bootstrap.min.css" typet="text/css">
<link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/custom.css" typet="text/css">
<link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/parsley.css" typet="text/css">
<link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/style.css" typet="text/css">
<link rel="stylesheet" href="<?= base_url() ?>uploads/assets/css/admin/animate.min.css" typet="text/css">

<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/admin/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>uploads/assets/js/parsley.min.js"></script>
</head>

<body style="background:#F7F7F7;">

<div class="">
    <a class="hiddenanchor" id="toforgotpassword"></a>
    <a class="hiddenanchor" id="tologin"></a>


    <div id="wrapper">
        <div id="login" class="animate form">
            <section class="login_content">
                <form id="forgot" data-parsley-validate method="post" action="<?=  base_url(ADMIN_SITE.'/add_new_password')?>"  ENCTYPE="multipart/form-data">
                    <h1>Reset your password </h1>
                    <?php if(!empty($msg)){ ?>
                        <div class="form-group">
                            <div class="col-sm-12 text-center" id="div_msg">
                                <?php echo $msg;?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    <?php }	?>
                    <div>
                        <input data-parsley-minlength="6" type="password" name="password" id="password" class="form-control parsley-validated" placeholder="New password" type="text" required />
                    </div>
                    <div>                       
                        <input type="password" name="rpassword" placeholder="Re-enter new password" id="rpassword" class="form-control parsley-validated" type="text" required data-parsley-equalto="#password" />
                    </div>
                    <p>&nbsp;</p>
                    <div>
                       <input type="hidden" id="tokenID" name="tokenID"  value="<?php echo $this->input->get('token');?>">
                      <input type="submit"  class="btn btn-default submit"  name="forgotpass" id="forgotpass" value="Submit">
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">

                        <p class="change_link">
                            <a href="<?=  base_url(ADMIN_SITE)?>" class="to_forgotpassword"> Log in </a>
                        </p>
                        <div class="clearfix"></div>
                        <br />
                        <div>
                            <h1><i class="fa fa-paw" style="font-size: 26px;"></i> <?=SITE_NAME?></h1>

                            <p>©<?php echo date("Y"); ?> All Rights Reserved. Privacy and Terms</p>
                        </div>
                    </div>
                </form>
                <!-- form -->
            </section>

        </div>
    </div>
</div>



</body></html>
<script type="text/javascript">
    $(document).ready(function(){
        $("#div_msg").fadeOut(8000);
    });
</script>