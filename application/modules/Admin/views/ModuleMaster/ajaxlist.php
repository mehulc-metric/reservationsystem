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
$admin_session = $this->session->userdata('res_admin_session');
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
                <th <?php if (isset($sortfield) && $sortfield == 'module_name') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('module_name', '<?php echo $sorttypepass; ?>')">Module Name</th>
                
                <th <?php if (isset($sortfield) && $sortfield == 'controller_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('controller_name', '<?php echo $sorttypepass; ?>')">Controller Name</th>
                <th <?php if (isset($sortfield) && $sortfield == 'status') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 10%;" onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')">Status</th>
                <th class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" customer="columnheader" rowspan="1" colspan="1" aria-label="CSS grade" width="5%">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>   
<?php if(isset($information) && count($information) > 0 ){ ?>
            <?php foreach($information as $data){
            if($data['status'] == '1'){
                $data['status'] = lang('active');
            }else{
                $data['status'] = lang('inactive');
            }?>

                    <tr>
                        <td><?= !empty($data['module_name']) ? $data['module_name'] : '' ?></td>
                        <td><?= !empty($data['controller_name']) ? $data['controller_name'] : '' ?></td>
                        <td><?= !empty($data['status']) ? $data['status'] : '' ?></td>
                        <td class="hidden-xs hidden-sm">
                             <?php if(checkAdminPermission('ModuleMaster','edit')){ ?>
                            <a class="btn btn-xs btn-primary" href="<?= $this->config->item('admin_base_url') . $this->viewname; ?>/edit/<?= $data['module_id'] ?>" title="Edit Record"><i class="fa fa-pencil"></i></a> &nbsp;
                             <?php } ?>
                            <?php if(checkAdminPermission("ModuleMaster","delete")){?><a class="btn btn-xs btn-danger" data-href="javascript:;" title="<?= lang('delete')?>" onclick="delete_request(<?php echo $data['module_id']; ?>);" ><i class="fa fa-times"></i</a><?php } ?>
                            <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                            <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                        </td>
                    </tr>
                    
                    
                    
    <?php } ?> 
<?php }else { ?>
                <tr>
                    <td colspan="5"><?php echo lang("common_no_record_found");?></td>
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
        var baseurl = '<?php echo base_url() . 'Admin'; ?>';
    </script>

