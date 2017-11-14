<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
|Mehul Patel
|--------------------------------------------------------------------------
|
| Set the default App Language
| 
*/

  //Loads configuration from database into global CI config
   function set_lang()
  {
	$CI =& get_instance();
	$system_lang = $CI->common_model->get_lang();

	$CI->config->set_item('language', $system_lang);

	$CI->lang->load('label', $system_lang ? $system_lang : 'english');
  }
?>