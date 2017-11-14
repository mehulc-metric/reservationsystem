<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Model{
	function __construct() {
		$this->tableName = 'customers';
		$this->primaryKey = 'customers_id';
	}
	
	public function checkCustomer($data = array()){
		
		//echo "<pre>"; print_r($data); exit;
		
		$this->db->select($this->primaryKey);
		$this->db->from($this->tableName);
		$this->db->where(array('oauth_provider'=>$data['oauth_provider'],'oauth_uid'=>$data['oauth_uid']));
		$prevQuery = $this->db->get();
		$prevCheck = $prevQuery->num_rows();
		
		if($prevCheck > 0){
			$prevResult = $prevQuery->row_array();
			$data['updated_at'] = date('Y-m-d H:i:s');
			$update = $this->db->update($this->tableName,$data,array('customers_id'=>$prevResult['customers_id']));
			$customerID = $prevResult['customers_id'];
		}else{
			
			$insert = $this->db->insert($this->tableName,$data);
			$customerID = $this->db->insert_id();
		}

		return $customerID?$customerID:FALSE;
    }
}
