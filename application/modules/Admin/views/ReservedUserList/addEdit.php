<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$disable = "";
?>
<script>
    var checkEmailDuplicateURL = "<?php echo base_url($crnt_view . '/isDuplicateEmail'); ?>";
</script>
<div class="content-wrapper">
    <div class="container">
        <div clas="row">
            <div class="col-md-12 error-list">
                <?= isset($validation) ? $validation : ''; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">
                    <?php echo ($screenType == 'edit') ? 'View User Details' : ''; ?>
                </h1>
            </div>
        </div>
        <?php
        $attributes = array("name" => "customer_add_edit", "id" => "customer_add_edit", "data-parsley-validate" => "", "class" => "form-horizontal", 'novalidate' => '');
        echo form_open_multipart($form_action_path, $attributes);
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                               <?php echo lang("user_information"); ?>
                            </a>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">

                            <div class="panel-body">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("reservation_code"); ?>:</label>
                                        <div class="col-sm-8">
                                            <?php if ($reservation_code != "") { ?>
                                            <p class="form-control-static"><?php echo $reservation_code; ?></p>
                                            <?php } else { ?>
                                                <p><?php echo "--"; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("cancellation_code"); ?>:</label>
                                        <div class="col-sm-8">
                                            <?php if ($cancellation_code != "") { ?>
                                                <p class="form-control-static" ><?php echo $cancellation_code; ?></p>
                                            <?php } else { ?>
                                                <p><?php echo "--"; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("number_of_people"); ?>:</label>
                                        <div class="col-sm-8">
                                            <?php if ($no_of_people != "") { ?>
                                                <p class="form-control-static" ><?php echo $no_of_people; ?></p>
                                            <?php } else { ?>
                                                <p><?php echo "--"; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("email_id"); ?>:</label>
                                        <div class="col-sm-8">
                                            <?php if ($email != "") { ?>
                                                <p class="form-control-static" ><?php echo $email; ?></p>
                                            <?php } else { ?>
                                                <p><?php echo "--"; ?></p>
                                            <?php } ?>                           
                                        </div>
                                    </div>
                                </div>    
                                
                                <div class="clearfix"></div>
                                
                               <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("transcation_id"); ?>:</label>
                                        <div class="col-sm-8">
                                            <?php if ($transaction_id != "") { ?>
                                                <p class="form-control-static" ><?php echo $transaction_id; ?></p>
                                            <?php } else { ?>
                                                <p><?php echo "--"; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("transcation_amount"); ?>:</label>
                                        <div class="col-sm-8">
                                            <?php if ($transaction_amount != "") { ?>
                                                <p class="form-control-static" ><?php echo $transaction_amount; ?>€</p>
                                            <?php } else { ?>
                                                <p>0€</p>
                                            <?php } ?>                           
                                        </div>
                                    </div>
                                </div>  
                                 <?php if ($group_name != "") { ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4 required"><?php echo lang("group_name"); ?>:</label>
                                        <div class="col-sm-8">
                                            <?php if ($group_name != "") { ?>
                                                <p class="form-control-static" ><?php echo $group_name; ?></p>
                                            <?php } else { ?>
                                                <p><?php echo "--"; ?></p>
                                            <?php } ?>                          
                                        </div>
                                    </div>
                                </div>  
                                <?php } ?> 
                                <div class="clearfix"></div>

                                <div class="col-sm-12 text-center">
                                    <div class="bottom-buttons">
                                        <a href="<?php echo base_url($crnt_view) ?>" class="btn btn-default"><?php echo lang("COMMON_LABEL_CANCEL"); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>