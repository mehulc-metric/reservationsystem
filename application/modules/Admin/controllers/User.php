<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        if(checkAdminPermission('User','view') == false)
        {
               redirect('/Admin/Dashboard');
        }
        check_admin_login();
        $this->type = ADMIN_SITE;
        $this->viewname = $this->uri->segment(2);
     //   $this->load->library(array('form_validation'));
    }

    /*
      @Author : Mehul Patel
      @Desc   : User Listing form
      @Input  :
      @Output :
      @Date   : 13/06/2017
     */

    public function index($page = '') {
        
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('customers_sortsearchpage_data');
        }

        $searchsort_session = $this->session->userdata('customers_sortsearchpage_data');
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
                $sortfield = 'user_id';
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
            //$perpage = $this->input->post('perpage');
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
        //$config['base_url'] = base_url($this->module . '/' . $this->viewname . '/index');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }        
        //Query
        
        
        $table = USER . ' as c';       
        //$where = array("c.is_delete" => '0',"rm.status"=>'1',"rm.is_delete"=>'0');
        $where = " c.is_delete=0 AND rm.status=1 AND rm.is_delete=0 AND c.role_type != 5";
        $fields = array("c.user_id, c.role_type, CONCAT(`first_name`,' ', `last_name`) as customer_name, c.first_name, c.last_name, c.email, c.status, rm.role_name");
        $params['join_tables'] = array(ROLE_MASTER . ' as rm' => 'rm.role_id=c.role_type');
	$params['join_type'] = 'left';
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim($searchtext));        
            
            $match = '';
            $where_search = '((c.first_name LIKE "%' . $searchtext . '%" OR c.last_name LIKE "%' . $searchtext . '%" OR c.email LIKE "%' . $searchtext . '%" OR rm.role_name LIKE "%' . $searchtext . '%") AND c.is_delete = "0" AND c.role_type != 5)';
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', $match, $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where_search);          
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', $match, '', '', $sortfield, $sortby, '', $where_search, '', '', '1');
        } else {
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, '', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', $sortfield, $sortby, '', $where, '', '', '1');           
        }
       
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $data['footerJs'][0] = base_url('uploads/custom/js/User/user.js');
        
        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('customers_sortsearchpage_data', $sortsearchpage_data);

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type.'/assets/template',$data);
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : User Form validation
      @Input  :
      @Output :
      @Date   : 13/06/2017
     */

    public function formValidation($currentCustomerEmail) {

        if ($this->input->post('email') != $currentCustomerEmail) {
            $is_unique = 'is_unique[res_user.email]';
        } else {
            $is_unique = '';
        }

        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[100]|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[100]|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[100]|valid_email|xss_clean');        
    }

    function phone_valid($str) {
        return preg_match('/^[\d\+\-\.\(\)\/\s]*$/', $str);
    }

    /*
      Author : Mehul Patel
      Desc  : Add cutosmer
      Input  :
      Output :
      Date   : 06/06/2017
     */

    public function add() {

        $this->formValidation(''); // form Validation fields

        if ($this->form_validation->run() == FALSE) {

            $data['validation'] = validation_errors();
            $data['roleType'] = getUserType();
            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/add';
            $data['main_content'] = $this->viewname . '/addEdit';
            $data['screenType'] = 'add';
            $data['footerJs'] = array('0' => base_url() . 'uploads/custom/js/User/user.js');
            //$this->load->view(ADMIN_SITE . '/assets/template', $data);
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        } else {
            //success form
            $this->insertData();
        }
    }

    /*
      @Author : Mehul Patel 
      @Desc   : Customer Insert Data
      @Input  :
      @Output :
      @Date   : 06/06/2017
     */

    public function insertData() {

        $randomPassword = rand_string(8);
        // Inserted Array Data
        $data = array(
            'role_type' => $this->input->post('usertype'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'password' => md5($randomPassword), // helper function call for random password generate           
            'status' => '1',
            'modified_at' => date("Y-m-d H:i:s"),            
            'created_at' => date("Y-m-d H:i:s")
        );

        // Insert query
        if ($this->common_model->insert(USER, $data)) {
            //if (true) {

            $this->sendMailToCustomer($data, $randomPassword); // send mail

            $msg = $this->lang->line('user_add_successfull');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = $this->lang->line('user_add_error');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }

        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    /*
      @Author : Mehul Patel
      @Desc   : Customer Edit Function
      @Input  : customerId
      @Output :
      @Date   : 06/06/2017
     */

    public function edit($customerId) {

        $tableName = USER;
        $fields = array('*');
        $match = array('user_id' => $customerId,'status' => '1','is_delete'=> '0');
		
        $data['editCustomerRecord'] = $this->common_model->get_records($tableName, $fields, '', '', $match, '', '', '', '', '', '', '');
        $data['roleType'] = getUserType();
        $data['footerJs'] = array('0' => base_url() . 'uploads/custom/js/User/user.js');
		$data['roleName']="";
        if (!empty($data['editCustomerRecord'][0])) {

            $currentCustomerEmail = $data['editCustomerRecord'][0]['email'];
            $this->formValidation($currentCustomerEmail); // Form fields validation

            if ($this->form_validation->run() == FALSE) {

                /* Start - Edit data in form */
                $data['editCustomerId'] = $data['editCustomerRecord'][0]['user_id'];
                $data['editFirstName'] = $data['editCustomerRecord'][0]['first_name'];
                $data['editLastName'] = $data['editCustomerRecord'][0]['last_name'];
                $data['editEmail'] = $data['editCustomerRecord'][0]['email'];             
                $data['editRoleType'] = $data['editCustomerRecord'][0]['role_type'];             
                /* End - Edit data in form */

                $data['validation'] = validation_errors();

                $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
                $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $customerId;
                $data['main_content'] = $this->viewname . '/addEdit';
                $data['screenType'] = 'edit';
                $data['footerJs'] = array(
                    '0' => base_url() . 'uploads/custom/js/User/user.js'
                );
		if (isset($data['editCustomerRecord'][0]['role_type'])) {
		    $roleName = getRoleName($data['editCustomerRecord'][0]['role_type']);
		    $data['roleName'] = $roleName[0]['role_name'];
		}
                $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
            } else {
                // success form
                $this->updateData($customerId);
            }
        } else {
            // error
            $msg =  $this->lang->line('invalid_id_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Customer Update Data
      @Input  : CustomerId
      @Output :
      @Date   : 06/06/2017
     */

    public function updateData($customerId) {
       
        $data = array(
            'role_type' => $this->input->post('usertype'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),           
            'status' => '1',
            'modified_at' => date("Y-m-d H:i:s")
        );

        // update customer
        $where = array('user_id' => $customerId);

        if ($this->common_model->update(USER, $data, $where)) { //Update data
            $msg = $this->lang->line('user_add_success');
            $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
        } else {
            // error
            $msg = $this->lang->line('user_add_error');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
        }

        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    private function sendMailToCustomer($data = array(), $randomPassword = '') {

        if (!empty($data) && !empty($randomPassword)) {

            /* Send Created Customer password with Link */
            $toEmailId = $data['email'];
            $customerName = $data['first_name'] . ' ' . $data['last_name'];
            $loginLink = base_url('Admin/');

            $find = array(
                '{NAME}',
                '{EMAIL}',
                '{PASSWORD}',
                '{LINK}',
            );

            $replace = array(
                'NAME' => $customerName,
                'EMAIL' => $toEmailId,
                'PASSWORD' => $randomPassword,
                'LINK' => $loginLink,
            );

            $emailSubject = lang('welcome_sub'). lang('reservation_system');
            $emailBody = '<div>'
                    . '<p>'. lang('hello').'</p> '
                    . '<p>'. lang('your_credentials').'</p> '
                    . '<p>'. lang('emails').' : {EMAIL} </p> '
                    . '<p>'. lang('password').' : {PASSWORD}</p> '
                    . '<p>'. lang('please_check_below_link').'</p> '
                    . '<p><a herf= "{LINK}">{LINK} </a> </p> '
                    . '<div>';


            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($toEmailId, $emailSubject, $finalEmailBody, FROM_EMAIL_ID);
        }
        return true;
    }

    /*
      @Author : Mehul Patel
      @Desc   : Customer Check Duplicate email
      @Input  :
      @Output :
      @Date   : 06/06/2017
     */

    public function isDuplicateEmail() {

        $isduplicate = 0;
        $emailName = trim($this->input->post('email'));
        $customer_id = trim($this->input->post('customer_id'));       
        if (!empty($emailName)) {

            $tableName = USER;
            $fields = array('COUNT(user_id) AS cntData');

            if (!empty($customer_id)) { // edit 
                $match = array('email' => $emailName, 'user_id <>' => $customer_id, 'is_delete' => '0', 'status' => '1', 'role_type <>'=> '5');
            } else {
                $match = array('email' => $emailName, 'is_delete' => '0', 'status' => '1', 'role_type <>'=> '5');
            }
           
            $duplicateEmail = $this->common_model->get_records($tableName, $fields, '', '', $match);
            
            if ($duplicateEmail[0]['cntData'] > 0) {
                $isduplicate = 1;
            } else {
                $isduplicate = 0;
            }
        }

        echo $isduplicate;
    }
	 /*
      @Author : Mehul Patel
      @Desc   : Bulk Delete projects
      @Input 	:
      @Output	:
      @Date   : 11/5/2017
     */

    public function customerBulkDelete() {
        $id = $this->input->get('customerid');
        $customers_ids = explode(",", $id);
        if (!empty($id)) {
            foreach ($customers_ids as $customersIds) {
                $data = array('is_delete' => 1 );
                $where = array('user_id' => $customersIds, 'user_id != ' => 1);               
                
                if ($this->common_model->update(USER, $data, $where)) {
                    $msg = $this->lang->line('user_delete');  
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    unset($id);
                } else {
                    // error
                    $msg = $this->lang->line('user_add_error');
                    //$msg = $this->lang->line('error_msg');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            }
        }
         redirect(ADMIN_SITE.'/User');
    }

}
