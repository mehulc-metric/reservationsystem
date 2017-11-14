<?php

/*
  @Author : Mehul Patel
  @Desc	  : Function for use Pre in Short-Cut
  @Input  : Array
  @Output : Array
  @Date	  : 06/06/2017
 */
function pr($var) {
    echo '<pre>';
    if (is_array($var)) {
        print_r($var);
    } else {
        var_dump($var);
    }
    echo '</pre>';
}

function check_customer_login()
{
    $CI = & get_instance();  //get instance, access the CI superobject
    $customerLogin = $CI->session->userdata('LOGGED_IN');   
    (!empty($customerLogin['ROLE_TYPE']))?'': redirect('Login');
}

function check_admin_login()
{
    $CI = & get_instance();  //get instance, access the CI superobject
    $adminLogin = $CI->session->userdata('reservation_admin_session'); 
    (!empty($adminLogin['admin_id']))?'':redirect('Admin');
}

if (!function_exists('lang')) {

    function lang($line, $id = '') {
        $CI = & get_instance();
        $line = $CI->lang->line($line);

        if ($id != '') {
            $line = '<label for="' . $id . '">' . $line . "</label>";
        }

        return $line;
    }

}

function setActiveSession($activeSession) {
    $CI = & get_instance();

    $CI->load->library('session');
    $sess_array =  $CI->session->all_userdata();
   
    foreach ($sess_array as $key => $val) {

        if ($key != 'session_id' && $key != $activeSession && $key != 'LOGGED_IN') { // Except Login Session
            $CI->session->unset_userdata($key);
        }
    }

}

/*
  @Author : Mehul Patel
  @Desc   :Generates Token on Form
  @Input  :
  @Output :
  @Date   : 06/06/2017
 */

function createFormToken() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $secret = md5(uniqid(rand(), true));
    $CI->session->set_userdata('FORM_SECRET', $secret);
    return $secret;
}

/*
  @Author : Mehul Patel
  @Desc   :validates Token on Form
  @Input  :
  @Output :
  @Date   : 06/06/2017
 */

function validateFormSecret() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $frmSession = $CI->session->userdata('FORM_SECRET');
    $form_secret = isset($_POST["form_secret"]) ? $_POST["form_secret"] : '';

    if (isset($frmSession)) {
        if (strcasecmp($form_secret, $frmSession) === 0) {
            /* Put your form submission code here after processing the form data, unset the secret key from the session */
            $CI->session->unset_userdata('FORM_SECRET', '');
            return true;
        } else {
            //Invalid secret key
            return false;
        }
    } else {
        //Secret key missing
        return false;
    }
}

/*
  @Author : Mehul Patel
  @Desc   : Function for Formate Date
  @Input  : Date Formate
  @Output : Date
  @Date   : 06/06/2017
 */

function datetimeformat($date = '') {
    if (!empty($date)) {
        return date("Y-m-d H:i:s", strtotime($date));
    } else {
        return date("Y-m-d H:i:s");
    }
}
/*
  @Author : Niral Patel
  @Desc   : Function for generate random password /number
  @Input  : Date Formate
  @Output : Date
  @Date   : 23/06/2017
 */
function randompassword()
{
      $chars        = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $number       = "0123456789";
      $specialchar  = "!@#$%^&*()_-=+:?";
      $password     = substr( str_shuffle( $chars ), 0, 6 ).substr( str_shuffle( $number ), 0, 1).substr( str_shuffle( $specialchar ), 0, 1);
      return $password;
}
function randomnumber()
{
      $chars        = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $number       = "0123456789";
      //$specialchar  = "!@#$%^&*()_-=+:?";
      $string     = substr( str_shuffle( $chars ), 0, 6 ).substr( str_shuffle( $number ), 0, 1);
      return $string;
}
/*
  @Author : Niral Patel
  @Desc   : Function for Formate Date
  @Input  : Date Formate
  @Output : Date
  @Date   : 13/06/2017
 */

function dateformat($date = '') {
    if (!empty($date)) {
        return date("Y-m-d", strtotime($date));
    } else {
        return date("Y-m-d");
    }
}
/*
  @Author : Niral Patel
  @Desc   : Function for Formate Date
  @Input  : Date Formate
  @Output : Date
  @Date   : 14/06/2017
 */

function displaydateformat($date = '') {
    if (!empty($date)) {
        return date("m/d/Y", strtotime($date));
    } else {
        return date("m/d/Y");
    }
}

/*
  @Author : Mehul Patel
  @Desc   : random string generate for password
  @Input  : Length
  @Output : random string
  @Date   : 06/06/2017
 */
function rand_string( $length ) {

	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	return substr(str_shuffle($chars),0,$length);
}

/*
  @Author : Mehul Patel
  @Desc   : create slug
  @Input  : string
  @Output : string
  @Date   : 06/06/2017
 */
 
function create_slug($str){
	
	$str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', "-", $str);
    return rtrim($str, '-');
}


/*
  @Author : Mehul Patel
  @Desc   : Check slug exists or not
  @Input  : slug, tablename, fieldName
  @Output : updated slug sting
  @Date   : 06/06/2017
*/

function slugExists($slug='', $tableName='', $slugFieldName=''){
	
	if(!empty($slug) && !empty($tableName) && !empty($slugFieldName)){
    
		$CI = & get_instance();
		$fields = array('count(*)');
		$matchCond = array($slugFieldName => $slug);
		
		$countSlug = $CI->common_model->get_records($tableName, $fields, '', '', $matchCond, '', '', '', '', '', '', '');
		
		if($slugExists > 0){
			$updatedSlug = $slug.'-'.$countSlug;
		}else{
			$updatedSlug = $slug;
		}
	}
}
/*
  @Author : Mehul Patel
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 13/06/2017
 */

function getUserType($role_id = null) {
    $ci = & get_instance();
    $table = ROLE_MASTER . ' as rm';
    $fields = array("rm.role_id, rm.role_name");
    $where = array('rm.status' => 1, 'rm.is_delete' => 0);    
    $data['role_option'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['role_option'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 13/06/2017
 */

function getUserTypeList() {

    $ci = & get_instance();
    $ci->db->select('*')->from(ROLE_MASTER);
    $ci->db->where('`role_id` NOT IN (SELECT `role_id` FROM `res_aauth_perm_to_group`)', NULL, FALSE);
    $ci->db->where('is_delete = 0');
    $query = $ci->db->get();
    $data['role_option'] = $query->result_array();
    return $data['role_option'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get User Type from Role Master
  @Input  :
  @Output :
  @Date   : 13/06/2017
 */

function getUserTypeAssign() {

    $ci = & get_instance();
    $ci->db->select('*')->from(ROLE_MASTER);
    $ci->db->where('`role_id` NOT IN (SELECT `role_id` FROM `res_aauth_perm_to_group`)', NULL, FALSE);
    $ci->db->where('is_delete = 0');
    $query = $ci->db->get();
    $data['role_option'] = $query->result_array();
    return $data['role_option'];
}
// Get User List from Role 
function getUserList($roleID) {
    $CI = & get_instance();
    $table3 = USER . ' as l';
    $where3 = array("l.role_type " => $roleID, "l.is_delete" => "0");
    $fields3 = array("l.user_id");
    $getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);

    return $getCountofSupportuser1;
}

function getSelectedModule($id) {
    $CI = & get_instance();
    $table3 = USER . ' as l';
    $where3 = array("l.user_id " => $id);
    $fields3 = array("l.role_type");
    $getCountofSupportuser1 = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);
    return $getCountofSupportuser1;
}

function getRoleName($role_id) {
    
    //echo "ROLE ID :".$role_id;
    $CI = & get_instance();
    $table = ROLE_MASTER . ' as rm';
    $match = "rm.role_id = " . $role_id;
    $fields = array("rm.role_name");
    $roleName = $CI->common_model->get_records($table, $fields, '', '', $match);
    //print_r($roleName);
    return $roleName;
}

/*
  @Author : Mehul Patel
  @Desc   : Helperfunction for checkpermission
  @Input  : action name
  @Output : if has permission then return true else false
  @Date   : 13/06/2017
 */

function checkPermission($controller, $method) {
    $CI = & get_instance();
    
    $system_lang = $CI->common_model->get_lang();
    $CI->config->set_item('language', $system_lang);
    $CI->lang->load('label', $system_lang ? $system_lang : 'english');

    //$CI->loginpage_redirect();  //Function added by RJ for redirection

    if (!isset($CI->router)) { # Router is not loaded
        $CI->load->library('router');
    }
    if (!isset($CI->session)) { # Sessions are not loaded
        $CI->load->library('session');
        $CI->load->library('database');
    }
    $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
    $flag = 0;
    //$class = $CI->router->fetch_class();
    $class = $controller;
    // $method = $CI->router->fetch_method();
    
    if ($CI->session->has_userdata('LOGGED_IN')) {
        $session = $CI->session->userdata('LOGGED_IN');
        $CI->db->select('module_unique_name,controller_name,name,MM.component_name');
        $CI->db->from('aauth_perm_to_group as APG');
        $CI->db->join('module_master as MM', 'MM.module_id=APG.module_id');
        $CI->db->join('aauth_perms as AP', 'AP.id=APG.perm_id');
        $CI->db->where('role_id', $session['ROLE_TYPE']);
        $CI->db->where('controller_name', $class);
        $resultData = $CI->db->get()->result_array();
        
        $configPerms = $CI->load->config('acl');
        $newArr = array();
        $permsArray = $CI->config->item($class);

        if (count($resultData) > 0) {
            $dbPermArray = array_map(function ($obj) {
                return $obj['name'];
            }, $resultData);

            foreach ($dbPermArray as $prmObj) {
                if (array_key_exists($prmObj, $permsArray)) {
                    $permArrMaster[$prmObj] = $permsArray[$prmObj];
                }
            }
            if (array_key_exists($method, $permArrMaster)) {
                /*
                 * custom code for validating project status condition whether project is completed or not
                 */
                if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {

                    if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }
    
}

/*
  @Author : Mehul Patel
  @Desc   : Helperfunction for checkpermission
  @Input  : action name
  @Output : if has permission then return true else false
  @Date   : 13/06/2017
 */

function checkAdminPermission($controller, $method) {
    $CI = & get_instance();
  
    $system_lang = $CI->common_model->get_lang();
    $CI->config->set_item('language', $system_lang);
    $CI->lang->load('label', $system_lang ? $system_lang : 'english');

    //$CI->loginpage_redirect();  //Function added by RJ for redirection

    if (!isset($CI->router)) { # Router is not loaded
        $CI->load->library('router');
    }
    if (!isset($CI->session)) { # Sessions are not loaded
        $CI->load->library('session');
        $CI->load->library('database');
    }
    $dbPermArray = $resultData = $permArrMaster = $validateArr = array();
    $flag = 0;
    //$class = $CI->router->fetch_class();
    $class = $controller;
    // $method = $CI->router->fetch_method();
    
    if ($CI->session->has_userdata('reservation_admin_session')) {
        $session = $CI->session->userdata('reservation_admin_session');
        $CI->db->select('module_unique_name,controller_name,name,MM.component_name');
        $CI->db->from('aauth_perm_to_group as APG');
        $CI->db->join('module_master as MM', 'MM.module_id=APG.module_id');
        $CI->db->join('aauth_perms as AP', 'AP.id=APG.perm_id');
        $CI->db->where('role_id', $session['admin_type']);
        $CI->db->where('controller_name', $class);
        $resultData = $CI->db->get()->result_array();
        
        $configPerms = $CI->load->config('acl');
        $newArr = array();
        $permsArray = $CI->config->item($class);

        if (count($resultData) > 0) {
            $dbPermArray = array_map(function ($obj) {
                return $obj['name'];
            }, $resultData);

            foreach ($dbPermArray as $prmObj) {
                if (array_key_exists($prmObj, $permsArray)) {
                    $permArrMaster[$prmObj] = $permsArray[$prmObj];
                }
            }
            if (array_key_exists($method, $permArrMaster)) {
                /*
                 * custom code for validating project status condition whether project is completed or not
                 */
                if ($resultData[0]['component_name'] == 'PM' && $method != 'view' && $class != 'Projectmanagement') {

                    if ($CI->session->has_userdata('PROJECT_STATUS') && $CI->session->userdata('PROJECT_STATUS') == 3) {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }
    
}



/*
  @Author : Mehul Patel
  @Desc   :  Create Dropdown
  @Input 	:  $name ,array $options,$selected
  @Output	:  Dropdown create
  @Date   : 13/06/2017
 */

function dropdown($name, array $options, $selected = null, $readonly = null, $first_option = null, $second_option = null) {
    //pr($first_option);die();
    /*     * * begin the select ** */
    $dropdown = '<select class="form-control" name="' . $name . '" id="' . $name . '" ' . $readonly . '>' . "\n";

    $selected = $selected;
    /*     * * loop over the options ** */
    if ($first_option != '') {
        $dropdown .= '<option value="">' . $first_option . '</option>' . "\n";
    }
    if ($second_option != '') {
        $select = $selected == '0' ? ' selected' : null;
        $dropdown .= '<option value="0" ' . $select . '>' . $second_option . '</option>' . "\n";
    }
    foreach ($options as $key => $option) {
        /*         * * assign a selected value ** */
        $select = $selected == $key ? ' selected' : null;

        /*         * * add each option to the dropdown ** */

        $dropdown .= '<option value="' . $key . '"' . $select . '>' . $option . '</option>' . "\n";
    }

    /*     * * close the select ** */
    $dropdown .= '</select>' . "\n";

    /*     * * and return the completed dropdown ** */
    return $dropdown;
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 14/06/2017
 */

function getCRMModuleList() {
    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}
/*
  @Author : Mehul Patel
  @Desc   : Get permission list from aauth_perms
  @Input  :
  @Output :
  @Date   : 14/06/2017
 */

function getPermsList() {

    $ci = & get_instance();
    $table = AAUTH_PERMS . ' as ap';
    $match = "";
    $fields = array("ap.id, ap.name");
    $data['permsList'] = $ci->common_model->get_records($table, $fields);
    return $data['permsList'];
}

/*
  @Author : Mehul Patel
  @Desc   : Get Module list from Module Master
  @Input  :
  @Output :
  @Date   : 14/06/2017
 */

function getModuleList() {

    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $match = "";
    $fields = array("mm.module_id, mm.component_name, mm.module_name, mm.module_unique_name, mm.status");
    $where = array('mm.status' => '1');
    $data['moduleList'] = $ci->common_model->get_records($table, $fields, '', '', '', '', '', '', '', '', '', $where);
    return $data['moduleList'];
}
/*
  @Author : Mehul Patel
  @Desc   : Get Module Status from Module Master
  @Input  :
  @Output :
  @Date   : 14/06/2017
 */

function getModuleStatus() {

    $ci = & get_instance();
    $table = MODULE_MASTER . ' as mm';
    $fields = array("mm.status");
    $data['module_option'] = $ci->common_model->get_records($table, $fields);

    return $data['module_option'];
}

// Get User's Email ID from userID 
function getUserEmailID($userID) {
    $CI = & get_instance();
    $table3 = USER . ' as l';
    $where3 = array("l.user_id " => $userID, "l.is_delete" => "0");
    $fields3 = array("l.email");
    $getEmailid = $CI->common_model->get_records($table3, $fields3, '', '', '', '', '', '', '', '', '', $where3);

    return $getEmailid;
}
/*
  @Author : Niral Patel
  @Desc   : Get date range
  @Input  :
  @Output :
  @Date   : 3/07/2017
 */
function dateRangeArray($strDateFrom, $strDateTo, $format = "Y-m-d") {
        $strDateTo = date ("Y-m-d", strtotime ("$strDateTo +1 day"));
        $begin     = new DateTime($strDateFrom);
        $end       = new DateTime($strDateTo);

        $interval  = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $range[] = $date->format ($format);
        }

        return $range;
    }
   /*
  @Author : Mehul Patel
  @Desc   : Get languages
  @Input  :
  @Output :
  @Date   : 5/07/2017
 */ 
function getLanguages() {
    $CI = & get_instance();
    $table = LANGUAGE_MASTER . ' as lm';
    $fields = array("lm.language_id,lm.language_name,lm.name");
    $order_by = 'lm.language_name';
    $order = 'ASC';
    $language_data = $CI->common_model->get_records($table, $fields, '', '', '', '', '', '', $order_by, $order);
    return $language_data;
} 

/*
  @Author : Mehul Patel
  @Desc   : getLast Inserted Reservation user
  @Input  :
  @Output :
  @Date   : 11/08/2017
 */ 
function getLastInsertedScheduleUser(){
    
    $CI = & get_instance();
	$DataBaseName = $CI->db->database;
	$sql = 'SELECT AUTO_INCREMENT as id FROM information_schema.tables WHERE Table_SCHEMA ="'.$DataBaseName.'" AND TABLE_NAME = "res_'.USER_SHEDULE_TIMESLOT.'" ';
	
	$result = $CI->db->query($sql);
	$lastNewData = $result->result_array();
	if(!empty($lastNewData)){
		$newReservedId = $lastNewData[0]['id'];
	}else{
		$newReservedId = 1;
	}
	
	return $newReservedId;
   
    /*$table = USER_SHEDULE_TIMESLOT . ' as ust';
    $fields = array("ust.user_reservation_id");
    $order = 'DESC';
    $order_by = "ust.user_reservation_id";    
    $schedule_user_data = $CI->common_model->get_records($table, $fields, '', '', '', '', 1, 0, $order_by, $order); 
	*/
	 //SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_NAME = "res_user_shedule_time_slot" 
	
	
    /*$table1 = USER_CANCEL_SHEDULE_TIMESLOT . ' as cust';
    $fields1 = array("cust.user_reservation_id");
    $order1 = 'DESC';
    $order_by1 = "cust.user_reservation_id";    
    $schedule_user_data1 = $CI->common_model->get_records($table1, $fields1, '', '', '', '', 1, 0, $order_by1, $order1); 
    
    
    if(isset($schedule_user_data[0]['user_reservation_id']) && !empty($schedule_user_data[0]['user_reservation_id'])){ // Check User schedule time slots
        $reservedId =  $schedule_user_data[0]['user_reservation_id'] + 1;
    }elseif(isset($schedule_user_data1[0]['user_reservation_id']) && !empty($schedule_user_data1[0]['user_reservation_id'])){ // check from user cancle shedule time slot
        $reservedId =  $schedule_user_data1[0]['user_reservation_id'] + 1;
    }else{
        $reservedId = 1;
    }
	
    return $reservedId;
	*/
}

function generateFormToken() {
    $CI = & get_instance();
    $CI->load->library('session'); // load Session library
    $secret = md5(uniqid(rand(), true));
    $CI->session->set_userdata('FORM_SECRET_DATA', $secret);
    return $secret;
}
/*
  @Author : Mehul Patel
  @Desc   : getCofigAmount
  @Input  :
  @Output :
  @Date   : 24/10/2017
 */ 
function getCofigAmount(){
   
    // get Config Amout
    $config_amount = 0;
    $CI = & get_instance();
    $field1 = array('*');
    $match1 = array('config_key' => 'amount');
    $data['config_amount'] = $CI->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);
    if(!empty($data['config_amount'][0]['value'])){
        $config_amount = $data['config_amount'][0]['value'];
    }else{
        $config_amount = $config_amount;
    }
    return $config_amount;
}
/*
  @Author : Mehul Patel
  @Desc   : getConfigVat
  @Input  :
  @Output :
  @Date   : 24/10/2017
 */ 
function getConfigVat(){
    //get Config VAT
    $config_vat = 0;
    $CI = & get_instance();
    $field2 = array('*');
    $match2 = array('config_key' => 'vat');
    $data['config_vat'] = $CI->common_model->get_records(CONFIG_TABLE, $field2, '', '', $match2);
    if(!empty($data['config_vat'][0]['value'])){
        $config_vat = $data['config_vat'][0]['value'];
    }else{
        $config_vat = $config_vat;
    }
    return $config_vat;
}

/*
  @Author : Mehul Patel
  @Desc   : getConfigUser
  @Input  :
  @Output :
  @Date   : 24/10/2017
 */ 
function getConfigUser(){
    //get Config Users
    $getConfigUsers = 0;
    $CI = & get_instance();
    $field2 = array('*');
    $match2 = array('config_key' => 'no_of_people_per_hour');
    $data['getConfigUser'] = $CI->common_model->get_records(CONFIG_TABLE, $field2, '', '', $match2);
    if(!empty($data['getConfigUser'][0]['value'])){
        $getConfigUsers = $data['getConfigUser'][0]['value'];
    }else{
        $getConfigUsers = $getConfigUsers;
    }
    return $getConfigUsers;
}

/*
  @Author : Maitrak Modi
  @Desc   : check duplicate zipcode
  @Input  : zipcode
  @Output : true or false
  @Date   : 25th Oct 2017
 */ 
 
function checkUniqueZipcode($zip_code){
	
	$CI = & get_instance();
	$field = array('COUNT(zip_code) as isDuplicateZipcode');
	$match = array('zip_code' => $zip_code, 'is_delete' => 0);
	
	$data['isDuplicate'] = $CI->common_model->get_records(UPLOAD_ZIP_CODE, $field, '', '', $match);
	
	if(empty($data['isDuplicate'][0]['isDuplicateZipcode'])) {
		return true;
	}else{
		return false;
	}
	
}

/*
  @Author : Mehul Patel
  @Desc   : get Population name from zip code
  @Input  : zipcode
  @Output : Population name
  @Date   : 10-11-2017
 */ 

function getPopulationName($zip_code){

    $CI = & get_instance();
    $field = array('population');
    $match = array('zip_code' => $zip_code, 'is_delete' => 0);
    $data['population_name'] = $CI->common_model->get_records(UPLOAD_ZIP_CODE, $field, '', '', $match);
    $populatioName = "";
    if(!empty($data['population_name'][0]['population'])) {
        return $populatioName = $data['population_name'][0]['population'];
    }else{
        return $populatioName;
    }
}
/*
  @Author : Mehul Patel
  @Desc   : Check Zipcode is available into our database or not
  @Input  : zipcode
  @Output : true or false
  @Date   : 10-11-2017
 */ 

function checkZipCodeisAvailable($zip_code){

    $CI = & get_instance();
    $field = array('zip_code');
    $match = array('zip_code' => $zip_code, 'is_delete' => 0);
    $data['zip_code_available'] = $CI->common_model->get_records(UPLOAD_ZIP_CODE, $field, '', '', $match);
    if(!empty($data['zip_code_available'][0]['zip_code'])) {
        return true;
    }else{
        return false;
    }
}
/*
  @Author : Mehul Patel
  @Desc   : get Transaction ID from zip code
  @Input  : zipcode
  @Output : Transaction ID
  @Date   : 10-11-2017
 */ 

function getTransactionID($zip_code){

    $CI = & get_instance();
    $field = array('transaction_id');
    $match = array('res_code' => $zip_code);
    $data['getTransactionID'] = $CI->common_model->get_records(RESERVATION_PAYMENT, $field, '', '', $match);
    $transaction_id = "";
    if(!empty($data['getTransactionID'][0]['transaction_id'])) {
        return $transaction_id = $data['getTransactionID'][0]['transaction_id'];
    }else{
        return $transaction_id;
    }
}

/*
  @Author : Mehul Patel
  @Desc   : get Trasaction Amount from zip code
  @Input  : zipcode
  @Output : Trasaction Amount 
  @Date   :10-11-2017
 */ 

function getTransactionAmount($zip_code){

    $CI = & get_instance();
    $field = array('transaction_amount');
    $match = array('res_code' => $zip_code);
    $data['getTransactionAmount'] = $CI->common_model->get_records(RESERVATION_PAYMENT, $field, '', '', $match);
    $transaction_amount = "";
    if(!empty($data['getTransactionAmount'][0]['transaction_amount'])) {
        return $transaction_amount = $data['getTransactionAmount'][0]['transaction_amount'];
    }else{
        return $transaction_amount;
    }
}

/*
  @Author : Mehul Patel
  @Desc   : get Transaction ID from zip code
  @Input  : zipcode
  @Output : Transaction ID
  @Date   : 10-11-2017
 */ 

function getrefund_transaction_ID($zip_code){

    $CI = & get_instance();
    $field = array('refund_transaction_id');
    $match = array('res_code' => $zip_code);
    $data['getTransactionID'] = $CI->common_model->get_records(RESERVATION_PAYMENT, $field, '', '', $match);
    $refund_transaction_id = "";
    if(!empty($data['getTransactionID'][0]['refund_transaction_id'])) {
        return $refund_transaction_id = $data['getTransactionID'][0]['refund_transaction_id'];
    }else{
        return $refund_transaction_id;
    }
}

/*
  @Author : Mehul Patel
  @Desc   : get Trasaction Amount from zip code
  @Input  : zipcode
  @Output : Trasaction Amount 
  @Date   :10-11-2017
 */ 

function getnet_refund_Amount($zip_code){

    $CI = & get_instance();
    $field = array('gross_refund_amount');
    $match = array('res_code' => $zip_code);
    $data['getTransactionAmount'] = $CI->common_model->get_records(RESERVATION_PAYMENT, $field, '', '', $match);
    $total_refunded_amount = "";
    if(!empty($data['getTransactionAmount'][0]['gross_refund_amount'])) {
        return $total_refunded_amount = $data['getTransactionAmount'][0]['gross_refund_amount'];
    }else{
        return $total_refunded_amount;
    }
}

?>
