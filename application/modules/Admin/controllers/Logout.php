<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function index()
	{
        $this->type = ADMIN_SITE;
        $this->viewname = $this->uri->segment(2);
	$admin_session = $this->session->userdata('reservation_admin_session');
                
        if($admin_session['active']==TRUE)
        {
            $this->session->unset_userdata('reservation_admin_session');
            $this->session->unset_userdata('name');
            $this->session->unset_userdata('admin_id');
            $this->session->unset_userdata('admin_email');
            $this->session->unset_userdata('admin_type');
            $this->session->unset_userdata('admin_image');
            $this->session->unset_userdata('active');
            
            redirect(base_url($this->type));
        }
        else
            redirect(base_url($this->type));
	}
}
