<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CancelledReservedList extends CI_Controller {

    function __construct() {
		
        parent::__construct();
        if (checkAdminPermission('CancelledReservedList', 'view') == false) {
            redirect('/Admin/Dashboard');
        }
        check_admin_login();
        $this->type = ADMIN_SITE;
        $this->viewname = ucfirst($this->router->fetch_class());
        $this->load->model('CancelledReservedList_model');
    }

    /*
      @Author : Maitrak Modi
      @Desc   : CancelledReservedList form
      @Input  :
      @Output :
      @Date   : 17th Oct 2017
     */

    public function index($page = '') {
        
		//echo "<pre>"; print_r($_POST); exit;
        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');
        $daterange = $this->input->post('daterange');
	$data['daterange'] = $daterange;
        /*$start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $start_time = ($this->input->post('start_time'))?date('H:i:s', strtotime($this->input->post('start_time'))):'';
        $end_time = $this->input->post('end_time') ? date('H:i:s', strtotime($this->input->post('end_time'))) : '';  //$this->input->post('end_time'); 
        */
        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('CancelledReservedList_sortsearchpage_data');
        }

        $searchsort_session = $this->session->userdata('CancelledReservedList_sortsearchpage_data');
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
                $config['per_page'] = NO_OF_RECORDS_PER_PAGE;
                $data['perpage'] = NO_OF_RECORDS_PER_PAGE;
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
        $table = USER_CANCEL_SHEDULE_TIMESLOT . ' as ucs ';
        $where = array(" c.is_delete" => '1', "ucs.status" => '1', "ucs.is_delete" => '0');
        $fields = array("ucs.user_reservation_id, c.user_id, ucs.reservation_code, c.email, SUM(ucs.no_of_people) as no_of_people, ht.date, ht.start_time, ht.end_time, gp.group_name, TIMESTAMP(ht.date, ht.start_time) as new_date, rp.*");
        $params['join_tables'] = array(
									USER . ' as c' => 'c.user_id = ucs.user_id', 
									HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = ucs.hourly_ts_id', 
									GROUP_RESERVATION . ' as gp' => 'gp.group_id = ucs.group_id',
									RESERVATION_PAYMENT . ' as rp' => 'rp.res_code = ucs.reservation_code'
									);
        $params['join_type'] = 'left';
		
		$params['group_by'] = 'ucs.reservation_code';
		
		$where_search = '';
		$applyCondition = '';
        if (!empty($searchtext) || !empty($daterange)) {
			
			// Apply condtition type
			if (!empty($searchtext) && !empty($daterange)) {
				$applyCondition = " AND ";
			}
			
			// Search Text filter
			if(!empty($searchtext)){
				
				$searchtext = html_entity_decode(trim($searchtext));
				
				$where_search .= '(
						c.email LIKE "%' . $searchtext . '%"
						OR ucs.reservation_code LIKE "%' . $searchtext . '%" 
						OR c.email LIKE "%' . $searchtext . '%" 
						OR gp.group_name LIKE "%' . $searchtext . '%"
						OR rp.refund_transaction_id LIKE "%' . $searchtext . '%"
						OR rp.gross_refund_amount LIKE "%' . $searchtext . '%"
					)';
					
			}
			
			// Date Range filter
			if(!empty($daterange)) {

				$explodeDate = explode('-', trim($daterange));
				
				$startDateTime = trim($explodeDate[0]);
				$endDateTime = trim($explodeDate[1]);
				
				$updateStartDateFormat = date('Y-m-d H:i:s', strtotime($startDateTime)); // Set date time format
				$updateEndDateFormat = date('Y-m-d H:i:s', strtotime($endDateTime)); // Set date time format
				
				//$where_search .=  $applyCondition." (TIMESTAMP(ht.date, ht.start_time) >= '".$updateStartDateFormat."' AND TIMESTAMP(ht.date, ht.start_time) < '".$updateEndDateFormat."' )" ;
				$where_search .=  $applyCondition." (TIMESTAMP(ht.date, ht.start_time) BETWEEN '".$updateStartDateFormat."' AND '".$updateEndDateFormat."' )" ;
				
			}
			
			//$match = array("c.is_delete" => '0', "ucs.status" => '1', "ucs.is_delete" => '0');
			$data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, $params['group_by'], $where_search);
			$config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', $sortfield, $sortby, $params['group_by'], $where_search, '', '', '1');
        }else {
			//$match = array('c.is_delete' => 0);
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, $params['group_by'], '');
            $config['total_rows'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', $sortfield, $sortby, $params['group_by'], '', '', '', '1');
        }
		//echo $this->db->last_query(); exit;	
       
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
		
        //$data['footerJs'][0] = base_url('uploads/custom/js/CancelledReservedList/CancelledReservedList.js');
		
        $data['footerJs'] = array(
			'0' => base_url('uploads/custom/js/CancelledReservedList/CancelledReservedList.js'),
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
			
        $this->session->set_userdata('CancelledReservedList_sortsearchpage_data', $sortsearchpage_data);

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type . '/assets/template', $data);
        }
    }
	
    /*
      @Author : Maitrak Modi
      @Desc   : Cancelled Reservation view functionality
      @Input  : Reservation Code
      @Output :
      @Date   : 17th Oct 2017
     */

    public function view($reservation_code) {

		// Query
        $table = USER_CANCEL_SHEDULE_TIMESLOT . ' as ucs';
        $where = array("c.is_delete" => '1', "ucs.status" => '1', "ucs.is_delete" => '0', 'ucs.reservation_code' => $reservation_code);
        $fields = array("c.user_id, ucs.reservation_code, c.email, SUM(ucs.no_of_people) as no_of_people, ht.date, ht.start_time, ht.end_time, ucs.cancellation_code, ucs.qr_code");
        $params['join_tables'] = array(USER . ' as c' => 'c.user_id=ucs.user_id', HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = ucs.hourly_ts_id');
        $params['join_type'] = 'left';
		$params['group_by'] = 'ucs.reservation_code';
        
		$data['editCustomerRecord'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], $where, '', '', '', '', '', $params['group_by'], '');
		//echo $this->db->last_query(); exit;
		//pr($data['editCustomerRecord']); exit;
        if (!empty($data['editCustomerRecord'][0])) {

            /* Start - Edit data in form */
            $data['reservation_code'] = $data['editCustomerRecord'][0]['reservation_code'];
            $data['email'] = $data['editCustomerRecord'][0]['email'];
            $data['no_of_people'] = $data['editCustomerRecord'][0]['no_of_people'];
            $data['reserved_date'] = date("d-m-Y", strtotime($data['editCustomerRecord'][0]['date'])) . " " . date('h:i a', strtotime($data['editCustomerRecord'][0]['start_time']));
            $data['transaction_id'] = getrefund_transaction_ID($data['editCustomerRecord'][0]['reservation_code']);
            $data['transaction_amount'] = getnet_refund_Amount($data['editCustomerRecord'][0]['reservation_code']);
            /* End - Edit data in form */

            $data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
            $data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $reservation_code;
            $data['main_content'] = $this->viewname . '/addEdit';
            $data['screenType'] = 'edit';
          
            $this->parser->parse(ADMIN_SITE . '/assets/template', $data);
        } else {
            // error
            $msg = $this->lang->line('invalid_reservation_code_found');
            $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");

            redirect(ADMIN_SITE . '/' . $this->viewname);
        }
    }
    
	 /*
	  @Author : Maitrak Modi
	  @Desc   : Export to Excel
	  @Input  : Cancelled Reservation Code
	  @Output :
	  @Date   : 17th Oct 2017
	 */
     /*public function exportToexcel() {
         
        $data_arr = array(); 
        $id = $this->input->get('customerid'); 
        $searchtext = $this->input->get('searchtext'); 
        $start_date = $this->input->get('start_date'); 
        $start_time = $this->input->get('start_time'); 
        $end_date = $this->input->get('end_date'); 
        $end_time = $this->input->get('end_time'); 
        
        $table = USER_CANCEL_SHEDULE_TIMESLOT . ' as ucs ';
        $where = array("c.is_delete" => '0', "ucs.status" => '1', "ucs.is_delete" => '0');
        $fields = array("ucs.reservation_code");
        $params['join_tables'] = array(USER . ' as c' => 'c.user_id=ucs.user_id', HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = ucs.hourly_ts_id', GROUP_RESERVATION . ' as gp' => 'gp.group_id = ucs.group_id');
        $params['join_type'] = 'left';
        $params['group_by'] = 'ucs.reservation_code';
        
        if (!empty($id)) {
            $dbsearch = " c.is_delete=0 AND ucs.status=1 AND ucs.is_delete =0 AND ucs.reservation_code IN (".$id.")";
            $data['export_data'] = $this->ReservedUserList_model->exportCsvData($dbsearch);                      
        }elseif (!empty($searchtext)) {
			
            $searchtext = html_entity_decode(trim($searchtext));
            $match = '';            
            $where_search = '((c.email LIKE "%' . $searchtext . '%" OR ucs.reservation_code LIKE "%' . $searchtext . '%" OR c.email LIKE "%' . $searchtext . '%" OR gp.group_name LIKE "%' . $searchtext . '%" ) AND c.is_delete = "0")';            
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', $match, '', '', '', '', $params['group_by'], $where_search);
         
        }else if(!empty($start_date) && empty($end_date) && empty($start_time) && empty($end_time)){
            $match = '';                        
            $where_search =  " ht.date = '".$start_date."' AND c.is_delete = '0'";
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', $match, '', '', '', '', $params['group_by'], $where_search);
           
        }else if(!empty($end_date) && empty($start_date) && empty($start_time) && empty($end_time)){       
           
            $match = '';                       
            $where_search =  " ht.date = '".$end_date."' AND c.is_delete = '0'";
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', $match, '', '', '', '', $params['group_by'], $where_search);
           
        }else if(!empty($start_date) && !empty($end_date) && !empty($start_time) && !empty($end_time) ){              
            $match = '';     
            $where_search =  " ht.date BETWEEN '".$start_date."' AND '".$end_date."' AND c.is_delete = '0' AND ht.start_time BETWEEN '".$start_time."' AND '".$end_time."'";
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', $match, '', '', '', '', $params['group_by'], $where_search);
           
        }else if(!empty($start_date) && !empty($end_date) && empty($start_time) && empty($end_time) ){            
            
            $match = '';     
            $where_search =  " ht.date BETWEEN '".$start_date."' AND '".$end_date."' AND c.is_delete = '0'";
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', $match, '', '', '', '', $params['group_by'], $where_search);
                      
        }else {
            $data['datalist'] = $this->common_model->get_records($table, $fields, $params['join_tables'], $params['join_type'], '', '', '', '', '', '', $params['group_by'], $where);
        }
        
        foreach($data['datalist'] as $datalist){
            array_push($data_arr, "'".$datalist['reservation_code']."'");
        }
        if(!empty($data_arr)){
            $ids = implode(',', $data_arr);
        }
        if(!empty($ids)){
            $dbsearch = " c.is_delete=0 AND ucs.status=1 AND ucs.is_delete =0 AND ucs.reservation_code IN (".$ids.")";
            $data['export_data'] = $this->CancelledReservedList_model->exportCsvData($dbsearch);    
        }
        
     } */
}
