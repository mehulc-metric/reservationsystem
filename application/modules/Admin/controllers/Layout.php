<?php

defined ('BASEPATH') OR exit('No direct script access allowed');

class Layout extends CI_Controller {

    function __construct() {
        $this->CI = &get_instance ();
        // parent::__construct();
    }

    /*
      Author : Niral Patel
      Desc   : Call Header For all the template
      Input  : Bunch of Array
      Output : Top Side Header(Logo, Menu, Language)
      Date   : 12/06/2017
     */

    public function header($param = NULL) {
        $data = array();
        $this->CI->load->view ('Admin/assets/header', $data);
    }

    public function sidebar($param = NULL) {
        $data = array();
        $this->CI->load->view ('Admin/assets/left', $data);
    } 
    public function footer($param = NULL) {
        $data = array();
        $this->CI->load->view ('Admin/assets/footer', $data);
    }

}
