<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_customer_login();
        $this->module = $this->uri->segment(1);
        $this->viewname = $this->uri->segment(2);
        $this->load->library(array('form_validation', 'Session', 'breadcrumbs'));
	}
    /*
     @Author : Mehul Patel
     @Desc   : Dashboard Index
     @Input  :
     @Output :
     @Date   : 06/06/2017
    */    
    public function index() {        
       
        $data['header'] = array('menu_module' => 'crm');
        $data['main_content'] = '/Dashboard';
        $data['js_content'] = '/loadJsFiles';
        /*
         * logged in user data
         */
        $data['user_info'] = $this->session->userdata('LOGGED_IN');
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $umatch = "customers_id =" . $user_id;
        $ufields = array("*");
        $data['logged_user'] = $this->common_model->get_records(CUSTOMER_TABLE, $ufields, '', '', $umatch);
        /*
         * logged in user data ends
         */
        if ($this->input->is_ajax_request()) {
            $this->load->view('Dashboard', $data);
        } else {

            $this->parser->parse('layouts/DefaultTemplate', $data);
        }
    }
    /*
     @Author : Mehul Patel
     @Desc   : Logout
     @Input  :
     @Output :
     @Date   : 06/06/2017
    */
    public function logout() {
        $user_session = $this->session->userdata('LOGGED_IN');
        if ($user_session) {		
            $this->session->unset_userdata('LOGGED_IN');
            $this->session->unset_userdata('token');
            $error_msg = 'You have successfully logged out';
            $this->session->set_userdata('ERRORMSG', $error_msg);
            $this->session->sess_destroy();
            redirect(base_url('/Login'));
        } else {
            redirect(base_url());
        }
    }

    /*
     @Author : Mehul Patel
     @Desc   : profile Edit Page
     @Input 	:
     @Output	:
     @Date   : 06/06/2017
    */
    public function profile() {
        $user_id = $this->session->userdata('LOGGED_IN')['ID'];
        $umatch = "customers_id =" . $user_id;
        $ufields = array("*");
        $data['logged_user'] = $this->common_model->get_records(CUSTOMER_TABLE, $ufields, '', '', $umatch);
        $data['crnt_view'] = $this->viewname;
        $data['main_content'] = '/profile';
        $this->parser->parse('layouts/DefaultTemplate', $data);
    }
}
