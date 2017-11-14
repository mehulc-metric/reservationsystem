<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : Maitrak Modi
  @Desc   : Cancelled Reservation Data
  @Input  :
  @Output :
  @Date   : 17th Oct 2017
 */

class CancelledReservedList_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function exportCsvData($dbSearch) {
        $this->load->dbutil();
        
        $delimiter = ",";
        $newline = "\r\n";
        $filename = date("Y-m-d") . "_cancelled_reservation.csv";
		
        $table = USER_CANCEL_SHEDULE_TIMESLOT . ' as ucs';              
        $group_by = 'ucs.reservation_code';
        $fields = array("ucs.reservation_code, gp.group_name,c.email, ht.date,DATE_FORMAT(ht.start_time, '%r') as start_time, SUM(ucs.no_of_people) as no_of_people");
        $this->db->select($fields);
        $this->db->from($table);        
        $this->db->join(USER . ' as c', 'c.user_id=ucs.user_id', 'left');
        $this->db->join(HOURLY_TIMESLOT . ' as ht', 'ht.hourly_ts_id = ucs.hourly_ts_id', 'left');
        $this->db->join(GROUP_RESERVATION . ' as gp', 'gp.group_id = ucs.group_id', 'left');
        $this->db->where($dbSearch, '', false);
        $this->db->group_by($group_by);
        $dataarr = $this->db->get();
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
		
        force_download($filename, $data1);
    }
    
}
