<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
  Author : Rupesh Jorkar(RJ)
  Desc   : Controller for set the Language
  Input  : 
  Output : 
  Date   : 19/01/2016
 */
class Set_language extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		//$this->session->set_userdata('lang', $this->input->get('lang'));
		$this->load->helper('cookie');
		// set cookie
		$cookie = array(
			'name'   => 'languageSet',
			'value'  => $this->input->get('lang'),
			'expire' => time()+86500,
			'domain' => $_SERVER['SERVER_NAME'],
			'path'   => '/',
			'prefix' => '',
			);
		set_cookie($cookie);
		redirect($_SERVER["HTTP_REFERER"]);
	}
}
/* End of file sys_language.php */
/* Location: ./application/controllers/sys_language.php */