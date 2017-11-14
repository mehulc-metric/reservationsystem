<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReservedUserList extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (checkAdminPermission('ReservedUserList', 'view') == false) {
            redirect('/Admin/Dashboard');
        }
        check_admin_login();
        $this->type = ADMIN_SITE;
        $this->viewname = ucfirst($this->router->fetch_class());
        $this->load->model('ReservedUserList_model');
        $this->load->library(array('paypal_lib', 'PayPalRefund'));
    }

    /*
      @Author : Mehul Patel
      @Desc   : ReservedUserList form
      @Input  :
      @Output :
      @Date   : 13/06/2017
     */

    public function index($page = '') {

        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $daterange = $this->input->post('daterange');

        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('ReservedUserList_sortsearchpage_data');
        }

        $searchsort_session = $this->session->userdata('ReservedUserList_sortsearchpage_data');
        //Sorting
        if (!empty($sortfield) && !empty($sortby)) {
            $data['sortfield'] = $sortfield;
            $data['sortby'] = $sortby;
        } else {
            if (!empty($searchsort_session['sortfield'])) {
                $data['sortfield'] = $searchsort_session['sortfield'];
                $data['sortby'] = $searchsort_session['sortby'];
                $sortfield = $searchsort_session['sortfield'];
                $sortby = $searchsort_session['sortby'];
            } else {
                $sortfield = 'user_reservation_id';
                $sortby = 'desc';
                $data['sortfield'] = $sortfield;
                $data['sortby'] = $sortby;
            }
        }
        //Search text
        if (!empty($searchtext)) {
            $data['searchtext'] = $searchtext;
        } else {
            if (empty($allflag) && !empty($searchsort_session['searchtext'])) {
                $data['searchtext'] = $searchsort_session['searchtext'];
                $searchtext = $data['searchtext'];
            } else {
                $data['searchtext'] = '';
            }
        }

        if (!empty($perpage) && $perpage != 'null') {
            //$perpage = $this->input->post('perpage');
            $data['perpage'] = $perpage;
            $config['per_page'] = $perpage;
        } else {
            if (!empty($searchsort_session['perpage'])) {
                $data['perpage'] = trim($searchsort_session['perpage']);
                $config['per_page'] = trim($searchsort_session['perpage']);
            } else {
                $config['per_page'] = '10';
                $data['perpage'] = '10';
            }
        }
        //pagination configuration
        $config['first_link'] = 'First';
        $config['base_url'] = base_url() . $this->type . '/' . $this->viewname . '/index';
        //$config['base_url'] = base_url($this->module . '/' . $this->viewname . '/index');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }
        //Query
        
        $table = USER_SHEDULE_TIMESLOT . ' as us ';
        $is_payment[] = '0';
        $is_payment[] = '1';
        $where_in = array("us.is_payment" => $is_payment);
        
        $where = array("c.is_delete" => '0', "us.status" => '1', "us.is_delete" => '0', "rp.is_refund" => NULL);
        $fields = array("us.user_reservation_id, c.user_id, us.reservation_code,uzc.population, us.cancellation_code, us.zip_code, c.email, SUM(us.no_of_people) as no_of_people, ht.date, ht.start_time, ht.end_time, gp.group_name, TIMESTAMP(ht.date, ht.start_time) as new_date, rp.*");
        $params['join_tables'] = array(
            USER . ' as c' => 'c.user_id = us.user_id',
            HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = us.hourly_ts_id',
            GROUP_RESERVATION . ' as gp' => 'gp.group_id = us.group_id',
            UPLOAD_ZIP_CODE . ' as uzc' => 'uzc.zip_code = us.zip_code',
            RESERVATION_PAYMENT . ' as rp' => 'rp.res_code = us.reservation_code'
        );
        $params['join_type'] = 'left';

        $params['group_by'] = 'us.reservation_code';

        $where_search = '';
        $applyCondition = '';
        if (!empty($searchtext) || !empty($daterange)) {

            // Apply condtition type 
            if (!empty($searchtext) && !empty($daterange)) {
                $applyCondition = " AND ";
            }

            // Search Text filter
            if (!empty($searchtext)) {

                $searchtext = html_entity_decode(trim($searchtext));

                $where_search .= '(
						c.email LIKE "%' . $searchtext . '%"
						OR us.reservation_code LIKE "%' . $searchtext . '%"
						OR us.zip_code LIKE "%' . $searchtext . '%"
                                                OR uzc.population LIKE "%' . $searchtext . '%"
						OR c.email LIKE "%' . $searchtext . '%" 
						OR gp.group_name LIKE "%' . $searchtext . '%"
						OR rp.transaction_id LIKE "%' . $searchtext . '%"
						OR rp.transaction_amount LIKE "%' . $searchtext . '%"
					)';
            }

            // Date Range filter
            if (!empty($daterange)) {

                $explodeDate = explode('-', trim($daterange));

                $startDateTime = trim($explodeDate[0]);
                $endDateTime = trim($explodeDate[1]);

                $updateStartDateFormat = date('Y-m-d H:i:s', strtotime($startDateTime)); // Set date time format
                $updateEndDateFormat = date('Y-m-d H:i:s', strtotime($endDateTime)); // Set date time format
                //$where_search .=  $applyCondition." (TIMESTAMP(ht.date, ht.start_time) >= '".$updateStartDateFormat."' AND TIMESTAMP(ht.date, ht.start_time) < '".$updateEndDateFormat."' )" ;
                $where_search .= $applyCondition . " (TIMESTAMP(ht.date, ht.start_time) BETWEEN '" . $updateStartDateFormat . "' AND '" . $updateEndDateFormat . "' )";
            }

            //$match = array("c.is_delete" => '0', "ucs.status" => '1', "ucs.is_delete" => '0');
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, $params['group_by'], $where_search,'',$where_in);
           
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', $sortfield, $sortby, $params['group_by'], $where_search, '', $where_in, '1');
        } else {
            //$match = array('c.is_delete' => 0);
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, $params['group_by'], '','',$where_in);
          
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', $sortfield, $sortby, $params['group_by'], '', '', $where_in, '1');
        }
            //echo "<pre>"; print_r($data['datalist']); exit;
            // echo $this->db->last_query(); exit;	
        
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        //$data['footerJs'][0] = base_url('uploads/custom/js/ReservedUserList/ReservedUserList.js');

        $data['footerJs'] = array(
            '0' => base_url('uploads/custom/js/ReservedUserList/ReservedUserList.js'),
            '1' => base_url('uploads/assets/js/admin/moment.min.js'),
            '2' => base_url('uploads/assets/js/admin/daterangepicker.js'),
        );

        $data['footerCss'] = array(
            '0' => base_url('uploads/assets/css/admin/daterangepicker.css'),
        );

        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);

        $this->session->set_userdata('ReservedUserList_sortsearchpage_data', $sortsearchpage_data);

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type . '/assets/template', $data);
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : SendMail To User
      @Input  : $reservation_code,$email,$no_of_people,$reserved_date
      @Output :
      @Date   : 06/06/2017
     */

    private function sendMailToUser($reservation_code, $email, $no_of_people, $reserved_date, $cancellation_code, $attachment) {

        if (!empty($email)) {
            $find = array(
                '{RESERVATION_CODE}',
                '{EMAIL}',
                '{NO_OF_PEOPLE}',
                '{RESERVED_DATE}',
                '{CANCELLATION_CODE}',
            );

            $replace = array(
                'RESERVATION_CODE' => $reservation_code,
                'EMAIL' => $email,
                'NO_OF_PEOPLE' => $no_of_people,
                'RESERVED_DATE' => $reserved_date,
                'CANCELLATION_CODE' => $cancellation_code,
            );

            $emailSubject = lang('reservation_system').':'.lang('reservation_reminder');
            $emailBody = '<div>'
                    . '<p>'. lang('hello').'</p> '
                    . '<p>'. lang('reservation_reminder_msg1').'</p> '
                    . '<p>'.lang('reservation_code').' : {RESERVATION_CODE} </p> '
                    . '<p>'. lang('reservation_email_id').' : {EMAIL}</p> '
                    . '<p>'. lang('number_of_people').' : {NO_OF_PEOPLE}</p> '
                    . '<p>'. lang('reservation_date_time').' : {RESERVED_DATE}</p> '
                    . '<p>'. lang('cancellation_code').' : {CANCELLATION_CODE}</p> '
                    . '<p>'. lang('Sincerely').'<br></p> '
                    . '<p>'. lang('reservation_team').'</p> '
                    . '<div>';


            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($email, $emailSubject, $finalEmailBody, FROM_EMAIL_ID, '', '', $attachment);
        }
        return true;
    }

    /*
      @Author : Mehul Patel
      @Desc   : Send Email to All Selected User
      @Input 	:
      @Output	:
      @Date   : 11/5/2017
     */

    public function sendEmailToSelectedUser() {
        $id = $this->input->get('customerid');
        $customers_ids = explode(",", $id);

        if (!empty($id)) {
            foreach ($customers_ids as $customersIds) {

                $table = USER_SHEDULE_TIMESLOT . ' as us';
                $where = array("c.is_delete" => '0', "us.status" => '1', "us.is_delete" => '0', 'us.reservation_code' => $customersIds);
                $fields = array("c.user_id, us.reservation_code, c.email, SUM(us.no_of_people) AS no_of_people, ht.date, ht.start_time, ht.end_time,us.cancellation_code,us.qr_code,us.pdf_file_name");
                $params['join_tables'] = array(USER . ' as c' => 'c.user_id=us.user_id', HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = us.hourly_ts_id');
                $params['join_type'] = 'left';

                $getRecords = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', '', '');

                if (is_array($getRecords[0]) && !empty($getRecords[0])) {
                    $reservation_code = $getRecords[0]['reservation_code'];
                    $email = $getRecords[0]['email'];
                    $no_of_people = $getRecords[0]['no_of_people'];
                    $reserved_date = date("d-m-Y", strtotime($getRecords[0]['date'])) . " " . date('h:i a', strtotime($getRecords[0]['start_time']));
                    $cancellation_code = $getRecords[0]['cancellation_code'];
                    $attachment = $this->config->item('pdf_upload_path') . $getRecords[0]['pdf_file_name'];
                    if ($this->sendMailToUser($reservation_code, $email, $no_of_people, $reserved_date, $cancellation_code, $attachment)) {
                        $msg = "Mail Send Successfully";
                        $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                        $this->email->clear(TRUE);
                    } else {
                        $msg = 'Something went wrong. Please try after sometime.';
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                    }
                    // unset($getRecords[0]['pdf_file_name']);
                } else {
                    $msg = 'Something went wrong. Please try after sometime.';
                    $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
                }
            }
        }
        redirect(ADMIN_SITE . '/ReservedUserList');
    }

    /*
      @Author : Mehul Patel
      @Desc   : Reaseved user view functionality
      @Input  : customerId
      @Output :
      @Date   : 06/06/2017
     */

    public function view($customerId) {

        $table = USER_SHEDULE_TIMESLOT . ' as us';
        $where = array("c.is_delete" => '0', "us.status" => '1', "us.is_delete" => '0', 'us.reservation_code' => $customerId);
        $fields = array("c.user_id, us.reservation_code, c.email, SUM(us.no_of_people) AS no_of_people, ht.date, ht.start_time, ht.end_time,us.cancellation_code,us.qr_code,us.zip_code,gr.group_name");
        $params['join_tables'] = array(USER . ' as c' => 'c.user_id=us.user_id', HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = us.hourly_ts_id',GROUP_RESERVATION . ' as gr' => 'gr.user_id=c.user_id');
        $params['join_type'] = 'left';

        $data['editCustomerRecord'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', '', '');
        
        if (!empty($data['editCustomerRecord'][0])) {

            /* Start - Edit data in form */
            $data['reservation_code'] = $data['editCustomerRecord'][0]['reservation_code'];
            $data['cancellation_code'] = $data['editCustomerRecord'][0]['cancellation_code'];
            $data['email'] = $data['editCustomerRecord'][0]['email'];
            $data['no_of_people'] = $data['editCustomerRecord'][0]['no_of_people'];
            $data['reserved_date'] = date("d-m-Y", strtotime($data['editCustomerRecord'][0]['date'])) . " " . date('h:i a', strtotime($data['editCustomerRecord'][0]['start_time']));
            $data['zip_code'] = $data['editCustomerRecord'][0]['zip_code'];
            $data['population'] = getPopulationName($data['editCustomerRecord'][0]['zip_code']);
            $data['transaction_id'] = getTransactionID($data['editCustomerRecord'][0]['reservation_code']);
            $data['transaction_amount'] = getTransactionAmount($data['editCustomerRecord'][0]['reservation_code']);
            $data['group_name'] = $data['editCustomerRecord'][0]['group_name'];
            /* End - Edit data in form */

            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $customerId;
            $data['main_content'] = $this->viewname . '/addEdit';
            $data['screenType'] = 'edit';
            //pr($data); exit();
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        } else {
            // error
            $msg = 'Invalid User Id is found.'; //$this->lang->line('error_msg');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Export to Excel
      @Input  : Reservation Code
      @Output :
      @Date   : 10/03/2017
      @changes : Maitrak Modi , Dt: 26th Oct 2017 , Note : Remove unnecessary code
     */

    public function exportToexcel() {

        $data_arr = array();
        $id = $this->input->get('customerid');
        $searchtext = $this->input->get('searchtext');
        $daterange = $this->input->get('daterange');

        $table = USER_SHEDULE_TIMESLOT . ' as us ';
        $where = array("c.is_delete" => '0', "us.status" => '1', "us.is_delete" => '0');
        $fields = array("us.reservation_code");
        $params['join_tables'] = array(USER . ' as c' => 'c.user_id=us.user_id', HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = us.hourly_ts_id', GROUP_RESERVATION . ' as gp' => 'gp.group_id = us.group_id');
        $params['join_type'] = 'left';

        $params['group_by'] = 'us.reservation_code';

        if (!empty($id)) {
            $dbsearch = " c.is_delete=0 AND us.status=1 AND us.is_delete =0 AND us.reservation_code IN (" . $id . ")";
            $data['export_data'] = $this->ReservedUserList_model->exportCsvData($dbsearch);
            die();
        } else {
            $where_search = '';
            $applyCondition = '';
            if (!empty($searchtext) || !empty($daterange)) {

                // Apply condtition type 
                if (!empty($searchtext) && !empty($daterange)) {
                    $applyCondition = " AND ";
                }

                // Search Text filter
                if (!empty($searchtext)) {

                    $searchtext = html_entity_decode(trim($searchtext));

                    $where_search .= '(
							c.email LIKE "%' . $searchtext . '%"
							OR us.reservation_code LIKE "%' . $searchtext . '%" 
							OR c.email LIKE "%' . $searchtext . '%" 
							OR gp.group_name LIKE "%' . $searchtext . '%"
						)';
                }

                // Date Range filter
                if (!empty($daterange)) {

                    $explodeDate = explode('-', trim($daterange));

                    $startDateTime = trim($explodeDate[0]);
                    $endDateTime = trim($explodeDate[1]);

                    $updateStartDateFormat = date('Y-m-d H:i:s', strtotime($startDateTime)); // Set date time format
                    $updateEndDateFormat = date('Y-m-d H:i:s', strtotime($endDateTime)); // Set date time format

                    $where_search .= $applyCondition . " (TIMESTAMP(ht.date, ht.start_time) BETWEEN '" . $updateStartDateFormat . "' AND '" . $updateEndDateFormat . "' )";
                }

                $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', $params['group_by'], $where_search);
            } else {
                $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', $params['group_by'], '');
            }
            //echo $this->db->last_query(); exit;

            foreach ($data['datalist'] as $datalist) {
                array_push($data_arr, "'" . $datalist['reservation_code'] . "'");
            }

            if (!empty($data_arr)) {
                $ids = implode(',', $data_arr);
            }

            if (!empty($ids)) {
                $dbsearch = " c.is_delete=0 AND us.status=1 AND us.is_delete =0 AND us.reservation_code IN (" . $ids . ")";
                $data['export_data'] = $this->ReservedUserList_model->exportCsvData($dbsearch);
                die();
            }
        }
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Cancel the reservation
      @Input  :
      @Output :
      @Date   : 18th Oct 2017
     */

    function cancelReservation() {
		
        set_time_limit(0);
        $res_user_id = $this->input->post('user_id');
        $res_code = $this->input->post('code');

        $table = USER_SHEDULE_TIMESLOT . ' as us ';

        $where = array("c.is_delete" => '0', "us.status" => '1', "us.is_delete" => '0', "us.user_id" => $res_user_id, "us.reservation_code" => $res_code);

        //$fields = array("us.user_reservation_id, c.user_id, us.reservation_code, c.email, us.no_of_people, ht.date, ht.start_time, ht.end_time, gp.group_name");
        $fields = array("us.*, ht.date,ht.start_time, c.email, TIMESTAMP(ht.date, ht.start_time) as new_date, rp.* ");

        $params['join_tables'] = array(
            USER . ' as c' => 'c.user_id = us.user_id',
            HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = us.hourly_ts_id',
            GROUP_RESERVATION . ' as gp' => 'gp.group_id = us.group_id',
            RESERVATION_PAYMENT . ' as rp' => 'rp.res_code = us.reservation_code'
        );
        $params['join_type'] = 'left';

        $datalist = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', '', '');
        //echo $this->db->last_query(); exit;
        //echo "<pre>"; print_r($datalist); exit;
        if (!empty($datalist)) {

            $lastCancellationDateTime = date('Y-m-d H:i:s', strtotime(CANCELLATION_DURATION)); // Future 24 hours date time
            //echo CANCELATION_DURATION. $lastCancellationDateTime; exit;
            if ($datalist[0]['new_date'] >= $lastCancellationDateTime) {

                $is_refund = $datalist[0]['is_refund'];
                $res_code = $datalist[0]['res_code'];
                $allowMail = 0;
                //echo $is_refund;

                if (empty($is_refund) && (!empty($res_code))) {

                    $refundStatus = $this->refund($res_code); // Call Refund function to give refund
                    $refundStatusData = json_decode($refundStatus, true);

                    if ($refundStatusData['status']) {

                        //$count_total_grp_people = $this->updateCancelationInfo($datalist); // Cancel the reservation 
						$count_total_grp_people = 0;

						for ($i = 0; $i < count($datalist); $i++) {

							$user_reservation_id = $datalist[$i]['user_reservation_id'];
							$count_total_grp_people += $datalist[$i]['no_of_people'];
							$email = $datalist[$i]['email'];
							$date = $datalist[$i]['date'];
							$start_time = $datalist[$i]['start_time'];
							$reserved_date = date("m/d/Y h:i a", strtotime($date . ' ' . $start_time));

							unset($datalist[$i]['user_reservation_id'], $datalist[$i]['date'], $datalist[$i]['start_time'], $datalist[$i]['email'], $datalist[$i]['new_date']); // unset unwanted variable

							$insertCancelSlotData = array(
								'user_id' => $datalist[$i]['user_id'],
								'group_id' => $datalist[$i]['group_id'],
								'weekly_ts_id' => $datalist[$i]['weekly_ts_id'],
								'hourly_ts_id' => $datalist[$i]['hourly_ts_id'],
								'no_of_group_people' => $datalist[$i]['no_of_group_people'],
								'no_of_people' => $datalist[$i]['no_of_people'],
								'config_no_of_people' => $datalist[$i]['config_no_of_people'],
								'reservation_code' => $datalist[$i]['reservation_code'],
								'qr_code' => $datalist[$i]['qr_code'],
								'pdf_file_name' => $datalist[$i]['pdf_file_name'],
								'cancellation_code' => $datalist[$i]['cancellation_code'],
								'zip_code' => $datalist[$i]['zip_code'],
								'is_delete' => $datalist[$i]['is_delete'],
								'status' => $datalist[$i]['status'],
								'created_at' => ($datalist[$i]['created_at']) ? $datalist[$i]['created_at'] : datetimeformat(),
								'modified_at' => datetimeformat(),
							);

							$this->common_model->insert(USER_CANCEL_SHEDULE_TIMESLOT, $insertCancelSlotData); // Insert Cancellation reservation data
							$this->common_model->delete(USER_SHEDULE_TIMESLOT, array('user_reservation_id' => $user_reservation_id)); // Remove from reservation table
							$this->common_model->update(USER, array('is_delete' => 1), array('email' => $email)); // Remove from reservation table
						}

                        $allowMail = 1;
                        //send mail to user for cancellation of reservation
                        //$this->sendMailToUserCancellation($datalist[0]['reservation_code'], $email, $count_total_grp_people, $reserved_date);
                        $this->session->set_flashdata('sucess_msg', "<div class='alert alert-success text-center'>" . $refundStatusData['msg'] . "</div>");
                    } else {
                        $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . $refundStatusData['msg'] . "</div>");
                    }
                } else { // for without zipcode user (No zipcode found in table)
				
                    //$count_total_grp_people = $this->updateCancelationInfo($datalist); // Cancel the reservation
					
					$count_total_grp_people = 0;

					for ($i = 0; $i < count($datalist); $i++) {

						$user_reservation_id = $datalist[$i]['user_reservation_id'];
						$count_total_grp_people += $datalist[$i]['no_of_people'];
						$email = $datalist[$i]['email'];
						$date = $datalist[$i]['date'];
						$start_time = $datalist[$i]['start_time'];
						$reserved_date = date("m/d/Y h:i a", strtotime($date . ' ' . $start_time));

						unset($datalist[$i]['user_reservation_id'], $datalist[$i]['date'], $datalist[$i]['start_time'], $datalist[$i]['email'], $datalist[$i]['new_date']); // unset unwanted variable

						$insertCancelSlotData = array(
							'user_id' => $datalist[$i]['user_id'],
							'group_id' => $datalist[$i]['group_id'],
							'weekly_ts_id' => $datalist[$i]['weekly_ts_id'],
							'hourly_ts_id' => $datalist[$i]['hourly_ts_id'],
							'no_of_group_people' => $datalist[$i]['no_of_group_people'],
							'no_of_people' => $datalist[$i]['no_of_people'],
							'config_no_of_people' => $datalist[$i]['config_no_of_people'],
							'reservation_code' => $datalist[$i]['reservation_code'],
							'qr_code' => $datalist[$i]['qr_code'],
							'pdf_file_name' => $datalist[$i]['pdf_file_name'],
							'cancellation_code' => $datalist[$i]['cancellation_code'],
							'zip_code' => $datalist[$i]['zip_code'],
							'is_delete' => $datalist[$i]['is_delete'],
							'status' => $datalist[$i]['status'],
							'created_at' => ($datalist[$i]['created_at']) ? $datalist[$i]['created_at'] : datetimeformat(),
							'modified_at' => datetimeformat(),
						);

						$this->common_model->insert(USER_CANCEL_SHEDULE_TIMESLOT, $insertCancelSlotData); // Insert Cancellation reservation data
						$this->common_model->delete(USER_SHEDULE_TIMESLOT, array('user_reservation_id' => $user_reservation_id)); // Remove from reservation table
						$this->common_model->update(USER, array('is_delete' => 1), array('email' => $email)); // Remove from reservation table
					}
					
					$allowMail = 1;
                    $msg = lang('reservation_has_been_cancelled');
                    $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                }

                //send mail to user for cancellation of reservation
                if ($allowMail) {
                    $this->sendMailToUserCancellation($datalist[0]['reservation_code'], $email, $count_total_grp_people, $reserved_date);
                }
            } else {
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>" . lang('no_cancel_after_time_duration') . "</div>");
            }
        }

        echo true;
        exit;
    }

    /*
      @Author : Maitrak Modi
      @Desc   : SendMail To User for cancellation
      @Input  : $reservation_code,$email,$no_of_people,$reserved_date
      @Output :
      @Date   : 18th Oct 2017
     */

    private function sendMailToUserCancellation($reservation_code, $email, $no_of_people, $reserved_date ) {

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

            $emailSubject = lang('reservation_system') .':'. lang('reservation_cancellation');
            $emailBody = '<div>'
                    . '<p>'. lang('hello').'</p> '
                    . '<p>'. lang('reservation_cancelled').'</p> '
                    . '<p>'. lang('reservation_code').' : {RESERVATION_CODE} </p> '
                    . '<p>'. lang('reservation_email_id').' : {EMAIL}</p> '
                    . '<p>'. lang('number_of_people').' : {NO_OF_PEOPLE}</p> '
                    . '<p>'. lang('reservation_date_time').' : {RESERVED_DATE}</p> '
                    . '<p>'. lang('Sincerely').'<br></p> '
                    . '<p>'. lang('reservation_team').'</p> '
                    . '<div>';


            $finalEmailBody = str_replace($find, $replace, $emailBody);

            return $this->common_model->sendEmail($email, $emailSubject, $finalEmailBody, FROM_EMAIL_ID, '', '');
        }
        return true;
    }

    /*
      @Author : Maitrak Modi
      @Desc   : Refund Proccess
      @Input  : $reservation_code,$email
      @Output :
      @Date   : 3rd Nov 2017
     */

    protected function refund($reservationCode) {

        if (!empty($reservationCode)) {

            $field = array('*');
            $match = array('res_code' => trim($reservationCode));
            $paymentExists = $this->common_model->get_records(RESERVATION_PAYMENT, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');

            if (!empty($paymentExists)) {

                //echo"<pre>"; print_r($paymentExists); exit;

                $dataInArray['transactionID'] = $paymentExists[0]['transaction_id'];
                $dataInArray['refundType'] = $this->config->item('paypal_refund_type');
                $dataInArray['currencyCode'] = $this->config->item('paypal_lib_currency_code');
                $dataInArray['memo'] = "FULL AMOUNT REFUND";

                //echo "<pre>"; print_r($dataInArray); exit;
                $ref = new PayPalRefund();
                $aryRes = $ref->refundAmount($dataInArray);
                $responseArray = array();
                //echo "<pre>"; print_r($aryRes); exit;
                //if($aryRes) {

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
                //}
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

	/*protected function updateCancelationInfo($datalist){
		
		$count_total_grp_people = 0;

		for ($i = 0; $i < count($datalist); $i++) {

			$user_reservation_id = $datalist[$i]['user_reservation_id'];
			$count_total_grp_people += $datalist[$i]['no_of_people'];
			$email = $datalist[$i]['email'];
			$date = $datalist[$i]['date'];
			$start_time = $datalist[$i]['start_time'];
			$reserved_date = date("m/d/Y h:i a", strtotime($date . ' ' . $start_time));

			unset($datalist[$i]['user_reservation_id'], $datalist[$i]['date'], $datalist[$i]['start_time'], $datalist[$i]['email'], $datalist[$i]['new_date']); // unset unwanted variable

			$insertCancelSlotData = array(
				'user_id' => $datalist[$i]['user_id'],
				'group_id' => $datalist[$i]['group_id'],
				'weekly_ts_id' => $datalist[$i]['weekly_ts_id'],
				'hourly_ts_id' => $datalist[$i]['hourly_ts_id'],
				'no_of_group_people' => $datalist[$i]['no_of_group_people'],
				'no_of_people' => $datalist[$i]['no_of_people'],
				'config_no_of_people' => $datalist[$i]['config_no_of_people'],
				'reservation_code' => $datalist[$i]['reservation_code'],
				'qr_code' => $datalist[$i]['qr_code'],
				'pdf_file_name' => $datalist[$i]['pdf_file_name'],
				'cancellation_code' => $datalist[$i]['cancellation_code'],
				'zip_code' => $datalist[$i]['zip_code'],
				'is_delete' => $datalist[$i]['is_delete'],
				'status' => $datalist[$i]['status'],
				'created_at' => ($datalist[$i]['created_at']) ? $datalist[$i]['created_at'] : datetimeformat(),
				'modified_at' => datetimeformat(),
			);

			$this->common_model->insert(USER_CANCEL_SHEDULE_TIMESLOT, $insertCancelSlotData); // Insert Cancellation reservation data
			$this->common_model->delete(USER_SHEDULE_TIMESLOT, array('user_reservation_id' => $user_reservation_id)); // Remove from reservation table
			$this->common_model->update(USER, array('is_delete' => 1), array('email' => $email)); // Remove from reservation table
		}
		
	
	} */
}