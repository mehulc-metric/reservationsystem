<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ModuleMaster extends CI_Controller {

    function __construct() {
        parent::__construct();
        if(checkAdminPermission('ModuleMaster','view') == false)
        {
               redirect('/Admin/Dashboard');
        }
        check_admin_login();
        $this->type = "Admin";
        $this->viewname = "ModuleMaster";       
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'Session'));
    }

    /*
      @Author : Mehul Patel
      @Desc   : Ingredient Category Index Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function index() {
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = 10;
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('modulemaster_data');
        }

        $searchsort_session = $this->session->userdata('modulemaster_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'module_id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/index';

        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query
        $table = MODULE_MASTER . ' as mm';
        $where = "";
        $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");

        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim(addslashes($searchtext)));
            $match = '';
            $where_search = '((mm.module_name LIKE "%' . $searchtext . '%" OR mm.controller_name LIKE "%' . $searchtext . '%"))';

            $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else {
            $data['information'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');
        }
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);

        $this->session->set_userdata('modulemaster_data', $sortsearchpage_data);
        //setActiveSession('modulemaster_data'); // set current Session active
//        $data['header'] = array('menu_module' => 'masters' , 'menu_child' => 'ingredienttype');
        $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
        $data['footerJs'][0] = base_url('uploads/custom/js/modulemaster/modulemaster.js');
        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajaxlist', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/Modulemasterlist';
            $this->parser->parse($this->type . '/assets/template', $data);
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Ingredient Type list view Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function add() {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $data['crnt_view'] = $this->viewname;
            //Get Records From AAUTH_PERMS Table
            $table = MODULE_MASTER . ' as mm';
            $match = "";
            $fields = array("mm.module_id, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");
            $data['footerJs'][0] = base_url('uploads/custom/js/modulemaster/modulemaster.js');
            $data['moduleInformation'] = $this->common_model->get_records($table, $fields, '', '', $match);
            //Pass Role Master Table Record In View
            $data['main_content'] = $this->type . '/' . $this->viewname . '/add';
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/add';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->insertModule();
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Insert New Module Details
      @Input  :
      @Output :
      @Date   : 10/03/2017
     */

    public function insertModule() {

        if (!validateFormSecret()) {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('error') . "</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
        $data['crnt_view'] = $this->viewname;

        $data = array(
            'module_name' => $this->input->post('module_name'),
            'module_unique_name' => $this->input->post('module_name'),
            'controller_name' => $this->input->post('controller_name'),
            'created_date' => datetimeformat(),
            'created_by ' => $this->session->userdata['reservation_admin_session']['admin_id'],
            'component_name' => $this->input->post('component_name'),
            'status' => $this->input->post('module_status')
        );
        //Insert Record in Database
        if ($this->common_model->insert(MODULE_MASTER, $data)) {
            $msg = $this->lang->line('module_add_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Ingredient Type Edit Page
      @Input 	:
      @Output	:
      @Date   : 10/03/2017
     */

    public function edit($id) {
        $this->formValidation();
        if ($this->form_validation->run() == FALSE) {
            $table = MODULE_MASTER . ' as mm';
            $match = "mm.module_id = " . $id;
            $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");
            $data['editModuleRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);
            $data['id'] = $id;
            $data['module_status'] = getModuleStatus();
            $data['footerJs'][0] = base_url('uploads/custom/js/modulemaster/modulemaster.js');
            $data['crnt_view'] = $this->viewname;
            $data['form_action_path'] = $this->type . '/' . $this->viewname . '/edit/' . $id;
            $data['main_content'] = $this->type . '/' . $this->viewname . '/add';
            $this->parser->parse($this->type . '/assets/template', $data);
        } else {
            $this->updateModule();
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Module Master update Query
      @Input  : Post record from Module Master List
      @Output : Update data in database and redirect
      @Date   : 11/03/2017
     */

    public function updateModule() {

        $id = $this->input->post('module_id');
        //Get Records From AAUTH_PERMS Table
        $getModuleData = array();
        $getModuleData['table'] = MODULE_MASTER . ' as mm';
        $getModuleData['match'] = "mm.module_id = " . $id;
        $getModuleData['fields'] = array("mm.module_id, mm.module_name, mm.module_unique_name,mm.controller_name,mm.status");

        $data['module_data_list'] = $this->common_model->get_records_array($getModuleData);

        //Get Records From Module Master Table
        $data['module_id'] = $id;
        $data['crnt_view'] = $this->viewname;

        $data = array(
            'module_name' => $this->input->post('module_name'),
            'module_unique_name' => $this->input->post('module_name'),
            'controller_name' => $this->input->post('controller_name'),
            'updated_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
            'updated_date' => datetimeformat(),
            'status' => $this->input->post('module_status')
        );
        //$id =  $this->input->post('role_id');
        //Update Record in Database
        $where = array('module_id' => $id);

        // Update form data into database
        if ($this->common_model->update(MODULE_MASTER, $data, $where)) {
            $msg = $this->lang->line('module_update_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        } else {
            // error
            $msg = $this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect(ADMIN_SITE . '/' . $this->viewname);
        }

        redirect(ADMIN_SITE . '/' . $this->viewname); //Redirect On Listing page
    }

    public function deleteModuleData($id) {
        //	$id = $this->input->get('id');
        //Delete Record From Database
        if (!empty($id)) {
            $where = array('module_id' => $id);
            //  $this->common_model->delete(LOGIN,$where);
            if ($this->common_model->delete(MODULE_MASTER, $where)) {

                $msg = $this->lang->line('module_delete_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                unset($id);
                redirect(ADMIN_SITE . '/' . $this->viewname);
            } else {
                // error
                $msg = $this->lang->line('error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect(ADMIN_SITE . '/' . $this->viewname);
            }
        }
    }

    public function formValidation($id = null) {
        $this->form_validation->set_rules('component_name', 'Component Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('module_name', 'Module Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('controller_name', 'Controller Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('controller_name', 'Module Status', 'trim|required|xss_clean');
    }

}


