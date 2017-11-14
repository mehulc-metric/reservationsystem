<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configs extends CI_Controller 
{

    function __construct() {
        parent::__construct();
        if (checkAdminPermission('Configs', 'view') == false) {
            redirect('/Admin/Dashboard');
        }
        $this->type = ADMIN_SITE;
        $this->viewname = ucfirst($this->router->fetch_class());        
    }

    /*
      Author : Niral Patel
      Desc   : View Configs
      Input  :
      Output :
      Date   :13/06/2016
     */

    public function index() {
        //get no_of_slot_per_hour
        $field = array('*');
        $match = array('config_key' => 'no_of_slot_per_hour');
        $data['no_of_slot_per_hour'] = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
        //get no_of_people_per_hour
       
        $field3 = array('*');
        $match3 = array('config_key' => 'no_of_people_per_hour');
        $data['no_of_people_per_hour'] = $this->common_model->get_records(CONFIG_TABLE, $field3, '', '', $match3);

        // get Aount per user
        $field1 = array('*');
        $match1 = array('config_key' => 'amount');
        $data['amount'] = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);
        // get vat
        $field2 = array('*');
        $match2 = array('config_key' => 'vat');
        $data['vat'] = $this->common_model->get_records(CONFIG_TABLE, $field2, '', '', $match2);
   
        $data['main_content'] = '/' . $this->viewname . '/add';
        $data['footerJs'][0] = base_url('uploads/custom/js/config/config.js');
        $this->load->view($this->type . '/assets/template', $data);
    }

    /*
      Author : Niral Patel
      Desc   : Update Configs
      Input  :
      Output :
      Date   :13/06/2016
     */

    function update_data() {
        //mollie payment secret key update
        $cdata['value'] = trim($this->input->post('no_of_slot_per_hour'));
        $where = array('config_key' => 'no_of_slot_per_hour');
        $this->common_model->update(CONFIG_TABLE, $cdata, $where);

        //lower credit limit color notification
        $cdata1['value'] = trim($this->input->post('no_of_people_per_hour'));
        $where1 = array('config_key' => 'no_of_people_per_hour');
        $this->common_model->update(CONFIG_TABLE, $cdata1, $where1);

        $this->session->set_flashdata('msg1', "<div class='alert alert-success text-center'>" . lang('update_successfully') . "</div>");
       
        redirect($this->type . '/' . $this->viewname);
    }

    /*
      Author : Mehul Patel
      Desc   : PaymentSettings
      Input  :
      Output :
      Date   :13/10/2017
     */

    function update_payment_data() {

        $cdata['value'] = trim($this->input->post('amount'));
        $where = array('config_key' => 'amount');
        $this->common_model->update(CONFIG_TABLE, $cdata, $where);

        //lower credit limit color notification
        $cdata1['value'] = trim($this->input->post('vat'));
        $where1 = array('config_key' => 'vat');
        $this->common_model->update(CONFIG_TABLE, $cdata1, $where1);

        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>" . lang('update_successfully') . "</div>");
        
         redirect($this->type . '/' . $this->viewname);
    }

}
