<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Model{
	function __construct() {
		$this->tableName = 'customers';
		$this->primaryKey = 'customers_id';
                $this->email = 'email';
	}
	public function checkUser($data = array()){
                $this->db->select($this->primaryKey);
		$this->db->select($this->email);
		$this->db->from($this->tableName);
		$this->db->where(array('email'=>$data['email'],'oauth_uid'=>$data['oauth_uid']));
		$prevQuery = $this->db->get();
		$prevCheck = $prevQuery->num_rows();
		
		if($prevCheck > 0){
			$prevResult = $prevQuery->row_array();
                     //   print_r($prevResult); exit();
			$data['updated_at'] = date("Y-m-d H:i:s");
                        $data['email'] = $prevResult['email'];                        
			$update = $this->db->update($this->tableName,$data,array('customers_id'=>$prevResult['customers_id']));
			$userID =  $prevResult['customers_id'];
		}else{
			unset($data['gender']);
			unset($data['locale']);
			unset($data['profile_url']);	
			unset($data['picture_url']);	
			$data['role_id '] = 2;
			$data['created_at'] = date("Y-m-d H:i:s");
			$data['updated_at'] = date("Y-m-d H:i:s");
			$insert = $this->db->insert($this->tableName,$data);
			$userID = $this->db->insert_id();
		}

		return $userID?$userID:FALSE;
    }
}
