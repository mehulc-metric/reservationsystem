<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  @Author : Mehul Patel
  @Desc   : ReservedUserList
  @Input  :
  @Output :
  @Date   : 03/10/2017
 */

class ReservedUserList_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function exportCsvData($dbSearch) {
        $this->load->dbutil();
        
        $delimiter = ",";
        $newline = "\r\n";
        $filename = date("Y-m-d") . "_Reserved_User_Data.csv";
        $table = USER_SHEDULE_TIMESLOT . ' as us';              
        $group_by = 'us.reservation_code';
        $fields = array("us.reservation_code, gp.group_name,c.email, ht.date,DATE_FORMAT(ht.start_time, '%r') as start_time,(select SUM(rr.no_of_people) AS no_of_people from res_user_shedule_time_slot as rr where rr.reservation_code=us.reservation_code GROUP BY reservation_code) as no_of_people");
        $this->db->select($fields);
        $this->db->from($table);        
        $this->db->join(USER . ' as c', 'c.user_id=us.user_id', 'left');
        $this->db->join(HOURLY_TIMESLOT . ' as ht', 'ht.hourly_ts_id = us.hourly_ts_id', 'left');
        $this->db->join(GROUP_RESERVATION . ' as gp', 'gp.group_id = us.group_id', 'left');
        $this->db->where($dbSearch, '', false);
        $this->db->group_by($group_by);
        $dataarr = $this->db->get();       
        $data1 = $this->dbutil->csv_from_result($dataarr, $delimiter, $newline);
        force_download($filename, $data1);
    }
    
}
