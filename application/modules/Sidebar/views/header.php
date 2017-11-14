<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
$user_info = $this->session->userdata('LOGGED_IN');
if (isset($user_info) && !empty($user_info) && $user_info != "") {
    $logoRedirect = base_url('Dashboard');
} else {
    $logoRedirect = base_url();
}
?>
<!--
	<a class="brand" href="<?php echo base_url();?>"><span><?= SITE_NAME ?></span></a>
--> 
<!-- start: Header Menu --> 

<ul class="nav navbar-nav navbar-right">
		<?php if($this->uri->segment(2) == "cancelReservation"){?>
		<li class="active display-from-sm"><a class="cancel-res" href="<?php echo base_url('Usershedule/cancelReservation'); ?>"><span><?php echo lang('cancle_reservation'); ?></span></a></li>
	<?php }else{ ?>
		<li><a class="cancel-res" href="<?php echo base_url('Usershedule/cancelReservation'); ?>"><span><?php echo lang('cancle_reservation'); ?></span></a></li>
	<?php }?> 
    <!-- <li><a class="cancel-res" href="<?php // echo base_url('Usershedule/cancelReservation'); ?>"><span><?php //echo lang('cancle_reservation'); ?></span></a></li> -->
	<?php $lang_data = getLanguages(); //pr($lang_data); echo "Lang : ".$_COOKIE['languageSet'];
                foreach ($lang_data as $data) { 
                    if($data['language_name'] == $this->input->cookie('languageSet')){
                    ?>
                    
                    <li class="active display-from-sm"><a class="cancel-res" href="<?php echo base_url('Set_language?lang=' . $data['language_name']); ?>"><?= $data['name'] ?></a></li>
                    <?php }else{?>
                    <li class="display-from-sm"><a class="cancel-res" href="<?php echo base_url('Set_language?lang=' . $data['language_name']); ?>"><?= $data['name'] ?></a></li>
                    <?php }?>
                   <?php } ?>
    <li class="display-mobile">
      <div class="dropdown">
            <a class="cancel-res dropdown-toggle" type="button" data-toggle="dropdown"><?php echo lang('LANGUAGE_HEADER_MENU_LABEL'); ?>
                <span class="caret"></span></a>
            <ul class="dropdown-menu"> 
                <?php $lang_data = getLanguages();
                foreach ($lang_data as $data) { ?>
                <?php if($data['language_name'] == $this->input->cookie('languageSet')){ ?>
                    <li><a class="active" href="<?php echo base_url('Set_language?lang=' . $data['language_name']); ?>"><?= $data['name'] ?></a></li>
                <?php }else{?>
                    <li><a href="<?php echo base_url('Set_language?lang=' . $data['language_name']); ?>"><?= $data['name'] ?></a></li>
                <?php }?>  
                <?php } ?>
            </ul>
        </div>
    </li>
</ul>
<!-- end: Header Menu -->
