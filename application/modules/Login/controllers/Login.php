<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
		
        parent::__construct();
	// check_customer_login();
        $this->viewname = $this->uri->segment(1);
        $this->load->helper(array('form'));
        //This method will have the credentials validation
        $this->load->library('form_validation');
        $this->load->library('session');      
        $this->load->model('user');     
        
    }

    public function index() {        
        $customer_session = $this->session->userdata('LOGGED_IN');   
        if (isset($customer_session['EMAIL']) && $customer_session['EMAIL'] !== "") {
          redirect('Dashboard');
        } else {            
             $this->login();
        }      
    }

    public function removed_session() {
        $session = $this->input->post('session_id');
        $where = array('id' => $session);
        $this->common_model->delete(CI_SESSION, $where);
    }

    /*
     * 	Login Function call
     */

    public function login() {
        $data['error'] = $this->session->userdata('ERRORMSG');   //Pass Error message
        $data['main_content'] = '/Login';      //Pass Content
        $data['session_id'] = session_id();
        $this->parser->parse('layouts/LoginTemplate', $data);        
    }
    /*
      Author : Mehul Patel
      Desc   : Verify login information
      Input  : Post User Email and password for verify
      Output : If login then redirect on Home page and if not then redirect on login page
      Date   : 06/06/2017
     */

    public function verifylogin() {
        
        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
      
        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            // $error_msg = 'Please enter valid credential.';
            $error_msg = ERROR_START_DIV_NEW . " Please enter valid credential." . ERROR_END_DIV;
            $this->session->set_userdata('ERRORMSG', $error_msg);
            redirect($this->viewname);
        } else {
            //Login sucessfully done so now redirect on Dashboard page
            //$login_info = $this->session->userdata('LOGGED_IN');
            $data['user_info'] = $this->session->userdata('LOGGED_IN');  //Current Login information
            redirect(base_url('Dashboard'));
        }
    }

    /*
      Author : Mehul Patel
      Desc   : This function is Call back function
      Input  : $password
      Output : Return false and true
      Date   : 06/06/2017
     */

    function check_database() {

        $browser = $_SERVER['HTTP_USER_AGENT'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $session_id = session_id();
        
        $email = quotes_to_entities($this->input->post('email'));
        $password = quotes_to_entities($this->input->post('password'));
       
        
        $timezone = $this->input->post('timezone');
        //Compare Email and password from database
        $match = "email = '" . $email . "' && password = '" . md5($password) . "' && status = 'active' && is_deleted = 0 && role_id = 2 ";
		
        $result = $this->common_model->get_records(CUSTOMER_TABLE, array("customers_id, first_name, last_name, email, role_id,profile_img"), '', '', $match);
        
        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'ID' => $row['customers_id'],
                    'EMAIL' => $row['email'],
                    'FIRSTNAME' => $row['first_name'],
                    'LASTNAME' => $row['last_name'],
                    'ROLE_TYPE' => $row['role_id'],
                    'PROFILE_PHOTO' => $row['profile_img'],
                    'TIMEZONE' => $timezone,
                    'session_id' => $session_id
                );

                $this->session->set_userdata('LOGGED_IN', $sess_array);

                $match = "login_id = '" . $row['customers_id'] . "'";
                $log_data = $this->common_model->get_records(LOG_MASTER, array("login_id, ip_address, session_id"), '', '', $match);
                foreach ($log_data as $log_result) {
                    $where = array('id' => $log_result['session_id']);
                    $this->common_model->delete(CI_SESSION, $where);
                }

                $login_id = $row['customers_id'];
                $check_login['session_id'] = $session_id;
                $check_login['login_id'] = $login_id;
                $check_login['ip_address'] = $ip_address;
                $check_login['browser'] = $browser;
                $check_login['date'] = date('Y-m-d');
               
                $this->common_model->insert(LOG_MASTER, $check_login);
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', $this->lang->line('ERROR_INVALID_CREDENTIALS'));
            return false;
        }
    }

    /*
      Author : Mehul Patel
      Desc   : Forgotpassword page redirect
      Input  :
      Output :
      Date   : 06/06/2017
     */

    public function forgotpassword() {
        $data['main_content'] = '/forgotpassword';
        $this->parser->parse('layouts/LoginTemplate', $data);      
    }

    /*
      Author : Mehul Patel
      Desc   : resetpassword prepare template and sent to requester
      Input  :
      Output :
      Date   : 06/06/2017
     */

    public function resetpassword() {
        $this->form_validation->set_error_delimiters(ERROR_START_DIV, ERROR_END_DIV);
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $msg = validation_errors();
            $this->session->set_flashdata('msgs', $msg);
            redirect('Login/forgotpassword');
        } else {
            $exitEmailId = $this->checkEmailId($this->input->post('email'));
            if (empty($exitEmailId)) {
                // error               
                $msg = 'Email is not exists.';
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");              
                redirect('Login/forgotpassword');
            } else {
                if ($this->input->post('email')) {
                    $token = md5($this->input->post('email') . date("Y-m-d H:i:s"));
                    $newpasswordlink = "<a href='" . base_url() . "Login/updatepassword?token=" . $token . "'>" . "Click Here" . "</a>";
                  
                    $find = array(
                        '{PASS_KEY_URL}',
                        '{SITE_NAME}',                       
                    );

                    $replace = array(
                        'PASS_KEY_URL' => $newpasswordlink,
                        'SITE_NAME' => base_url(),                        
                    );
                    
                    
                    $emailSubject = 'Reservation System :: Forgot Password';
                   
                    $emailBody = '<div>'
                            . '<p>Reset Your Account Password&nbsp;</p>'
                            . '<p>A request was made to reset the password for your Reservation System account. If you did not request your password to be reset, simply disregard this email and your password will continue to stay the same.&nbsp;</p>'
                            . '<p><span style="line-height: 17.1429px;">{PASS_KEY_URL} </span>&nbsp;to reset your password&nbsp;</p>'
                            . 'You are receiving this email because a user of&nbsp; {SITE_NAME} password requested again.<p>Sincerely,<br></p>'
                            . '<p>Reservation System Team</p>'
                            . '<div>';
                    
                    $finalEmailBody = str_replace($find, $replace, $emailBody);
                    $to_emailid = $this->input->post('email');
                    
                    $data = array('reset_password_token' => $token, 'updated_at' => datetimeformat());
                    $where = array('email' => $this->input->post('email'));
                    //$toEmailIds = $this->input->post('email');
                    if ($this->common_model->update(CUSTOMER_TABLE, $data, $where)) {
                        //send_mail($to, $subject, $body);
                        if ($this->common_model->sendEmail($to_emailid, $emailSubject, $finalEmailBody, FROM_EMAIL_ID)) {
                         
                            $msg = 'Password reset link has been sent to your email address.';
                        } else {
                            $msg = 'There is a problem with sending an email. Please check your mail Configuration';
                        }
                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        redirect('Login/forgotpassword');
                    } else {
                        // error
                        $msg = $this->lang->line('error_msg');
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        //redirect('user/register');
                        redirect('Login/forgotpassword');
                    }
                }
            }

            redirect('Login/forgotpassword');
        }
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
            $table1 = CUSTOMER_TABLE . ' as c';
            $match1 = "c.reset_password_token = '" . $token_ID . "'";
            $fields1 = array("c.customers_id");
            $checkTokenexist = $this->common_model->get_records($table1, $fields1, '', '', $match1);
            if (isset($checkTokenexist[0]['customers_id']) && $checkTokenexist[0]['customers_id'] != "") {
                $data['main_content'] = '/updatepassword';

                $this->parser->parse('layouts/LoginTemplate', $data);
            } else {
                redirect('Login');
            }
        } else {
            redirect('Login');
        }
    }
    /*
      Author : Mehul Patel
      Desc   : Update Password to requested person redirect to updatepassword page
      Input  :
      Output :
      Date   : 06/06/2017
     */

    public function updatePasswords() {

        $this->form_validation->set_rules('password', 'New Password', 'trim|required|md5');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|md5|matches[password]');

        if ($this->form_validation->run() == FALSE) {

            $msg = validation_errors();
            $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");

            $redirect_to = str_replace(base_url(), '', $_SERVER['HTTP_REFERER']);
            redirect($redirect_to);
        } else {

            $tokenID = $this->input->post('tokenID');
            $password = $this->input->post('password');

            if ($tokenID != "") {

                $data = array('password' => $password, 'updated_at' => datetimeformat());
                $where = array('reset_password_token' => $tokenID);

                $affectedrow = $this->common_model->update(CUSTOMER_TABLE, $data, $where);
                //$affectedrow = $this->db->affected_rows();


                if ($affectedrow) {
                    $msg = 'New Password has been set Successfully.';
                    $this->session->set_flashdata('msgs', "<div class='alert alert-success text-center'>$msg</div>");
                    // Once Requester update the password with token then here Token will be remove from db.
                    $data1 = array('reset_password_token' => '', 'updated_at' => datetimeformat());
                    $where1 = array('reset_password_token' => $tokenID);
                    $this->common_model->update(CUSTOMER_TABLE, $data1, $where1);

                    redirect('Login');
                } else {
                    // error
                    $msg = 'Your change password token is expired please try again.';
                    $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
                    //redirect('user/register');
                    redirect('Login/updatepassword');
                }
            } else {
                // error
                $msg = 'Your change password token is expired please try again.';
                $this->session->set_flashdata('msgs', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('user/register');
                //redirect('Login');
            }
        }
    }

    /*
      Author : Mehul Patel
      Desc   : Check Email id is exist into DB or not
      Input  :
      Output :
      Date   : 06/06/2017
     */

    public function checkEmailId($emailID) {
        $table = CUSTOMER_TABLE . ' as l';
        $match = "l.email = '" . $emailID . "' AND l.is_deleted = 0";
        $fields = array("l.customers_id,l.status");
        $data['duplicateEmail'] = $this->common_model->get_records($table, $fields, '', '', $match);
        return $data['duplicateEmail'];
    }

}
