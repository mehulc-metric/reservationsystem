<?php
/*
  @Description:Uploaded Zip code & Contract Code List
  @Author: Mehul patel
  @Date: 16-10-2017
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
				
				<th style="width: 1%;" class="text-center"><input type="checkbox" class="selecctall" id="selecctall" value="selecctall"></th>
				
                <th <?php if (isset($sortfield) && $sortfield == 'zip_code') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
                } else {
                    echo "class = 'sorting'";
                } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('zip_code', '<?php echo $sorttypepass; ?>')"><?php echo lang("zip_code"); ?></th>
				
				<th <?php if (isset($sortfield) && $sortfield == 'population') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
                } else {
                    echo "class = 'sorting'";
                } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('population', '<?php echo $sorttypepass; ?>')"><?php echo lang("population"); ?></th>
							
			    <th <?php if (isset($sortfield) && $sortfield == 'province') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
                } else {
                    echo "class = 'sorting'";
                } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('province', '<?php echo $sorttypepass; ?>')"><?php echo lang("province"); ?></th>    
                
                <th style="min-width: 50px;" class="sorting_disabled" data-filterable="true" customer="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="5%"><?php echo lang("action"); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>   
<?php
if (!empty($datalist)) {
    foreach ($datalist as $row) { ?>
            <tr id="customer_ids">
				<td class="text-center"><input type="checkbox" name="customer_list[]"  class="checkbox1" value="<?php echo $row['id'];?>"></td>
                <td><?= !empty($row['zip_code']) ? $row['zip_code'] : '' ?></td>                    
                <td><?= !empty($row['population']) ? $row['population'] : '' ?></td>                    
                <td><?= !empty($row['province']) ? $row['province'] : '' ?></td>                    
                <td>
                    <?php if(checkAdminPermission('UploadZipCode','edit')){ ?>  <a class="btn btn-xs btn-primary" href="<?= $this->config->item('admin_base_url') . $this->viewname; ?>/edit/<?= $row['id'] ?>" title="<?php echo lang("edit_record"); ?>"><i class="fa fa-pencil"></i></a> <?php }?>&nbsp;
                    <?php if(checkAdminPermission('UploadZipCode','delete')){ ?>    <button class="btn btn-xs btn-danger" title="<?php echo lang("delete_record"); ?>"  onclick="delete_customers(<?php echo $row['id'] ?>);"> <i class="fa fa-times"></i> </button><?php }?>                        
                    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                </td>                
            </tr>
    <?php } ?> 
<?php }else { ?>
            <tr>
                <td colspan="5" class="text-center"><?php echo lang("common_no_record_found");?></td>
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
    var deleteRecord = "<?php echo base_url() . 'Admin/UploadZipCode/deleteData/'; ?>";
    var sendEmailToSelectedUser = "<?php echo base_url() . 'Admin/ReservedUserList/sendEmailToSelectedUser/'; ?>";
    var re_send_email_confirmation = "<?php echo lang('re_send_email_confirmation');?>";
    var are_you_sure_re_send_email_to_selected_user = "<?php echo lang('are_you_sure_re_send_email_to_selected_user');?>";
    var please_select_user = "<?php echo lang('please_select_user');?>";
    var value_should_be_greater_then_start_time = "<?php echo lang('value_should_be_greater_then_start_time'); ?>";
    var exportData = "<?php echo base_url() . 'Admin/ReservedUserList/exportToexcel/'; ?>";
    var export_message = "<?php echo lang('export_message');?>";
    var export_data_confirmation = "<?php echo lang('export_data_confirmation');?>";
    var duplicateErrorMessage = "<?php echo lang('zip_code_dupliate_error_msg');?>";
	
	var confirm_zip_code_delete_msg = "<?php echo lang('confirm_zip_code_delete_msg');?>";
	var zipCodeDeleteURL = "<?php echo base_url('Admin/UploadZipCode/deleteZipcode'); ?>";
	var baseURL = "<?php echo base_url('Admin/UploadZipCode'); ?>";
	var delete_selected_data = "<?php echo lang('delete_selected_data');?>";
        var delete_confirm = "<?php echo lang('delete_confirm');?>";
        var please_select_data = "<?php echo lang('please_select_data');?>";
        var loading = "<?php echo lang('loading');?>";
        var ok_btn = "<?php echo lang('ok_btn');?>";
        
</script>

