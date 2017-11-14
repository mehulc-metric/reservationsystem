<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sidebar extends CI_Controller {

    function __construct() {
        $this->CI = & get_instance();

        $system_lang = $this->CI->common_model->get_lang();
      //  echo "System Lang :".$system_lang; exit();
        $this->selectedLang = $system_lang;
    }

    /*
      Author : Mehul Patel
      Desc   : Call Head area
      Input  : Bunch of Array
      Output : All CSS and JS
      Date   : 11/05/2017
     */

    public function head($param = NULL) {
        $data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 
        $this->CI->load->view('Sidebar/head', $data);
    }

    /*
      Author : Mehul Patel
      Desc   : Call Header For all the template
      Input  : Bunch of Array
      Output : Top Side Header(Logo, Menu, Language)
      Date   : 11/05/2017
     */

    public function header($param = NULL) {
        $selected_new_language = $this->selectedLang;  //get Selected Language file 
	
        $table = LANGUAGE_MASTER . ' as lm';
        $where = "lm.language_name= '" . $selected_new_language . "' ";
        $fieldsn = array("name");
        $language = $this->CI->common_model->get_records($table, $fieldsn, '', '', '', '', '', '', '', '', '', $where, '', '');
        $data['selected_language'] = $language;
        
        $this->CI->load->view('Sidebar/header', $data);
    }

    /*
      Author : Mehul Patel
      Desc   : Call Header For Login template
      Input  : Bunch of Array
      Output : Top Side Header(Logo, Menu, Language)
      Date   : 11/05/2017
     */

    public function loginheader($param = NULL) {
        $data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $selected_new_language = $this->selectedLang;  //get Selected Language file 
              
        $table = LANGUAGE_MASTER . ' as lm';
        $where = "lm.language_name = '" . $selected_new_language . "' ";
        $fieldsn = array("name");
        $language = $this->CI->common_model->get_records($table, $fieldsn, '', '', '', '', '', '', '', '', '', $where, '', '');
        $data['selected_language'] = $language;
        $this->CI->load->view('Sidebar/loginheader', $data);
    }

    /*
      Author : Mehul Patel
      Desc   : Call Footer
      Input  : Bunch of Array
      Output : Top Side Header(Logo, Menu, Language)
      Date   : 11/05/2017
     */

    public function loginfooter($param = NULL) {
        $data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 
        $this->CI->load->view('Sidebar/loginfooter', $data);
    }

    public function footer($param = NULL) {
        $data['param'] = $param;           //Default Parameter 
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View 
        $data['selected_language'] = $this->selectedLang;  //get Selected Language file 
        $this->CI->load->view('Sidebar/footer', $data);
    }

    /*
      Author : Mehul Patel
      Desc   : Call Left Menu area
      Input  : Bunch of array
      Output : Unset Error Session
      Date   : 11/05/2017
     */

    public function leftmenu($param = NULL) {
        $data['param'] = $param;           //Default Parameter
        $data['cur_viewname'] = $this->CI->router->fetch_class();     //Current View
        $data['sub_domain'] = array_shift((explode(".", $_SERVER['HTTP_HOST'])));
        $this->CI->load->view('Sidebar/leftmenu', $data);
    }

    /*
      Author : Mehul Patel
      Desc   : Unset Error Message Variable for all Form
      Input  :
      Output : Unset Error Session
      Date   : 11/05/2017
     */

    public function unseterror() {
        $error = $this->CI->session->userdata('ERRORMSG');
        if (isset($error) && !empty($error)) {
            $this->CI->session->unset_userdata('ERRORMSG');
        }
        //session_destroy();
    }

}
