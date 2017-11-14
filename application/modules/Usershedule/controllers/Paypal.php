<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paypal extends CI_Controller 
{
    function  __construct(){
        parent::__construct();
        $this->load->library('paypal_lib');
        $this->load->library('PayPalRefund');
		//$this->viewname = ucfirst($this->router->fetch_class());
    }
	 
	/*
      @Author : Maitrak Modi
      @Desc   : Paypal Cancelled the Payment URL
      @Input  :
      @Output :
      @Date   : 30th Oct 2017
    */
	 
    public function cancel(){
		$this->session->set_flashdata('session_msg', "<div class='alert alert-danger text-center'>" . lang('cancelled_payment_msg') . "</div>");
		redirect('/'); // redirect to the main page.
    }
    
	
	// Need to setup the domain
    public function ipn(){
		
        //paypal return transaction details array
        //$paypalInfo    = $_POST;
		//echo "<pre>"; print_r($paypalInfo); exit;
        $data['user_id'] = $paypalInfo['custom'];
        $data['product_id']    = $paypalInfo["item_number"];
        $data['txn_id']    = $paypalInfo["txn_id"];
        $data['payment_gross'] = $paypalInfo["mc_gross"];
        $data['currency_code'] = $paypalInfo["mc_currency"];
        $data['payer_email'] = $paypalInfo["payer_email"];
        $data['payment_status']    = $paypalInfo["payment_status"];

        $paypalURL = $this->paypal_lib->paypal_url;     
        $result    = $this->paypal_lib->curlPost($paypalURL,$paypalInfo);
        
        //check whether the payment is verified
        if(preg_match("/VERIFIED/i",$result)){
            //insert the transaction data into the database
            //$this->product->insertTransaction($data);
			// Insert the data from here
        }
    }	
	
	
	// Refund Proccess
	/*public function refund($reservationCode) {
		
		echo "Call"; exit;
		if(!empty($reservationCode)) {
			
			$field = array('*');
			$match = array('res_code' => trim($reservationCode));
			$paymentExists = $this->common_model->get_records(RESERVATION_PAYMENT, $field, '', '', $match, '', '', '', '', '', '', '', '', '', '');
				
			if(!empty($paymentExists)) {
				
				echo"<pre>"; print_r($paymentExists); exit;
				
				$dataInArray['transactionID'] =  $refundTrasncationId;
				$dataInArray['refundType'] = $this->config->item('paypal_refund_type');
				$dataInArray['currencyCode'] = $this->config->item('paypal_lib_currency_code');
				$dataInArray['memo'] = "FULL AMOUNT REFUND";
			 
				$ref = new PayPalRefund();
				$aryRes = $ref->refundAmount($dataInArray);
			 
				if($aryRes['ACK'] == "Success"){
					$response = "Success";
				}else {
					$response = "Failure";
				}
			}else{
				$response = "";
			}
		}else{
			$response = "Failure";
		}
		return $response;
	}*/
}