<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payinfo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library(array('MyPayPal', 'PayPalRefund'));
    }

    /*
      @Author : Maitrak Modi
      @Desc   : External Payment link
      @Input  : 
      @Output :
      @Date   : 7th Nov 2017
     */

    public function index() {			
		
		set_time_limit(0);
		
		//$currentDateTime = date('Y-m-d H:i:s'); // Current Time
		$payment_token = $this->input->get('token'); // token
		
		if(!empty($payment_token)) {
			
			//Get User Details
			$table = USER . ' as u ';
			
			$fields = array("u.*");
			
			$where = array(
						"u.is_delete" => '0',
						"u.status" => '1',
						"u.paypal_token" => $payment_token
					);
			
			$userInfo = $this->common_model->get_records($table, $fields, '', '', $where, '', '', '', '', '', '','');
			
			if(!empty($userInfo)){
				
				$createdDateTime = $userInfo[0]['created_at']; // user created time
				$userId = $userInfo[0]['user_id']; // user id
				$email = $userInfo[0]['email']; // user email id
				$lastCancellationDateTime = date('Y-m-d H:i:s', strtotime($createdDateTime . CANCELLATION_DURATION)); // Future 24 hours date time
				
				// Check user shedule
				$table1 = USER_SHEDULE_TIMESLOT . ' as us';
				$fields = array("us.*, Sum(us.no_of_people) as totalPeople, u.*, TIMESTAMP(ht.date, ht.start_time) as new_date");
				$match = array( "us.status" => '1',
								"us.is_delete" => '0',
								"us.is_payment" => '2',
								"us.user_id" => $userId
							);

				$params['join_tables'] = array(
								USER . ' as u' => 'u.user_id = us.user_id',
								HOURLY_TIMESLOT . ' as ht' => 'ht.hourly_ts_id = us.hourly_ts_id',
							);
							
				$groupBy = 'us.reservation_code';
				
				$reservationInfo = $this->common_model->get_records($table1, $fields, $params['join_tables'], 'left', $match, '', '', '', '', '', $groupBy,'');
				//pr($reservationInfo); exit;
				if(!empty($reservationInfo)){
					//pr($reservationInfo); exit;
					$sheduleDate = $reservationInfo[0]['new_date'];
					//pr($lastCancellationDateTime);
					//pr($sheduleDate); exit;
					if($sheduleDate >= $lastCancellationDateTime){
					
						$custom_data = array(
							'hourly_ts_id' => $reservationInfo[0]['hourly_ts_id'],
							'weekly_ts_id' => $reservationInfo[0]['weekly_ts_id'],
							'no_of_people' => $reservationInfo[0]['totalPeople'],
							'zip_code' => $reservationInfo[0]['zip_code'],
							'user_id' => $reservationInfo[0]['user_id'],
							'email' => $email,
							'current_time' => datetimeformat(),
						);
						
						$totalAmount = ($reservationInfo[0]['totalPeople'] * getCofigAmount());
						$totalVat = ( $totalAmount * getConfigVat() ) / 100 ;
						$totalFinalAmount = $totalAmount + $totalVat;
						
						$totalFinalAmount = number_format((float)$totalFinalAmount, 2, '.', ''); 
						
						$json_custom_data = json_encode($custom_data); // json encode
						
						$products = [];
						$products[0]['ItemName'] = 'TZOH'; //Item Name
						$products[0]['ItemPrice'] = $totalFinalAmount; //Item Price
						//$products[0]['ItemNumber'] = $_POST('itemnumber'); //Item Number
						$products[0]['ItemDesc'] = 'TZOH'; //Item Number
						$products[0]['ItemQty']	= '1'; // Item Quantity
						$products[0]['customData']	= $json_custom_data;
						
						$paypal= new MyPayPal();						
						
						$paypal->SetExpressCheckOut($products, $charges=array());
					}else{
						$msg = lang('invalid_token');
						$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
						redirect('/');
					}
					
				}else{
					$msg = lang('token_has_been_expried');
					$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
					redirect('/');
				}
			}else{
				$msg = lang('invalid_valid_token_found');
				$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect('/');
			}
		}else{
			$msg = lang('invalid_valid_token_found');
            $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
			redirect('/');
		}
		
		//exit;
		//redirect('/');
    }
	
	 /*
      @Author : Maitrak Modi
      @Desc   : Process on this page
      @Input  : 
      @Output :
      @Date   : 7th Nov 2017
     */
	
	public function process(){
		
		if((!empty($_GET['token'])) && (!empty($_GET['PayerID']))){
				
			$paypal= new MyPayPal(); // create paypal object
			
			$httpParsedResponse = $paypal->DoExpressCheckoutPayment(); // Method call
			
			foreach($httpParsedResponse as $key => $value){
				$httpParsedResponseAr[$key] = urldecode($value);
			}
			
			//echo "<pre>"; print_r($httpParsedResponseAr); exit;
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"])){
				 
				if('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
						
						$getPaymentDetails = $paypal->GetTransactionDetailsInfo();
						/*foreach($getPaymentDetails as $key => $value){
							$getPaymentDetailsAr[$key] = urldecode($value);
						}*/
						
						$tanscationId = $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"];
						$tanscationAmount = $httpParsedResponseAr["PAYMENTINFO_0_AMT"];
						$tanscationStatus = $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"];
						$tanscationCurrency = $httpParsedResponseAr["PAYMENTINFO_0_CURRENCYCODE"];
						$tanscationCustom = urldecode($getPaymentDetails["CUSTOM"]);
						
						$postData = json_decode($tanscationCustom,true);
										
						/* Start */
						// get schedule slot data
						$table = USER_SHEDULE_TIMESLOT . ' as us ';
						$where = array("c.is_delete" => '0', "us.status" => '1', "us.is_delete" => '0', "us.is_payment" => '2', "c.email" => $postData['email']);
						$fields = array("us.*, c.*");
						$params['join_tables'] = array(
													USER . ' as c' => 'c.user_id = us.user_id'
												);
											
						$data['dataList'] = $this->common_model->get_records($table, $fields, $params['join_tables'], 'left', $where, '', '', '', '', '', '', '');
						
						if(!empty($data['dataList'])){
							
							// Update the Slot Payment mode
							if($this->common_model->update(USER_SHEDULE_TIMESLOT, array('is_payment' => '1'), array('user_id' => $postData['user_id']))){
					
									// Insert the payment
									$paymentData = array(
										'res_code' => $data['dataList'][0]['reservation_code'],
										'transaction_id' => $tanscationId,
										'transaction_amount' => $tanscationAmount,
										'transaction_status' => $tanscationStatus,
										'currency' => $tanscationCurrency,
										'custom_message' => $tanscationCustom,
										'created_at' => datetimeformat()
									);
									
									if($this->common_model->insert(RESERVATION_PAYMENT, $paymentData)){
										//echo "<pre>"; print_r($data); exit;
										$this->common_model->update(USER, array('paypal_token' => ''), array('user_id' => $postData['user_id']));
										
										$data['userPerHour'] = getConfigUser(); //get current config no of prople
										$data['config_amount'] = getCofigAmount(); // get Config Amout          
										$data['config_vat'] = getConfigVat();//get Config VAT
										
										$data['main_content'] = 'Usershedule/adminpaymentpage';
										
										$this->parser->parse('layouts/UsersheduleTemplate', $data);
										
									}else{
										$msg = 'Something Went wrong.';
										$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
										redirect('/');
									}
							}else{
								$msg = lang('somthing_went_wrong');
								$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
								redirect('/');
							}
						}else{
							$msg = lang('somthing_went_wrong');
							$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
							redirect('/');
						}
						/* end */																
					}else{
						$msg = lang('payment_issue_found');
						$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
						redirect('/');
					}
					
					/*elseif('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]){
						
						echo '<div style="color:red">Transaction Complete, but payment may still be pending! '.
						'If that\'s the case, You can manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
					}*/
			}elseif ("SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
                $msg = $httpParsedResponseAr["L_LONGMESSAGE0"];
                $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect('/');	
            } else {
                $msg = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
                $this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
                redirect('/');
            }
			/*else{
				$msg = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
				$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>$msg</div>");
				redirect('/');
			}*/
		}
		
	}
   
}
