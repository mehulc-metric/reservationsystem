<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Removereservation extends CI_Controller 
{
    function  __construct(){
		
        parent::__construct();        
    }
	
	/*
      @Author : Maitrak Modi
      @Desc   : Remove reservation and user, if payment has not been done in last 24 hours
      @Input  :
      @Output :
      @Date   : 6th Nov 2017
    */
	public function index(){
		
		// Only access via command line
		if(!$this->input->is_cli_request()){
			echo "This script can only be accessed via the command line" . PHP_EOL;
			return;
		}
  
		set_time_limit(0);
		
		$currentDateTime = date('Y-m-d H:i:s');
		$timeDuration = "( CURDATE() - INTERVAL 1 DAY)";
		//$timeDuration = "( now() - INTERVAL 24 HOUR)";
		
		echo "------------------------------------------- Cron Start :".$currentDateTime."------------------------------------------------<br/>";
		
		//Query
		$table = USER_SHEDULE_TIMESLOT . ' as us ';
		
        $where = array("c.is_delete" => '0', 
						"us.status" => '1', 
						"us.is_delete" => '0', 
						"us.is_payment" => '2', 
						"us.created_at >=" => $timeDuration
				);
		// now() - INTERVAL 24 HOUR;
        $fields = array("us.*");
		
        $params['join_tables'] = array(
										USER . ' as c' => 'c.user_id = us.user_id'
									);
									
        $params['join_type'] = 'left';
		
		$params['group_by'] = 'us.reservation_code';
		
		$reservationList = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', $params['group_by'],'');
		echo $this->db->last_query(); exit;
		//pr($reservationList); exit;
		
		// check data are exists or not
		if(!empty($reservationList)){
			
			foreach($reservationList as $reservationDetails){ // loop of reservation records
			
				$userId = $reservationDetails['user_id']; // user Id
				$reservationId = $reservationDetails['user_reservation_id']; // Reservation id
				
				// Remove the User
				if($this->common_model->delete(USER, array('id' => $userId))){ // Remove the user from table
					
					// Remove Reservation
					if($this->common_model->delete(USER_SHEDULE_TIMESLOT, array('user_reservation_id' => $reservationId, "user_id" => $userId))){

						echo "Record is removed successfully. UserId : <strong>".$userId." </strong> Reservation Id is : <strong>".$reservationId."</strong><br/>";
						
					}else{
						echo "Something went wrong for userId : <strong>".$userId." </strong> Reservation Id is : <strong>".$reservationId."</strong><br/>";
					}					
				}else{
					echo "Something went wrong for userId : <strong>".$userId."</strong><br/>";
				}
			}
		}else{
			echo "No Records are found <br/>";
		}
		
		echo "------------------------------------------- Cron End :".$currentDateTime."-------------------------------------------------";
	}
}