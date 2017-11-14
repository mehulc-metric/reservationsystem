<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

	
    /*
      Author : Mehul Patel
      Desc  :
      Input  :
      Output :
      Date   :06/06/2017
     */

    public function index() {
      
        $admin_session = $this->session->userdata('reservation_admin_session');
        if ($admin_session['active'] === TRUE) {
            redirect(base_url(ADMIN_SITE . '/Dashboard'));
        } else {
            $this->do_login();
        }
    }

    /*
      @Description : Check Login is valid or not
      @Author      : Mehul Patel
      @Input       : adminemail, passowrd and / or adminemail
      @Output      : true or false
      @Date        : 06/06/2017
     */

    public function do_login() {
        
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));
		
          if ($email && $password) {
               
            $field = array('first_name', 'last_name', 'role_type', 'user_id', 'email', 'status');
            $match = array('email' => $email, 'password' => $password);
            $udata = $this->common_model->get_records(USER, $field, '', '', $match);
           
            if (count($udata) > 0) {
                if ($udata[0]['role_type'] == 1 || $udata[0]['role_type'] == 2) {
                    if ($udata[0]['status'] == '1') {
                        $newdata = array( 
                            'name' => !empty($udata[0]['first_name']) ? $udata[0]['first_name'] : '',						
                            'admin_id' => $udata[0]['user_id'],
                            'admin_email' => $udata[0]['email'],
                            'admin_type' => $udata[0]['role_type'],                           
                            'active' => TRUE);
                        $this->session->set_userdata('reservation_admin_session', $newdata);
                        redirect(base_url(ADMIN_SITE));
                    } else {
                        $msg = $this->lang->line('inactive_account');
                        $newdata = array('msg' => $msg);
                        $data['msg'] = $msg;
                        $this->load->view(ADMIN_SITE, $data);
                    }
                } else {
                    $msg = $this->lang->line('invalid_us_pass');
                    $newdata = array('msg' => $msg);
                    $data['msg'] = $msg;
                    $this->load->view(ADMIN_SITE, $data);
                }
            } else {
                $msg = $this->lang->line('invalid_us_pass');
                $newdata = array('msg' => $msg);
                $data['msg'] = $msg;
                $this->load->view(ADMIN_SITE, $data);
            }
        } else {
            $data['msg'] = $this->session->flashdata('msg');
            $this->load->view(ADMIN_SITE, $data);
        }       
    }

    /*
      Author : Mehul Patel
      Desc   : Send mail to given email id
      Input  : Email id
      Output : Sent mail to given email id
      Date   : 06/06/2017
     */

    public function forgot_password() {

        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('forgot_email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $msg = validation_errors();
            $this->session->set_flashdata('msgs', $msg);
            redirect(ADMIN_SITE);
        } else {
            
            $table_user = USER . ' as l';
            
            $match_email = "l.email = '" . $this->input->post('forgot_email') . "' AND l.is_delete = 0 AND l.role_type != 5";
           
            $fields_user = array("l.role_type,l.status");
            
            $getRoletype = $this->common_model->get_records($table_user, $fields_user, '', '', $match_email);
            
            if(!empty($getRoletype[0]['role_type'])){
                $exitEmailId = $this->checkEmailId($this->input->post('forgot_email'), $getRoletype[0]['role_type']);
            }else{
                $exitEmailId = $this->checkEmailId($this->input->post('forgot_email'), $roleID = 1);
            }
            

            if (empty($exitEmailId)) {
                // error               
                $msg = lang('email_does_not_exists');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                $this->load->view('reset_password');
            } else {
                if ($this->input->post('forgot_email')) {
                    $token = md5($this->input->post('forgot_email') . date("Y-m-d H:i:s"));
                    $newpasswordlink = "<a href='" . base_url() . "Admin/updatepassword?token=" . $token . "'>" . "Click Here" . "</a>";

                    $find = array(
                        '{PASS_KEY_URL}',
                        '{SITE_NAME}',
                    );

                    $replace = array(
                        'PASS_KEY_URL' => $newpasswordlink,
                        'SITE_NAME' => base_url(),
                    );


                    $emailSubject = lang('reservation_system')."::".lang('COMMON_FORGET_PASS_MENU');

                    $emailBody = '<div>'
                            . '<p>'. lang('reset_your_account_password').'&nbsp;</p>'
                            . '<p>'.lang('resert_password_msg_1').'&nbsp;</p>'
                            . '<p><span style="line-height: 17.1429px;">{PASS_KEY_URL} </span>&nbsp;'. lang('to_reset_your_password').'&nbsp;</p>'
                            . ''. lang('recived_passsword_reset').' of&nbsp; {SITE_NAME} '. lang('password_request_again').'<p>'. lang('Sincerely').'<br></p>'
                            . '<p>'.lang('reservation_team').'</p>'
                            . '<div>';

                    $finalEmailBody = str_replace($find, $replace, $emailBody);
                    $to_emailid = $this->input->post('forgot_email');

                    $data = array('reset_password_token' => $token, 'modified_at' => datetimeformat());
                    $where = array('email' => $this->input->post('forgot_email'));
                    //$toEmailIds = $this->input->post('email');
                    if ($this->common_model->update(USER, $data, $where)) {
                        //send_mail($to, $subject, $body);
                        if ($this->common_model->sendEmail($to_emailid, $emailSubject, $finalEmailBody, FROM_EMAIL_ID)) {

                            $msg = lang('sent_password_link');
                        } else {
                            $msg = lang('smtp_error_msg');
                        }
                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        redirect(ADMIN_SITE);
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect(ADMIN_SITE);
                        // $this->load->view('reset_password');
                    }
                }
            }
            redirect(ADMIN_SITE);
        }

    }

    /*
      Author : Mehul Patel
      Desc  :
      Input  :
      Output :
      Date   :06/06/2017
     */

    public function reset_password() {
        $this->load->view('reset_password');
    }

    public function add_new_password() {

        $this->form_validation->set_rules('password', 'New Password', 'trim|required|md5');
        $this->form_validation->set_rules('rpassword', 'Confirm Password', 'trim|required|md5|matches[password]');

        if ($this->form_validation->run() == FALSE) {

            $msg = validation_errors();
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            $redirect_to = str_replace(base_url(ADMIN_SITE), '', $_SERVER['HTTP_REFERER']);

            redirect($redirect_to);
        } else {

            $tokenID = $this->input->post('tokenID');
            $password = $this->input->post('password');

            if ($tokenID != "") {

                $data = array('password' => $password, 'modified_at' => datetimeformat());
                $where = array('reset_password_token' => $tokenID);

                $affectedrow = $this->common_model->update(USER, $data, $where);
                //$affectedrow = $this->db->affected_rows();

                if ($affectedrow) {
                    // Once Requester update the password with token then here Token will be remove from db.
                    $data1 = array('reset_password_token' => '', 'modified_at' => datetimeformat());
                    $where1 = array('reset_password_token' => $tokenID);
                    $this->common_model->update(USER, $data1, $where1);
                    $msg = lang('new_password_sent');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                    redirect(ADMIN_SITE);
                } else {
                    // error
                    $msg = lang('password_token_expired');
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    //redirect('user/register');
                    redirect(ADMIN_SITE);
                }
            } else {
                // error
                $msg = lang('password_token_expired');
                $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect(ADMIN_SITE);
                //redirect('Login');
            }
        }
    }

    public function check_user() {
        $user_data = $this->login_model->get_all_data('users');
        $data['main_content'] = ADMIN_SITE . '/login/login';
        $this->load->view(ADMIN_SITE . '/assets/templatelogoin', $data);
    }

    /*
      Author : Mehul Patel
      Desc   : Check Email id is exist into DB or not
      Input  :
      Output :
      Date   : 06/06/2017
     */

    public function checkEmailId($emailID, $roleID = 0) {
        $table = USER . ' as l';
        if ($roleID == 1 || $roleID == 2) {
            $match = "l.role_type = '" . $roleID . "' AND l.email = '" . $emailID . "' AND l.is_delete = 0";
        } else {
            $match = "l.email = '" . $emailID . "' AND l.is_delete = 0";
        }

        $fields = array("l.role_type,l.status");
        $data['duplicateEmail'] = $this->common_model->get_records($table, $fields, '', '', $match);
        return $data['duplicateEmail'];
    }

    /*
      Author : Mehul Patel
      Desc   : Update Password Page
      Input  :
      Output :
      Date   : 06/06/2017
     */

    public function updatepassword() {
        $token_ID = $this->input->get('token');
        if ($token_ID != "") {
            $table1 = USER . ' as c';
            $match1 = "c.reset_password_token = '" . $token_ID . "'";
            $fields1 = array("c.user_id");
            $checkTokenexist = $this->common_model->get_records($table1, $fields1, '', '', $match1);

            if (isset($checkTokenexist[0]['user_id']) && $checkTokenexist[0]['user_id'] != "") {
                //$data['main_content'] = '/reset_password';              
                $this->load->view('reset_password');
            } else {
                redirect(ADMIN_SITE);
            }
        } else {

            redirect(ADMIN_SITE);
        }
    }

}
