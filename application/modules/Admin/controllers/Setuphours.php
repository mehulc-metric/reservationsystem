<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setuphours extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (checkAdminPermission('Setuphours', 'view') == false) {
            redirect('/Admin/Dashboard');
        }
        check_admin_login();
        $this->type = ADMIN_SITE;
        $this->viewname = ucfirst($this->router->fetch_class());
    }

    /*
      @Author : Niral Patel
      @Desc   : Setuphours list
      @Input  :
      @Output :
      @Date   : 3/07/2017
     */

    public function index() {
        //Insert dynamic weekly slot 
        $postData = $this->input->post();
        $data = array();
        if (!empty($postData['date']) && $postData['nextPrev']) {
            if ($postData['nextPrev'] == 'next') {
                $data['daterange'] = array(date('Y-m-d', strtotime("+1 day", strtotime($postData['date']))), date('Y-m-d', strtotime("+7 day", strtotime($postData['date']))));
            } else {
                $data['daterange'] = array(date('Y-m-d', strtotime("-7 day", strtotime($postData['date']))), date('Y-m-d', strtotime("-1 day", strtotime($postData['date']))));
            }
        } else {
            $date = date('Y-m-d');
            $data['daterange'] = $this->getStartAndEndDate(date("W", strtotime($date)), date("Y", strtotime($date)));
        }
        //get open weekly hours
        $wherestring = 'is_open =1 and (date >= "' . $data['daterange'][0] . '" and date <= "' . $data['daterange'][1] . '")';
        $fields = array('ws.weekly_ts_id,ws.date,ws.is_open,ws.start_time,ws.end_time,count(us.weekly_ts_id) as totaluser');
        $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.weekly_ts_id=ws.weekly_ts_id');
        $data['openWeeklyData'] = $this->common_model->get_records(WEEKLY_TIMESLOT . ' as ws', $fields, $joinTables, 'left', '', '', '', '', 'ws.start_date,ws.start_time', '', 'ws.weekly_ts_id', $wherestring, '', '', '');

        //get close weekly hours
        $wherestring = 'is_open =0 and (date >= "' . $data['daterange'][0] . '" and date <= "' . $data['daterange'][1] . '")';
        $fields = array('ws.weekly_ts_id,ws.date,ws.is_open,ws.start_time,ws.end_time');
        $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.weekly_ts_id=ws.weekly_ts_id');
        $data['closeWeeklyData'] = $this->common_model->get_records(WEEKLY_TIMESLOT . ' as ws', $fields, '', '', '', '', '', '', '', '', '', $wherestring, '', '', '');

        if ($this->input->is_ajax_request()) {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $data['footerJs'][0] = base_url('uploads/custom/js/Setuphours/Setuphours.js');
            $this->load->view($this->type . '/assets/template', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Setuphours form
      @Input  :
      @Output :
      @Date   : 3/07/2017
     */

    public function add() {


        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/add';
            $data['footerJs'][0] = base_url('uploads/custom/js/Setuphours/Setuphours.js');
            $this->load->view($this->type . '/assets/template', $data);
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Setuphours form
      @Input  :
      @Output :
      @Date   : 4/07/2017
     */

    function editHour($weekly_ts_id) {
        $postData = $this->input->post();

        //check weekly hours
        $match = array('weekly_ts_id' => $weekly_ts_id);
        $data['weeklyData'] = $this->common_model->get_records(WEEKLY_TIMESLOT . ' as ws', array('weekly_ts_id,is_open,date,start_time,end_time'), '', '', $match, '', '', '', '', '', '', '', '', '', '');

        $this->load->view($this->type . '/' . $this->viewname . '/ajax_edit', $data);
    }

    /*
      @Author : Niral Patel
      @Desc   : Update weekly hour
      @Input  :
      @Output :
      @Date   : 4/07/2017
     */

    function updateHour() {
        $postData = $this->input->post();

        $weeklyUpdate = array(
            'weekly_ts_id' => $postData['weekly_ts_id'],
            'is_open' => $postData['is_open'],
            'modified_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
            'modified_at' => date("Y-m-d H:i:s")
        );
        $this->common_model->update(WEEKLY_TIMESLOT, $weeklyUpdate, array('weekly_ts_id' => $postData['weekly_ts_id']));
        $hourlyUpdate = array(
            'is_reservable' => $postData['is_open'],
            'modified_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
            'modified_at' => date("Y-m-d H:i:s")
        );
        $this->common_model->update(HOURLY_TIMESLOT, $hourlyUpdate, array('weekly_ts_id' => $postData['weekly_ts_id']));
    }

    /*
      @Author : Niral Patel
      @Desc   : Delete weekly hour
      @Input  :
      @Output :
      @Date   : 4/07/2017
     */

    function deleteHour() {
        $postData = $this->input->post();

        if ($this->common_model->delete(WEEKLY_TIMESLOT, array('weekly_ts_id' => $postData['weekly_ts_id']))) {
            $this->common_model->delete(HOURLY_TIMESLOT, array('weekly_ts_id' => $postData['weekly_ts_id']));
            echo $msg = '1';
        } else {
            // error
            echo $msg = '0';
        }
    }

    /*
      @Author : Niral Patel
      @Desc   : Setuphours form
      @Input  :
      @Output :
      @Date   : 3/07/2017
     */

    function getStartAndEndDate($week, $year) {
        $dates[0] = date("Y-m-d", strtotime($year . 'W' . str_pad($week, 2, 0, STR_PAD_LEFT) . ' -1 days'));
        $dates[1] = date("Y-m-d", strtotime($year . 'W' . str_pad($week, 2, 0, STR_PAD_LEFT) . ' +5 days'));
        return $dates;
    }

    public function insert() {

        $postData = $this->input->post();

        //check weekday selected or not
        if (!isset($postData['weekday'])) {
            $postData['weekday'] = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        }

        if ($postData['start_time'] <= $postData['end_time']) {
            if ($postData['type'] == 'monthly') {
                $currMonthNo = date("m", time());
                $monthNo = $postData['month'];
                if ($monthNo >= $currMonthNo) {
                    $year = date("Y", time());
                } else {
                    $year = date('Y', strtotime('+1 years'));
                }
                /* $first_date = date('d-m-Y',strtotime('first day of '.strtolower($postData['month']).' '.$year));
                  $last_date = date('d-m-Y',strtotime('last day of '.strtolower($postData['month']).' '.$year)); */
                $first_date = date('01-' . $monthNo . '-' . $year);
                $last_date = date(date('t', strtotime($first_date)) . '-' . $monthNo . '-' . $year);
                $dates = $this->createDateRangeArray($first_date, $last_date, $postData['weekday']);
            } else {
                $dates = $this->createDateRangeArray($postData['start_date'], $postData['end_date'], $postData['weekday']);
            }

            if (!empty($dates)) {
                foreach ($dates as $date) {
                    $startTime = date('H', strtotime($postData['start_time']));
                    $endTime = date('H', strtotime($postData['end_time']));

                    if ($startTime != $endTime) {
                        if ($startTime == "00" && $endTime == "23") {
                            $startTime = $startTime;
                            $endTime = $endTime;
                        } else {
                            if ($endTime != "00") {
                                $endTime = $endTime - 1;
                                if ($endTime < 10) {
                                    $endTime = "0" . $endTime;
                                } else {
                                    $endTime = $endTime;
                                }
                            } else {
                                $endTime = $endTime;
                            }
                        }
                    }
                    if ($date . ' ' . $postData['start_time'] > datetimeformat()) {
                        for ($i = $startTime; $i <= $endTime; $i++) {
                            $hourlyData = array();
                            $hourlyUpdateData = array();
                            $hStastTime = $i;
                            $hEndTime = $hStastTime + 1;
                            //check weekly hours
                            $match = array('ws.date' => $date, 'ws.start_time' => $hStastTime . ':00:00');
                            $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.weekly_ts_id=ws.weekly_ts_id');
                            $weeklyData = $this->common_model->get_records(WEEKLY_TIMESLOT . ' as ws', array('ws.weekly_ts_id,ws.is_open,count(us.weekly_ts_id) as totaluser'), $joinTables, 'left', $match, '', '', '', '', '', 'ws.weekly_ts_id', '', '', '', '');

                            if (!empty($weeklyData)) { //update if exist
                                if ($weeklyData[0]['totaluser'] == 0) {
                                    if ($weeklyData[0]['is_open'] != $postData['is_open']) {
                                        $weeklyUpdate = array(
                                            'is_open' => $postData['is_open']
                                        );
                                        $this->common_model->update(WEEKLY_TIMESLOT, $weeklyUpdate, array('weekly_ts_id' => $weeklyData[0]['weekly_ts_id']));
                                    }
                                }
                                $weeklyId = $weeklyData[0]['weekly_ts_id'];
                            } else { //insert weekly slot
                                $weeklyData = array(
                                    'date' => $date,
                                    'is_open' => $postData['is_open'],
                                    'start_time' => $hStastTime . ':00:00',
                                    'end_time' => $hEndTime . ':00:00',
                                    'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                                    'created_at' => date("Y-m-d H:i:s")
                                );

                                $weeklyId = $this->common_model->insert(WEEKLY_TIMESLOT, $weeklyData);
                            }
                            if (!empty($weeklyId)) {
                                //$weeklyId =1;
                                //insert hourly slot duration
                                //get no_of_slot_per_hour
                                
                                $wherestring1 = '"'.$date.'" BETWEEN week_start_date and week_end_date';
                                $closeWeeklyData = $this->common_model->get_records(WEEKLT_TOTAL_SLOT, array('total_slot'), '', '', '', '', '', '', '', '', '', $wherestring1, '', '', '');
                                if(!empty($closeWeeklyData[0]['total_slot'])){
                                    $slotPerHour[0]['value'] = $closeWeeklyData[0]['total_slot'];
                                }else{
                                    $field = array('*');
                                    $match = array('config_key' => 'no_of_slot_per_hour');
                                    $slotPerHour = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
                              
                                }
                                  $slotDuration = 60 / $slotPerHour[0]['value'];

                                $stTime = '';
                                $etTime = '';
                                /* Insert hourly slot as per config total slot */
                                for ($j = 1; $j <= $slotPerHour[0]['value']; $j++) {
                                    $stTime = (empty($etTime)) ? $hStastTime . ':00:00' : $etTime;
                                    $etTime = date('H:i:s', strtotime("+" . $slotDuration . " minutes", strtotime($stTime)));
                                    //get total exist or not
                                    $match = array('date' => $date, 'start_time' => $stTime, 'end_time' => $etTime);
                                    $joinTables = array(USER_SHEDULE_TIMESLOT . ' as us' => 'us.hourly_ts_id=ht.hourly_ts_id');
                                    $hourlyCount = $this->common_model->get_records(HOURLY_TIMESLOT . ' as ht', array('ht.weekly_ts_id,ht.hourly_ts_id,count(us.hourly_ts_id) as totalhour'), $joinTables, 'left', $match, '', '', '', '', '', '', '', '', '', '');

                                    //insert
                                    if (empty($hourlyCount[0]['hourly_ts_id']) && $hourlyCount[0]['totalhour'] == 0) {
                                        $hourlyData[] = array(
                                            'weekly_ts_id' => $weeklyId,
                                            'date' => $date,
                                            'start_time' => $stTime,
                                            'end_time' => $etTime,
                                            'is_reservable' => $postData['is_open'],
                                            'created_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                                            'created_at' => date("Y-m-d H:i:s")
                                        );
                                    } else { //update
                                        if ($hourlyCount[0]['totalhour'] == 0) {
                                            $hourlyUpdateData[] = array(
                                                'hourly_ts_id' => $hourlyCount[0]['hourly_ts_id'],
                                                'is_reservable' => $postData['is_open'],
                                                'modified_by' => $this->session->userdata['reservation_admin_session']['admin_id'],
                                                'modified_at' => date("Y-m-d H:i:s")
                                            );
                                        }
                                    }
                                }
                                if (!empty($hourlyUpdateData)) {
                                    $this->common_model->update_batch(HOURLY_TIMESLOT, $hourlyUpdateData, 'hourly_ts_id');
                                }
                                //insert hourly slot
                                if (!empty($hourlyData)) {
                                    $hourlyId = $this->common_model->insert_batch(HOURLY_TIMESLOT, $hourlyData);
                                }

                                //Insert dynamic weekly slot 
                                $daterange = $this->getStartAndEndDate(date("W", strtotime($date)), date("Y", strtotime($date)));
                                //check weekly hours
                                $match = array('week_start_date' => $daterange[0]);
                                $weeklyTotalData = $this->common_model->get_records(WEEKLT_TOTAL_SLOT, array('total_slot_id'), '', '', $match, '', '', '', '', '', '', '', '', '', '1');

                                if (empty($weeklyTotalData)) {
                                    //get no_of_slot_per_hour
                                    $field = array('*');
                                    $match = array('config_key' => 'no_of_slot_per_hour');
                                    $slot = $this->common_model->get_records(CONFIG_TABLE, $field, '', '', $match);
                                    $weeklyData = array(
                                        'total_slot' => !empty($slotPerHour[0]['value']) ? $slotPerHour[0]['value'] : '',
                                        'week_start_date' => $daterange[0],
                                        'week_end_date' => $daterange[1],
                                        'created_at' => date("Y-m-d H:i:s")
                                    );

                                    $weeklyTotalId = $this->common_model->insert(WEEKLT_TOTAL_SLOT, $weeklyData);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('something_went_wrong') . "</div>");
        }
        redirect($this->type . '/' . $this->viewname);
    }

    function createDateRangeArray($strDateFrom, $strDateTo, $days, $format = "Y-m-d") {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script

        $strDateTo = date("Y-m-d", strtotime("$strDateTo +1 day"));
        $begin = new DateTime($strDateFrom);
        $end = new DateTime($strDateTo);

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $date = $date->format($format);
            $day = date('l', strtotime($date));
            if (in_array($day, $days)) {
                $range[] = $date;
            }
        }

        return $range;
    }

}
