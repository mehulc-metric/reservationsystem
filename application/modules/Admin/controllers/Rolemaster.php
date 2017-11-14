<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rolemaster extends CI_Controller {
    //public $data;
    function __construct() {
        parent::__construct();
        if(checkAdminPermission('Rolemaster','view') == false)
        {
               redirect('/Admin/Dashboard');
        }
        check_admin_login();
        $this->type = ADMIN_SITE;
        $this->viewname = $this->uri->segment(2);
        
        /*if(checkPermission('Rolemaster','view') == false)
        {
            redirect('/Dashboard');
        }*/
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'Session'));
        // $data['roleMasterJs'] = $this->load->view('rolemasterJsFile','',true);
    }

    /*
     @Author : Mehul Patel
     @Desc   : RoleMaster list View
     @Input  :
     @Output :
     @Date   : 14/06/2017
     */
    public function index() {     
        $searchtext='';
        $perpage='';
        $searchtext = $this->input->post('searchtext');
        $sortfield  = $this->input->post('sortfield');
        $sortby     = $this->input->post('sortby');
        $perpage    = 10;
        $allflag    = $this->input->post('allflag');
        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('rolemaster_data');
        }

        $searchsort_session = $this->session->userdata('rolemaster_data');
        //Sorting
        if(!empty($sortfield) && !empty($sortby))
        {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        }
        else
        {
            if(!empty($searchsort_session['sortfield']))
            {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            }
            else
            {
                $sortfield = 'role_id';
                $sortby = 'desc';
                $data['sortfield']		= $sortfield;
                $data['sortby']			= $sortby;
            }
        }
        //Search text
        if(!empty($searchtext))
        {
            $data['searchtext'] = $searchtext;
        } else
        {
            if(empty($allflag) && !empty($searchsort_session['searchtext']))
            {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext =  $data['searchtext'];
            }
            else
            {
                $data['searchtext'] = '';
            }
        }

        if(!empty($perpage) && $perpage != 'null')
        {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        }
        else
        {
            if(!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link']  = 'First';
        $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/index';

        if(!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 3;
            $uri_segment = $this->uri->segment(3);
        }
        //Query
        $table = ROLE_MASTER.' as rm';
        $where = array("rm.is_delete"=> "0");
        $fields = array("rm.role_id, rm.role_name, rm.status");

        if(!empty($searchtext))
        {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search='((rm.role_name LIKE "%'.$searchtext.'%" OR rm.status LIKE "%'.$searchtext.'%"))';
            $data['information']  = $this->common_model->get_records($table,$fields,'','','',$match,$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where_search);

            $config['total_rows']  = $this->common_model->get_records($table,$fields,'','','',$match,'','',$sortfield,$sortby,'',$where_search,'','','1');
        }
        else
        {
            $data['information']      = $this->common_model->get_records($table,$fields,'','','','',$config['per_page'],$uri_segment,$sortfield,$sortby,'',$where);
            $config['total_rows']  = $this->common_model->get_records($table,$fields,'','','','','','',$sortfield,$sortby,'',$where,'','','1');
        }
//pr($data['campaign_info']);exit;
        $this->ajax_pagination->initialize($config);
        $data['pagination']  = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield'  => $data['sortfield'],
            'sortby'     => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage'    => trim($data['perpage']),
            'uri_segment'=> $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('rolemaster_data', $sortsearchpage_data);
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/Rolemaster/Rolemaster.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/role_list';
            $this->parser->parse($this->type.'/assets/template', $data);
        }
    }

    /*
     @Author : Mehul Patel
     @Desc   : Role insertdata
     @Input  :
     @Output :
     @Date   : 18/06/2017
     */

    public function insertdata() {

        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect('Admin/Rolemaster/index'); //Redirect On Listing page
        }
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/Rolemaster/Rolemaster.js');
        $data['header'] = array('menu_module'=>'settings');
        $duplicateRole= $this->checkDuplicateRole($this->input->post('role_name'));
        $getcount = count($duplicateRole);

        if( isset($duplicateRole) && empty($duplicateRole) && $getcount == 0){

            //insert the Role details into database
            $data = array(
                'role_name' => $this->input->post('role_name'),               
                'created_date' => datetimeformat(),
                'created_by ' => $this->session->userdata['reservation_admin_session']['admin_id'],
                'status' => $this->input->post('status')
            );

            //Insert Record in Database
            $roleId = $this->common_model->insert(ROLE_MASTER, $data);
            if ($roleId) {
                //if ($this->common_model->insert(ROLE_MASTER, $data)) {
                $this->assignPermission($roleId);
                //	$msg = $this->lang->line('role_add_msg');
                //$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");


                //redirect('Rolemaster/index');
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

                redirect($this->viewname);
            }

        }else{

            $msg = "Role ". $this->input->post('role_name') ." has already been entered";
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            echo "<script>window.location.href=window.location.href</script>";
            //redirect($this->viewname);

        }

    }

    function checkDuplicateRole($role_name,$role_id=null){
        //Get Records From Role Master Table
        $table = ROLE_MASTER . ' as rm';
        if(NULL!==$role_id)
        {
            $match = "rm.role_name = '" . addslashes($role_name)."' and rm.role_id <> '".$role_id."'"." AND rm.is_delete = 0 ";
        }
        else
        {
            $match = "rm.role_name = '" . addslashes($role_name)."' AND rm.is_delete = 0";
        }

        $fields = array("rm.role_id,rm.status");
        $data['duplicateRole'] = $this->common_model->get_records($table, $fields, '', '', $match);

        return $data['duplicateRole'];

    }


    /*
     @Author : Mehul Patel
     @Desc   : Add Role View Page
     @Input  :
     @Output :
     @Date   : 18/04/2017
     */

    public function add() {
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        //Get Records From Role Master Table
        $table = ROLE_MASTER . ' as rm';
        $match = "";
        $fields = array("rm.role_id, rm.role_name, rm.status");
        $data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['footerJs'][0] = base_url('uploads/custom/js/Rolemaster/Rolemaster.js');
        //Pass Role Master Table Record In View
        $data['main_content'] = $this->type . '/' . $this->viewname . '/add_role';
        $this->parser->parse($this->type.'/assets/template', $data);
    }
    /*
	 @Author : Mehul Patel
	 @Desc   : Role list view Page
	 @Input  :
	 @Output :
	 @Date   : 18/04/2017
	 */

    public function role_list() {
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        //Get Records From Role Master Table
        $table = ROLE_MASTER . ' as rm';
        $match = "";
        $fields = array("rm.role_id, rm.role_name, rm.status");
        $data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);

        //Get Records From MODULE_MASTER Table


        $getModuleData = array();
        $getModuleData['table'] = MODULE_MASTER . ' as mm';
        $getModuleData['fields'] = array("mm.module_id, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");
        $data['module_data_list'] = $this->common_model->get_records_array($getModuleData);

        //Get Records From AAUTH_PERMS Table

        $getPermsData = array();
        $getPermsData['table'] = AAUTH_PERMS . ' as ap';
        $getPermsData['fields'] = array("ap.id,ap.name,ap.defination");
        $data['perms_list'] = $this->common_model->get_records_array($getPermsData);
        $data['perms_to_role_list'] = $this->common_model->permsToRoleList();
        $data['footerJs'][0] = base_url('uploads/custom/js/Rolemaster/Rolemaster.js');
        //Pass Role Master Table Record In View
        $data['header'] = array('menu_module'=>'settings');
        $data['main_content'] = $this->type . '/' . $this->viewname . '/role_list';
        $this->parser->parse($this->type.'/assets/template', $data);

    }

    /*
     @Author : Mehul Patel
     @Desc   : Role List Edit Page
     @Input  :
     @Output :
     @Date   : 18/04/2017
     */
    public function edit($id) {

        //Get Records From Role Master Table
        $table = ROLE_MASTER . ' as rm';
        $match = "rm.role_id = " . $id;
        $fields = array("rm.role_id, rm.role_name, rm.status");
        $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
        $data['id'] = $id;
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $data['main_content'] = $this->type . '/' . $this->viewname . '/add_role';
        $this->parser->parse($this->type.'/assets/template', $data);
    }

    /*
     @Author : Mehul Patel
     @Desc   : Role List Update Query
     @Input  : Post record from userlist
     @Output : Update data in database and redirect
     @Date   : 18/04/2017
     */

    public function updatedata()
    {
        $sess_array = array('setting_current_tab' => 'setting_role_permission');
        $this->session->set_userdata($sess_array);

        $id = $this->input->get('id');
        $assignedRole = $this->checkRoleAssignedToUserStatus($id);

        if($this->input->post('status')){
            $roleStatus = $this->input->post('status');
        }
        if(!empty($assignedRole))
        { // Role is assigned to User or not
            if($assignedRole[0]['roleStatus'] != $roleStatus){ // if Role is assigned then you don't allow to change the status
                $msg = $this->lang->line('change_role_status');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                echo "<script>window.location.href=window.location.href</script>";
                redirect('Admin/Rolemaster');
            }else{ // Allow to update the Role
                $table = ROLE_MASTER . ' as rm';
                $match = "rm.role_id = " . $id;
                $fields = array("rm.role_name, rm.status");
                $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
                $data['id'] = $id;
                $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;

                $duplicateRole= $this->checkDuplicateRole($this->input->post('role_name'),$id);
                //print_r($duplicateRole); exit;
                $getcount = count($duplicateRole);

                if( isset($duplicateRole) && empty($duplicateRole) && $getcount == 0){
                    $data = array(
                        'role_name' => $this->input->post('role_name'),
                     //   'role_slug' => $this->input->post('role_name'),
                        'updated_by'=>$this->session->userdata['reservation_admin_session']['admin_id'],
                        'updated_date' => datetimeformat(),
                        'status' => $this->input->post('status')
                    );
                    //$id =  $this->input->post('role_id');
                    //Update Record in Database
                    $where = array('role_id' => $id);

                    // Update form data into database
                    if ($this->common_model->update(ROLE_MASTER, $data, $where)) {
                        $msg = $this->lang->line('role_update_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

                        //	redirect('Rolemaster/index');
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        //redirect($this->viewname);
                    }

                    //redirect($this->viewname); //Redirect On Listing page
                    redirect('Admin/Rolemaster');
                }else{
                    $msg = "Role ". $this->input->post('role_name') ." has already been entered";
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    echo "<script>window.location.href=window.location.href</script>";
                    //redirect($this->viewname);

                }
            }
        }else{ // Allow to update the Role

            $table = ROLE_MASTER . ' as rm';
            $match = "rm.role_id = " . $id;
            $fields = array("rm.role_name, rm.status");
            $data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['id'] = $id;
            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;

            $duplicateRole= $this->checkDuplicateRole($this->input->post('role_name'),$id);
            //print_r($duplicateRole); exit;
            $getcount = count($duplicateRole);

            if( isset($duplicateRole) && empty($duplicateRole) && $getcount == 0){
                $data = array(
                    'role_name' => $this->input->post('role_name'),
                 //   'role_slug' => $this->input->post('role_name'),
                    'updated_by'=>$this->session->userdata['reservation_admin_session']['admin_id'],
                    'updated_date' => datetimeformat(),
                    'status' => $this->input->post('status')
                );
                //$id =  $this->input->post('role_id');
                //Update Record in Database
                $where = array('role_id' => $id);

                // Update form data into database
                if ($this->common_model->update(ROLE_MASTER, $data, $where)) {
                    $msg = $this->lang->line('role_update_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

                    //	redirect('Rolemaster/index');
                } else {
                    // error
                    $msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    //redirect($this->viewname);
                }

                //redirect($this->viewname); //Redirect On Listing page
            }else{
                $msg = "Role ". $this->input->post('role_name') ." has already been entered";
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                echo "<script>window.location.href=window.location.href</script>";
                //redirect($this->viewname);

            }
}
        redirect('Admin/Rolemaster');
    }

    /*
     @Author : Mehul Patel
     @Desc   : User List Delete Query
     @Input 	: Post id from List page
     @Output	: Delete data from database and redirect
     @Date   : 18/04/2017
     */

    public function deletedata($id) {
       
        $role_id =  $this->config->item('super_admin_role_id');

        if($role_id != $id){
            $sess_array = array('setting_current_tab' => 'setting_role_permission');
            $this->session->set_userdata($sess_array);

            $status = $this->checkRoleStatus($id);
            $assignedRole = $this->checkRoleAssignedToUser($id);

            if(!empty($assignedRole)){
                $msg = $this->lang->line('assign_role_del_error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                //	redirect($this->viewname);
                redirect('Admin/Rolemaster');
                //redirect('Rolemaster/index'); //Redirect On Listing page
            }else{

                //if($status['status'] !=1){
                //Delete Record From Database
                if (!empty($id)) {

                    $data = array('is_delete'=>1);
                    $where = array('role_id' => $id);

                    if ($this->common_model->update(ROLE_MASTER,$data, $where)) {
                        $msg = $this->lang->line('role_delete_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        unset($id);

                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        //redirect($this->viewname);
                        redirect('Admin/Rolemaster');
                    }
                }
            }
        }else{
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('Admin/Rolemaster');
        }
        redirect('Admin/Rolemaster');


    }

    /*
     @Author : Mehul Patel
     @Desc   : Add permission View Page
     @Input  :
     @Output :
     @Date   : 18/04/2017
     */

    public function addPermission() {
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        //Get Records From AAUTH_PERMS Table
        $table = AAUTH_PERMS . ' as ap';
        $match = "";
        $fields = array("ap.id, ap.name, ap.defination");
        $data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Pass Role Master Table Record In View
        $data['main_content'] = $this->type . '/' . $this->viewname . '/perms_add';
        $this->parser->parse($this->type.'/assets/template', $data);
    }

    /*
     @Author : Mehul Patel
     @Desc   : Insert New Permission
     @Input  :
     @Output :
     @Date   : 18/04/2017
     */

    public function insertPerms() {

        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            //redirect($this->viewname . '/role_list'); //Redirect On Listing page
            redirect('Admin/Rolemaster/index');
        }

        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        //insert the Role details into database
        $data = array(
            'name' => $this->input->post('perms_name'),
            'defination' => $this->input->post('perms_defination')
        );

        //Insert Record in Database

        if ($this->common_model->insert(AAUTH_PERMS, $data)) {
            $msg = $this->lang->line('perms_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect('Admin/Rolemaster/index');
            //redirect($this->viewname . '/role_list');
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            redirect($this->viewname);
        }
    }

    /*
     @Author : Mehul Patel
     @Desc   : Role List Edit Page
     @Input  :
     @Output :
     @Date   : 20/04/2017
     */

    public function editPerms() {

        $id = $this->input->get('id');

        //Get Records From AAUTH_PERMS Table
        $table = AAUTH_PERMS . ' as ap';
        $match = "ap.id = " . $id;
        $fields = array("ap.id, ap.name, ap.defination");
        $data['perms_list'] = $this->common_model->get_records($table, $fields, '', '', $match);
        //Get Records From Permission Table
        $data['id'] = $id;
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $data['main_content'] = $this->type . '/' . $this->viewname . '/perms_add';
        $this->parser->parse($this->type.'/assets/template', $data);
    }

    /*
     @Author : Mehul Patel
     @Desc   : Permission Update Query
     @Input  : Post record from Permission List
     @Output : Update data in database and redirect
     @Date   : 20/04/2017
     */

    public function updatePerms() {

        $id = $this->input->get('id');

        //Get Records From AAUTH_PERMS Table

        $getPermsData = array();
        $getPermsData['table'] = AAUTH_PERMS . ' as ap';
        $getPermsData['match'] = "ap.id = " . $id;
        $getPermsData['fields'] = array("ap.id,ap.name,ap.defination");

        $data['perms_list'] = $this->common_model->get_records_array($getPermsData);

        //Get Records From Permission Table
        $data['id'] = $id;
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;

        $data = array(
            'name' => $this->input->post('perms_name'),
            'defination' => $this->input->post('perms_defination')
        );

        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('id' => $id);

        // Update form data into database
        if ($this->common_model->update(AAUTH_PERMS, $data, $where)) {
            $msg = $this->lang->line('perms_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

            redirect('Admin/Rolemaster/index');
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect($this->viewname);
        }

        redirect($this->viewname); //Redirect On Listing page
    }

    /*
     @Author : Mehul Patel
     @Desc   : Delete Permission Query
     @Input 	: Post id from List page
     @Output	: Delete data from database and redirect
     @Date   : 20/04/2017
     */

    public function deletePerms() {
        $id = $this->input->get('id');
        //Delete Record From Database
        if (!empty($id)) {
            $where = array('id' => $id);
            //  $this->common_model->delete(LOGIN,$where);
            if ($this->common_model->delete(AAUTH_PERMS, $where)) {

                $msg = $this->lang->line('perms_delete_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                unset($id);
                redirect('Admin/Rolemaster/index');//Redirect On Listing page
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
               redirect('Admin/Rolemaster/index'); //Redirect On Listing page

            }
        }
        //	redirect($this->viewname.'/userlist');	//Redirect On Listing page
    }

    /*
     @Author : Mehul Patel
     @Desc   : Assign Permission
     @Input  :
     @Output :
     @Date   : 20/04/2017
     */

    public function assignPermission($roleId) {
        if($this->input->post("moduleName") != ""){
            $moduleComponent = $this->input->post("moduleName");
        }else{
            $moduleComponent = "ReservationSystem";
        }
        $getRoles = $this->common_model->getRoles();
        if(empty($getRoles)){
            // error
            $msg = $this->lang->line('assign_role_error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            echo "<script>window.location.href=window.location.href</script>";
        }else{
            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            //Get Records From AAUTH_PERMS Table
            $table = AAUTH_PERMS_TO_ROLE . ' as apTOr';
            $match = "";
            $fields = array("apTOr.perm_id, apTOr.role_id");
            $data['information'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['userType'] = getUserTypeList();

            $data['perms_list'] = array();
            $data['module_list'] = getModuleList();
            //pr($data['module_list']);
            $data['CRM_module_list'] = getCRMModuleList();
            $data['roleId'] = $roleId;
            $data['getPermList'] = getPermsList();
            $data['footerJs'][0] = base_url('uploads/custom/js/Rolemaster/Rolemaster.js');
            //Pass Role Master Table Record In View
            $master_user_id =  $this->config->item('master_user_id');
            $sub_domain=array_shift((explode(".",$_SERVER['HTTP_HOST'])));

            $data['main_content'] = $this->type . '/' . $this->viewname . '/assign_perms';
            $this->parser->parse($this->type.'/assets/template', $data);

        }

    }
    /*
         @Author : Mehul Patel
         @Desc   : edit permission
         @Input 	:
         @Output	:
         @Date   : 19/04/2017
         */
    public function editPermission($id = null) {
        $frmSession = $this->session->userdata('FORM_SECRET_DATA');

        $role_id =  $this->config->item('super_admin_role_id');

        if($role_id != $id){

            if ($id != NULL) {
                // Get Role List
                $table1 = ROLE_MASTER . ' as rm';
                $fields1 = array("rm.role_id, rm.role_name");
                //$where = array('rm.status' => '1');
                $match1 = "";
                $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
                $table = AAUTH_PERMS_TO_ROLE . ' as apTOr';
                $match = "apTOr.role_id = " . $id;
                $fields = array("apTOr.perm_id, apTOr.role_id,apTOr.module_id");
                $permList = $data['view_perms_to_role_list'] = $this->common_model->get_records($table, $fields, '', '', $match);

                $data['userType'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);
                $data['perms_list'] = array();
                //	$data['module_list'] = getModuleList();
                $data['getPermList'] = getPermsList();
                $data['CRM_module_list'] = getCRMModuleList();
                $data['footerJs'][0] = base_url('uploads/custom/js/Rolemaster/Rolemaster.js');
                //Pass Role Master Table Record In View

                $master_user_id =  $this->config->item('master_user_id');
				$hostArr = (explode(".",$_SERVER['HTTP_HOST']));
                $sub_domain=array_shift($hostArr);
                $data['main_content'] = $this->type . '/' . $this->viewname . '/edit_perms';
             
                $this->parser->parse($this->type.'/assets/template', $data);
            } else {
                $msg = $this->lang->line('perms_assign_update_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect($this->viewname);
            }
        }else{
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('Admin/Rolemaster');
        }

    }

    /*
     @Author : Mehul Patel
     @Desc   : Insert New Permission
     @Input  :
     @Output :
     @Date   : 20/04/2017
     */

    public function insertAssginPerms() {
        $userCheckLimit1 = array();
        
       
        
        if($this->input->post('id') !=""){
            // $userCheckLimit1 =  $this->updateTimeCheckUserAvailbility($this->input->post('id'));

            //if($userCheckLimit1){

            $sess_array = array('setting_current_tab' => 'setting_role_permission');
            $this->session->set_userdata($sess_array);


            $exp1 = $exp3 = $exp4 = $exp5 = $expdata = array();
            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/Rolemaster/Rolemaster.js');
            //insert the Role details into database
            $postData = $exploded = array();
            if (count($this->input->post()) > 0) {
                $postData = $this->input->post();

                //unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id']);
                unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id'],$postData['editPerm'],$postData['submit_btn']);
                foreach ($postData as $key => $val) {
                    $exp1 = explode('checkbox', $key);
                    if(isset($exp1[1])){
                        $expdata = $exp1[1];
                    }
                    $expdata = explode('_', $expdata);
                    if(isset($expdata[0])){
                        $exp3 = $expdata[0];
                    }
                    if(isset($expdata[1])){
                        $exp4 = $expdata[1];
                    }
                    if( $exp3 != NULL && $exp4 != NULL && $exp5 != ""){
                        $exploded[] = array('perm_id' => $exp4, 'role_id' => $this->input->post('usertype'), 'module_id' => $exp3, 'component_name' => 'ReservationSystem');

                    }
                }
            }
            //Insert Record in Database
            if(empty($exploded)){
                $msg = $this->lang->line('perms_Assign_error');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Rolemaster');
            }


            if ($this->input->post('id')) {
                $this->common_model->delete(AAUTH_PERMS_TO_ROLE, array('role_id' => $this->input->post('id')));
            }


            if ($this->common_model->insert_batch(AAUTH_PERMS_TO_ROLE, $exploded)) {
                if($this->input->post('editPerm') != "" ){
                    $msg = $this->lang->line('perms_update_msg');
                }else{
                    $msg = $this->lang->line('perms_assign_add_msg');
                }
                // Update the Assigned module to User based on selected Role

                $hasUser = getUserList($this->input->post('id'));

                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

                //redirect('Rolemaster/index');
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            }
           redirect('Admin/Rolemaster');

        }else{

            $sess_array = array('setting_current_tab' => 'setting_role_permission');
            $this->session->set_userdata($sess_array);


            $exp1 = $exp3 = $exp4 = $exp5 = $expdata = array();
            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            $data['footerJs'][0] = base_url('uploads/custom/js/Rolemaster/Rolemaster.js');
            //insert the Role details into database
            $postData = $exploded = array();
            if (count($this->input->post()) > 0) {
                $postData = $this->input->post();

                //unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id']);
                unset($postData['usertype'], $postData['submit'], $postData['cancel'], $postData['id'],$postData['editPerm'],$postData['submit_btn']);
                foreach ($postData as $key => $val) {
                    $exp1 = explode('checkbox', $key);
                    if(isset($exp1[1])){
                        $expdata = $exp1[1];
                    }
                    $expdata = explode('_', $expdata);
                    if(isset($expdata[0])){
                        $exp3 = $expdata[0];
                    }
                    if(isset($expdata[1])){
                        $exp4 = $expdata[1];
                    }

                    if( $exp3 != NULL && $exp4 != NULL && $exp5 != ""){
                        $exploded[] = array('perm_id' => $exp4, 'role_id' => $this->input->post('usertype'), 'module_id' => $exp3, 'component_name' => 'ReservationSystem');

                    }
                }
            }


            //Insert Record in Database
            if(empty($exploded)){
                $msg = $this->lang->line('perms_Assign_error');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Rolemaster');
            }


            if ($this->input->post('id')) {
                $this->common_model->delete(AAUTH_PERMS_TO_ROLE, array('role_id' => $this->input->post('id')));
            }

            if ($this->common_model->insert_batch(AAUTH_PERMS_TO_ROLE, $exploded)) {

                if($this->input->post('editPerm') != "" ){
                    $msg = $this->lang->line('perms_update_msg');
                }else{
                    $msg = $this->lang->line('perms_assign_add_msg');
                }
                // Update the Assigned module to User based on selected Role

                $hasUser = getUserList($this->input->post('id'));
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");

                //redirect('Rolemaster/index');
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

                //redirect($this->viewname);
            }
            redirect('Admin/Rolemaster');
        }
    }

    /*
     @Author : Mehul Patel
     @Desc   : View Permission
     @Input  :
     @Output :
     @Date   : 20/04/2017
     */

    public function view_perms_to_role_list($id = null) {
        // Get Role List
        $table1 = ROLE_MASTER . ' as rm';
        $fields1 = array("rm.role_id, rm.role_name");
        //$where = array('rm.status' => '1');
        $match1 = "";
        // $data['role_option'] = $this->common_model->get_records($table1, $fields1, '', '', $match1);

        //Get Records From AAUTH_PERMS Table
        $table = AAUTH_PERMS_TO_ROLE . ' as apTOr';
        $match = "apTOr.role_id = " . $id;
        $fields = array("apTOr.perm_id, apTOr.role_id,apTOr.module_id");
        $permList = $data['view_perms_to_role_list'] = $this->common_model->get_records($table, $fields, '', '', $match);

        $data['userType'] =  $this->common_model->get_records($table1, $fields1, '', '', $match1);;
        $data['perms_list'] = array();
        //$data['module_list'] = getModuleList();
        $data['CRM_module_list'] = getCRMModuleList();
        $data['getPermList'] = getPermsList();
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $master_user_id =  $this->config->item('master_user_id');
		
		$host = explode(".",$_SERVER['HTTP_HOST']);
        $sub_domain=array_shift($host);

        //Pass Role Master Table Record In View
        $data['main_content'] = $this->type . '/' . $this->viewname . '/veiw_assign_perms';
        //$this->load->view("veiw_assign_perms", $data);
        $this->parser->parse($this->type.'/assets/template', $data);
    }

    /*
     @Author : Mehul Patel
     @Desc   : Delete Assigned Permission
     @Input  : Post id from List page
     @Output : Delete data from database and redirect
     @Date   : 20/04/2017
     */

    public function deleteAssignperms($id) {

        //$id = $this->input->get('id');
        //Delete Record From Database
        if (!empty($id)) {
            $where = array('role_id' => $id);
            //  $this->common_model->delete(LOGIN,$where);
            if ($this->common_model->delete(AAUTH_PERMS_TO_ROLE, $where)) {

                $msg = $this->lang->line('perms_assign_delete_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                unset($id);
                redirect('Admin/Rolemaster/index'); //Redirect On Listing page
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('Admin/Rolemaster'); //Redirect On Listing page
            }
        }
        // redirect($this->viewname.'/userlist'); //Redirect On Listing page
    }

    /*
     @Author : Mehul Patel
     @Desc   : Check Role Status Active / Inactive
     @Input  : Post id from List page
     @Output : return Status Array
     @Date   : 21/04/2017
     */
    public function checkRoleStatus($id){

        $table = ROLE_MASTER . ' as rm';
        $match = "rm.role_id = " . $id;
        $fields = array("rm.role_id,rm.status");
        $roleStatus = $this->common_model->get_records($table, $fields, '', '', $match);

        return $roleStatus[0];
    }

    /*
     @Author : Mehul Patel
     @Desc   : checkRoleAssignedToUser
     @Input  : Post id from List page
     @Output : return Status Array
     @Date   : 21/04/2017
     */
    public function checkRoleAssignedToUser($id){

        $table = USER . ' as l';
        $match = "l.role_type = " . $id." AND l.is_delete = 0";
        $fields = array("l.role_type");
        $roleStatus = $this->common_model->get_records($table, $fields, '', '', $match);
        return $roleStatus[0];
    }

    /*
     @Author : Mehul Patel
     @Desc   : checkRoleAssignedToUser
     @Input  : Post id from List page
     @Output : return Status Array
     @Date   : 21/04/2017
     */
    public function checkRoleAssignedToUserStatus($id){

        $roleStatus = $this->common_model->checkRoleAssigne($id);

        return $roleStatus;
    }

    /*
     @Author : Mehul Patel
     @Desc   : Common pagination initialization
     @Input 	:
     @Output	:
     @Date   : 21/04/2017
     */
    private function pagingConfig($config, $page_url) {
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01 pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . $page_url . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';

        $this->pagination->cur_page = 3;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }


    /*
     @Author : Mehul Patel
     @Desc   : assignModuleCount
     @Input  :
     @Output :
     @Date   : 21/04/2017
     */
    public function assignModuleCount($role_id){

        $dataArr=array();
        $this->db->select('COUNT(*) AS ASSIGNED')->from(AAUTH_PERMS_TO_ROLE);
        $this->db->where('role_id =',$role_id);
        $query = $this->db->get();
        $data = $query->result_array();
        foreach ($data as $dataz){

            $dataArr[$dataz['component_name']]=$dataz['ASSIGNED'];
        }
        return  $dataArr;
        //return  json_encode($dataArr);

    }

}
