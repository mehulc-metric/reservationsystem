<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usershedule extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->viewname = ucfirst($this->router->fetch_class());
        $this->load->library(array('form_validation', 'Session', 'breadcrumbs', 'MyPayPal', 'PayPalRefund'));
    }

    /*
      @Author : Niral Patel
      @Desc   : Usershedule Index
      @Input  :
      @Output :
      @Date   : 21/06/2017
     */

    public function index() {

        //get current config no of prople
        $data['userPerHour'] = getConfigUser();

        // get Config Amout          
        $data['config_amount'] = getCofigAmount();

        //get Config VAT        
        $data['config_vat'] = getConfigVat();

        //load view
        $data['main_content'] = $this->viewname . '/add';
        $data['footerJs'][0] = base_url('uploads/custom/js/Usershedule/usershedule.js');

        $this->parser->parse('layouts/UsersheduleTemplate', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Add Timeshedule
      @Input  :
      @Output :
      @Date   : 21-6-2017
     */

    public function getEvents() {
        $postData = $this->input->post();

        $data = array();
        //get hourly weekly hours
        $wherestring = 'ht.is_reservable =1 and (date >= "' . $postData['start'] . '" and date <= "' . $postData['end'] . '")';
        $fields = array('ht.date, COUNT(DISTINCT ht.hourly_ts_id) as total_slot , COUNT(DISTINCT us.hourly_ts_id) as user_slot, COUNT(if((concat(ht.date," ",ht.start_time) >= "' . $postData['current_time'] . '" and ht.is_reservable =1 ),1,NULL)) as new_total_slot, COUNT(if((concat(ht.date, " ", ht.start_time) >= "' . $postData['current_time'] . '" and us.hourly_ts_id = ht.hourly_ts_id ), 1, NULL)) as user_new_total_slot');
        $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
        $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '', '', '', '', 'ht.date', $wherestring, '', '', '');

        if (!empty($hourlyData)) {
            foreach ($hourlyData as $row) {
                $color = '';
                //Get total slot which is partial full
                $userSheduleData = $this->common_model->get_userwise_shedule($row['date'], $postData['no_of_people'], $postData['current_time']);

                if ($row['date'] != date('Y-m-d')) { //if past date
                    if ($row['user_slot'] == 0) {
                        $color = GREEN_COLOR;
                        $colorType = '1';
                    } else {
                        if ($row['total_slot'] == $row['user_slot']) {
                            if ($userSheduleData[0]['total'] >= 1) {
                                $color = YELLOW_COLOR;
                                $colorType = '2';
                            } else {
                                $color = RED_COLOR;
                                $colorType = '3';
                            }
                        } else {
                            $color = YELLOW_COLOR;
                            $colorType = '2';
                        }
                    }
                } else { //current or future date
                    if ($row['total_slot'] == $row['user_slot']) {
                        if ($userSheduleData[0]['total'] >= 1) {
                            $color = YELLOW_COLOR;
                            $colorType = '2';
                        } else {
                            $color = RED_COLOR;
                            $colorType = '3';
                        }
                    } else {
                        if ($row['new_total_slot'] >= 1) {
                            if ($row['new_total_slot'] == $row['user_new_total_slot']) {
                                if ($userSheduleData[0]['total'] >= 1) {
                                    $color = YELLOW_COLOR;
                                    $colorType = '2';
                                } else {
                                    $color = RED_COLOR;
                                    $colorType = '3';
                                }
                            } else {
                                if ($row['user_new_total_slot'] == 0) {
                                    $color = GREEN_COLOR;
                                    $colorType = '1';
                                } else {
                                    $color = YELLOW_COLOR;
                                    $colorType = '2';
                                }
                            }
                        } else {
                            $color = RED_COLOR;
                            $colorType = '3';
                        }
                    }
                }
                $data[] = array(
                    'date' => $row['date'],
                    'title' => '&nbsp;',
                    'start' => $row['date'] . 'T12:00:00',
                    'end' => $row['date'] . 'T12:04:00',
                    'color' => $color,
                    'colortype' => $colorType,
                    'description' => $row['date'],
                );
            }
        }



        echo json_encode($data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Get total slot as per date
      @Input  :
      @Output :
      @Date   : 22-6-2017
     */

    public function getTimeslots() {

        $postData = $this->input->post();

        //get hourly weekly hours
        $wherestring = "ht.is_reservable =1 and ht.date = '" . $postData['reservation_date'] . "'";
        $fields = array('ht.weekly_ts_id,ws.start_time,ws.end_time,ws.date, COUNT(DISTINCT ht.hourly_ts_id) as total_slot , COUNT(DISTINCT us.hourly_ts_id) as user_slot, COUNT(if(concat(ht.date," ",ht.start_time) >= "' . $postData['current_time'] . '",1,NULL)) as new_total_slot');
        $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id',
            WEEKLY_TIMESLOT . ' as ws' => 'ws.weekly_ts_id=ht.weekly_ts_id');
        $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '', '', 'ht.start_time', 'asc', 'ht.weekly_ts_id', $wherestring, '', '', '');

        if (!empty($hourlyData)) {
            foreach ($hourlyData as $row) {
                if ($postData['colortype'] == 2) {
                    if ($row['new_total_slot'] >= 1) {
                        //Get total slot which is partial full
                        $userSheduleData = $this->common_model->hourly_available_shedule($row['weekly_ts_id'], $postData['no_of_people'], $postData['current_time']);
                        if ($row['user_slot'] == 0) { // if all slot available
                            if ($row['new_total_slot'] >= 1) {
                                $colorType = '1';
                            } else {
                                $colorType = '3';
                            }
                        } else { // if some slot available
                            if ($row['total_slot'] == $row['user_slot']) {
                                if ($userSheduleData[0]['total'] >= 1) {
                                    $colorType = '2';
                                } else {
                                    $colorType = '3';
                                }
                            } else {
                                if ($userSheduleData[0]['total'] >= 1) {
                                    $colorType = '2';
                                } else {
                                    if ($row['user_slot'] > 0) {
                                        $colorType = '2';
                                    } else {
                                        $colorType = '1';
                                    }
                                }
                            }
                        }
                    } else {
                        $colorType = '3';
                    }
                } else {
                    if ($postData['colortype'] == 3) {
                        $colorType = '3';
                    } else {
                        if ($row['new_total_slot'] >= 1) {
                            $colorType = '1';
                        } else {
                            $colorType = '3';
                        }
                    }
                }

                $hoursSlot[] = array(
                    'weekly_ts_id' => $row['weekly_ts_id'],
                    'date' => $row['date'],
                    'start_time' => $row['start_time'],
                    'end_time' => ($row['end_time'] == '24:00:00') ? '23:59:59' : $row['end_time'],
                    'slotcolor' => $colorType
                );
            }
        }
        $data['hoursSlot'] = $hoursSlot;
        $this->load->view('Usershedule/hoursSlot', $data);
        //echo json_encode($hoursSlot);
    }

    /*
      @Author : Niral Patel
      @Desc   : Get available slot as per time
      @Input  :
      @Output :
      @Date   : 23-6-2017
     */

    public function getAvailableSlot() {

        $postData = $this->input->post();

        $field1 = array('*');
        $match1 = array('config_key' => 'no_of_people_per_hour');
        $config1 = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);

        if ($postData['no_of_people'] > $config1[0]['value']) {
            // get last inserted slots based on selected hours 
            $this->checkSlotAvailbilityForGroup($postData);
        } else {

            if ($postData['colortype'] == 1) { //if all slot available
                $wherestring = 'ht.is_reservable =1 and ht.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(ht.date," ",ht.start_time) >= "' . $postData['current_time'] . '"';
                $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, '', '', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');
            } else {

                //get hourly timeslot as per selcted no of people
                $wherestring = 'h.is_reservable =1 and h.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(h.date," ",h.start_time) >= "' . $postData['current_time'] . '"';
                $fields = array('DATE_FORMAT(h.date, "%m/%d/%Y") as date,h.hourly_ts_id,TIME_FORMAT(h.start_time, "%h:%i %p") as start_time,TIME_FORMAT(h.end_time, "%h:%i %p") as end_time');
                $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) = " . $postData['no_of_people'];
                $joinTables = array(HOURLY_TIMESLOT . ' as h' => 'u.hourly_ts_id=h.hourly_ts_id');
                $hourlyData = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as u', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, $having, '', '');

                if (empty($hourlyData)) {
                    //get hourly weekly hours as per selcted no of people
                    $wherestring = 'h.is_reservable =1 and h.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(h.date," ",h.start_time) >= "' . $postData['current_time'] . '"';
                    $fields = array('DATE_FORMAT(h.date, "%m/%d/%Y") as date,h.hourly_ts_id,TIME_FORMAT(h.start_time, "%h:%i %p") as start_time,TIME_FORMAT(h.end_time, "%h:%i %p") as end_time');
                    $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) >= " . $postData['no_of_people'];
                    $joinTables = array(HOURLY_TIMESLOT . ' as h' => 'u.hourly_ts_id=h.hourly_ts_id');
                    $hourlyData = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as u', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, $having, '', '');

                    if (empty($hourlyData)) {
                        //get hourly weekly hours
                        $wherestring = 'ht.is_reservable =1 and ht.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and us.hourly_ts_id IS NULL and concat(ht.date," ",ht.start_time) >= "' . $postData['current_time'] . '"';
                        $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                        $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
                        $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');
                    }
                }
            }
            if (!empty($hourlyData)) {
                // Pass the selected people into array 
                $hourlyData[0]['no_of_people'] = $postData['no_of_people'];
                $hourlyData[0]['selected_email'] = $postData['selected_email'];

                echo json_encode($hourlyData);
            } else {
                echo json_encode(0);
            }
        }
    }

    /*
      @Author : Mehul  Patel
      @Desc   : Usershedule Insert
      @Input  :
      @Output :
      @Date   : 26/10/2017
     */

    public function checkSlotAvailbilityForGroup($postData) {

        // get last inserted slots based on selected hours 
        $where_hrs_ts = 'u.weekly_ts_id = ' . $postData['weekly_ts_id'];
        $field_hrs_ts = array('u.hourly_ts_id');
        $getHourly_ts_id = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as u', $field_hrs_ts, '', '', '', '', '1', '', 'u.hourly_ts_id', 'DESC', '', $where_hrs_ts, '', '', '');

        if (!empty($getHourly_ts_id[0]['hourly_ts_id'])) {
            // check selected user will set into next 2 slots or not 

            $hourly_ts_id[] = $getHourly_ts_id[0]['hourly_ts_id'] + 1;
            $hourly_ts_id[] = $getHourly_ts_id[0]['hourly_ts_id'] + 2;

            $where_in = array("ht.hourly_ts_id" => $hourly_ts_id);
            // SELECT start_time FROM `res_hourly_time_slot` WHERE hourly_ts_id in (603,604) AND weekly_ts_id = 47 AND `is_reservable` = 1 LIMIT 1
            $where_start_time = 'ht.weekly_ts_id = ' . $postData['weekly_ts_id'];

            $field_start_time = array('ht.start_time');

            $getHourly_start_time = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $field_start_time, '', '', '', '', '1', '', '', '', '', $where_start_time, '', $where_in);

            if (empty($getHourly_start_time[0]['start_time'])) {
                echo json_encode(0);
            } else {
                if ($postData['colortype'] == 1) { //if all slot available
                    $wherestring = 'ht.is_reservable =1 and ht.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(ht.date," ",ht.start_time) >= "' . $postData['current_time'] . '"';
                    $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                    $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, '', '', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');
                } else {

                    //get hourly timeslot as per selcted no of people
                    $wherestring = 'h.is_reservable =1 and h.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(h.date," ",h.start_time) >= "' . $postData['current_time'] . '"';
                    $fields = array('DATE_FORMAT(h.date, "%m/%d/%Y") as date,h.hourly_ts_id,TIME_FORMAT(h.start_time, "%h:%i %p") as start_time,TIME_FORMAT(h.end_time, "%h:%i %p") as end_time');
                    $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) = " . $postData['no_of_people'];
                    $joinTables = array(HOURLY_TIMESLOT . ' as h' => 'u.hourly_ts_id=h.hourly_ts_id');
                    $hourlyData = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as u', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, $having, '', '');

                    if (empty($hourlyData)) {
                        //get hourly weekly hours as per selcted no of people
                        $wherestring = 'h.is_reservable =1 and h.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(h.date," ",h.start_time) >= "' . $postData['current_time'] . '"';
                        $fields = array('DATE_FORMAT(h.date, "%m/%d/%Y") as date,h.hourly_ts_id,TIME_FORMAT(h.start_time, "%h:%i %p") as start_time,TIME_FORMAT(h.end_time, "%h:%i %p") as end_time');
                        $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) >= " . $postData['no_of_people'];
                        $joinTables = array(HOURLY_TIMESLOT . ' as h' => 'u.hourly_ts_id=h.hourly_ts_id');
                        $hourlyData = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as u', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, $having, '', '');

                        if (empty($hourlyData)) {
                            //get hourly weekly hours
                            $wherestring = 'ht.is_reservable =1 and ht.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and us.hourly_ts_id IS NULL and concat(ht.date," ",ht.start_time) >= "' . $postData['current_time'] . '"';
                            $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
                            $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');
                        }
                    }
                }
                if (!empty($hourlyData)) {
                    // Pass the selected people into array 
                    $hourlyData[0]['no_of_people'] = $postData['no_of_people'];
                    $hourlyData[0]['selected_email'] = $postData['selected_email'];

                    echo json_encode($hourlyData);
                } else {
                    echo json_encode(0);
                }
            }
            //$getHourly_start_time = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $field_start_time, '', '', '', '', '1', '', '', '', '', $where_start_time, $where_in, '', '');
        } else {
            if ($postData['colortype'] == 1) { //if all slot available
                $wherestring = 'ht.is_reservable =1 and ht.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(ht.date," ",ht.start_time) >= "' . $postData['current_time'] . '"';
                $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, '', '', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');
            } else {

                //get hourly timeslot as per selcted no of people
                $wherestring = 'h.is_reservable =1 and h.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(h.date," ",h.start_time) >= "' . $postData['current_time'] . '"';
                $fields = array('DATE_FORMAT(h.date, "%m/%d/%Y") as date,h.hourly_ts_id,TIME_FORMAT(h.start_time, "%h:%i %p") as start_time,TIME_FORMAT(h.end_time, "%h:%i %p") as end_time');
                $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) = " . $postData['no_of_people'];
                $joinTables = array(HOURLY_TIMESLOT . ' as h' => 'u.hourly_ts_id=h.hourly_ts_id');
                $hourlyData = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as u', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, $having, '', '');

                if (empty($hourlyData)) {
                    //get hourly weekly hours as per selcted no of people
                    $wherestring = 'h.is_reservable =1 and h.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and concat(h.date," ",h.start_time) >= "' . $postData['current_time'] . '"';
                    $fields = array('DATE_FORMAT(h.date, "%m/%d/%Y") as date,h.hourly_ts_id,TIME_FORMAT(h.start_time, "%h:%i %p") as start_time,TIME_FORMAT(h.end_time, "%h:%i %p") as end_time');
                    $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) >= " . $postData['no_of_people'];
                    $joinTables = array(HOURLY_TIMESLOT . ' as h' => 'u.hourly_ts_id=h.hourly_ts_id');
                    $hourlyData = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as u', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, $having, '', '');

                    if (empty($hourlyData)) {
                        //get hourly weekly hours
                        $wherestring = 'ht.is_reservable =1 and ht.weekly_ts_id = ' . $postData['weekly_ts_id'] . ' and us.hourly_ts_id IS NULL and concat(ht.date," ",ht.start_time) >= "' . $postData['current_time'] . '"';
                        $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                        $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
                        $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, $joinTables, 'left', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');
                    }
                }
            }
            if (!empty($hourlyData)) {
                // Pass the selected people into array 
                $hourlyData[0]['no_of_people'] = $postData['no_of_people'];
                $hourlyData[0]['selected_email'] = $postData['selected_email'];

                echo json_encode($hourlyData);
            } else {
                echo json_encode(0);
            }
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Usershedule Insert
      @Input  :
      @Output :
      @Date   : 26/06/2017
     */

    public function insertUserSlot() {

        $postData = $this->input->post();
        $no_of_people = $postData['no_of_people'];
        $field1 = array('*');
        $match1 = array('config_key' => 'no_of_people_per_hour');
        $config1 = $this->common_model->get_records(CONFIG_TABLE, $field1, '', '', $match1);

        if ($postData['no_of_people'] > $config1[0]['value']) {
            $fl = 1;
            $cancellationCode = randomnumber();
            $reservationCode = RESERVATION_CODE . getLastInsertedScheduleUser();
            $result_set = array();
            for ($i = 1; $i <= 2; $i++) {
                if ($fl == 1) {
                    $postData['no_of_people'] = $config1[0]['value'];
                    $postData['cancellation_code'] = $cancellationCode;
                    $postData['reservation_code'] = $reservationCode;
                    $postData['flag'] = $fl;
                    $result_set[] = $this->insertSlotsforUser($postData);
                } else {
                    $postData['no_of_people'] = ($no_of_people - $config1[0]['value']);
                    $postData['hourly_ts_id'] = $postData['hourly_ts_id'] + 1;
                    $postData['reservation_code'] = $reservationCode;
                    $postData['cancellation_code'] = $cancellationCode;
                    $postData['flag'] = $fl;
                    $result_set[] = $this->insertSlotsforUser($postData);
                }
                $fl++;
            }
        } else {
            $reservationCode = RESERVATION_CODE . getLastInsertedScheduleUser();
            $cancellationCode = randomnumber();
            $postData['reservation_code'] = $reservationCode;
            $postData['cancellation_code'] = $cancellationCode;
            $postData['flag'] = 1;
            $result_set[] = $this->insertSlotsforUser($postData);
        }
        echo json_encode(array('result' => $result_set));
    }

    /*
      @Author : Mehul Patel
      @Desc   : Usershedule Insert
      @Input  :
      @Output :
      @Date   : 26/10/2017
     */

    public function insertSlotsforUser($postData) {

        if (!empty($postData['email'])) {

            $pdfdata = array();
            $html = '';

            //get hourly weekly hours
            $wherestring = 'h.is_reservable =1 and h.hourly_ts_id = ' . $postData['hourly_ts_id'] . ' and concat(h.date," ",h.start_time) >= "' . $postData['current_time'] . '"';
            $fields = array('(max(u.config_no_of_people) - sum(u.no_of_people)) as available,max(u.config_no_of_people) as config_no_of_people,h.hourly_ts_id');
            $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) = " . $postData['no_of_people'];
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as u' => 'u.hourly_ts_id=h.hourly_ts_id');
            $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as h', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, '', '', '');


            //server side validation slot available on given post data
            if (!empty($hourlyData[0]['hourly_ts_id'])) {
                //get current config no of prople
                $field = array('*');
                $match = array('config_key' => 'no_of_people_per_hour');
                $userPerHour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
                if ($hourlyData[0]['available'] == '' && $hourlyData[0]['config_no_of_people'] == '') {
                    $hourlyData[0]['available'] = $postData['no_of_people'];
                }
                if (!empty($hourlyData[0]['available']) && $hourlyData[0]['available'] >= $postData['no_of_people']) {

                    //get user id
                    $field = array('user_id');
                    $match = array('email' => trim($postData['email']));
                    $usercheck = $this->common_model->get_records(USER, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');
                    if (empty($usercheck)) {
                        //Insert user
                        $userData = array(
                            'email' => trim($postData['email']),
                            'password' => md5(randompassword()),
                            'role_type' => 5,
                            'created_at' => datetimeformat()
                        );

                        $userId = $this->common_model->insert(USER, $userData);
                    } else {
                        $userId = $usercheck[0]['user_id'];
                    }

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

                    //get schedule time
                    $wherestring = "ht.is_reservable =1 and ht.hourly_ts_id = '" . $postData['hourly_ts_id'] . "'";
                    $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                    $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, '', '', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');

                    //Create pdf file
                    //load mPDF library
                    $this->load->library('M_pdf');


                    // Create QR code 
                    // $params['data'] = $converter->encode ($reservationCode.'time'.$postData['hourly_ts_id']);
                    $params['data'] = $postData['reservation_code'] . "," . $postData['cancellation_code'] . "," . $postData['email'] . "," . $postData['no_of_people'] . "," . $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'] . "," . $postData['zip_code'];
                    $params['level'] = 'H';
                    $params['size'] = 10;
                    $params['savename'] = $qrCodeConfig['imagedir'] . $qrcodeName;
                    $this->ci_qr_code->generate($params);

                    $population_name = "";
                    $is_agbarCustomer = lang('no');
                    if ($postData['zip_code'] != "") {
                        $population_name = getPopulationName($postData['zip_code']);
                        if (checkZipCodeisAvailable($postData['zip_code'])) {
                            $is_agbarCustomer = lang('yes');
                        } else {
                            $is_agbarCustomer = lang('no');
                        }
                    } else {
                        $population_name = $population_name;
                        $is_agbarCustomer = lang('no');
                    }

                    sleep(1);
                    $pdfdata['pdfdata'] = array(
                        'title' => 'Reservation Details',
                        'email' => $postData['email'],
                        'no_of_people' => $postData['no_of_people'],
                        'reservation_code' => $postData['reservation_code'],
                        'cancellation_code' => $postData['cancellation_code'],
                        'zip_code' => $postData['zip_code'],
                        'population_name' => $population_name,
                        'is_agbarCustomer' => $is_agbarCustomer,
                        'datetime' => $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'],
                        'qr_code' => $this->config->item('imagedir') . $qrcodeName
                            // 'qr_code' => $this->config->item('qrcode_img_url') . $qrcodeName
                    );

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
                    $mpdf = new mPDF('utf-8', 'A4', 0, '', 3.1, 3.1, 3.1, 3.1, 0, 0);
                    $mpdf->WriteHTML($html);
                    $mpdf->Output($pdfFilePath, 'F');
                    $data = array(
                        'user_id' => $userId,
                        'weekly_ts_id' => $postData['weekly_ts_id'],
                        'hourly_ts_id' => $postData['hourly_ts_id'],
                        'no_of_people' => $postData['no_of_people'],
                        'config_no_of_people' => !empty($userPerHour[0]['value']) ? $userPerHour[0]['value'] : 0,
                        'reservation_code' => $postData['reservation_code'],
                        'qr_code' => $qrcodeName,
                        'cancellation_code' => $postData['cancellation_code'],
                        'zip_code' => $postData['zip_code'],
                        'created_at' => datetimeformat()
                    );
                    $response = array();
                    if ($this->common_model->insert(USER_SHEDULE_TIMESLOT, $data)) {
                        $this->sendMailToUser($data['reservation_code'], $postData['email'], $postData['no_of_people'], $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'], $data['cancellation_code'], $postData['zip_code'], $this->config->item('pdf_upload_path') . $pdfname);

                        $response = array('status' => 1, 'message' => "Confirm Sucessfully", 'qrcode' => $qrcodeName, 'pdf' => $pdfname);
                    } else {
                        //    echo json_encode(array('status' => 0, 'message' => lang('something_went_wrong')));
                        $response = array('status' => 0, 'message' => lang('something_went_wrong'));
                    }
                } else {
                    $response = array('status' => 0, 'message' => lang('something_went_wrong'));
                    //return false;
                }
            } else {
                $response = array('status' => 0, 'message' => lang('something_went_wrong'));
            }
        } else {
            $response = array('status' => 0, 'message' => lang('please_enter_email_id'));
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

    private function sendMailToUser($reservation_code, $email, $no_of_people, $reserved_date, $cancellation_code, $zip_code, $attach) {

        if (!empty($email)) {
            $find = array(
                '{RESERVATION_CODE}',
                '{EMAIL}',
                '{NO_OF_PEOPLE}',
                '{RESERVED_DATE}',
                '{CANCELLATION_CODE}',
                '{ZIP_CODE}'
            );

            $replace = array(
                'RESERVATION_CODE' => $reservation_code,
                'EMAIL' => $email,
                'NO_OF_PEOPLE' => $no_of_people,
                'RESERVED_DATE' => $reserved_date,
                'CANCELLATION_CODE' => $cancellation_code,
                'ZIP_CODE' => $zip_code
            );

            $emailSubject = lang('reservation_system').' : '.lang('reservation_confrim');
            $emailBody = '<div>'
                    . '<p>'.lang('hello').'</p> '
                    . '<p>'.lang('your_reservation_schedule').'</p> '
                    . '<p>'.lang('reservation_code').' : {RESERVATION_CODE} </p> '
                    . '<p>'.lang('cancellation_code').' : {CANCELLATION_CODE} </p> '
                    . '<p>'.lang('MY_PROFILE_PINCODE').' : {ZIP_CODE} </p> '
                    . '<p>'.lang('reservation_email_id').' : {EMAIL}</p> '
                    . '<p>'.lang('number_of_people').' : {NO_OF_PEOPLE}</p> '
                    . '<p>'.lang('reservation_date_time').' : {RESERVED_DATE}</p> '
                    . '<p>'.lang('please_find_attachment').'</p> '
                    . '<p>'.lang('Sincerely').'<br></p> '
                    . '<p>'.lang('reservation_team').'</p> '
                    . '<div>';


            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($email, $emailSubject, $finalEmailBody, FROM_EMAIL_ID, '', '', $attach);
        }
        return true;
    }

    /*
      @Author : Niral Patel
      @Desc   : cancel reservation
      @Input  :
      @Output :
      @Date   : 28/06/2017
     */

    public function cancelReservation() {
        $data['main_content'] = $this->viewname . '/cancelReservation';
        $this->parser->parse('layouts/UsersheduleTemplate', $data);
    }

    /* public function insertCancelShedule() {

      $this->cancleSchedule();
      exit;
      } */

    /*
      @Author : Niral Patel
      @Desc   : SendMail To User for cancellation
      @Input  : $reservation_code,$email,$no_of_people,$reserved_date
      @Output :
      @Date   : 26/06/2017
     */

    private function sendMailToUserCancellation($reservation_code, $email, $no_of_people, $reserved_date) {

        if (!empty($email)) {
            $find = array(
                '{RESERVATION_CODE}',
                '{EMAIL}',
                '{NO_OF_PEOPLE}',
                '{RESERVED_DATE}'
            );

            $replace = array(
                'RESERVATION_CODE' => $reservation_code,
                'EMAIL' => $email,
                'NO_OF_PEOPLE' => $no_of_people,
                'RESERVED_DATE' => $reserved_date
            );

            $emailSubject = lang('reservation_system').' : '.lang('reservation_cancellation');;
            $emailBody = '<div>'
                    . '<p>'. lang('hello').'</p> '
                    . '<p>'. lang('reservation_cancelled').'</p> '
                    . '<p>'. lang('reservation_code').' : {RESERVATION_CODE} </p> '
                    . '<p>'.lang('reservation_email_id').' : {EMAIL}</p> '
                    . '<p>'. lang('number_of_people').' : {NO_OF_PEOPLE}</p> '
                    . '<p>'. lang('reservation_date_time').' : {RESERVED_DATE}</p> '
                    . '<p>'. lang('Sincerely').'<br></p> '
                    . '<p>'.lang('reservation_team').'</p> '
                    . '<div>';


            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($email, $emailSubject, $finalEmailBody, FROM_EMAIL_ID, '', '');
        }
        return true;
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

        $isduplicate = 0;
        $emailName = trim($this->input->post('email'));

        if (!empty($emailName)) {
            $tableName = USER;
            $fields = array('COUNT(user_id) AS cntData');
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
      @Author : Maitrak Modi
      @Desc   : Paypal Details
      @Input  :
      @Output :
      @Date   : 27th Oct 2017
     */

    public function payment() {

        $custom_data = array(
            'hourly_ts_id' => $this->input->post('hourly_ts_id'),
            'weekly_ts_id' => $this->input->post('weekly_ts_id'),
            'no_of_people' => $this->input->post('no_of_people'),
            'zip_code' => $this->input->post('zip_code'),
            'email' => $this->input->post('email'),
            'current_time' => $this->input->post('current_time'),
        );

        $json_custom_data = json_encode($custom_data); // json encode

        $data = array(
            'amount' => $this->input->post('payment_amount'), // Total Amount for payment
            'json_custom_data' => $json_custom_data, // Total Amount for payment
        );

        echo $this->load->view('Usershedule/paypalform', $data, true);
        exit;
    }

    public function submitPaymentform() {
        //echo "<pre>"; print_r($_POST); exit;

        $amount = $this->input->post('ItemPrice');
        $json_custom_data = urldecode($this->input->post('customData'));

        $products = [];
        $products[0]['ItemName'] = 'TZOH'; //Item Name
        $products[0]['ItemPrice'] = $amount; //Item Price
        //$products[0]['ItemNumber'] = $_POST('itemnumber'); //Item Number
        $products[0]['ItemDesc'] = 'TZOH'; //Item Number
        $products[0]['ItemQty'] = '1'; // Item Quantity
        $products[0]['customData'] = $json_custom_data;

        $paypal = new MyPayPal();
        $paypal->SetExpressCheckOut($products, $charges = '', '1', base_url('Usershedule/success'));  // Redirect		
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Paypal on success url
      @Input  :
      @Output :
      @Date   : 27th Oct 2017
      @Updated Date   : 8th Oct 2017
     */

    public function success() {


        if ((!empty($_GET['token'])) && (!empty($_GET['PayerID']))) {

            $paypal = new MyPayPal(); // create paypal object

            $httpParsedResponse = $paypal->DoExpressCheckoutPayment(); // Method call

            foreach ($httpParsedResponse as $key => $value) {
                $httpParsedResponseAr[$key] = urldecode($value);
            }

            //echo "<pre>"; print_r($httpParsedResponseAr); exit;
            if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"])) {

                if ('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {

                    $getPaymentDetails = $paypal->GetTransactionDetailsInfo();

                    $tanscationId = $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"];
                    $tanscationAmount = $httpParsedResponseAr["PAYMENTINFO_0_AMT"];
                    $tanscationStatus = $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"];
                    $tanscationCurrency = $httpParsedResponseAr["PAYMENTINFO_0_CURRENCYCODE"];
                    $tanscationCustom = urldecode($getPaymentDetails["CUSTOM"]);

                    $postData = json_decode($tanscationCustom, true);

                    $no_of_people = (isset($postData['no_of_people'])) ? $postData['no_of_people'] : '';
                    $data = array();

                    $postData['reservationCode'] = RESERVATION_CODE . getLastInsertedScheduleUser();
                    $cancellationCode = randomnumber();
                    $reservationCode = RESERVATION_CODE . getLastInsertedScheduleUser();

                    if ($postData['no_of_people'] > getConfigUser()) {
                        $fl = 1;

                        $result_set = array();
                        for ($i = 1; $i <= 2; $i++) {
                            if ($fl == 1) {
                                $postData['no_of_people'] = getConfigUser();
                                $postData['cancellation_code'] = $cancellationCode;
                                $postData['reservation_code'] = $reservationCode;
                                $postData['flag'] = $fl;
                                $data['qrcodeInfo'][] = json_decode($this->insertSlotsforUserPayment($postData), true);
                            } else {
                                $postData['no_of_people'] = ($no_of_people - getConfigUser());
                                $postData['hourly_ts_id'] = $postData['hourly_ts_id'] + 1;
                                $postData['reservation_code'] = $reservationCode;
                                $postData['cancellation_code'] = $cancellationCode;
                                $postData['flag'] = $fl;
                                $data['qrcodeInfo'][] = json_decode($this->insertSlotsforUserPayment($postData), true);
                            }
                            $fl++;
                        }
                    } else {
                        $postData['reservation_code'] = $reservationCode;
                        $postData['cancellation_code'] = $cancellationCode;
                        $postData['flag'] = 1;
                        $data['qrcodeInfo'][] = json_decode($this->insertSlotsforUserPayment($postData), true);
                    }

                    if ($data['qrcodeInfo'][0]['status']) {

                        /* Start - Insert Payment Info in table */
                        $paymentData = array(
                            'res_code' => $postData['reservation_code'],
                            'transaction_id' => $tanscationId,
                            'transaction_amount' => $tanscationAmount,
                            'transaction_status' => $tanscationStatus,
                            'currency' => $tanscationCurrency,
                            'custom_message' => $tanscationCustom,
                            'created_at' => datetimeformat()
                        );

                        if ($this->common_model->insert(RESERVATION_PAYMENT, $paymentData)) {

                            $data['userPerHour'] = getConfigUser(); //get current config no of prople
                            $data['config_amount'] = getCofigAmount(); // get Config Amout
                            $data['config_vat'] = getConfigVat(); //get Config VAT        
                            $data['main_content'] = $this->viewname . '/paymentpage'; //load view

                            $this->parser->parse('layouts/UsersheduleTemplate', $data);
                        } else {

                            $msg = lang('somthing_went_wrong');
                            $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");

                            redirect('/');
                        }
                        /* End - Insert Payment Info in table */
                    } else {
                        $msg = lang('somthing_went_wrong');
                        $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                        redirect('/');
                    }
                } else {
                    $msg = lang('somthing_went_wrong');
                    $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    redirect('/');
                }
            } elseif ("SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
                $msg = $httpParsedResponseAr["L_LONGMESSAGE0"];
                $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/');
            } else {
                $msg = lang('somthing_went_wrong');
                $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/');
            }
        } else {
            $msg = lang('somthing_went_wrong');
            $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
            redirect('/');
        }
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Usershedule Insert
      @Input  :
      @Output :
      @Date   : 30th Oct 2017
     */

    public function insertSlotsforUserPayment($postData) {

        if (!empty($postData['email'])) {

            $pdfdata = array();
            $html = '';

            //get hourly weekly hours
            $wherestring = 'h.is_reservable =1 and h.hourly_ts_id = ' . $postData['hourly_ts_id'] . ' and concat(h.date," ",h.start_time) >= "' . $postData['current_time'] . '"';
            $fields = array('(max(u.config_no_of_people) - sum(u.no_of_people)) as available,max(u.config_no_of_people) as config_no_of_people,h.hourly_ts_id');
            $having = "(max(u.config_no_of_people) - sum(u.no_of_people)) = " . $postData['no_of_people'];
            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as u' => 'u.hourly_ts_id=h.hourly_ts_id');
            $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as h', $fields, $joinTables, 'left', '', '', '1', '', 'h.start_time', 'asc', 'u.hourly_ts_id', $wherestring, '', '', '');

            //server side validation slot available on given post data
            if (!empty($hourlyData[0]['hourly_ts_id'])) {
                //get current config no of prople
                $field = array('*');
                $match = array('config_key' => 'no_of_people_per_hour');
                $userPerHour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
                if ($hourlyData[0]['available'] == '' && $hourlyData[0]['config_no_of_people'] == '') {
                    $hourlyData[0]['available'] = $postData['no_of_people'];
                }
                if (!empty($hourlyData[0]['available']) && $hourlyData[0]['available'] >= $postData['no_of_people']) {

                    //get user id
                    $field = array('user_id');
                    $match = array('email' => trim($postData['email']));
                    $usercheck = $this->common_model->get_records(USER, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');
                    if (empty($usercheck)) {
                        //Insert user
                        $userData = array(
                            'email' => trim($postData['email']),
                            'password' => md5(randompassword()),
                            'role_type' => 5,
                            'created_at' => datetimeformat()
                        );

                        $userId = $this->common_model->insert(USER, $userData);
                    } else {
                        $userId = $usercheck[0]['user_id'];
                    }

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

                    //get schedule time
                    $wherestring = "ht.is_reservable =1 and ht.hourly_ts_id = '" . $postData['hourly_ts_id'] . "'";
                    $fields = array('DATE_FORMAT(ht.date, "%m/%d/%Y") as date,ht.hourly_ts_id,TIME_FORMAT(ht.start_time, "%h:%i %p") as start_time,TIME_FORMAT(ht.end_time, "%h:%i %p") as end_time');
                    $hourlyData = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', $fields, '', '', '', '', '1', '', 'ht.start_time', 'asc', '', $wherestring, '', '', '');

                    //Create pdf file
                    //load mPDF library
                    $this->load->library('M_pdf');


                    // Create QR code 
                    // $params['data'] = $converter->encode ($reservationCode.'time'.$postData['hourly_ts_id']);
                    $params['data'] = $postData['reservation_code'] . "," . $postData['cancellation_code'] . "," . $postData['zip_code'] . "," . $postData['email'] . "," . $postData['no_of_people'] . "," . $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'];
                    $params['level'] = 'H';
                    $params['size'] = 10;
                    $params['savename'] = $qrCodeConfig['imagedir'] . $qrcodeName;
                    $this->ci_qr_code->generate($params);

                    $population_name = "";
                    $is_agbarCustomer = lang('no');
                    if ($postData['zip_code'] != "") {
                        $population_name = getPopulationName($postData['zip_code']);
                        if (checkZipCodeisAvailable($postData['zip_code'])) {
                            $is_agbarCustomer = lang('yes');
                        } else {
                            $is_agbarCustomer = lang('no');
                        }
                    } else {
                        $population_name = $population_name;
                        $is_agbarCustomer = lang('no');
                    }

                    $pdfdata['pdfdata'] = array(
                        'title' => 'Reservation Details',
                        'email' => $postData['email'],
                        'no_of_people' => $postData['no_of_people'],
                        'reservation_code' => $postData['reservation_code'],
                        'cancellation_code' => $postData['cancellation_code'],
                        'zip_code' => $postData['zip_code'],
                        'population_name' => $population_name,
                        'is_agbarCustomer' => $is_agbarCustomer,
                        'datetime' => $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'],
                        'qr_code' => $this->config->item('imagedir') . $qrcodeName
                            // 'qr_code' => $this->config->item('qrcode_img_url') . $qrcodeName
                    );

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
                    $data = array(
                        'user_id' => $userId,
                        'weekly_ts_id' => $postData['weekly_ts_id'],
                        'hourly_ts_id' => $postData['hourly_ts_id'],
                        'no_of_people' => $postData['no_of_people'],
                        'config_no_of_people' => !empty($userPerHour[0]['value']) ? $userPerHour[0]['value'] : 0,
                        'reservation_code' => $postData['reservation_code'],
                        'qr_code' => $qrcodeName,
                        'cancellation_code' => $postData['cancellation_code'],
                        'zip_code' => $postData['zip_code'],
                        'created_at' => datetimeformat(),
                        'is_payment' => '1'
                    );
                    $response = array();
                    if ($this->common_model->insert(USER_SHEDULE_TIMESLOT, $data)) {

                        $this->sendMailToUser($data['reservation_code'], $postData['email'], $postData['no_of_people'], $hourlyData[0]['date'] . ' ' . $hourlyData[0]['start_time'], $data['cancellation_code'], $postData['zip_code'], $this->config->item('pdf_upload_path') . $pdfname);

                        $response = array('status' => 1, 'message' => "Confirm Sucessfully", 'qrcode' => $qrcodeName, 'pdf' => $pdfname);
                    } else {
                        //    echo json_encode(array('status' => 0, 'message' => lang('something_went_wrong')));
                        $response = array('status' => 0, 'message' => lang('something_went_wrong'));
                    }
                } else {
                    $response = array('status' => 0, 'message' => lang('something_went_wrong'));
                    //return false;
                }
            } else {
                $response = array('status' => 0, 'message' => lang('something_went_wrong'));
            }
        } else {
            $response = array('status' => 0, 'message' => lang('please_enter_email_id'));
        }

        $jsoneResponse = json_encode($response);

        return $jsoneResponse;
    }

    // Refund Proccess
    protected function refund($reservationCode) {

        //echo "Call"; exit;
        if (!empty($reservationCode)) {

            $field = array('*');
            $match = array('res_code' => trim($reservationCode));
            $paymentExists = $this->common_model->get_records(RESERVATION_PAYMENT, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');

            if (!empty($paymentExists)) {

                $dataInArray['transactionID'] = $paymentExists[0]['transaction_id'];
                $dataInArray['refundType'] = $this->config->item('paypal_refund_type');
                $dataInArray['currencyCode'] = $this->config->item('paypal_lib_currency_code');
                $dataInArray['memo'] = "FULL AMOUNT REFUND";

                $ref = new PayPalRefund();
                $aryRes = $ref->refundAmount($dataInArray);

                $responseArray = array();
                //$json_format = json_encode($aryRes);

                if ($aryRes['ACK'] == "Success") {

                    $refundInfo = array(
                        'is_refund' => 1,
                        'refund_transaction_id' => $aryRes['REFUNDTRANSACTIONID'],
                        'fee_refund_amount' => $aryRes['FEEREFUNDAMT'],
                        'gross_refund_amount' => $aryRes['GROSSREFUNDAMT'],
                        'net_refund_amount' => $aryRes['NETREFUNDAMT'],
                        'refund_currency_code' => $aryRes['CURRENCYCODE'],
                        'total_refunded_amount' => $aryRes['TOTALREFUNDEDAMOUNT'],
                        'refund_timestamp' => $aryRes['TIMESTAMP'],
                        'correlation_id' => $aryRes['CORRELATIONID'],
                        'refund_ack' => $aryRes['ACK'],
                        'refund_build' => $aryRes['BUILD'],
                        'refund_status' => $aryRes['REFUNDSTATUS'],
                        'pending_reason' => $aryRes['PENDINGREASON']
                    );

                    if ($this->common_model->update(RESERVATION_PAYMENT, $refundInfo, $match)) {
                        //$response = "Success";
                        $responseArray = array(
                            'status' => 1,
                            'msg' => lang('reservation_has_been_cancelled')
                        );
                    } else {
                        $responseArray = array(
                            'status' => 0,
                            'msg' => lang('something_went_wrong')
                        );
                    }
                } else {
                    //$response = $aryRes;
                    $responseArray = array(
                        'status' => 0,
                        'msg' => $aryRes['L_LONGMESSAGE0']
                    );
                }
            } else {
                $responseArray = array(
                    'status' => 1,
                    'msg' => lang('reservation_has_been_cancelled')
                );
            }
        } else {
            $responseArray = array(
                'status' => 0,
                'msg' => lang('something_went_wrong')
            );
        }

        $returnJson = json_encode($responseArray);
        //echo "<pre>"; print_r($returnJson); exit;
        return $returnJson;
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Usershedule Cancel reservation
      @Input  :
      @Output :
      @Date   : 30th Oct 2017
     */

    public function cancelSchedule() {

        $postData = $this->input->post();

        if (!empty($postData)) {

            //$lastCancellationDateTime = date('Y-m-d H:i:s', strtotime("+24 Hour")); // Future 24 hours date time
            $lastCancellationDateTime = date('Y-m-d H:i:s', strtotime(CANCELLATION_DURATION)); // Future 24 hours date time

            $email = $postData['email'];
            $cancellationCode = $postData['cancellation_code'];

            if (!empty($email) && !empty($cancellationCode)) {

                //Check user Exists or not
                $field = array('user_id');
                $match = array('email' => trim($email));
                $usercheck = $this->common_model->get_records(USER, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');
                //pr($usercheck); exit;
                if (!empty($usercheck)) {

                    $userId = $usercheck[0]['user_id'];
                    $field = array('h.date, h.start_time, TIMESTAMP(h.date, h.start_time) as new_date, u.*, rp.*');

                    $match = array('u.cancellation_code' => trim($cancellationCode), 'u.user_id' => $userId);
                    $joinTables = array(
                        HOURLY_TIMESLOT . ' as h' => 'u.hourly_ts_id = h.hourly_ts_id',
                        RESERVATION_PAYMENT . ' as rp' => 'rp.res_code = u.reservation_code'
                    );
                    $scheduleData = $this->common_model->get_records(USER_SHEDULE_TIMESLOT . ' as u', $field, $joinTables, 'left', $match, '', '', '', '', '', '', '', '', '', '');

                    //echo "<pre>"; print_r($scheduleData); exit;

                    if (!empty($scheduleData)) {

                        if ($scheduleData[0]['new_date'] >= $lastCancellationDateTime) {

                            //get cancel reservation details
                            $field = array('*');
                            $match = array('cancellation_code' => trim($cancellationCode), 'user_id' => $userId);
                            $cancelScheduleData = $this->common_model->get_records(USER_CANCEL_SHEDULE_TIMESLOT, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '1');

                            if (empty($cancelScheduleData)) {

                                $reservationCode = $scheduleData[0]['reservation_code'];
                                $totalNoOfPeople = 0;

                                $is_refund = $scheduleData[0]['is_refund'];
                                $res_code = $scheduleData[0]['res_code'];

                                $allowMail = 0;
                                if (empty($is_refund) && (!empty($res_code))) {

                                    $refundStatus = $this->refund($reservationCode); // Call Refund function
                                    $refundStatusData = json_decode($refundStatus, true);

                                    if ($refundStatusData['status']) { // on success payment
                                        for ($i = 0; $i < count($scheduleData); $i++) {

                                            $user_reservation_id = $scheduleData[$i]['user_reservation_id'];
                                            $scheduleData[$i]['created_at'] = datetimeformat();
                                            $date = $scheduleData[$i]['date'];
                                            $start_time = $scheduleData[$i]['start_time'];
                                            $totalNoOfPeople += $scheduleData[$i]['no_of_people'];
                                            //$email = $scheduleData[$i]['email'];

                                            unset($scheduleData[$i]['user_reservation_id'], $scheduleData[$i]['date'], $scheduleData[$i]['start_time'], $scheduleData[$i]['email'], $scheduleData[$i]['new_date']); // unset unwanted variable

                                            $insertCancelSlotData = array(
                                                'user_id' => $scheduleData[$i]['user_id'],
                                                'group_id' => $scheduleData[$i]['group_id'],
                                                'weekly_ts_id' => $scheduleData[$i]['weekly_ts_id'],
                                                'hourly_ts_id' => $scheduleData[$i]['hourly_ts_id'],
                                                'no_of_group_people' => $scheduleData[$i]['no_of_group_people'],
                                                'no_of_people' => $scheduleData[$i]['no_of_people'],
                                                'config_no_of_people' => $scheduleData[$i]['config_no_of_people'],
                                                'reservation_code' => $scheduleData[$i]['reservation_code'],
                                                'qr_code' => $scheduleData[$i]['qr_code'],
                                                'pdf_file_name' => $scheduleData[$i]['pdf_file_name'],
                                                'cancellation_code' => $scheduleData[$i]['cancellation_code'],
                                                'zip_code' => $scheduleData[$i]['zip_code'],
                                                'is_delete' => $scheduleData[$i]['is_delete'],
                                                'status' => $scheduleData[$i]['status'],
                                                'created_at' => ($scheduleData[$i]['created_at']) ? $scheduleData[$i]['created_at'] : datetimeformat(),
                                                'modified_at' => datetimeformat(),
                                            );

                                            // Insert tickets details into the cancellation table
                                            $cancelSchedule = $this->common_model->insert(USER_CANCEL_SHEDULE_TIMESLOT, $insertCancelSlotData);
                                            $deleteTimeSlot = $this->common_model->delete(USER_SHEDULE_TIMESLOT, array('user_reservation_id' => $user_reservation_id));
                                            $deleteUser = $this->common_model->update(USER, array('is_delete' => 1), array('email' => $email)); // Remove from reservation table
                                        }

                                        $allowMail = 1;
                                        //$this->sendMailToUserCancellation($reservationCode, $postData['email'], $totalNoOfPeople, date("m/d/Y h:i a", strtotime($date . ' ' . $start_time)));
                                        $this->session->set_flashdata('session_msg', "<div class='alert alert-success text-center'>" . $refundStatusData['msg'] . "</div>");
                                    } else {
                                        $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>" . $refundStatusData['msg'] . "</div>");
                                    }
                                } else { // for without zipcode user (No zipcode found in table)
                                    for ($i = 0; $i < count($scheduleData); $i++) {

                                        $user_reservation_id = $scheduleData[$i]['user_reservation_id'];
                                        $scheduleData[$i]['created_at'] = datetimeformat();
                                        $date = $scheduleData[$i]['date'];
                                        $start_time = $scheduleData[$i]['start_time'];
                                        $totalNoOfPeople += $scheduleData[$i]['no_of_people'];
                                        //$email = $scheduleData[$i]['email'];

                                        unset($scheduleData[$i]['user_reservation_id'], $scheduleData[$i]['date'], $scheduleData[$i]['start_time'], $scheduleData[$i]['email'], $scheduleData[$i]['new_date']); // unset unwanted variable

                                        $insertCancelSlotData = array(
                                            'user_id' => $scheduleData[$i]['user_id'],
                                            'group_id' => $scheduleData[$i]['group_id'],
                                            'weekly_ts_id' => $scheduleData[$i]['weekly_ts_id'],
                                            'hourly_ts_id' => $scheduleData[$i]['hourly_ts_id'],
                                            'no_of_group_people' => $scheduleData[$i]['no_of_group_people'],
                                            'no_of_people' => $scheduleData[$i]['no_of_people'],
                                            'config_no_of_people' => $scheduleData[$i]['config_no_of_people'],
                                            'reservation_code' => $scheduleData[$i]['reservation_code'],
                                            'qr_code' => $scheduleData[$i]['qr_code'],
                                            'pdf_file_name' => $scheduleData[$i]['pdf_file_name'],
                                            'cancellation_code' => $scheduleData[$i]['cancellation_code'],
                                            'zip_code' => $scheduleData[$i]['zip_code'],
                                            'is_delete' => $scheduleData[$i]['is_delete'],
                                            'status' => $scheduleData[$i]['status'],
                                            'created_at' => ($scheduleData[$i]['created_at']) ? $scheduleData[$i]['created_at'] : datetimeformat(),
                                            'modified_at' => datetimeformat(),
                                        );

                                        // Insert tickets details into the cancellation table
                                        $cancelSchedule = $this->common_model->insert(USER_CANCEL_SHEDULE_TIMESLOT, $insertCancelSlotData);
                                        $deleteTimeSlot = $this->common_model->delete(USER_SHEDULE_TIMESLOT, array('user_reservation_id' => $user_reservation_id));
                                        $deleteUser = $this->common_model->update(USER, array('is_delete' => 1), array('email' => $email)); // Remove from reservation table
                                    }

                                    //$allowMail = 1;
                                    //$this->sendMailToUserCancellation($reservationCode, $postData['email'], $totalNoOfPeople, date("m/d/Y h:i a", strtotime($date . ' ' . $start_time)));
                                    //$this->session->set_flashdata('session_msg', "<div class='alert alert-success text-center'>" . $refundStatusData['msg'] . "</div>");

                                    $allowMail = 1;
                                    $msg = lang('reservation_has_been_cancelled');
                                    $this->session->set_flashdata('session_msg', "<div class='alert alert-success text-center'>$msg</div>");
                                }

                                //send mail to user for cancellation of reservation
                                if ($allowMail) {
                                    $this->sendMailToUserCancellation($reservationCode, $postData['email'], $totalNoOfPeople, date("m/d/Y h:i a", strtotime($date . ' ' . $start_time)));
                                }
                            } else {
                                $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>" . lang('already_cancelled_reservation') . "</div>");
                            }
                        } else {
                            $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>" . lang('no_cancel_after_time_duration') . "</div>");
                        }
                    } else {
                        $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>" . lang('no_schedule_slot_exists') . "</div>");
                    }
                } else {
                    $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>" . lang('Invalid_email_id') . "</div>");
                }
            } else {
                $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>" . lang('Invalid_email_id') . "</div>");
                //redirect('Usershedule/cancelReservation');
            }
        }
        redirect('Usershedule/cancelReservation');
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Usershedule Download the PDF File
      @Input  :
      @Output :
      @Date   : 13th Nov 2017
     */

    public function download() {

        $fullPath = $_GET['file'];

        if ($fullPath) {
            $path_parts = pathinfo($fullPath);

            //$pdfDirPath = $this->config->item('pdf_base_path');
            // load download helder
            $this->load->helper('download');
            $filename = $path_parts["basename"];
            $data = file_get_contents($fullPath);
            force_download($filename, $data);
            exit;
        }
    }

    public function test_mail() {

        //SMTP & mail configuration
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.thezoneofhope.com',
            'smtp_port' => 25,
            'smtp_user' => 'info@thezoneofhope.com',
            'smtp_pass' => '8raPRUd3eyeQ',
            'mailtype' => 'html',
            'charset' => 'utf-8'
        );
        
        $this->load->library('email', $config);
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        //Email content
        $htmlContent = '<h1>Sending email via SMTP server</h1>';
        $htmlContent .= '<p>This email has sent via SMTP server from CodeIgniter application.</p>';

        $this->email->to('mehul.patel@c-metric.com');
        $this->email->from('info@thezoneofhope.com', 'MyWebsite');
        $this->email->subject('How to send email via SMTP server in CodeIgniter');
        $this->email->message($htmlContent);

        //echo $htmlContent; exit;
        //Send email
        if ($this->email->send()) {
            //Success email Sent
            echo $this->email->print_debugger();
            echo "Success fully ";
        } else {
            //Email Failed To Send
            echo $this->email->print_debugger();
            echo "Error generated";
        }
    }

}
