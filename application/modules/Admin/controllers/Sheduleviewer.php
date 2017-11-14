<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sheduleviewer extends CI_Controller {

    function __construct() {
        parent::__construct();

        check_admin_login();
        $this->type = ADMIN_SITE;
        $this->viewname = ucfirst($this->router->fetch_class());
        $this->load->library(array('form_validation', 'Session', 'breadcrumbs'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Add Sheduleviewer
      @Input  :
      @Output :
      @Date   : 16-6-2017
     */

    public function index() {
        // echo "Reservation Code : ".RESERVATION_CODE.getLastInsertedScheduleUser(); exit();
        //check weekly total slot
        $startDate = (date('D') == 'Sun' ? date('Y-m-d') : date('Y-m-d', strtotime('last sunday')));
        //get total slot
        $match = array('week_start_date' => $startDate);
        $totalWeekSlot = $this->common_model->get_records(WEEKLT_TOTAL_SLOT, array('total_slot_id', 'total_slot'), '', '', $match, '', '', '', '', '', '', '', '', '', '');


        //get no_of_slot_per_hour
        $field = array('*');
        $slotPerHour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', '');
        $total_slot = (!empty($totalWeekSlot[0]['total_slot'])) ? $totalWeekSlot[0]['total_slot'] : $slotPerHour[0]['value'];
        $data['slot_duration'] = 60 / $total_slot;
        $data['no_of_people_per_hour'] = $slotPerHour[1]['value'];
        $data['available_people'] = "";
        $data['main_content'] = $this->type . '/' . $this->viewname . '/add';
        $data['footerJs'][0] = base_url('uploads/custom/js/Sheduleviewer/sheduleviewer.js');
        $this->load->view($this->type . '/assets/timeshedule_template', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Add Timeshedule
      @Input  :
      @Output :
      @Date   : 14-6-2017
     */

    public function getEvents() {
        $postData = $this->input->post();
        //$diff=date_diff($postData['start'],$postData['end']);
        $diff = strtotime($postData['end']) - strtotime($postData['start']);
        $datediff = floor($diff / (60 * 60 * 24));
        $data = array();
        if ($datediff > 7) {
            //get hourly weekly hours
            $wherestring = 'ht.is_reservable =1 and (date >= "' . $postData['start'] . '" and date <= "' . $postData['end'] . '")';
            $fields = array('ht.date, COUNT(DISTINCT ht.hourly_ts_id) as total_slot , COUNT(DISTINCT us.hourly_ts_id) as user_slot');
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
            $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '', '', '', '', 'ht.date', $wherestring, '', '', '');

            // echo $this->db->last_query();exit;
            if (!empty($hourlyData)) {
                foreach ($hourlyData as $row) {

                    $color = '';
                    $sheduleData = $this->common_model->get_datewise_shedule($row['date']);

                    if ($row['user_slot'] == 0) {
                        $color = '#008000';
                    } elseif ($row['total_slot'] == $sheduleData[0]['total_asign']) {
                        $color = '#ff0000';
                    } else {
                        $color = '#ffff00';
                    }

                    $data[] = array(
                        'date' => $row['date'],
                        'title' => '&nbsp;',
                        'start' => $row['date'] . 'T12:00:00',
                        'end' => $row['date'] . 'T12:04:00',
                        'color' => $color,
                        'description' => '',
                        'calender_view_type' => 'month_view',
                    );
                }
            }
        } else {

            //get hourly weekly hours
            /* $wherestring = 'ht.is_reservable =1 and (date >= "' . $postData['start'] . '" and date <= "' . $postData['end'] . '")';
              $fields = array('u.email,gp.group_name,us.reservation_code,ht.hourly_ts_id,ht.weekly_ts_id,ht.weekly_ts_id,ht.date,ht.start_time,ht.end_time,ht.is_reservable,sum(us.no_of_people) as totalpleople,max(us.config_no_of_people) as config_no_of_people,us.no_of_group_people,us.group_id,us.no_of_people,us.user_reservation_id,(select us.no_of_people from res_user_shedule_time_slot where hourly_ts_id = ht.hourly_ts_id and group_id != 0 group by hourly_ts_id) as group_user');
              $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id', USER . ' as u' => 'u.user_id=us.user_id', GROUP_RESERVATION . ' as gp' => 'gp.group_id=us.group_id');
              $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '', '', '', '', 'ht.hourly_ts_id', $wherestring, '', '', '');
             */
            $wherestring = 'ht.is_reservable =1 and (date >= "' . $postData['start'] . '" and date <= "' . $postData['end'] . '")';
            $fields = array('us.reservation_code,ht.hourly_ts_id,ht.weekly_ts_id,ht.weekly_ts_id,ht.date,ht.start_time,ht.end_time,ht.is_reservable,sum(us.no_of_people) as totalpleople,max(us.config_no_of_people) as config_no_of_people,us.no_of_group_people,us.group_id,us.no_of_people,us.user_reservation_id,(select us.no_of_people from res_user_shedule_time_slot where hourly_ts_id = ht.hourly_ts_id and group_id != 0 group by hourly_ts_id) as group_user');
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
            $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '', '', '', '', 'ht.hourly_ts_id', $wherestring, '', '', '');

            // echo $this->db->last_query(); exit();
            if (!empty($hourlyData)) {
                //get no_of_people_per_hour
                $field = array('*');
                $match = array('config_key' => 'no_of_people_per_hour');
                $no_of_people_per_hour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);

                foreach ($hourlyData as $row) {
                    $htm = '';
                    $resArray = array();
                    $ple = $no_of_people_per_hour[0]['value'];
                    $ple = !empty($row['config_no_of_people']) ? $row['config_no_of_people'] : $ple;

                    // For Group Reservation
                    $fields_group_data = array('us.reservation_code,u.email,gp.group_name');
                    $where_group_string = 'us.hourly_ts_id = ' . $row['hourly_ts_id'] . ' AND us.group_id != 0';
                    $join_Tables = array(USER . ' as u' => 'u.user_id=us.user_id', GROUP_RESERVATION . ' as gp' => 'gp.group_id=us.group_id');
                    $hourly_group_Data = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as us', $fields_group_data, $join_Tables, 'left', '', '', '', '', '', '', '', $where_group_string, '', '', '');

                    // Single Slot Reservation
                    $fields_data = array('us.user_reservation_id,us.reservation_code,u.email,us.no_of_people');
                    $where_string = 'us.hourly_ts_id = ' . $row['hourly_ts_id'] . ' AND us.group_id = 0';
                    $join_Tables = array(USER . ' as u' => 'u.user_id=us.user_id', GROUP_RESERVATION . ' as gp' => 'gp.group_id=us.group_id');
                    $hourly_single_Data = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as us', $fields_data, $join_Tables, 'left', '', '', '', '', '', '', '', $where_string, '', '', '');

                    for ($i = 1; $i <= $ple; $i++) {
                        if ($i <= $row['totalpleople']) {
                            if (!empty($row['group_user']) && $i <= $row['group_user']) {
                                $htm .= '<span><i class="fa fa-circle fa-2x text-blue" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true"  data-title="Group Name : ' . $hourly_group_Data[0]['group_name'] . ' </br> Email : ' . $hourly_group_Data[0]['email'] . ' </br> ReservationCode : ' . $hourly_group_Data[0]['reservation_code'] . ' From : ' . date('h:i a', strtotime($row['start_time'])) . ' to ' . date('h:i a', strtotime($row['end_time'])) . ' " ></i></span>&nbsp;&nbsp;';
                            } else {
                                if (count($hourly_single_Data) > 0) {

                                    foreach ($hourly_single_Data as $sgdata) {
                                        if ($sgdata['no_of_people'] > 0) {
                                            for ($k = 1; $k <= $sgdata['no_of_people']; $k++) {
                                                if (@count(array_keys(@$resArray, @$sgdata['reservation_code'])) < $sgdata['no_of_people']) {
                                                    $htm .= '<span><i class="fa fa-circle fa-2x text-red" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" data-title=" Email : ' . $sgdata['email'] . ' </br> ReservationCode : ' . $sgdata['reservation_code'] . ' From : ' . date('h:i a', strtotime($row['start_time'])) . ' to ' . date('h:i a', strtotime($row['end_time'])) . ' "></i></span>&nbsp;&nbsp;';
                                                }
                                                $resArray[] = $sgdata['reservation_code'];
                                            }
                                        }
                                    }
                                    //  goto tes;
                                }
                            }
                        } else {
                            $htm .= '<span><i class="fa fa-circle fa-2x text-green" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" data-title="From : ' . date('h:i a', strtotime($row['start_time'])) . ' to ' . date('h:i a', strtotime($row['end_time'])) . ' " ></i></span>&nbsp;&nbsp;';
                        }
                    }
                    //   tes:
                    // Added by Mehul Patel 
                    // Check if config user full then administrator should not allow to make reservation for slot
                    if ($row['totalpleople'] < $row['config_no_of_people']) {
                        $remove_url = 0;
                    } elseif ($row['totalpleople'] == NULL || $row['config_no_of_people'] == NULL) {
                        $remove_url = 0;
                    } else {
                        $remove_url = 1;
                    }

                    $isRes = ($row['is_reservable'] == 1) ? 'Open' : 'Close';
                    $data[] = array('hourly_ts_id' => $row['hourly_ts_id'],
                        'date' => $row['date'],
                        'title' => $htm,
                        'weekly_ts_id' => $row['weekly_ts_id'],
                        'start' => $row['date'] . 'T' . $row['start_time'],
                        'start_date' => $row['date'] . ' ' . $row['start_time'],
                        'end' => ($row['end_time'] == '00:00:00') ? $row['date'] . 'T' . '23:59:59' : $row['date'] . 'T' . $row['end_time'],
                        // 'color'         => ($row['is_reservable'] == 1)?'#008000':'#ff0000',
                        'description' => 'From ' . date('h:i a', strtotime($row['start_time'])) . ' to ' . date('h:i a', strtotime($row['end_time'])),
                        'remove_url' => $remove_url,
                        'calender_view_type' => 'week_view',
                        'url' => base_url(ADMIN_SITE . '/Sheduleviewer/editRecord/' . $row['hourly_ts_id']));
                }
            }
        }

        echo json_encode($data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Get time slot week wise
      @Input  :
      @Output :
      @Date   : 19-6-2017
     */

    function getTimeSlot() {
        $postData = $this->input->post();
        //get total slot as per week 
        $wherestring = "'" . dateformat($postData['week_start_date']) . "' between week_start_date and week_end_date";
        $match = array('week_start_date' => dateformat($postData['week_start_date']));
        $totalWeekSlot = $this->common_model->get_records(WEEKLT_TOTAL_SLOT, array('total_slot_id', 'total_slot'), '', '', '', '', '', '', '', '', '', $wherestring, '', '', '');

        //get no_of_slot_per_hour
        $field = array('*');
        $match = array('config_key' => 'no_of_slot_per_hour');
        $slotPerHour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);

        $total_slot = (!empty($totalWeekSlot[0]['total_slot'])) ? $totalWeekSlot[0]['total_slot'] : $slotPerHour[0]['value'];
        echo $slot_duration = 60 / $total_slot;
    }

    /*
      @Author : Mehul Patel
      @Desc   : Add Timeshedule
      @Input  :
      @Output :
      @Date   : 19-7-2017
     */

    public function editRecord($id) {
     
        $data = array();
        $config_people = "";
        $flag_is_group = 0;
        //check weekly hours
        $match = array('hourly_ts_id' => $id);
        $data['hourlyData'] = $this->common_model->get_records(HOURLY_TIMESLOT, array('hourly_ts_id', 'weekly_ts_id', 'date', 'end_time', 'start_time', 'is_reservable'), '', '', $match, '', '', '', '', '', '', '', '', '', '');

         // get Config Amout          
        $data['config_amount'] = getCofigAmount();
        
        //get Config VAT        
        $data['config_vat'] = getConfigVat();
        
        // Check people availbility 
        if (!empty($data['hourlyData'][0]['hourly_ts_id']) && !empty($data['hourlyData'][0]['weekly_ts_id'])) {
            $match_reservable_slot = array('hourly_ts_id' => $id, 'weekly_ts_id' => $data['hourlyData'][0]['weekly_ts_id'], 'is_delete' => '0', 'status' => '1');
            $available_people = $this->common_model->get_records(USER_SHEDULE_TIMESLOT, array('SUM(no_of_people) as no_of_people', 'config_no_of_people'), '', '', $match_reservable_slot, '', '', '', '', '', '', '', '', '', '');

            if ($available_people[0]['no_of_people'] != "" && isset($available_people) && !empty($available_people)) {
                $data['available_people'] = $available_people[0]['config_no_of_people'] - $available_people[0]['no_of_people'];
            } else {
                $data['no_of_people_per_hour'] = "";
                $field = array('*');
                $match_config_people = array('config_key' => 'no_of_people_per_hour');
                $config_people = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match_config_people);
                if (isset($config_people[0]['value']) && !empty($config_people[0]['value'])) {
                    $data['available_people'] = $config_people[0]['value'];
                } else {
                    $data['no_of_people_per_hour'] = "";
                }
            }
        } else {
            $data['no_of_people_per_hour'] = "";
        }

        // Check Availbility for Group Reservation of Single slot
        if (!empty($data['hourlyData'][0]['hourly_ts_id']) && !empty($data['hourlyData'][0]['weekly_ts_id'])) {
            // get last inserted slots based on selected hours 
            // check selected user will set into next 2 slots or not 
            $hourly_ts_id[] = $id;
            $hourly_ts_id[] = $id + 1;
            $where_in = array("ht.hourly_ts_id" => $hourly_ts_id);
            $fields = array('ht.date');
            $getDate = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht ', $fields, '', '', '', '', '', '', '', '', '', '', '', $where_in);
          
            if (isset($getDate[0]['date']) && isset($getDate[1]['date'])) {

                if (strtotime($getDate[0]['date']) == strtotime($getDate[1]['date'])) {

                    $where_in1 = array("us.hourly_ts_id" => $hourly_ts_id);
                    $field_start_time = array('SUM(us.no_of_people) ');
                    $getReservedUser = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as us ', $field_start_time, '', '', '', '', '', '', '', '', '', '', '', $where_in1);
                    
                    if ((isset($getReservedUser[0]['SUM(us.no_of_people)']) && $getReservedUser[0]['SUM(us.no_of_people)'] < 1) || $getReservedUser[0]['SUM(us.no_of_people)'] == "") {
                        $flag_is_group = 1;
                    } else {
                        $flag_is_group = 0;
                    }
                } else {
                    $flag_is_group = 0;
                }
            } else {
                $flag_is_group = 0;
            }
            $data['flag_is_group'] = $flag_is_group;
        }       
       
        $this->load->view($this->type . '/' . $this->viewname . '/ajax_add', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Add Timeshedule
      @Input  :
      @Output :
      @Date   : 12-6-2017
     */

    public function insert() {

        $postData = $this->input->post();       
        // For Single slot reservation with large family 
        $field1 = array('*');
        $getResultmatch1 = array('config_key' => 'no_of_people_per_hour');
        $config1 = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $getResultmatch1);
        
        if ($postData['selected_no_user'] > $config1[0]['value']) {
          
            $checkSlotsAvailbility = $this->checkSlotAvailbilityForSingleSlotGroup($postData);
            
            if ($checkSlotsAvailbility) {

                $getResult = $this->insertSlotsForSingleSlotGroup($postData);
                
                $getResult = array_unique($getResult);
                                         
                if(!empty($getResult[0]['status'])){
                    if($getResult[0]['status'] == 1){
                         echo $msg = '1';
                    }else{
                         echo $msg = '0';
                    }
                }

            } else {

                echo $msg = '0';
            }
        } else {

            $totalDuration = $postData['total_duration'];
            $totalMinute = $postData['total_minute'];
            //check weekly hours and insert if not exist
            $match = array('week_start_date' => dateformat($postData['week_start_date']));
            $weeklyTotalData = $this->common_model->get_records(WEEKLT_TOTAL_SLOT, array('total_slot_id'), '', '', $match, '', '', '', '', '', '', '', '', '', '1');

            $no_of_people_selected = $postData['selected_no_user'];
        
            $postData['selected_total_users'] = $no_of_people_selected;
            $setPaymentFlag = 0;
            $paypal_token = NULL;
            // Check Allow to free Entry or not
            if (isset($postData['is_free_entry']) && isset($postData['is_agbar_customer'])) {
                if ($postData['is_free_entry'] == "" && $postData['is_agbar_customer'] == 0) {
                    $paypal_token = randomnumber();
                    $setPaymentFlag = 1;
                }else if($postData['is_free_entry'] == 0 && $postData['is_agbar_customer'] == 0){
                    $paypal_token = randomnumber();
                    $setPaymentFlag = 1;
                }else {
                    $paypal_token = NULL;
                    $setPaymentFlag = 0;
                }
            }
            $postData['paypal_token'] = $paypal_token;
            $postData['setPaymentFlag'] = $setPaymentFlag;
            
            if (empty($weeklyTotalData)) {
                //get no_of_slot_per_hour
                $field = array('*');
                $match = array('config_key' => 'no_of_slot_per_hour');
                $slot = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
                $weeklyData = array(
                    'total_slot' => !empty($slot[0]['value']) ? $slot[0]['value'] : '',
                    'week_start_date' => dateformat($postData['week_start_date']),
                    'week_end_date' => date('Y-m-d', (strtotime('-1 day', strtotime($postData['week_end_date'])))),
                    'created_at' => date("Y-m-d H:i:s")
                );

                $weeklyTotalId = $this->common_model->insert(WEEKLT_TOTAL_SLOT, $weeklyData);
            }

            //Insert weekly and hourly 
            $starttime = explode(':', $postData['start_time']);
            $endtime = explode(':', $postData['end_time']);
            $prevhour = '';
            $totalSlot = (($totalMinute / $totalDuration) == 0) ? 1 : ($totalMinute / $totalDuration);

            for ($i = 1; $i <= $totalSlot; $i++) {
                $stTime = (empty($etTime)) ? $postData['start_time'] : $etTime;
                $etTime = date('H:i:s', strtotime("+" . $totalDuration . " minutes", strtotime($stTime)));

                $startHour = explode(':', $stTime);

                if ($startHour[0] != $prevhour) {
                    if ($startHour[0] == 00) {
                        $startTime = '00:00';
                        $endTime = '01:00';
                    } else {
                        $startTime = $startHour[0] . ':00';
                        $endTime = date('H:i', strtotime($startTime . '+1 hour'));
                    }
                    //check weekly hours
                    $match = array('date' => dateformat($postData['date']), 'start_time' => $startTime, 'end_time' => $endTime);
                    $weeklyData = $this->common_model->get_records(WEEKLY_TIMESLOT, array('weekly_ts_id'), '', '', $match, '', '', '', '', '', '', '', '', '', '');

                    if (!empty($weeklyData)) {
                        $weeklyId = $weeklyData[0]['weekly_ts_id'];
                    } else { //insert weekly slot
                        $weeklyData = array(
                            'date' => dateformat($postData['date']),
                            'start_time' => $startTime,
                            'is_open' => $postData['slot_type'],
                            'end_time' => $endTime,
                            'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                            'created_at' => date("Y-m-d H:i:s")
                        );
                        $weeklyId = $this->common_model->insert(WEEKLY_TIMESLOT, $weeklyData);
                    }
                }

                //get total exist or not
                $match = array('date' => dateformat($postData['date']), 'start_time' => $stTime, 'end_time' => $etTime);
                $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
                $hourlyCount = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', array('ht.weekly_ts_id,ht.hourly_ts_id,count(us.hourly_ts_id) as totalhour'), $joinTables, 'left', $match, '', '', '', '', '', '', '', '', '', '');

                //insert
                if (empty($hourlyCount[0]['hourly_ts_id']) && $hourlyCount[0]['totalhour'] == 0) {
                    $hourlyData[] = array(
                        'weekly_ts_id' => $weeklyId,
                        'date' => dateformat($postData['date']),
                        'start_time' => $stTime,
                        'end_time' => $etTime,
                        'is_reservable' => $postData['slot_type'],
                        'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                        'created_at' => date("Y-m-d H:i:s")
                    );
                    $this->common_model->insert_batch(HOURLY_TIMESLOT, $hourlyData);
                    $hourlyId = $this->db->insert_id();
                } else { //update
                    if ($hourlyCount[0]['totalhour'] == 0) {
                        $hourlyUpdateData = array(
                            // 'hourly_ts_id' => $hourlyCount[0]['hourly_ts_id'],
                            'is_reservable' => $postData['slot_type'],
                            'modified_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                            'modified_at' => date("Y-m-d H:i:s")
                        );
                        $where_up = array('hourly_ts_id' => $hourlyCount[0]['hourly_ts_id']);
                        $this->common_model->update(HOURLY_TIMESLOT, $hourlyUpdateData, $where_up);

                        $hourlyData[] = array(
                            'weekly_ts_id' => $weeklyId,
                            'date' => dateformat($postData['date']),
                            'start_time' => $stTime,
                            'end_time' => $etTime,
                            'is_reservable' => $postData['slot_type'],
                            'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                            'created_at' => date("Y-m-d H:i:s")
                        );

                        $hourlyId = $hourlyCount[0]['hourly_ts_id'];
                    }
                }
                $prevhour = $startHour[0];
            }
            //insert hourly slot
            if (!empty($hourlyData)) {
                // Insert User slot for reservation done by admin for single slot
                $hourly_ts_id = $hourlyId;
                $current_date_time = $postData['current_time'];
                $email_id = $postData['email_id'];
                $no_of_people = $postData['selected_no_user'];
                $weekly_ts_id = $weeklyId;

                $reservationCode = RESERVATION_CODE.getLastInsertedScheduleUser();
                $cancellationCode = randomnumber();
                $result = $this->insertUserSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id,$reservationCode,$cancellationCode,$postData);
            }
            echo $msg = '1';
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Signle slot reservation on pre sechulded slots based on availbility
      @Input  :
      @Output :
      @Date   : 21-7-2017
     */

    public function update() {
        $postData = $this->input->post();
        $no_of_people = $postData['selected_no_user'];
        $field1 = array('*');
        $match1 = array('config_key' => 'no_of_people_per_hour');
        $config1 = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);
        $postData['selected_total_users'] = $no_of_people;
        $setPaymentFlag = 0;
        // Check Allow to free Entry or not
        if (isset($postData['is_free_entry']) && isset($postData['is_agbar_customer'])) {
            if ($postData['is_free_entry'] == "" && $postData['is_agbar_customer'] == 0) {
                $paypal_token = randomnumber();
                $setPaymentFlag = 1;
            } else {
                $paypal_token = NULL;
                $setPaymentFlag = 0;
            }
        }
        $postData['paypal_token'] = $paypal_token;
        $postData['setPaymentFlag'] = $setPaymentFlag;
 
        if ($postData['selected_no_user'] > $config1[0]['value']) {
            $fl = 1;
            $cancellationCode = randomnumber();
            $reservationCode = RESERVATION_CODE . getLastInsertedScheduleUser();
            $result_set = array();
            for ($i = 1; $i <= 2; $i++) {
                if ($fl == 1) {
                    $postData['selected_no_user'] = $config1[0]['value'];
                    $postData['cancellation_code'] = $cancellationCode;
                    $postData['reservation_code'] = $reservationCode;
                    $postData['flag'] = $fl;
                    $result_set[] = $this->update_for_group($postData);
                } else {

                    $postData['selected_no_user'] = ($no_of_people - $config1[0]['value']);
                    $postData['hourly_ts_id'] = $postData['hourly_ts_id'] + 1;
                    $postData['reservation_code'] = $reservationCode;
                    $postData['cancellation_code'] = $cancellationCode;
                    $postData['flag'] = $fl;

                    $result_set[] = $this->update_for_group($postData);
                }
                $fl++;
            }
        } else {
            $reservationCode = RESERVATION_CODE . getLastInsertedScheduleUser();
            $cancellationCode = randomnumber();
            $postData['reservation_code'] = $reservationCode;
            $postData['cancellation_code'] = $cancellationCode;
            $postData['flag'] = 1;
            $result_set[] = $this->update_for_group($postData);
        }
        $result_set = array_unique($result_set);

        if (!empty($result_set[0]['status'])) {
            if ($result_set[0]['status'] == 1) {
                echo $msg = '1';
            } else {
                echo $msg = '0';
            }
        }

        //  echo json_encode(array('result' => $result_set));
    }

    /*
      @Author : Mehul Patel
      @Desc   : Signle slot reservation on pre sechulded slots based on availbility
      @Input  :
      @Output :
      @Date   : 21-7-2017
     */

    public function update_for_group($postData) {
        
        $match = array('hourly_ts_id' => $postData['hourly_ts_id']);
        $data['user_booked_slots'] = $this->common_model->get_records(USER_SHEDULE_TIMESLOT, array('hourly_ts_id', 'weekly_ts_id'), '', '', $match, '', '', '', '', '', '', '', '', '', '');
      
        // check if slots available as per selected user or not 
        if (isset($data['user_booked_slots']) && !empty($data['user_booked_slots'])) {
            $current_date_time = $postData['current_time'];
            $email_id = $postData['email_id'];
            $no_of_people = $postData['selected_no_user'];
            $weekly_ts_id = $data['user_booked_slots'][0]['weekly_ts_id'];
            $hourly_ts_id = $data['user_booked_slots'][0]['hourly_ts_id'];
        } else {
            // if slot has been schedule as reservable and there is no reservation done on selected slot then use bellow query
            $match1 = array('hourly_ts_id' => $postData['hourly_ts_id'], 'is_reservable' => '1');
            $data1['hourlyData'] = $this->common_model->get_records(HOURLY_TIMESLOT, array('hourly_ts_id', 'weekly_ts_id'), '', '', $match1, '', '', '', '', '', '', '', '', '', '');

            if (isset($data1['hourlyData']) && !empty($data1['hourlyData'])) {
                $current_date_time = $postData['current_time'];
                $email_id = $postData['email_id'];
                $no_of_people = $postData['selected_no_user'];
                $weekly_ts_id = $data1['hourlyData'][0]['weekly_ts_id'];
                $hourly_ts_id = $data1['hourlyData'][0]['hourly_ts_id'];
            }
        }
        
        $result = $this->insertUserSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id,$postData['reservation_code'],$postData['cancellation_code'],$postData);
        return $result;

    }

    /*
      @Author : Mehul Patel
      @Desc   : Usershedule Insert
      @Input  :
      @Output :
      @Date   : 20/07/2017
     */

    public function insertUserSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id, $reservation_code, $cancellation_code,$postData) {
        
        if (!empty($email_id)) {

            //get hourly weekly hours
            $wherestring = 'h.is_reservable =1 and h.hourly_ts_id = ' . $hourly_ts_id . ' and concat(h.date," ",h.start_time) >= "' . $current_date_time . '"';
            $fields = array('(max(u.config_no_of_people) - sum(u.no_of_people)) as available,max(u.config_no_of_people) as config_no_of_people,h.hourly_ts_id');
            $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) = " . $no_of_people;
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as u' => 'u.hourly_ts_id=h.hourly_ts_id');
            $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as h', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, '', '', '');

            //server side validation slot available on given post data
            if (!empty($hourlyData[0]['hourly_ts_id'])) {
                //get current config no of prople
                $field = array('*');
                $match = array('config_key' => 'no_of_people_per_hour');
                $userPerHour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
                if ($hourlyData[0]['available'] == '' && $hourlyData[0]['config_no_of_people'] == '') {
                    $hourlyData[0]['available'] = $no_of_people;
                }
                //  echo  $hourlyData[0]['available']; exit();
                if (!empty($hourlyData[0]['available']) && $hourlyData[0]['available'] >= $no_of_people) {

                    //get user id
                    $field = array('user_id');
                    $match = array('email' => trim($email_id));
                    $usercheck = $this->common_model->get_records(USER, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');
                 
                    if (empty($usercheck)) {
                        //Insert user
                        $userData = array(
                            'email' => trim($email_id),
                            'password' => md5(randompassword()),
                            'role_type' => 5,
                            'paypal_token' => $postData['paypal_token'],
                            'created_at' => datetimeformat()
                        );

                        $userId = $this->common_model->insert(USER, $userData);
                    } else {
                        $userId = $usercheck[0]['user_id'];
                    }

                    $reservationCode = $reservation_code;
                    $cancellationCode = $cancellation_code;       
                    //generate qrcode
                    $this->load->library('Encryption');
                    $converter = new Encryption;

                    $this->load->library('ci_qr_code');
                    $qrCodeConfig = array();
                    $qrCodeConfig['cacheable'] = $this->config->item('cacheable');
                    $qrCodeConfig['cachedir'] = $this->config->item('cachedir');
                    $qrCodeConfig['imagedir'] = $this->config->item('imagedir');
                    $qrCodeConfig['errorlog'] = $this->config->item('errorlog');
                    $qrCodeConfig['ciqrcodelib'] = $this->config->item('ciqrcodelib');
                    $qrCodeConfig['quality'] = $this->config->item('quality');
                    $qrCodeConfig['size'] = $this->config->item('size');
                    $qrCodeConfig['black'] = $this->config->item('black');
                    $qrCodeConfig['white'] = $this->config->item('white');

                    $this->ci_qr_code->initialize($qrCodeConfig);
                    
                    $population_name = "";
                    $is_agbarCustomer = lang('no');
                    if($postData['zip_code'] != ""){
                        $population_name = getPopulationName($postData['zip_code']); 
                        if(checkZipCodeisAvailable($postData['zip_code'])){
                            $is_agbarCustomer = lang('yes');
                        }else{
                             $is_agbarCustomer = lang('no');
                        }
                    }else{
                        $population_name = $population_name;
                        $is_agbarCustomer = lang('no');
                    }

                    $qrcodeName = 'qr' . $userId . '_' . time() . '.png';

                    //get schedule time
                    $wherestring = "ht.is_reservable =1 and ht.hourly_ts_id = '" . $hourly_ts_id . "'";
                    $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                    $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, '', '', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');

                    //Create pdf file
                    //load mPDF library
                    $this->load->library('M_pdf');

                    $pdfdata['pdfdata'] = array(
                        'title' => 'Reservation Details',
                        'email' => $email_id,
                        'no_of_people' => $no_of_people,
                        'reservation_code' => $reservationCode,
                        'cancellation_code' => $cancellationCode,
                        'datetime' => $hourlyData[0]['date'] . ' - ' . $hourlyData[0]['start_time'],
                        'zip_code' => $postData['zip_code'],
                        'population_name' => $population_name,
                        'is_agbarCustomer' => $is_agbarCustomer,
                        'qr_code' => $this->config->item('imagedir') . $qrcodeName
                    );
                 
                    // Create QR code 
                    // $params['data'] = $converter->encode ($reservationCode.'time'.$postData['hourly_ts_id']);
                    $params['data'] = $reservationCode . "," . $cancellationCode . "," . $email_id . "," . $no_of_people . "," . $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time']. "," . $postData['zip_code'] ;
                    $params['level'] = 'H';
                    $params['size'] = 10;
                    $params['savename'] = $qrCodeConfig['imagedir'] . $qrcodeName;
                    $this->ci_qr_code->generate($params);

                    //now pass the data //
                    $html = $this->load->view($this->viewname . '/pdfView', $pdfdata, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
                    //this the the PDF filename that user will get to download

                    $pdfDirPath = $this->config->item('pdf_base_path');
                    if (!is_dir($pdfDirPath)) {
                        //create directory
                        mkdir($pdfDirPath, 0777, TRUE);
                    }

                    sleep(1);
                    $pdfname = "pdf-" . time() . ".pdf";
                    $pdfFilePath = $pdfDirPath . $pdfname;
             //actually, you can pass mPDF parameter on this load() function
                    //$mpdf = new mPDF('utf-8', 'A4');
                    $mpdf = new mPDF('utf-8', 'A4', 0, '', 3.1, 3.1, 3.1, 3.1, 0, 0);
                    $mpdf->WriteHTML($html);
                    $mpdf->Output($pdfFilePath, 'F');
                    //end pdf file code
                    //Insert user slot
                    $is_payment = '0';
                    if($postData['setPaymentFlag'] == 0){
                        $is_payment = '0';
                    }else{
                         $is_payment = '2';
                    }
                    $data = array(
                        'user_id' => $userId,
                        'weekly_ts_id' => $weekly_ts_id,
                        'hourly_ts_id' => $hourly_ts_id,
                        'no_of_people' => $no_of_people,
                        'config_no_of_people' => !empty($userPerHour[0]['value']) ? $userPerHour[0]['value'] : 0,
                        'reservation_code' => $reservationCode,
                        'qr_code' => $qrcodeName,
                        'pdf_file_name' => $pdfname,
                        'cancellation_code' => $cancellationCode,
                        'zip_code' => $postData['zip_code'],
                        'is_payment' => $is_payment,
                        'created_at' => datetimeformat()
                    );
                
                    $response = array();
                    
               
                    
                    if ($this->common_model->insert(USER_SHEDULE_TIMESLOT, $data)) {
                        
                        if($postData['setPaymentFlag'] == 0){
                            $this->sendMailToUser($data['reservation_code'], $email_id, $no_of_people, $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'], $data['cancellation_code'], $this->config->item('pdf_upload_path') . $pdfname,$postData['zip_code']);
                        }else{
                            $this->sendMailToUserToPayment($email_id, $postData['selected_total_users'], $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'], $postData['paypal_token']);
                            
                        }
                                                
                        $response = array('status' => 1);
                    
                    } else {
                        $response = array('status' => 0);
                    
                    }
                } else {
                    $response = array('status' => 0);
                   
                }
            } else {
                $response = array('status' => 0);
                
            }
        } else {
            $response = array('status' => 0);
          
        }
        return $response;
    }

    /*
      @Author : Niral Patel
      @Desc   : SendMail To User
      @Input  : $reservation_code,$email,$no_of_people,$reserved_date
      @Output :
      @Date   : 26/06/2017
     */

    private function sendMailToUser($reservation_code, $email, $no_of_people, $reserved_date, $cancellation_code, $attach,$zip_code, $end_time = "") {

        if (!empty($email)) {

            if ($end_time != "") {
                $find = array(
                    '{RESERVATION_CODE}',
                    '{EMAIL}',
                    '{NO_OF_PEOPLE}',
                    '{RESERVED_DATE}',
                    '{END_TIME}',
                    '{ZIP_CODE}',
                    '{CANCELLATION_CODE}'
                );
                $replace = array(
                    'RESERVATION_CODE' => $reservation_code,
                    'EMAIL' => $email,
                    'NO_OF_PEOPLE' => $no_of_people,
                    'RESERVED_DATE' => date("m/d/Y h:i a", strtotime($reserved_date)),
                    'END_TIME' => date("h:i a", strtotime($end_time)),
                    '{ZIP_CODE}' => $zip_code,
                    'CANCELLATION_CODE' => $cancellation_code
                );
            } else {

                $find = array(
                    '{RESERVATION_CODE}',
                    '{EMAIL}',
                    '{NO_OF_PEOPLE}',
                    '{RESERVED_DATE}',
                    '{ZIP_CODE}',
                    '{CANCELLATION_CODE}'
                );
                $replace = array(
                    'RESERVATION_CODE' => $reservation_code,
                    'EMAIL' => $email,
                    'NO_OF_PEOPLE' => $no_of_people,
                    'RESERVED_DATE' => $reserved_date,
                    '{ZIP_CODE}' => $zip_code,
                    'CANCELLATION_CODE' => $cancellation_code
                );
            }


            $emailSubject = lang('reservation_system').' : '. lang('reservation_confrim') ;
            if ($end_time != "") {
                $emailBody = '<div>'
                        . '<p>'. lang('hello').'</p> '
                        . '<p>'. lang('your_reservation_schedule').'</p> '
                        . '<p>'. lang('reservation_code').' : {RESERVATION_CODE} </p> '
                        . '<p>'. lang('cancellation_code').' : {CANCELLATION_CODE} </p> '
                        . '<p>'. lang('reservation_email_id').' : {EMAIL}</p> '
                        . '<p>'. lang('number_of_people').' : {NO_OF_PEOPLE}</p> '
                        . '<p>'. lang('reservation_date_time').' : {RESERVED_DATE} - {END_TIME}</p> '
                        . '<p>'. lang('MY_PROFILE_PINCODE').' : {ZIP_CODE}</p> '
                        . '<p>'. lang('please_find_attachment').'</p> '
                        . '<p>'. lang('Sincerely').'<br></p> '
                        . '<p>'. lang('reservation_team').'</p> '
                        . '<div>';
            } else {
                $emailBody = '<div>'
                        . '<p>'. lang('hello').'</p> '
                        . '<p>'. lang('your_reservation_schedule').'</p> '
                        . '<p>'. lang('reservation_code').' : {RESERVATION_CODE} </p> '
                        . '<p>'. lang('cancellation_code').' : {CANCELLATION_CODE} </p> '
                        . '<p>'. lang('reservation_email_id').' : {EMAIL}</p> '
                        . '<p>'. lang('number_of_people').' : {NO_OF_PEOPLE}</p> '
                        . '<p>'. lang('reservation_date_time').' : {RESERVED_DATE}</p> '
                        . '<p>'. lang('MY_PROFILE_PINCODE').' : {ZIP_CODE}</p> '
                        . '<p>'. lang('please_find_attachment').'</p> '
                        . '<p>'. lang('Sincerely').'<br></p> '
                        . '<p>'. lang('reservation_team').'</p> '
                        . '<div>';
            }



            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($email, $emailSubject, $finalEmailBody, FROM_EMAIL_ID, '', '', $attach);
        }
        return true;
    }

       /*
      @Author : Niral Patel
      @Desc   : SendMail To User
      @Input  : $reservation_code,$email,$no_of_people,$reserved_date
      @Output :
      @Date   : 26/06/2017
     */

    private function sendMailToUserToPayment($email, $no_of_people, $reserved_date, $paymentToken, $end_time = "") {

        if (!empty($email)) {

            $paymentLink = "<a href='" . base_url() . "Usershedule/Payinfo?token=" . $paymentToken . "'>" . "Pay Here" . "</a>";

            // Amount Calclucation
            // get Config Amout          
            $config_amount = getCofigAmount();

            //get Config VAT        
            $config_vat = getConfigVat();
            
            $getVal = $no_of_people * $config_amount;
            $get_val_personatage = ($getVal * $config_vat) / 100;
            $final = $getVal + $get_val_personatage;
            $final_amount = round($final, 2);

            $final_amount = $final_amount . "€";

            if ($end_time != "") {

                $find = array(
                    '{EMAIL}',
                    '{NO_OF_PEOPLE}',
                    '{RESERVED_DATE}',
                    '{END_TIME}',
                    '{FINAL_AMOUNT}',
                    '{PAYMENT_LINK}'
                );
                $replace = array(
                    'EMAIL' => $email,
                    'NO_OF_PEOPLE' => $no_of_people,
                    'RESERVED_DATE' => date("m/d/Y h:i a", strtotime($reserved_date)),
                    'END_TIME' => date("h:i a", strtotime($end_time)),
                    '{FINAL_AMOUNT}' => $final_amount,
                    'PAYMENT_LINK' => $paymentLink
                );
            } else {
                $find = array(
                    '{EMAIL}',
                    '{NO_OF_PEOPLE}',
                    '{RESERVED_DATE}',
                    '{FINAL_AMOUNT}',
                    '{PAYMENT_LINK}'
                );
                $replace = array(
                    'EMAIL' => $email,
                    'NO_OF_PEOPLE' => $no_of_people,
                    'RESERVED_DATE' => $reserved_date,
                    'FINAL_AMOUNT' => $final_amount,
                    'PAYMENT_LINK' => $paymentLink
                );
            }


            $emailSubject = lang('reservation_system').' : '. lang('reservation_confrim') ;
            if ($end_time != "") {
                $emailBody = '<div>'
                        . '<p>'. lang('hello').'</p> '
                        . '<p>'.lang('your_reservation_schedule').'</p> '
                        . '<p>'. lang('reservation_email_id').' : {EMAIL}</p> '
                        . '<p>'. lang('number_of_people').' : {NO_OF_PEOPLE}</p> '
                        . '<p>'. lang('reservation_date_time').' : {RESERVED_DATE} - {END_TIME}</p> '
                        . '<p>'. lang('Final Amount').' : {FINAL_AMOUNT}</p> '
                        . '<p>'. lang('pay_here').' : {PAYMENT_LINK}</p> '
                        . '<p>'. lang('payment_note').'</p> '
                        . '<p>'. lang('Sincerely').'<br></p> '
                        . '<p>'. lang('reservation_team').'</p> '
                        . '<div>';
            } else {
                $emailBody = '<div>'
                        . '<p>'. lang('hello').'</p> '
                        . '<p>'.lang('your_reservation_schedule').'</p> '
                        . '<p>'. lang('reservation_email_id').' : {EMAIL}</p> '
                        . '<p>'. lang('number_of_people').' : {NO_OF_PEOPLE}</p> '
                        . '<p>'. lang('reservation_date_time').' : {RESERVED_DATE}</p> '
                        . '<p>'. lang('Final Amount').' : {FINAL_AMOUNT}</p> '
                        . '<p>'. lang('pay_here').' : {PAYMENT_LINK}</p> '
                        . '<p>'. lang('payment_note').'</p> '
                        . '<p>'. lang('Sincerely').'<br></p> '
                        . '<p>'. lang('reservation_team').'</p> '
                        . '<div>';
            }



            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($email, $emailSubject, $finalEmailBody, FROM_EMAIL_ID, '', '', $attach);
        }
        return true;
    }

    
    /*
      @Author : Mehul Patel
      @Desc   : check Slot availblility for group
      @Input  :
      @Output :
      @Date   : 24/7/2017
     */

    public function checkSlotAvailbilityForGroup() {

        $postData = $this->input->post();
  
        $totalDuration = $postData['total_duration'];
        $totalMinute = $postData['total_minute'];
        $slotISavailable = FALSE;
        $flag = TRUE;
        $field1 = array('*');
        $match1 = array('config_key' => 'no_of_slot_per_hour');
        $config1 = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);

        if ($totalDuration == "") {
            $totalDuration = 60 / $config1[0]['value'];
        }

        $field = array('*');
        $match = array('config_key' => 'no_of_people_per_hour');
        $config = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);

        if ($postData['number_of_user'] <= $config[0]['value']) {
            $slotISavailable = TRUE;
        } else {
            $slot_allocate_to_user = $postData['number_of_user'] / $config[0]['value'];

            $totalSlot = ($slot_allocate_to_user > 0) ? $slot_allocate_to_user : 1;

            if (ceil($totalSlot) >= 1) {

                for ($i = 1; $i <= ceil($totalSlot); $i++) {
                    $stTime = (empty($etTime)) ? $postData['start_time'] : $etTime;
                    $etTime = date('H:i:s', strtotime("+" . $totalDuration . " minutes", strtotime($stTime)));
                    if ($stTime == "00:00:00" && $postData['start_time'] != "00:00:00") {
                        $flag = FALSE;
                        break;
                    }
                }

                $where_string = 'ht.date = "' . dateformat($postData['startDate']) . '" and ht.start_time >= "' . $postData['start_time'] . '" and ht.end_time <= "' . $etTime . '" and ht.start_time<=ht.end_time ';
                $fields = array('SUM(us.no_of_people) as no_of_people');
                $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
                $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '', '', '', '', '', $where_string, '', '', '');

                if (strlen($hourlyData[0]['no_of_people']) === 0) {
                    $slotISavailable = TRUE;
                } else {
                    $slotISavailable = FALSE;
                }
            } else {
                $slotISavailable = TRUE;
            }
        }

        if (($slotISavailable == TRUE || $slotISavailable == 1) && ($flag == TRUE || $flag == 1)) {
            echo $msg = '1';
        } else {
            echo $msg = '0';
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Insert slot for Group
      @Input  :
      @Output :
      @Date   : 24/7/2017
     */

    function insertSlotsForGroup() {

        $postData = $this->input->post();
        $totalDuration = $postData['total_duration'];
        $totalMinute = $postData['total_minute'];

        $field_config = array('*');
        $match_config = array('config_key' => 'no_of_people_per_hour');
        $config = $this->common_model->get_records(CONFIG_TABLE, $field_config, '', '', $match_config);
        $slot_allocate_to_user = $postData['number_of_user'] / $config[0]['value'];
        // Get total sots need to be insert for group 
        $total_Slot = ($slot_allocate_to_user > 0) ? $slot_allocate_to_user : 1;

        $field1 = array('*');
        $match1 = array('config_key' => 'no_of_slot_per_hour');
        $config1 = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);

        if ($totalDuration == "") {
            $totalDuration = 60 / $config1[0]['value'];
        }

        if (ceil($total_Slot) >= 1) {
            // based on total slot calculate total minutes of each slots
            if ($totalMinute != "") {
                $totalMinute = $totalMinute * ceil($total_Slot);
            } else {
                $totalMinute = $totalDuration * ceil($total_Slot);
            }

            $st_Time = "";
            $et_Time = "";
            for ($i = 1; $i <= ceil($total_Slot); $i++) {
                $st_Time = (empty($et_Time)) ? $postData['start_time'] : $et_Time;
                $et_Time = date('H:i:s', strtotime("+" . $totalDuration . " minutes", strtotime($st_Time)));
            }
            if ($et_Time != "") {
                $postData['end_time'] = $et_Time; // if end time is not define then calculate end time from slot and Start time
            }
            // Prepare data for User assign into slots 
            $userGroup = $postData['number_of_user'];
            $user_per_slot = $config[0]['value'];
            $total_group = ceil($total_Slot);
            $max = $total_group;
            $min = (int) $total_Slot;
            $diff = 0;
        }

        //check weekly hours and insert if not exist
        $match = array('week_start_date' => dateformat($postData['week_start_date']));
        $weeklyTotalData = $this->common_model->get_records(WEEKLT_TOTAL_SLOT, array('total_slot_id'), '', '', $match, '', '', '', '', '', '', '', '', '', '1');

        if (empty($weeklyTotalData)) {
            //get no_of_slot_per_hour
            $field = array('*');
            $match = array('config_key' => 'no_of_slot_per_hour');
            $slot = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
            $weeklyData = array(
                'total_slot' => !empty($slot[0]['value']) ? $slot[0]['value'] : '',
                'week_start_date' => dateformat($postData['week_start_date']),
                'week_end_date' => date('Y-m-d', (strtotime('-1 day', strtotime($postData['week_end_date'])))),
                'created_at' => date("Y-m-d H:i:s")
            );

            $weeklyTotalId = $this->common_model->insert(WEEKLT_TOTAL_SLOT, $weeklyData);
        }

        //Insert weekly and hourly 
        $starttime = explode(':', $postData['start_time']);
        $endtime = explode(':', $postData['end_time']);
        $prevhour = '';

        $totalSlot = (($totalMinute / $totalDuration) == 0) ? 1 : ($totalMinute / $totalDuration);

        // Generate QR code for Group Reservation         
        $generateQR = $this->generateQRcodeForGroup($postData['email_id'], $postData['group_name'], $postData['number_of_user'], $postData['date'], $postData['start_time'], $postData['end_time'],$postData['group_zip_code']);

        for ($i = 1; $i <= $totalSlot; $i++) {
            $stTime = (empty($etTime)) ? $postData['start_time'] : $etTime;
            $etTime = date('H:i:s', strtotime("+" . $totalDuration . " minutes", strtotime($stTime)));

            $startHour = explode(':', $stTime);

            if ($startHour[0] != $prevhour) {
                if ($startHour[0] == 00) {
                    $startTime = '00:00';
                    $endTime = '01:00';
                } else {
                    $startTime = $startHour[0] . ':00';
                    $endTime = date('H:i', strtotime($startTime . '+1 hour'));
                }
                //check weekly hours
                $match = array('date' => dateformat($postData['date']), 'start_time' => $startTime, 'end_time' => $endTime);
                $weeklyData = $this->common_model->get_records(WEEKLY_TIMESLOT, array('weekly_ts_id'), '', '', $match, '', '', '', '', '', '', '', '', '', '');

                if (!empty($weeklyData)) {
                    $weeklyId = $weeklyData[0]['weekly_ts_id'];
                } else { //insert weekly slot
                    $weeklyData = array(
                        'date' => dateformat($postData['date']),
                        'start_time' => $startTime,
                        'is_open' => $postData['slot_type'],
                        'end_time' => $endTime,
                        'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                        'created_at' => date("Y-m-d H:i:s")
                    );
                    $weeklyId = $this->common_model->insert(WEEKLY_TIMESLOT, $weeklyData);
                }
            }

            //get total exist or not
            $match = array('date' => dateformat($postData['date']), 'start_time' => $stTime, 'end_time' => $etTime);
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
            $hourlyCount = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', array('ht.weekly_ts_id,ht.hourly_ts_id,count(us.hourly_ts_id) as totalhour'), $joinTables, 'left', $match, '', '', '', '', '', '', '', '', '', '');

            //insert
            if (empty($hourlyCount[0]['hourly_ts_id']) && $hourlyCount[0]['totalhour'] == 0) {

                $hourlyData = array(
                    'weekly_ts_id' => $weeklyId,
                    'date' => dateformat($postData['date']),
                    'start_time' => $stTime,
                    'end_time' => $etTime,
                    'is_reservable' => $postData['slot_type'],
                    'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                    'created_at' => date("Y-m-d H:i:s")
                );

                if (!empty($hourlyData)) {

                    $hourlyId = $this->common_model->insert(HOURLY_TIMESLOT, $hourlyData);
                }

                // Prepare user count per slot 
                $diff = $userGroup - ($user_per_slot * $i);
                if ($diff < 0) {
                    $groupAmt = ($user_per_slot + $diff);
                } else {
                    $groupAmt = $config[0]['value'];
                }

                $current_date_time = $postData['current_time'];
                $email_id = $postData['email_id'];
                $no_of_people = $groupAmt;
                $weekly_ts_id = $weeklyId;
                $hourly_ts_id = $hourlyId;
                $group_name = $postData['group_name'];
                $total_group_user = $postData['number_of_user'];
                $end_time = $postData['end_time'];
                $group_zip_code = $postData['group_zip_code'];    
                // Insert User into slots 
                $finalResult = $this->insertGroupUserIntoSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id, $total_group_user, $end_time,$group_zip_code, $generateQR);
            } else { //update
                if ($hourlyCount[0]['totalhour'] == 0) {
                    $hourlyUpdateData = array(
                        'is_reservable' => $postData['slot_type'],
                        'modified_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                        'modified_at' => date("Y-m-d H:i:s")
                    );
                    $where_update = array('hourly_ts_id' => $hourlyCount[0]['hourly_ts_id']);
                    $this->common_model->update(HOURLY_TIMESLOT, $hourlyUpdateData, $where_update);
                }

                $hourlyData = array(
                    'weekly_ts_id' => $weeklyId,
                    'date' => dateformat($postData['date']),
                    'start_time' => $stTime,
                    'end_time' => $etTime,
                    'is_reservable' => $postData['slot_type'],
                    'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                    'created_at' => date("Y-m-d H:i:s")
                );

                // Prepare user count per slot 
                $diff = $userGroup - ($user_per_slot * $i);
                if ($diff < 0) {
                    $groupAmt = ($user_per_slot + $diff);
                } else {
                    $groupAmt = $config[0]['value'];
                }

                $current_date_time = $postData['current_time'];
                $email_id = $postData['email_id'];
                $no_of_people = $groupAmt;
                $hourly_ts_id = $hourlyCount[0]['hourly_ts_id'];
                $weekly_ts_id = $hourlyCount[0]['weekly_ts_id'];
                $group_name = $postData['group_name'];
                $total_group_user = $postData['number_of_user'];
                $end_time = $postData['end_time'];
                $group_zip_code = $postData['group_zip_code']; 

                // Insert User into slots 
                $finalResult = $this->insertGroupUserIntoSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id, $total_group_user, $end_time,$group_zip_code, $generateQR);
            }
            $prevhour = $startHour[0];
        }

        // Final inserted Slot details sent to User 
        if (is_array($finalResult)) {

            $this->sendMailToUser($finalResult['reservationCode'], $finalResult['email_id'], $finalResult['no_of_people'], $generateQR['reservation_date_time'], $finalResult['cancellation_code'], $finalResult['pdf'],$finalResult['zip_code'], $finalResult['end_time']);
            echo $msg = '1';
        } else {
            echo $msg = '0';
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Insert Group users into Slots
      @Input  :
      @Output :
      @Date   : 26/7/2017
     */

    function insertGroupUserIntoSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id, $total_group_user, $end_time,$group_zip_code, $generateQR = array()) {


        if (!empty($email_id)) {

            //get hourly weekly hours
            $wherestring = 'h.is_reservable =1 AND h.hourly_ts_id = ' . $hourly_ts_id . ' and concat(h.date," ",h.start_time) >= "' . $current_date_time . '"';
            $fields = array('(max(u.config_no_of_people) - sum(u.no_of_people)) as available,max(u.config_no_of_people) as config_no_of_people,h.hourly_ts_id');
            $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) = " . $no_of_people;
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as u' => 'u.hourly_ts_id=h.hourly_ts_id');
            $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as h', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, '', '', '');

            //server side validation slot available on given post data
            if (!empty($hourlyData[0]['hourly_ts_id'])) {
                //get current config no of prople
                $field = array('*');
                $match = array('config_key' => 'no_of_people_per_hour');
                $userPerHour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
                if ($hourlyData[0]['available'] == '' && $hourlyData[0]['config_no_of_people'] == '') {
                    $hourlyData[0]['available'] = $no_of_people;
                }

                if (!empty($hourlyData[0]['available']) && $hourlyData[0]['available'] >= $no_of_people) {

                    //Insert user slot
                    $data = array(
                        'user_id' => $generateQR['user_id'],
                        'group_id' => $generateQR['group_id'],
                        'weekly_ts_id' => $weekly_ts_id,
                        'hourly_ts_id' => $hourly_ts_id,
                        'no_of_people' => $no_of_people,
                        'no_of_group_people' => $no_of_people,
                        'config_no_of_people' => !empty($userPerHour[0]['value']) ? $userPerHour[0]['value'] : 0,
                        'reservation_code' => $generateQR['reservation_Code'],
                        'qr_code' => $generateQR['qr_name'],
                        'pdf_file_name' => $generateQR['pdf_name'],
                        'cancellation_code' => $generateQR['cancellation_Code'],
                        'zip_code' => $group_zip_code,
                        'created_at' => datetimeformat()
                    );

                    $user_reservation_id = $this->common_model->insert(USER_SHEDULE_TIMESLOT, $data);

                    $finalData = array(
                        'user_reservation_id' => $user_reservation_id,
                        'reservationCode' => $generateQR['reservation_Code'],
                        'cancellation_code' => $generateQR['cancellation_Code'],
                        'email_id' => $email_id,
                        'no_of_people' => $total_group_user,
                        'date_time' => $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'],
                        'end_time' => $end_time,
                        'zip_code' => $group_zip_code,
                        'pdf' => $this->config->item('pdf_upload_path') . $generateQR['pdf_name'],
                    );

                    return $finalData;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : generate QRcode For Group
      @Input  :
      @Output :
      @Date   : 27/7/2017
     */

    public function generateQRcodeForGroup($email_id, $group_name, $total_group_user, $start_date, $start_time, $end_time,$group_zip_code) {

        if (!empty($email_id)) {
            //get user id
            $field = array('user_id');
            $match = array('email' => trim($email_id));
            $usercheck = $this->common_model->get_records(USER, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');
            if (empty($usercheck)) {
                //Insert user
                $userData = array(
                    'email' => trim($email_id),
                    'password' => md5(randompassword()),
                    'role_type' => 5,
                    'created_at' => datetimeformat()
                );

                $userId = $this->common_model->insert(USER, $userData);
            } else {
                $userId = $usercheck[0]['user_id'];
            }

            // Inser Group Details into Group table
            $groupData = array(
                'group_name' => $group_name,
                'no_of_people' => $total_group_user,
                'user_id' => $userId,
                'created_at' => datetimeformat()
            );

            $groupID = $this->common_model->insert(GROUP_RESERVATION, $groupData);

            //genetate reservation code
            // $reservationCode = randomnumber();
            $reservationCode = RESERVATION_CODE . getLastInsertedScheduleUser();
            $cancellationCode = randomnumber();

            //generate qrcode
            $this->load->library('Encryption');
            $converter = new Encryption;

            $this->load->library('ci_qr_code');
            $qrCodeConfig = array();
            $qrCodeConfig['cacheable'] = $this->config->item('cacheable');
            $qrCodeConfig['cachedir'] = $this->config->item('cachedir');
            $qrCodeConfig['imagedir'] = $this->config->item('imagedir');
            $qrCodeConfig['errorlog'] = $this->config->item('errorlog');
            $qrCodeConfig['ciqrcodelib'] = $this->config->item('ciqrcodelib');
            $qrCodeConfig['quality'] = $this->config->item('quality');
            $qrCodeConfig['size'] = $this->config->item('size');
            $qrCodeConfig['black'] = $this->config->item('black');
            $qrCodeConfig['white'] = $this->config->item('white');

            $this->ci_qr_code->initialize($qrCodeConfig);

            $qrcodeName = 'qr' . $userId . '_' . time() . '.png';
            
            $population_name = "";
            $is_agbarCustomer = lang('no');
            
            if($group_zip_code != ""){
                $population_name = getPopulationName($group_zip_code); 
                if(checkZipCodeisAvailable($group_zip_code)){
                    $is_agbarCustomer = lang('yes');
                }else{
                    $is_agbarCustomer = lang('no');
                }
            }else{
                $population_name = $population_name;
                $is_agbarCustomer = lang('no');
            }
            
            //Create pdf file
            //load mPDF library
            $this->load->library('M_pdf');
            
            $population_name = "";
            $is_agbarCustomer = lang('no');
            $big_family = lang('no');
            if($total_group_user > 4){
                $big_family = lang('yes');
            }else{
                 $big_family = lang('no');
            }
            if($group_zip_code != ""){
                $population_name = getPopulationName($group_zip_code); 
                if(checkZipCodeisAvailable($group_zip_code)){
                    $is_agbarCustomer = lang('yes');
                }else{
                     $is_agbarCustomer = lang('no');
                }
            }else{
                $population_name = $population_name;
                $is_agbarCustomer = lang('no');
            }
            
            $pdfdata['pdfdata'] = array(
                'title' => 'Reservation Details',
                'email' => $email_id,
                'no_of_people' => $total_group_user,
                'reservation_code' => $reservationCode,
                'cancellation_code' => $cancellationCode,
                'datetime' => $start_date . ' ' . $start_time . ' - ' . $end_time,
                'zip_code' => $group_zip_code,
                'population_name' => $population_name,
                'is_agbarCustomer' => $is_agbarCustomer,
                'big_family' => $big_family,
                'qr_code' => $this->config->item('imagedir') . $qrcodeName
            );

            // Create QR code 
            // $params['data'] = $converter->encode ($reservationCode.'time'.$postData['hourly_ts_id']);
            $params['data'] = $reservationCode . "," . $cancellationCode . "," . $email_id . "," . $total_group_user . "," . $start_date . ' ' . $start_time . "," . $end_time. "," . $group_zip_code;
            $params['level'] = 'H';
            $params['size'] = 10;
            $params['savename'] = $qrCodeConfig['imagedir'] . $qrcodeName;
            $this->ci_qr_code->generate($params);

            //now pass the data //
            $html = $this->load->view($this->viewname . '/pdfView', $pdfdata, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
            //this the the PDF filename that user will get to download

            $pdfDirPath = $this->config->item('pdf_base_path');
            if (!is_dir($pdfDirPath)) {
                //create directory
                mkdir($pdfDirPath, 0777, TRUE);
            }
//            $pdfname = "pdf-" . time() . ".pdf";
//            $pdfFilePath = $pdfDirPath . $pdfname;
//            //actually, you can pass mPDF parameter on this load() function
//            $this->m_pdf->pdf->WriteHTML($html, 2);
//            $this->m_pdf->pdf->Output($pdfFilePath, "F");
             sleep(1);
             $pdfname = "pdf-" . time() . ".pdf";
             $pdfFilePath = $pdfDirPath . $pdfname;
             //actually, you can pass mPDF parameter on this load() function
             //$mpdf = new mPDF('utf-8', 'A4');
             $mpdf = new mPDF('utf-8', 'A4', 0, '', 3.1, 3.1, 3.1, 3.1, 0, 0);
             $mpdf->WriteHTML($html);
             $mpdf->Output($pdfFilePath, 'F');
             
            $generateQRcodeArr = array(
                'user_id' => $userId,
                'group_id' => $groupID,
                'reservation_Code' => $reservationCode,
                'cancellation_Code' => $cancellationCode,
                'qr_name' => $qrcodeName,
                'pdf_name' => $pdfname,
                'zip_code' => $group_zip_code,
                'population_name' => $population_name,
                'is_agbarCustomer' => $is_agbarCustomer,
                'reservation_date_time' => $start_date . ' ' . $start_time,
            );

            return $generateQRcodeArr;
        }
    }
    
    
     /*
      @Author : Mehul Patel
      @Desc   : generate QRcode For Group
      @Input  :
      @Output :
      @Date   : 27/7/2017
     */

    public function generateQRcodeForSingleSlotGroup($email_id,$total_group_user, $start_date, $start_time, $end_time,$reservation_Code,$cancellation_Code,$postData) {

        if (!empty($email_id)) {
            //get user id
            $field = array('user_id');
            $match = array('email' => trim($email_id));
            $usercheck = $this->common_model->get_records(USER, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');
            if (empty($usercheck)) {
                //Insert user
                $userData = array(
                    'email' => trim($email_id),
                    'password' => md5(randompassword()),
                    'role_type' => 5,
                    'paypal_token' => $postData['paypal_token'],
                    'created_at' => datetimeformat()
                );

                $userId = $this->common_model->insert(USER, $userData);
            } else {
                $userId = $usercheck[0]['user_id'];
            }

            //genetate reservation code
            // $reservationCode = randomnumber();
            $reservationCode = $reservation_Code;
            $cancellationCode = $cancellation_Code;

            //generate qrcode
            $this->load->library('Encryption');
            $converter = new Encryption;

            $this->load->library('ci_qr_code');
            $qrCodeConfig = array();
            $qrCodeConfig['cacheable'] = $this->config->item('cacheable');
            $qrCodeConfig['cachedir'] = $this->config->item('cachedir');
            $qrCodeConfig['imagedir'] = $this->config->item('imagedir');
            $qrCodeConfig['errorlog'] = $this->config->item('errorlog');
            $qrCodeConfig['ciqrcodelib'] = $this->config->item('ciqrcodelib');
            $qrCodeConfig['quality'] = $this->config->item('quality');
            $qrCodeConfig['size'] = $this->config->item('size');
            $qrCodeConfig['black'] = $this->config->item('black');
            $qrCodeConfig['white'] = $this->config->item('white');

            $this->ci_qr_code->initialize($qrCodeConfig);

            $qrcodeName = 'qr' . $userId . '_' . time() . '.png';

            //Create pdf file
            //load mPDF library
            $this->load->library('M_pdf');

            // Create QR code 
            // $params['data'] = $converter->encode ($reservationCode.'time'.$postData['hourly_ts_id']);
            $params['data'] = $reservationCode . "," . $cancellationCode . "," . $email_id . "," . $total_group_user . "," . $start_date . ' ' . $start_time . "," . $end_time. "," . $postData['zip_code'];
            $params['level'] = 'H';
            $params['size'] = 10;
            $params['savename'] = $qrCodeConfig['imagedir'] . $qrcodeName;
           
            $this->ci_qr_code->generate($params);
            
            $population_name = "";
            $is_agbarCustomer = lang('no');
            if($postData['zip_code'] != ""){
                $population_name = getPopulationName($postData['zip_code']); 
                if(checkZipCodeisAvailable($postData['zip_code'])){
                    $is_agbarCustomer = lang('yes');
                }else{
                     $is_agbarCustomer = lang('no');
                }
            }else{
                $population_name = $population_name;
                $is_agbarCustomer = lang('no');
            }
            
            sleep(1);
            $pdfdata['pdfdata'] = array(
                'title' => 'Reservation Details',
                'email' => $email_id,
                'no_of_people' => $total_group_user,
                'reservation_code' => $reservationCode,
                'cancellation_code' => $cancellationCode,
                'datetime' => $start_date . ' ' . $start_time . ' - ' . $end_time,
                'zip_code' => $postData['zip_code'],
                'population_name' => $population_name,
                'is_agbarCustomer' => $is_agbarCustomer,
                'qr_code' => $this->config->item('imagedir') . $qrcodeName
            );
         
            //now pass the data //
            $html = $this->load->view($this->viewname . '/pdfView', $pdfdata, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
            //this the the PDF filename that user will get to download

            $pdfDirPath = $this->config->item('pdf_base_path');
            if (!is_dir($pdfDirPath)) {
                //create directory
                mkdir($pdfDirPath, 0777, TRUE);
            }
//            $pdfname = "pdf-" . time() . ".pdf";
//            $pdfFilePath = $pdfDirPath . $pdfname;
//            //actually, you can pass mPDF parameter on this load() function
//            $this->m_pdf->pdf->WriteHTML($html, 2);
//            $this->m_pdf->pdf->Output($pdfFilePath, "F");
             sleep(1);
             $pdfname = "pdf-" . time() . ".pdf";
             $pdfFilePath = $pdfDirPath . $pdfname;
             //actually, you can pass mPDF parameter on this load() function
             $mpdf = new mPDF('utf-8', 'A4', 0, '', 3.1, 3.1, 3.1, 3.1, 0, 0);
             $mpdf->WriteHTML($html);
             $mpdf->Output($pdfFilePath, 'F');
             
            $generateQRcodeArr = array(
                'user_id' => $userId,
                'group_id' => $groupID,
                'reservation_Code' => $reservationCode,
                'cancellation_Code' => $cancellationCode,
                'qr_name' => $qrcodeName,
                'pdf_name' => $pdfname,
                'zip_code' => $postData['zip_code'],
                'reservation_date_time' => $start_date . ' ' . $start_time,
            );

            return $generateQRcodeArr;
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : checkZip code
      @Input  :
      @Output :
      @Date   : 24/10/2017
     */

    public function checkZipCode() {

        $postData = $this->input->post();
        if (!empty($postData)) {
            $zip_code = $postData['zip_code'];
            $tableName = UPLOAD_ZIP_CODE;
            $fields = array('COUNT(zip_code) AS zip_code');
            $match = array('zip_code' => $zip_code, 'is_delete' => '0');

            $zip_code_data = $this->common_model->get_records($tableName, $fields, '', '', $match);

            if ($zip_code_data[0]['zip_code'] > 0) {
                $ismatched = 1;
            } else {
                $ismatched = 0;
            }
            echo $ismatched;
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Customer Check Duplicate email
      @Input  :
      @Output :
      @Date   : 24/10/2017
     */

    public function isDuplicateEmail() {

        $emailName = trim($this->input->post('email'));

        if (!empty($emailName)) {
            $tableName = USER;
            $fields = array('COUNT(user_id) AS cntData ');
            $match = array('email' => $emailName, 'is_delete' => '0', 'status' => '1');
            $duplicateEmail = $this->common_model->get_records($tableName, $fields, '', '', $match);
           
            if ($duplicateEmail[0]['cntData'] > 0) {
                $isduplicate = 1;
            } else {
                $isduplicate = 0;
            }
        }
        echo $isduplicate;
    }
    
    
       /*
      @Author : Mehul Patel
      @Desc   :checkSlotAvailbilityForSingleSlotGroup
      @Input  :
      @Output :
      @Date   : 6/11/2017
     */

    public function checkSlotAvailbilityForSingleSlotGroup($postData) {

        //$postData = $this->input->post();
       
        $totalDuration = $postData['total_duration'];
        $totalMinute = $postData['total_minute'];
        $slotISavailable = FALSE;
        $flag = TRUE;
        $field1 = array('*');
        $match1 = array('config_key' => 'no_of_slot_per_hour');
        $config1 = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);

        if ($totalDuration == "") {
            $totalDuration = 60 / $config1[0]['value'];
        }

        $field = array('*');
        $match = array('config_key' => 'no_of_people_per_hour');
        $config = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);

        if ($postData['selected_no_user'] <= $config[0]['value']) {
            $slotISavailable = TRUE;
        } else {
            $slot_allocate_to_user = $postData['selected_no_user'] / $config[0]['value'];

            $totalSlot = ($slot_allocate_to_user > 0) ? $slot_allocate_to_user : 1;

            if (ceil($totalSlot) >= 1) {

                for ($i = 1; $i <= ceil($totalSlot); $i++) {
                    $stTime = (empty($etTime)) ? $postData['start_time'] : $etTime;
                    $etTime = date('H:i:s', strtotime("+" . $totalDuration . " minutes", strtotime($stTime)));
                    if ($stTime == "00:00:00" && $postData['start_time'] != "00:00:00") {
                        $flag = FALSE;
                        break;
                    }
                }

                $where_string = 'ht.date = "' . dateformat($postData['startDate']) . '" and ht.start_time >= "' . $postData['start_time'] . '" and ht.end_time <= "' . $etTime . '" and ht.start_time<=ht.end_time ';
                $fields = array('SUM(us.no_of_people) as no_of_people');
                $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
                $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '', '', '', '', '', $where_string, '', '', '');

                if (strlen($hourlyData[0]['no_of_people']) === 0) {
                    $slotISavailable = TRUE;
                } else {
                    $slotISavailable = FALSE;
                }
            } else {
                $slotISavailable = TRUE;
            }
        }

        if (($slotISavailable == TRUE || $slotISavailable == 1) && ($flag == TRUE || $flag == 1)) {
            return $msg = '1';
        } else {
            return $msg = '0';
        }
    }
    
       /*
      @Author : Mehul Patel
      @Desc   : insertSlotsForSingleSlotGroup
      @Input  :
      @Output :
      @Date   : 06/11/2017
     */

    function insertSlotsForSingleSlotGroup($postData) {
      
      //  $postData = $this->input->post();
        $totalDuration = $postData['total_duration'];
        $totalMinute = $postData['total_minute'];

        $field_config = array('*');
        $match_config = array('config_key' => 'no_of_people_per_hour');
        $config = $this->common_model->get_records(CONFIG_TABLE, $field_config, '', '', $match_config);
        $slot_allocate_to_user = $postData['selected_no_user'] / $config[0]['value'];
        // Get total sots need to be insert for group 
        $total_Slot = ($slot_allocate_to_user > 0) ? $slot_allocate_to_user : 1;

        $field1 = array('*');
        $match1 = array('config_key' => 'no_of_slot_per_hour');
        $config1 = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);

        if ($totalDuration == "") {
            $totalDuration = 60 / $config1[0]['value'];
        }

        if (ceil($total_Slot) >= 1) {
            // based on total slot calculate total minutes of each slots
            if ($totalMinute != "") {
                $totalMinute = $totalMinute * ceil($total_Slot);
            } else {
                $totalMinute = $totalDuration * ceil($total_Slot);
            }

            $st_Time = "";
            $et_Time = "";
            for ($i = 1; $i <= ceil($total_Slot); $i++) {
                $st_Time = (empty($et_Time)) ? $postData['start_time'] : $et_Time;
                $et_Time = date('H:i:s', strtotime("+" . $totalDuration . " minutes", strtotime($st_Time)));
            }
            if ($et_Time != "") {
                $postData['end_time'] = $et_Time; // if end time is not define then calculate end time from slot and Start time
            }
            // Prepare data for User assign into slots 
            $userGroup = $postData['selected_no_user'];
            $user_per_slot = $config[0]['value'];
            $total_group = ceil($total_Slot);
            $max = $total_group;
            $min = (int) $total_Slot;
            $diff = 0;
        }

        //check weekly hours and insert if not exist
        $match = array('week_start_date' => dateformat($postData['week_start_date']));
        $weeklyTotalData = $this->common_model->get_records(WEEKLT_TOTAL_SLOT, array('total_slot_id'), '', '', $match, '', '', '', '', '', '', '', '', '', '1');

        if (empty($weeklyTotalData)) {
            //get no_of_slot_per_hour
            $field = array('*');
            $match = array('config_key' => 'no_of_slot_per_hour');
            $slot = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
            $weeklyData = array(
                'total_slot' => !empty($slot[0]['value']) ? $slot[0]['value'] : '',
                'week_start_date' => dateformat($postData['week_start_date']),
                'week_end_date' => date('Y-m-d', (strtotime('-1 day', strtotime($postData['week_end_date'])))),
                'created_at' => date("Y-m-d H:i:s")
            );

            $weeklyTotalId = $this->common_model->insert(WEEKLT_TOTAL_SLOT, $weeklyData);
        }

        //Insert weekly and hourly 
        $starttime = explode(':', $postData['start_time']);
        $endtime = explode(':', $postData['end_time']);
        $prevhour = '';

        $totalSlot = (($totalMinute / $totalDuration) == 0) ? 1 : ($totalMinute / $totalDuration);
       
        // Generate QR code for Group Reservation         
        // $generateQR = $this->generateQRcodeForSingleSlotGroup($postData['email_id'], $postData['selected_no_user'], $postData['date'], $postData['start_time'], $postData['end_time']);
        
        // Genrate Reservation Code & cancellation code
        
        $reservation_Code = RESERVATION_CODE . getLastInsertedScheduleUser();
        $cancellation_Code = randomnumber();
        $f = 1;
        $no_of_people_selected = $postData['selected_no_user'];
        
        $postData['selected_total_users'] = $no_of_people_selected;
        $setPaymentFlag = 0;
        $paypal_token = NULL;
       
        // Check Allow to free Entry or not
        if (isset($postData['is_free_entry']) && isset($postData['is_agbar_customer'])) {
                if ($postData['is_free_entry'] == "" && $postData['is_agbar_customer'] == 0) {
                    $paypal_token = randomnumber();
                    $setPaymentFlag = 1;
                }else if($postData['is_free_entry'] == 0 && $postData['is_agbar_customer'] == 0){
                    $paypal_token = randomnumber();
                    $setPaymentFlag = 1;
                }else {
                    $paypal_token = NULL;
                    $setPaymentFlag = 0;
                }
         }
        $postData['paypal_token'] = $paypal_token;
        $postData['setPaymentFlag'] = $setPaymentFlag;
        
                
        $results = array();
        for ($i = 1; $i <= $totalSlot; $i++) {
            
            if ($f == 1) {
                  $postData['selected_no_user'] = $config[0]['value'];                  
            }else{
                 $postData['selected_no_user'] = ($no_of_people_selected - $config[0]['value']);                
            }
            
            $stTime = (empty($etTime)) ? $postData['start_time'] : $etTime;
            $etTime = date('H:i:s', strtotime("+" . $totalDuration . " minutes", strtotime($stTime)));

            $startHour = explode(':', $stTime);

            if ($startHour[0] != $prevhour) {
                if ($startHour[0] == 00) {
                    $startTime = '00:00';
                    $endTime = '01:00';
                } else {
                    $startTime = $startHour[0] . ':00';
                    $endTime = date('H:i', strtotime($startTime . '+1 hour'));
                }
                //check weekly hours
                $match = array('date' => dateformat($postData['date']), 'start_time' => $startTime, 'end_time' => $endTime);
                $weeklyData = $this->common_model->get_records(WEEKLY_TIMESLOT, array('weekly_ts_id'), '', '', $match, '', '', '', '', '', '', '', '', '', '');

                if (!empty($weeklyData)) {
                    $weeklyId = $weeklyData[0]['weekly_ts_id'];
                } else { //insert weekly slot
                    $weeklyData = array(
                        'date' => dateformat($postData['date']),
                        'start_time' => $startTime,
                        'is_open' => $postData['slot_type'],
                        'end_time' => $endTime,
                        'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                        'created_at' => date("Y-m-d H:i:s")
                    );
                    $weeklyId = $this->common_model->insert(WEEKLY_TIMESLOT, $weeklyData);
                }
            }

            //get total exist or not
            $match = array('date' => dateformat($postData['date']), 'start_time' => $stTime, 'end_time' => $etTime);
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
            $hourlyCount = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', array('ht.weekly_ts_id,ht.hourly_ts_id,count(us.hourly_ts_id) as totalhour'), $joinTables, 'left', $match, '', '', '', '', '', '', '', '', '', '');

            //insert
            if (empty($hourlyCount[0]['hourly_ts_id']) && $hourlyCount[0]['totalhour'] == 0) {

                $hourlyData = array(
                    'weekly_ts_id' => $weeklyId,
                    'date' => dateformat($postData['date']),
                    'start_time' => $stTime,
                    'end_time' => $etTime,
                    'is_reservable' => $postData['slot_type'],
                    'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                    'created_at' => date("Y-m-d H:i:s")
                );

                if (!empty($hourlyData)) {

                    $hourlyId = $this->common_model->insert(HOURLY_TIMESLOT, $hourlyData);
                }

                // Prepare user count per slot 
                $diff = $userGroup - ($user_per_slot * $i);
                if ($diff < 0) {
                    $groupAmt = ($user_per_slot + $diff);
                } else {
                    $groupAmt = $config[0]['value'];
                }

                $current_date_time = $postData['current_time'];
                $email_id = $postData['email_id'];
                $no_of_people = $groupAmt;
                $weekly_ts_id = $weeklyId;
                $hourly_ts_id = $hourlyId;
                $group_name = " ";
                $total_group_user = $postData['selected_no_user'];
                $end_time = $postData['end_time'];
                
                // Generate QR code for Group Reservation         
                $generateQR = $this->generateQRcodeForSingleSlotGroup($postData['email_id'], $postData['selected_no_user'], $postData['date'], $postData['start_time'], $postData['end_time'],$reservation_Code,$cancellation_Code,$postData);
        
                
                // Insert User into slots 
                $finalResult = $this->insertGroupForSingleSlotUserIntoSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id, $total_group_user, $end_time,$postData, $generateQR);
             
                
             } else { //update
                if ($hourlyCount[0]['totalhour'] == 0) {
                    $hourlyUpdateData = array(
                        'is_reservable' => $postData['slot_type'],
                        'modified_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                        'modified_at' => date("Y-m-d H:i:s")
                    );
                    $where_update = array('hourly_ts_id' => $hourlyCount[0]['hourly_ts_id']);
                    $this->common_model->update(HOURLY_TIMESLOT, $hourlyUpdateData, $where_update);
                }

                $hourlyData = array(
                    'weekly_ts_id' => $weeklyId,
                    'date' => dateformat($postData['date']),
                    'start_time' => $stTime,
                    'end_time' => $etTime,
                    'is_reservable' => $postData['slot_type'],
                    'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                    'created_at' => date("Y-m-d H:i:s")
                );

                // Prepare user count per slot 
                $diff = $userGroup - ($user_per_slot * $i);
                if ($diff < 0) {
                    $groupAmt = ($user_per_slot + $diff);
                } else {
                    $groupAmt = $config[0]['value'];
                }

                $current_date_time = $postData['current_time'];
                $email_id = $postData['email_id'];
                $no_of_people = $groupAmt;
                $hourly_ts_id = $hourlyCount[0]['hourly_ts_id'];
                $weekly_ts_id = $hourlyCount[0]['weekly_ts_id'];
                $group_name = $postData['group_name'];
                $total_group_user = $postData['selected_no_user'];
                $end_time = $postData['end_time'];
                
                // Generate QR code for Group Reservation         
                $generateQR = $this->generateQRcodeForSingleSlotGroup($postData['email_id'], $postData['selected_no_user'], $postData['date'], $postData['start_time'], $postData['end_time'],$reservation_Code,$cancellation_Code,$postData);
        
                // Insert User into slots 
                $finalResult = $this->insertGroupForSingleSlotUserIntoSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id, $total_group_user, $end_time,$postData, $generateQR);
            }
            $prevhour = $startHour[0];
            $f++;
            
           
               // Final inserted Slot details sent to User 
            if (is_array($finalResult)) {
                                
                if($postData['setPaymentFlag'] == 0){
                    $this->sendMailToUser($finalResult['reservationCode'], $finalResult['email_id'], $finalResult['no_of_people'], $generateQR['reservation_date_time'], $finalResult['cancellation_code'], $finalResult['pdf'], $finalResult['zip_code'], $finalResult['end_time']);
                }else{
                    $this->sendMailToUserToPayment($email_id, $postData['selected_total_users'], $generateQR['reservation_date_time'], $postData['paypal_token'], $finalResult['end_time']);
                }
                                
                $results[] =  array('status' => 1);
            } else {
                $results[] =  array('status' => 1);
                //echo $msg = '0';
            }
            
        }
        return $results;
        // Final inserted Slot details sent to User 
//        if (is_array($finalResult)) {
//
//            $this->sendMailToUser($finalResult['reservationCode'], $finalResult['email_id'], $finalResult['no_of_people'], $generateQR['reservation_date_time'], $finalResult['cancellation_code'], $finalResult['pdf'], $finalResult['end_time']);
//            echo $msg = '1';
//        } else {
//            echo $msg = '0';
//        }
    }
    
        /*
      @Author : Mehul Patel
      @Desc   : Insert Group users into Slots
      @Input  :
      @Output :
      @Date   : 07/11/2017
     */

    function insertGroupForSingleSlotUserIntoSlot($current_date_time, $email_id, $no_of_people, $weekly_ts_id, $hourly_ts_id, $total_group_user, $end_time, $postData, $generateQR = array()) {


        if (!empty($email_id)) {

            //get hourly weekly hours
            $wherestring = 'h.is_reservable =1 AND h.hourly_ts_id = ' . $hourly_ts_id . ' and concat(h.date," ",h.start_time) >= "' . $current_date_time . '"';
            $fields = array('(max(u.config_no_of_people) - sum(u.no_of_people)) as available,max(u.config_no_of_people) as config_no_of_people,h.hourly_ts_id');
            $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) = " . $no_of_people;
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as u' => 'u.hourly_ts_id=h.hourly_ts_id');
            $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as h', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, '', '', '');

            //server side validation slot available on given post data
            if (!empty($hourlyData[0]['hourly_ts_id'])) {
                //get current config no of prople
                $field = array('*');
                $match = array('config_key' => 'no_of_people_per_hour');
                $userPerHour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
                if ($hourlyData[0]['available'] == '' && $hourlyData[0]['config_no_of_people'] == '') {
                    $hourlyData[0]['available'] = $no_of_people;
                }

                if (!empty($hourlyData[0]['available']) && $hourlyData[0]['available'] >= $no_of_people) {
                    
                    $is_payment = '0';
                    if($postData['setPaymentFlag'] == 0){
                        $is_payment = '0';
                    }else{
                         $is_payment = '2';
                    }
                    //Insert user slot
                    $data = array(
                        'user_id' => $generateQR['user_id'],                     
                        'weekly_ts_id' => $weekly_ts_id,
                        'hourly_ts_id' => $hourly_ts_id,
                        'no_of_people' => $no_of_people,
                        'no_of_group_people' => 0,
                        'config_no_of_people' => !empty($userPerHour[0]['value']) ? $userPerHour[0]['value'] : 0,
                        'reservation_code' => $generateQR['reservation_Code'],
                        'qr_code' => $generateQR['qr_name'],
                        'pdf_file_name' => $generateQR['pdf_name'],
                        'cancellation_code' => $generateQR['cancellation_Code'],
                        'zip_code' => $postData['zip_code'],
                        'is_payment' => $is_payment,
                        'created_at' => datetimeformat()
                    );
                 
                    $user_reservation_id = $this->common_model->insert(USER_SHEDULE_TIMESLOT, $data);

                    $finalData = array(
                        'user_reservation_id' => $user_reservation_id,
                        'reservationCode' => $generateQR['reservation_Code'],
                        'cancellation_code' => $generateQR['cancellation_Code'],
                        'email_id' => $email_id,
                        'no_of_people' => $total_group_user,
                        'date_time' => $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'],
                        'end_time' => $end_time,
                        'zip_code' => $postData['zip_code'],
                        'pdf' => $this->config->item('pdf_upload_path') . $generateQR['pdf_name'],
                    );

                    return $finalData;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}
