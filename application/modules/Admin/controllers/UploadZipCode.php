<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UploadZipCode extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (checkAdminPermission('ReservedUserList', 'view') == false) {
            redirect('/Admin/Dashboard');
        }
        check_admin_login();
        $this->type = ADMIN_SITE;
        $this->viewname = ucfirst($this->router->fetch_class());
       // $this->load->model('Upload_Zip_Code_model');
	   $this->load->library(array('form_validation'));
	   
	   $this->load->library('excel');
    }

    /*
      @Author : Mehul Patel
      @Desc   : Upload zip code List form
      @Input  :
      @Output :
      @Date   : 16/10/2017
     */

    public function index($page = '') {

        $cur_uri = explode('/', $_SERVER['PATH_INFO']);
        $cur_uri_segment = array_search($page, $cur_uri);
        $searchtext = '';
        $perpage = '';
        $searchtext = $this->input->post('searchtext');

        $sortfield = $this->input->post('sortfield');
        $sortby = $this->input->post('sortby');
        $perpage = $this->input->post('perpage');
        $allflag = $this->input->post('allflag');
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $this->session->unset_userdata('upload_zip_sortsearchpage_data');
        }

        $searchsort_session = $this->session->userdata('upload_zip_sortsearchpage_data');
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
                $sortfield = 'id';
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
        if (!empty($allflag) && ($allflag == 'all' || $allflag == 'changesorting' || $allflag == 'changesearch')) {
            $config['uri_segment'] = 0;
            $uri_segment = 0;
        } else {
            $config['uri_segment'] = 4;
            $uri_segment = $this->uri->segment(4);
        }

        $table = UPLOAD_ZIP_CODE . ' as uz ';
        $where = array("uz.is_delete" => '0');
        $fields = array("uz.id, uz.zip_code, uz.population, uz.province");
        
        if (!empty($searchtext)) {
            $searchtext = html_entity_decode(trim($searchtext));
            $match = '';
            $where_search = '(uz.zip_code LIKE "%' . $searchtext . '%" 
							OR uz.population LIKE "%' . $searchtext . '%" 
							OR uz.province LIKE "%' . $searchtext . '%" 
							)';
			
            $data['datalist'] = $this->common_model->get_records($table, $fields, '', '', $where, '', $config['per_page'], $uri_segment, $sortfield, $sortby, 'uz.id', $where_search);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', $where, '', '', '', $sortfield, $sortby, 'uz.id', $where_search, '', '', '1');
        } else {
            $data['datalist'] = $this->common_model->get_records($table, $fields, '', '', '', '', $config['per_page'], $uri_segment, $sortfield, $sortby, 'uz.id', $where);
            $config['total_rows'] = $this->common_model->get_records($table, $fields, '', '', '', '', '', '', $sortfield, $sortby, 'uz.id', $where, '', '', '1');
        }
       
	   
        $this->ajax_pagination->initialize($config);
        $data['pagination'] = $this->ajax_pagination->create_links();
        $data['uri_segment'] = $uri_segment;
        $data['footerJs'][0] = base_url('uploads/custom/js/UploadZipCode/UploadZipCode.js');

        $sortsearchpage_data = array(
            'sortfield' => $data['sortfield'],
            'sortby' => $data['sortby'],
            'searchtext' => $data['searchtext'],
            'perpage' => trim($data['perpage']),
            'uri_segment' => $uri_segment,
            'total_rows' => $config['total_rows']);
        $this->session->set_userdata('upload_zip_sortsearchpage_data', $sortsearchpage_data);

        if ($this->input->post('result_type') == 'ajax') {
            $this->load->view($this->type . '/' . $this->viewname . '/ajax_list', $data);
        } else {
            $data['main_content'] = $this->type . '/' . $this->viewname . '/list';
            $this->load->view($this->type . '/assets/template', $data);
        }
    }

    /*
      @Author : Mehul Patel
      @Desc   : Edit the Zipcode and Contract code
      @Input  :
      @Output :
      @Date   : 16/10/2017
     */

    public function edit($id) {
		
		$table = UPLOAD_ZIP_CODE . ' as uz';
		$match = array('uz.id' => $id, 'uz.is_delete' => 0);
		$fields = array("uz.id, uz.zip_code, uz.population, uz.province, uz.is_delete");
		$data['editRecord'] = $this->common_model->get_records($table, $fields, '', '', $match);		
		//echo "<pre>"; print_r($data['editRecord']); exit;
		if (!empty($data['editRecord'])) {
		
			$checkDuplicateZipcode = checkUniqueZipcode($this->input->post('zip_code')); // check duplicate records of zip code
			if(($this->input->post('zip_code') != $data['editRecord'][0]['zip_code']) && !$checkDuplicateZipcode ){
				
				//var_dump($checkDuplicateZipcode); exit;
				if(!$checkDuplicateZipcode){
					$is_unique =  '|is_unique[res_upload_zip_code.zip_code]';
				}else{
					$is_unique =  '';
				}
			   
			} else {
			   $is_unique =  '|';
			}
			$this->form_validation->set_rules('zip_code', 'Postal Code', 'trim|required|numeric|min_length[4]|max_length[10]'.$is_unique.'xss_clean'); // form validation
			$this->form_validation->set_rules('population', 'Population', 'trim|required|max_length[50]|xss_clean'); // form validation
			$this->form_validation->set_rules('province', 'Province', 'trim|required|max_length[50]|xss_clean'); // form validation
			
			if ($this->form_validation->run() == FALSE) {
				
				$data['id'] = $id;
				$data['crnt_view'] = ADMIN_SITE . '/' . $this->viewname;
				$data['form_action_path'] = ADMIN_SITE . '/' . $this->viewname . '/edit/' . $id;
				$data['main_content'] = $this->viewname . '/addEdit';
				$data['screenType'] = 'edit';
				$data['validation'] = validation_errors();
				$data['footerJs'][0] = base_url('uploads/custom/js/UploadZipCode/UploadZipCode.js');
				$this->parser->parse(ADMIN_SITE . '/assets/template', $data);
				
			}else{
				// Success
				$this->updateData($id);
				
			}
		}else{
			// error
			$msg = $this->lang->line('user_add_error');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			redirect(ADMIN_SITE . '/' . $this->viewname);
		}
    }

    /*
      @Author : Mehul Patel
      @Desc   : update Zipcode and Contract code
      @Input  :
      @Output :
      @Date   : 16/10/2017
     */

    public function updateData($id) {
		
			$data = array(
            'zip_code' => $this->input->post('zip_code'),
            'population' => $this->input->post('population'),
            'province' => $this->input->post('province'),
			);
			
			// update Record
			$where = array('id' => $id);
			if ($this->common_model->update(UPLOAD_ZIP_CODE, $data, $where)) { //Update data
				$msg = $this->lang->line('data_update_successfully');
				$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
			} else {
				// error
				$msg = $this->lang->line('user_add_error');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			}

			redirect(ADMIN_SITE . '/' . $this->viewname);
        
    }

    /*
      @Author : Mehul Patel
      @Desc   : Delete Record
      @Input  :
      @Output :
      @Date   : 11/5/2017
     */

    public function deleteData() {
		
        $id = $this->input->get('id');

        if (!empty($id)) {
            $data = array('is_delete' => 1);
            $where = array('id' => $id);

            if ($this->common_model->update(UPLOAD_ZIP_CODE, $data, $where)) {
                $msg = $this->lang->line('data_delete_successfully');
                $this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
                unset($id);
            } else {
                // error
                $msg = $this->lang->line('zipcode_error_msg');
                $this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
        }
        redirect(ADMIN_SITE . '/' . $this->viewname);
    }

    /*
      @Author : Mehul Patel
      @Desc   : Import CSV
      @Input  :
      @Output :
      @Date   : 17/10/2017
     */

    public function importCSV() {
		
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        } else {
            $data['modal_title'] = $this->lang->line('import_csv_file');
            $data['submit_button_title'] = $this->lang->line('import_csv');
            $data['uploadView'] = $this->type . '/' . $this->viewname;
            $this->load->view('UploadZipCode/importCSV', $data);
        }
    }
	
	/*
      @Author   : Maitrak Modi
      @Desc     : Import Excel file data
      @Input    :
      @Output   : 
      @Date     : 17/10/2017
      @updated: 9th Nov 2017
     */
	 
	public function importCSVdata() {

        set_time_limit(0);
		ini_set('auto_detect_line_endings',TRUE);
		
        $config['upload_path'] = FCPATH . '/uploads/zip_csv';
        $config['allowed_types'] = '*';
        $config['max_size'] = 40480;
        $config['charset'] = 'utf-8';

        $new_name = time() . "_" . str_replace(' ', '_', $_FILES["import_file"]['name']);
        $config['file_name'] = $new_name;
		
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('import_file')) {
			
            $msg = $this->upload->display_errors();
            $this->session->set_flashdata('error', $msg);
			
        } else {
			
			$mimes = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/excel','application/vnd.ms-excel');
			
			if (in_array($_FILES['import_file']['type'], $mimes)) {
				
				$file_path = './uploads/zip_csv/' . $new_name;
				
				try {
					$file_type	= PHPExcel_IOFactory::identify($file_path);
					$objReader = PHPExcel_IOFactory::createReader($file_type);	// For excel 2007
					$objReader->setReadDataOnly(true); //Load excel file
					$objPHPExcel = $objReader->load($file_path);
				} catch(Exception $e) {
					die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				}
				
				$sheet         = $objPHPExcel->getSheet(0); 
				$highestRow    = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
				$headings      = $sheet->rangeToArray('A1:'.$highestColumn.'1', NULL, TRUE, FALSE);
				
				if(in_array('POBLACION', $headings[0])){
					
					$sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					//pr($sheet_data); exit;
					if(!empty($sheet_data)){
						$count_success = 0;
						$count_fail = 0;
						//$total_record = 0;
						
						for($i=2;$i<=$highestRow;$i++) {
							
							if(is_numeric($sheet_data[$i]['A'])) { // check first column value is integer or not
							
								$data['zip_code'] = $sheet_data[$i]['A'];
								$data['population'] = $sheet_data[$i]['B'];
								$data['province'] = $sheet_data[$i]['C'];
								
								$checkDuplicateZipcode = checkUniqueZipcode($data['zip_code']); // check duplicate records of zip code
								if($checkDuplicateZipcode) {
									if ($this->common_model->insert(UPLOAD_ZIP_CODE, $data)) { // Insert the records in DB
										$count_success++; // No. of records insert into the DB
									}else{
										$count_fail++; // No. of failure OR duplicate records are in csv file
									}
								}else{
									if ($this->common_model->update(UPLOAD_ZIP_CODE, $data, array('zip_code'=> $data['zip_code']))) { // Update the records in DB
										$count_success++; // No. of records insert into the DB
									}else{
										$count_fail++; // No. of failure OR duplicate records are in csv file
									}
								}
							}
							//$total_record
						}
						
						$msg = "Succesfully Imported ! Total Record : $highestRow, Successfully Imported : $count_success, Fail Record : $count_fail ";
						$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
						
					}else{
						$msg = "No data found";
						$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
					}
				}else{
					$msg = "Invalid Excel file.";
					$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
				}				 
			}else{
				$msg = "Invalid Excel file.";
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
			}
        }	
        redirect(ADMIN_SITE . '/' . $this->viewname);
    }
	
	/*
      @Author   : Maitrak Modi
      @Desc     : Check Duplicate zipcode with same id
      @Input    : post zipcode and Edit id
      @Output   : return 0 or 1
      @Date     : 25th Oct 2017
     */
	 
	public function checkDuplicateZipcode(){
		
		$isduplicate = 0;
        $zipcode = trim($this->input->post('zipcode'));
        $edit_Id = trim($this->input->post('editId'));

        if (!empty($zipcode) && !empty($edit_Id)) {

            $tableName = UPLOAD_ZIP_CODE;
            $fields = array('COUNT(zip_code) AS zipCodeCnt');

            if (!empty($edit_Id)) { // edit
                $match = array('zip_code' => $zipcode, 'id <>' => $edit_Id, 'is_delete' => 0);
            }

            $duplicateZipCode = $this->common_model->get_records($tableName, $fields, '', '', $match);
            //echo $this->db->last_query();

            if ($duplicateZipCode[0]['zipCodeCnt'] > 0) {
                $isduplicate = 1;
            } else {
                $isduplicate = 0;
            }
        }

        echo $isduplicate;
		exit;
    }
	
	/*
      @Author   : Maitrak Modi
      @Desc     : Delete the Zipcode
      @Input    :
      @Output   :
      @Date     : 31st Oct 2017
    */
	 
	public function deleteZipcode() {
		
		$deletedIds = $this->input->post('deletedIds');
		//echo "<pre>"; print_r($deletedIds); exit;	
		$returnData = array();
		if(!empty($deletedIds)){
		
			$deletedIdList = implode(',', $deletedIds);
			
			$data = array('is_delete' => 1);
            $where = " id IN (".$deletedIdList.")";
			
            if ($this->common_model->update(UPLOAD_ZIP_CODE, $data, $where)) {
				
				$returnData = array(
					'status' => 1,
					//'msg' =>"<div class='alert alert-success text-center'>".$this->lang->line('zipcode_deleted_msg')."</div>",
					'redirecturl' => base_url(ADMIN_SITE .'/UploadZipCode')
				);
				
				$msg = $this->lang->line('zipcode_deleted_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-success text-center'>$msg</div>");
            } else {
				
				$returnData = array(
					'status' => 0,
					//'msg' => "<div class='alert alert-danger text-center'>".$this->lang->line('zipcode_error_msg')."</div>",
					'redirecturl' => base_url(ADMIN_SITE .'/UploadZipCode')
				);
				
				$msg = $this->lang->line('zipcode_error_msg');
				$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
            }
		}else{
			$returnData = array(
					'status' => 0,
					//'msg' => "<div class='alert alert-danger text-center'>".$this->lang->line('zipcode_error_msg')."</div>",
					'redirecturl' => base_url(ADMIN_SITE .'/UploadZipCode')
			);
			
			$msg = $this->lang->line('zipcode_error_msg');
			$this->session->set_flashdata('msg', "<div class='alert alert-danger text-center'>$msg</div>");
		}
		//redirect(ADMIN_SITE . '/' . $this->viewname);
		echo json_encode($returnData); exit;
	}
	
	/*
      @Author   : Maitrak Modi
      @Desc     : Download Sample File
      @Input    :
      @Output   :
      @Date     : 1st Nov 2017
    */
	public function downloadSampleFile(){
		
		//	$fileName = basename('sample_file.xlsx');
		//$fileName = 'sample_file.xlsx';
		
		//$filePath = FCPATH ."uploads/zip_csv/sample_file.xlsx";
		//$filePath = realpath(APPPATH . '../uploads/zip_csv/sample_file.xlsx');
		
		//if(!empty($fileName) && file_exists($filePath)){
	
			$this->excel = new PHPExcel();
			
			$this->excel->setActiveSheetIndex(0);
                
			$this->excel->getActiveSheet()->setTitle('sample');//name the worksheet
			
			//set cell A1 content with some text
			$this->excel->getActiveSheet()->setCellValue('A1', 'CP');
			$this->excel->getActiveSheet()->setCellValue('B1', 'POBLACION');
			$this->excel->getActiveSheet()->setCellValue('C1', 'PROVINCIA');
			
			$filename='sample.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.ms-excel'); //mime type
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache

			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
			
			//force user to download the Excel file without writing
			ob_end_clean();
			$objWriter->save('php://output');
			
			exit;
		//}else{
		//	echo 'The file does not exist.';exit();
		//}
	}

}