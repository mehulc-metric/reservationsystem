<?php
/*
  @Description:Customers list
  @Author: Mehul patel
  @Date: 12-5-2017
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php
$this->type = ADMIN_SITE;
$this->viewname = $this->uri->segment(2);
$admin_session = $this->session->userdata('reservation_admin_session');
$master_role_id = $this->config->item('super_admin_role_id');
$master_user_id = $this->config->item('master_user_id');
// pr($datalist); exit();
// echo "DATE : ".date("d-m-Y",strtotime($datalist[0]['date']))." ".date('H:i a',strtotime($datalist[0]['start_time']))."</br>"; exit();
?>
<?php
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped dataTable" id="example1" customer="grid" aria-describedby="example1_info">
        <thead>            
            <tr customer="row">
                <th style="width: 1%;"><input type="checkbox" class="selecctall" id="selecctall" value="selecctall"></th>
                <th <?php if (isset($sortfield) && $sortfield == 'reservation_code') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
                } else {
                    echo "class = 'sorting'";
                } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_data_sorting('reservation_code', '<?php echo $sorttypepass; ?>')"><?php echo lang("reservation_code"); ?></th>
                
                <th <?php if (isset($sortfield) && $sortfield == 'group_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
                } else {
                    echo "class = 'sorting'";
                } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_data_sorting('group_name', '<?php echo $sorttypepass; ?>')"><?php echo lang("group_name"); ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'email') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_data_sorting('email', '<?php echo $sorttypepass; ?>')"><?php echo lang('email_id'); ?></th>
                
                <th <?php if (isset($sortfield) && $sortfield == 'no_of_people') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_data_sorting('no_of_people', '<?php echo $sorttypepass; ?>')"><?php echo lang('number_of_people'); ?></th>
			
            <th <?php if (isset($sortfield) && $sortfield == 'date') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_data_sorting('new_date', '<?php echo $sorttypepass; ?>')"><?php echo lang('reserved_date'); ?></th>  
			
			<th <?php if (isset($sortfield) && $sortfield == 'refund_transaction_id') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_data_sorting('refund_transaction_id', '<?php echo $sorttypepass; ?>')"><?php echo lang('refund_id'); ?></th>  
			
			<th <?php if (isset($sortfield) && $sortfield == 'gross_refund_amount') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_data_sorting('gross_refund_amount', '<?php echo $sorttypepass; ?>')"><?php echo lang('refund_transaction_amount'); ?></th>  
			
			<th <?php if (isset($sortfield) && $sortfield == 'refund_timestamp') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_data_sorting('refund_timestamp', '<?php echo $sorttypepass; ?>')"><?php echo lang('refund_trans_date_time'); ?></th>  
			
                        <th class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" customer="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="5%"><?php echo lang('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>   
<?php

if (!empty($datalist)) {
    foreach ($datalist as $row) {
			
            if(!empty($row['new_date'])){
                $reservedDate = date("d-m-Y h:i a",strtotime($row['new_date']));
            }else{
                $reservedDate = "";
            }
?>
                    <tr id="customer_ids">
                        <td><input type="checkbox" name="customer_list[]"  class="checkbox1" value="<?php echo  $row['reservation_code'];?>"></td>

                        <td><?= !empty($row['reservation_code']) ? $row['reservation_code'] : '' ?></td>
                        <td><?= !empty($row['group_name']) ? $row['group_name'] : '-' ?></td>
                        <td><?= !empty($row['email']) ? $row['email'] : '' ?></td>
                        <td><?= !empty($row['no_of_people']) ? $row['no_of_people'] : '' ?></td>
                        <td><?= !empty($reservedDate) ? $reservedDate : '' ?></td>
						<td><?= !empty($row['refund_transaction_id']) ? $row['refund_transaction_id'] : '---' ?></td>
						<td><?= !empty($row['gross_refund_amount']) ? '€ '.$row['gross_refund_amount'] : '---' ?></td>
						<td><?= !empty($row['refund_timestamp']) ? date("d-m-Y h:i a",strtotime($row['refund_timestamp'])) : '---' ?></td>
                        <td class="hidden-xs hidden-sm">
                        <?php if(checkAdminPermission('ReservedUserList','view')){ ?>  <a href="<?= $this->config->item('admin_base_url') . $this->viewname; ?>/view/<?= $row['reservation_code'] ?>" title="View Record"><i class="fa fa-file-text-o"></i></a> <?php }?>
                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                        </td>
                    </tr>
    <?php } ?>
<?php }else { ?>
                <tr>
                    <td colspan="7" class="text-center"><?php echo lang("common_no_record_found");?></td>
                </tr>
<?php } ?>
                </tr>
        </tbody>
    </table>
</div>   
<div id="common_tb">
<?php
if (isset($pagination)) {
    echo $pagination;
}
?>
</div>
<script>
    //var sendEmailToSelectedUser = "<?php echo base_url() . 'Admin/ReservedUserList/sendEmailToSelectedUser/'; ?>";
    //var re_send_email_confirmation = "<?php echo lang('re_send_email_confirmation');?>";
    //var are_you_sure_re_send_email_to_selected_user = "<?php echo lang('are_you_sure_re_send_email_to_selected_user');?>";
    var please_select_user = "<?php echo lang('please_select_user');?>";
    var value_should_be_greater_then_start_time = "<?php echo lang('value_should_be_greater_then_start_time'); ?>";
    var exportData = "<?php echo base_url('Admin/CancelledReservedList/exportToexcel/'); ?>";
    var export_message = "<?php echo lang('export_message');?>";
	var export_data_confirmation = "<?php echo lang('export_data_confirmation');?>";
</script>

