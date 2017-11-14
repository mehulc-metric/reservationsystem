<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        check_admin_login();
        $this->type = ADMIN_SITE;
        $this->viewname = ucfirst($this->router->fetch_class());
    }

    /*
      Author : Mehul Patel
      Desc  : Dashbord Reports for the Spectators & Expected influance
      Input  :
      Output :
      Date   :06/06/2017
     */

    public function index() {

        //Spectators per day(Current Date Report)
        $today = date("Y-m-d");
        $data['min'] = 0;
        $visitedUser = array();
        $hours = array("00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "24:00");
        $table = USER_SHEDULE_TIMESLOT . ' as ust';
        $where = array('ust.is_delete' => '0', " uv.is_delete " => '0', "wts.date" => $today);
        $fields = array("wts.date, TIME_FORMAT(wts.start_time, '%H:%i')as start_time, SUM(ust.no_of_people) as users");

        $params['join_tables'] = array(WEEKLY_TIMESLOT . ' as wts' => 'wts.weekly_ts_id = ust.weekly_ts_id', USER_VISIT . ' as uv' => 'uv.user_id = ust.user_id');
        $params['join_type'] = 'left';
        $groupBy = "wts.weekly_ts_id";
        $visited_user_data = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', $groupBy);

        foreach ($visited_user_data as $vistedUser) {
            array_push($visitedUser, $vistedUser['users']);
        }
        // Get Max value for the Spectators graph 
        if (!empty($visitedUser)) {
            $data['max'] = max($visitedUser);
        } else {
            $data['max'] = 0;
        }
        // Prepared Data set for ploting the value into Graph
        $dataSet = array();
        if (isset($visited_user_data[0]) && $visited_user_data[0] != "") {
            foreach ($hours as $hoursArrs) {
                $allowFlag = false;
                foreach ($visited_user_data as $visited_user_datas) {
                    if ($visited_user_datas['start_time'] === $hoursArrs) {
                        $allowFlag = true;
                        array_push($dataSet, (int) $visited_user_datas['users']);
                    }
                }
                if (!$allowFlag) {
                    array_push($dataSet, 0);
                }
            }
        }

        $data['spectators_data'] = $dataSet;

        $data['hours_data'] = $hours;

        $table_reserved = USER_SHEDULE_TIMESLOT . ' as ust';
        $where_reserved = array(" ust.is_delete " => '0', "wts.date" => $today);
        $fields_reserved = array("wts.date, TIME_FORMAT(wts.start_time, '%H:%i')as start_time,ust.user_id,SUM(ust.no_of_people) as users");
        $params_reserved['join_tables'] = array(WEEKLY_TIMESLOT . ' as wts' => 'wts.weekly_ts_id = ust.weekly_ts_id');
        $params_reserved['join_type'] = 'left';
        $groupBy_reserved = " wts.weekly_ts_id";
        $reserved_user_data = $this->common_model->get_records($table_reserved, $fields_reserved, $params_reserved['join_tables'], $params_reserved['join_type'], $where_reserved, '', '', '', '', '', $groupBy_reserved);
      
        $reservUser = array();

        $data['min_reserve'] = 0;
        foreach ($reserved_user_data as $resevedUser) {
            array_push($reservUser, $resevedUser['users']);
        }

        // Get Max value for the Expected Afluence graph 
        if (!empty($reservUser)) {
            $data['max_reserve'] = max($reservUser);
        } else {
            $data['max_reserve'] = 0;
        }

        $data_reserved_Set = array();
        if (isset($reserved_user_data[0]) && $reserved_user_data[0] != "") {
            foreach ($hours as $hoursArrs) {
                $allowFlag = false;
                foreach ($reserved_user_data as $reserved_user_datas) {
                    if ($reserved_user_datas['start_time'] === $hoursArrs) {
                        $allowFlag = true;
                        array_push($data_reserved_Set, (int) $reserved_user_datas['users']);
                    }
                }
                if (!$allowFlag) {
                    array_push($data_reserved_Set, 0);
                }
            }
        }
        $data['reserved_data'] = $data_reserved_Set;
        $data['spectators_title'] = $this->lang->line('spectators_title_per_day');
        $data['spectators_X_lebal'] = $this->lang->line('hours');
        $data['afluence_title'] = $this->lang->line('afluence_title_per_day');
        $data['afluence_X_lebal'] = $this->lang->line('hours');
        $data['footerJs'][0] = base_url('uploads/dist/js/highchart.js');
        $data['footerJs'][1] = base_url('uploads/dist/js/exporting.js');
        $data['footerJs'][2] = base_url('uploads/custom/js/Dashboard/Dashboard.js');
        $data['main_content'] = ADMIN_SITE . '/' . $this->viewname . '/' . $this->viewname;
        $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
    }

    /*
      Author : Mehul Patel
      Desc  : filterSpectators Widget
      Input  :
      Output :
      Date   :26/06/2017
     */

    function filterSpectators() {
        $result = array();
        $filterType = $this->input->post('filterType');
        if ($filterType == "Weekly" && $filterType != "Monthly") {

            //Spectators of Current Week 
            $monday = strtotime("last monday");

            $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;

            $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");

            $this_week_sd = date("Y-m-d", $monday);

            $this_week_ed = date("Y-m-d", $sunday);
            // Call the function for get list of current week dates 
            $current_week_dates = $this->getDatesFromRange($this_week_sd, $this_week_ed);

            $data['min'] = 0;
            $visitedUser = array();
            $hours = $current_week_dates;
            $table = USER_SHEDULE_TIMESLOT . ' as ust';
            //$where = array('ust.is_delete' => '0', " uv.is_delete " => '0', "YEARWEEK(`wts`.`date`) = YEARWEEK(NOW())");
            $where = "ust.is_delete = 0 AND uv.is_delete = 0 AND YEARWEEK(`wts`.`date`) = YEARWEEK(NOW())";
            $fields = array("wts.date, TIME_FORMAT(wts.start_time, '%H:%i')as start_time, SUM(ust.no_of_people) as users");

            $params['join_tables'] = array(WEEKLY_TIMESLOT . ' as wts' => 'wts.weekly_ts_id = ust.weekly_ts_id', USER_VISIT . ' as uv' => 'uv.user_id = ust.user_id');
            $params['join_type'] = 'left';
            $groupBy = "wts.date";
            $visited_user_data = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', $groupBy, $where);

            foreach ($visited_user_data as $vistedUser) {
                array_push($visitedUser, $vistedUser['users']);
            }
            // Get Max value for the Spectators graph 
            if (!empty($visitedUser)) {
                $data['max'] = max($visitedUser);
            } else {
                $data['max'] = 0;
            }
            // Prepared Data set for ploting the value into Graph
            $dataSet = array();
            if (isset($visited_user_data[0]) && $visited_user_data[0] != "") {
                foreach ($hours as $hoursArrs) {
                    $allowFlag = false;
                    foreach ($visited_user_data as $visited_user_datas) {
                        if ($visited_user_datas['date'] === $hoursArrs) {
                            $allowFlag = true;
                            array_push($dataSet, (int) $visited_user_datas['users']);
                        }
                    }
                    if (!$allowFlag) {
                        array_push($dataSet, 0);
                    }
                }
            }

            $data['spectators_data'] = $dataSet;

            $data['hours_data'] = $hours;
            $data['spectators_title'] = $this->lang->line('spectators_title_current_week');
            $data['spectators_X_lebal'] = $this->lang->line('date');

            array_push($result, $data);
            if ($this->input->is_ajax_request()) {
                echo json_encode($result, JSON_NUMERIC_CHECK);
                die();
            }
        } elseif ($filterType != "Weekly" && $filterType == "Monthly") {

            //Spectators of Current Week            
            $data['min'] = 0;
            $visitedUser = array();
            $hours = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $table = USER_SHEDULE_TIMESLOT . ' as ust';
            $where = array('ust.is_delete' => '0', " uv.is_delete " => '0');
            $fields = array(" MONTHNAME(wts.date)AS month, TIME_FORMAT(wts.start_time, '%H:%i')as start_time, SUM(ust.no_of_people) as users");

            $params['join_tables'] = array(WEEKLY_TIMESLOT . ' as wts' => 'wts.weekly_ts_id = ust.weekly_ts_id', USER_VISIT . ' as uv' => 'uv.user_id = ust.user_id');
            $params['join_type'] = 'left';
            $groupBy = "";
            $visited_user_data = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', $groupBy);
           
            foreach ($visited_user_data as $vistedUser) {
                array_push($visitedUser, $vistedUser['users']);
            }
            // Get Max value for the Spectators graph 
            if (!empty($visitedUser)) {
                $data['max'] = max($visitedUser);
            } else {
                $data['max'] = 0;
            }
            // Prepared Data set for ploting the value into Graph
            $dataSet = array();
            if (isset($visited_user_data[0]) && $visited_user_data[0] != "") {
                foreach ($hours as $hoursArrs) {
                    $allowFlag = false;
                    foreach ($visited_user_data as $visited_user_datas) {
                        if ($visited_user_datas['month'] === $hoursArrs) {
                            $allowFlag = true;
                            array_push($dataSet, (int) $visited_user_datas['users']);
                        }
                    }
                    if (!$allowFlag) {
                        array_push($dataSet, 0);
                    }
                }
            }

            $data['spectators_data'] = $dataSet;
            $data['hours_data'] = $hours;
            $data['spectators_title'] = $this->lang->line('spectators_title_per_month');
            $data['spectators_X_lebal'] = $this->lang->line('month');
            array_push($result, $data);
            if ($this->input->is_ajax_request()) {
                echo json_encode($result, JSON_NUMERIC_CHECK);
                die();
            }
        } else {
            //Spectators per day
            $today = "'" . date("Y-m-d") . "'";
            $data['min'] = 0;
            $visitedUser = array();
            $hours = array("00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "24:00");
            $table = USER_SHEDULE_TIMESLOT . ' as ust';
            $where = array('ust.is_delete' => '0', " uv.is_delete " => '0', "wts.date" => $today);
            $fields = array("wts.date, TIME_FORMAT(wts.start_time, '%H:%i')as start_time, SUM(ust.no_of_people) as users");

            $params['join_tables'] = array(WEEKLY_TIMESLOT . ' as wts' => 'wts.weekly_ts_id = ust.weekly_ts_id', USER_VISIT . ' as uv' => 'uv.user_id = ust.user_id');
            $params['join_type'] = 'left';
            $groupBy = "wts.weekly_ts_id";
            $visited_user_data = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', $groupBy);

            foreach ($visited_user_data as $vistedUser) {
                array_push($visitedUser, $vistedUser['users']);
            }
            // Get Max value for the Spectators graph 
            if (!empty($visitedUser)) {
                $data['max'] = max($visitedUser);
            } else {
                $data['max'] = 0;
            }
            // Prepared Data set for ploting the value into Graph
            $dataSet = array();
            if (isset($visited_user_data[0]) && $visited_user_data[0] != "") {
                foreach ($hours as $hoursArrs) {
                    $allowFlag = false;
                    foreach ($visited_user_data as $visited_user_datas) {
                        if ($visited_user_datas['start_time'] === $hoursArrs) {
                            $allowFlag = true;
                            array_push($dataSet, (int) $visited_user_datas['users']);
                        }
                    }
                    if (!$allowFlag) {
                        array_push($dataSet, 0);
                    }
                }
            }

            $data['spectators_data'] = $dataSet;
            $data['hours_data'] = $hours;
            $data['spectators_title'] = $this->lang->line('spectators_title_per_day');
            $data['spectators_X_lebal'] = $this->lang->line('hours');
            array_push($result, $data);
            if ($this->input->is_ajax_request()) {
                echo json_encode($result, JSON_NUMERIC_CHECK);
                die();
            }
        }
    }

    /*
      Author : Mehul Patel
      Desc  : getDatesFromRange
      Input  : Week's Start Date, week's End date,Date format
      Output : Return array of current week's date list
      Date   :26/06/2017
     */

    function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach ($period as $date) {
            $array[] = $date->format($format);
        }
        return $array;
    }

    /*
      Author : Mehul Patel
      Desc  : Filter Expected Afluence Widget
      Input  :
      Output :
      Date   :27/06/2017
     */

    function filterExpectedAfluence() {
        $result = array();
        $filterType = $this->input->post('filterType');

        if ($filterType == "Weekly" && $filterType != "Monthly") {

            //Expected Afluence of Current Week 
            $monday = strtotime("last monday");

            $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;

            $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");

            $this_week_sd = date("Y-m-d", $monday);

            $this_week_ed = date("Y-m-d", $sunday);
            // Call the function for get list of current week dates 
            $current_week_dates = $this->getDatesFromRange($this_week_sd, $this_week_ed);

            $data['min'] = 0;
            $visitedUser = array();
            $hours = $current_week_dates;
            $table_reserved = USER_SHEDULE_TIMESLOT . ' as ust';
            // $where_reserved = array(" ust.is_delete " => '0', "wts.date" => $today);
            $where_reserved = "ust.is_delete = 0 AND YEARWEEK(`wts`.`date`) = YEARWEEK(NOW())";
            $fields_reserved = array("wts.date, TIME_FORMAT(wts.start_time, '%H:%i')as start_time,ust.user_id,SUM(ust.no_of_people) as users");
            $params_reserved['join_tables'] = array(WEEKLY_TIMESLOT . ' as wts' => 'wts.weekly_ts_id = ust.weekly_ts_id');
            $params_reserved['join_type'] = 'left';
            $groupBy_reserved = " wts.date";
            $visited_user_data = $this->common_model->get_records($table_reserved, $fields_reserved, $params_reserved['join_tables'], $params_reserved['join_type'], '', '', '', '', '', '', $groupBy_reserved, $where_reserved);
         
            foreach ($visited_user_data as $vistedUser) {
                array_push($visitedUser, $vistedUser['users']);
            }
            // Get Max value for the Expected Afluence graph 
            if (!empty($visitedUser)) {
                $data['max'] = max($visitedUser);
            } else {
                $data['max'] = 0;
            }
            // Prepared Data set for ploting the value into Graph
            $dataSet = array();
            if (isset($visited_user_data[0]) && $visited_user_data[0] != "") {
                foreach ($hours as $hoursArrs) {
                    $allowFlag = false;
                    foreach ($visited_user_data as $visited_user_datas) {
                        if ($visited_user_datas['date'] === $hoursArrs) {
                            $allowFlag = true;
                            array_push($dataSet, (int) $visited_user_datas['users']);
                        }
                    }
                    if (!$allowFlag) {
                        array_push($dataSet, 0);
                    }
                }
            }

            $data['spectators_data'] = $dataSet;

            $data['hours_data'] = $hours;
            $data['afluence_title'] = $this->lang->line('afluence_title_current_week');
            $data['afluence_X_lebal'] = $this->lang->line('date');
            array_push($result, $data);
            if ($this->input->is_ajax_request()) {
                echo json_encode($result, JSON_NUMERIC_CHECK);
                die();
            }
        } elseif ($filterType != "Weekly" && $filterType == "Monthly") {

            //Expected Afluence of year            
            $data['min'] = 0;
            $visitedUser = array();
            $hours = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $table_reserved = USER_SHEDULE_TIMESLOT . ' as ust';

            $where_reserved = "ust.is_delete = 0";
            $fields_reserved = array(" MONTHNAME(wts.date)AS month, TIME_FORMAT(wts.start_time, '%H:%i')as start_time,ust.user_id, SUM(ust.no_of_people) as users");
            $params_reserved['join_tables'] = array(WEEKLY_TIMESLOT . ' as wts' => 'wts.weekly_ts_id = ust.weekly_ts_id');
            $params_reserved['join_type'] = 'left';
            $groupBy_reserved = "MONTH(wts.date)";
            $visited_user_data = $this->common_model->get_records($table_reserved, $fields_reserved, $params_reserved['join_tables'], $params_reserved['join_type'], '', '', '', '', '', '', $groupBy_reserved, $where_reserved);
           // echo "Query :".$this->db->last_query(); exit();

            foreach ($visited_user_data as $vistedUser) {
                array_push($visitedUser, $vistedUser['users']);
            }
            // Get Max value for the Expected Afluence graph 
            if (!empty($visitedUser)) {
                $data['max'] = max($visitedUser);
            } else {
                $data['max'] = 0;
            }
            // Prepared Data set for ploting the value into Graph
            $dataSet = array();
            if (isset($visited_user_data[0]) && $visited_user_data[0] != "") {
                foreach ($hours as $hoursArrs) {
                    $allowFlag = false;
                    foreach ($visited_user_data as $visited_user_datas) {
                        if ($visited_user_datas['month'] === $hoursArrs) {
                            $allowFlag = true;
                            array_push($dataSet, (int) $visited_user_datas['users']);
                        }
                    }
                    if (!$allowFlag) {
                        array_push($dataSet, 0);
                    }
                }
            }

            $data['spectators_data'] = $dataSet;
            $data['hours_data'] = $hours;

            $data['afluence_title'] = $this->lang->line('afluence_title_per_month');
            $data['afluence_X_lebal'] = $this->lang->line('month');

            array_push($result, $data);
            if ($this->input->is_ajax_request()) {
                echo json_encode($result, JSON_NUMERIC_CHECK);
                die();
            }
        } else {
            //Expected Afluence per day
            $today = date("Y-m-d");
            $data['min'] = 0;
            $visitedUser = array();
            $hours = array("00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00", "24:00");

            $table_reserved = USER_SHEDULE_TIMESLOT . ' as ust';
            $where_reserved = array(" ust.is_delete " => '0', "wts.date" => $today);
            $fields_reserved = array("wts.date, TIME_FORMAT(wts.start_time, '%H:%i')as start_time,ust.user_id,SUM(ust.no_of_people) as users");
            $params_reserved['join_tables'] = array(WEEKLY_TIMESLOT . ' as wts' => 'wts.weekly_ts_id = ust.weekly_ts_id');
            $params_reserved['join_type'] = 'left';
            $groupBy_reserved = " wts.weekly_ts_id";
            $visited_user_data = $this->common_model->get_records($table_reserved, $fields_reserved, $params_reserved['join_tables'], $params_reserved['join_type'], $where_reserved, '', '', '', '', '', $groupBy_reserved);


            foreach ($visited_user_data as $vistedUser) {
                array_push($visitedUser, $vistedUser['users']);
            }
            // Get Max value for the Expected Afluence graph 
            if (!empty($visitedUser)) {
                $data['max'] = max($visitedUser);
            } else {
                $data['max'] = 0;
            }
            // Prepared Data set for ploting the value into Graph
            $dataSet = array();
            if (isset($visited_user_data[0]) && $visited_user_data[0] != "") {
                foreach ($hours as $hoursArrs) {
                    $allowFlag = false;
                    foreach ($visited_user_data as $visited_user_datas) {
                        if ($visited_user_datas['start_time'] === $hoursArrs) {
                            $allowFlag = true;
                            array_push($dataSet, (int) $visited_user_datas['users']);
                        }
                    }
                    if (!$allowFlag) {
                        array_push($dataSet, 0);
                    }
                }
            }
            $data['spectators_data'] = $dataSet;
            $data['hours_data'] = $hours;

            $data['afluence_title'] = $this->lang->line('afluence_title_per_day');
            $data['afluence_X_lebal'] = $this->lang->line('hours');

            array_push($result, $data);
            if ($this->input->is_ajax_request()) {
                echo json_encode($result, JSON_NUMERIC_CHECK);
                die();
            }
        }
    }

}
