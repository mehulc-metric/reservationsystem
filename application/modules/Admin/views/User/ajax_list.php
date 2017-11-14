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
// pr($_SESSION); exit();
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
                <th style="width: 1%;"><input type="checkbox" class="selecctall" id="selecctall" ></th>
                <th <?php if (isset($sortfield) && $sortfield == 'customer_name') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('customer_name', '<?php echo $sorttypepass; ?>')">User Name</th>
                <th <?php if (isset($sortfield) && $sortfield == 'email') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('email', '<?php echo $sorttypepass; ?>')">Email</th>
                <th <?php if (isset($sortfield) && $sortfield == 'role_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('role_name', '<?php echo $sorttypepass; ?>')">Role Type</th>
                <th class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" customer="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="5%">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>   
<?php
if (!empty($datalist)) {
    foreach ($datalist as $row) {

        $name = !empty($row['customer_name']) ? $row['customer_name'] : '';
        $name = str_replace("'", "\'", $name);
        ?>

                    <tr id="customer_ids">
                        <td><input type="checkbox" name="customer_list[]"  class="checkbox1" value="<?php echo  $row['user_id'];?>"></td>

                        <td><?= !empty($row['customer_name']) ? $row['customer_name'] : '' ?></td>
                        <td><?= !empty($row['email']) ? $row['email'] : '' ?></td>
                        <td><?= !empty($row['role_name']) ? $row['role_name'] : '' ?></td>
                        <td class="hidden-xs hidden-sm">
                        <?php if(checkAdminPermission('User','edit')){ ?>  <a class="btn btn-xs btn-primary" href="<?= $this->config->item('admin_base_url') . $this->viewname; ?>/edit/<?= $row['user_id'] ?>" title="Edit Record"><i class="fa fa-pencil"></i></a> <?php }?>&nbsp;
                        <?php if(($row['role_type'] != $master_role_id || $master_user_id != $row['user_id']) && $this->session->userdata['reservation_admin_session']['admin_id'] != $row['user_id']) {?>
                            <?php if(checkAdminPermission('User','delete')){ ?>    <button class="btn btn-xs btn-danger" title="Delete Record"  onclick="delete_customers(<?php echo $row['user_id'] ?>);"> <i class="fa fa-times"></i> </button><?php }?>
                         <?php } ?>
                        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                        </td>
                    </tr>
    <?php } ?> 
<?php }else { ?>
                <tr>
                    <td colspan="5"><?php echo lang("common_no_record_found"); ?></td>
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
    var customerDeleteurl = "<?php echo base_url() . 'Admin/User/customerBulkDelete/'; ?>";
</script>

