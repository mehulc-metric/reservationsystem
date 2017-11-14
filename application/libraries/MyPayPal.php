<?php

	class MyPayPal {
		
		function __construct(){
			$this->CI =& get_instance();
			$this->CI->load->config('paypallib_config');
			
		}
		function GetItemTotalPrice($item){
		
			//(Item Price x Quantity = Total) Get total amount of product;
			return $item['ItemPrice'] * $item['ItemQty']; 
		}
		
		function GetProductsTotalAmount($products){
		
			$ProductsTotalAmount=0;

			foreach($products as $p => $item){
				
				$ProductsTotalAmount = $ProductsTotalAmount + $this->GetItemTotalPrice($item);	
			}
			
			return $ProductsTotalAmount;
		}
		
		function GetGrandTotal($products, $charges){
			
			//Grand total including all tax, insurance, shipping cost and discount
			
			$GrandTotal = $this->GetProductsTotalAmount($products);
			
			foreach($charges as $charge){
				
				$GrandTotal = $GrandTotal + $charge;
			}
			
			return $GrandTotal;
		}
		
		function SetExpressCheckout($products, $charges, $noshipping='1', $returnURL ='', $cancelURL=''){
			
			//Parameters for SetExpressCheckout, which will be sent to PayPal
			
			$padata  = 	'&METHOD=SetExpressCheckout';
			
			$returnFinalURL = (!empty($returnURL))? $returnURL : $this->CI->config->item('paypal_return_url');
			$cancelFinalURL = (!empty($cancelURL))? $cancelURL : $this->CI->config->item('paypal_cancel_url');
			
			$padata .= 	'&RETURNURL='.urlencode($returnFinalURL);
			$padata .=	'&CANCELURL='.urlencode($cancelFinalURL);
			$padata .=	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
			//$padata .=	'&PAYMENTREQUEST_0_CUSTOM='.urlencode("132565sad");
			
			foreach($products as $p => $item){
				
				$padata .=	'&L_PAYMENTREQUEST_0_NAME'.$p.'='.urlencode($item['ItemName']);
				//$padata .=	'&L_PAYMENTREQUEST_0_NUMBER'.$p.'='.urlencode($item['ItemNumber']);
				$padata .=	'&L_PAYMENTREQUEST_0_DESC'.$p.'='.urlencode($item['ItemDesc']);
				$padata .=	'&L_PAYMENTREQUEST_0_AMT'.$p.'='.urlencode($item['ItemPrice']);
				$padata .=	'&L_PAYMENTREQUEST_0_QTY'.$p.'='. urlencode($item['ItemQty']);
				$padata .=	'&PAYMENTREQUEST_0_CUSTOM='.urlencode($item['customData']);
			}		

						
			$padata .=	'&NOSHIPPING='.$noshipping; //set 1 to hide buyer's shipping address, in-case products that does not require shipping
						
			$padata .=	'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($item['ItemPrice']);
			$padata .=	'&PAYMENTREQUEST_0_AMT='.urlencode($item['ItemPrice']);
			$padata .=	'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->CI->config->item('paypal_lib_currency_code'));
			$padata .=	'&PAYMENTREQUEST_0_CUSTOM='.urlencode('mycustomdata');
			
			
			//paypal custom template
			
			//$padata .=	'&LOCALECODE='.PPL_LANG; //PayPal pages to match the language on your website;
			//$padata .=	'&LOGOIMG='.PPL_LOGO_IMG; //site logo
			$padata .=	'&CARTBORDERCOLOR=FFFFFF'; //border color of cart
			$padata .=	'&ALLOWNOTE=1';
						
			############# set session variable we need later for "DoExpressCheckoutPayment" #######
			
			$_SESSION['ppl_products'] =  $products;
			//$_SESSION['ppl_charges'] 	=  $charges;
			
			$httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $padata);
			
			//Respond according to message we receive from Paypal
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){

				$paypalmode = ($this->CI->config->item('paypal_mode')=='sandbox') ? '.sandbox' : '';
			
				//Redirect user to PayPal store with Token received.
				
				$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
				
				header('Location: '.$paypalurl);
			}
			else{
				
				//Show error message
				
				echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				
				echo '<pre>';
					
					print_r($httpParsedResponseAr);
				
				echo '</pre>';
			}	
		}		
		
			
		function DoExpressCheckoutPayment(){
			
			if(!empty($_SESSION['ppl_products'])){
				
				$products=$_SESSION['ppl_products'];
				
				//$charges=$_SESSION['ppl_charges'];
				
				$padata  = 	'&TOKEN='.urlencode($_GET['token']);
				$padata .= 	'&PAYERID='.urlencode($_GET['PayerID']);
				$padata .= 	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
				//$padata .=	'&PAYMENTREQUEST_0_CUSTOM='.urlencode("132565sad");
				
				//set item info here, otherwise we won't see product details later	
				
				foreach($products as $p => $item){
					
					$padata .=	'&L_PAYMENTREQUEST_0_NAME'.$p.'='.urlencode($item['ItemName']);
					//$padata .=	'&L_PAYMENTREQUEST_0_NUMBER'.$p.'='.urlencode($item['ItemNumber']);
					$padata .=	'&L_PAYMENTREQUEST_0_DESC'.$p.'='.urlencode($item['ItemDesc']);
					$padata .=	'&L_PAYMENTREQUEST_0_AMT'.$p.'='.urlencode($item['ItemPrice']);
					$padata .=	'&L_PAYMENTREQUEST_0_QTY'.$p.'='. urlencode($item['ItemQty']);
					$padata .=	'&PAYMENTREQUEST_0_CUSTOM='.urlencode($item['customData']);
				}
				
				$padata .= 	'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($item['ItemPrice']);				
				$padata .= 	'&PAYMENTREQUEST_0_AMT='.urlencode($item['ItemPrice']);
				$padata .= 	'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->CI->config->item('paypal_lib_currency_code'));
				
				
				//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
				
				$httpParsedResponseAr = $this->PPHttpPost('DoExpressCheckoutPayment', $padata);
				
				return $httpParsedResponseAr;
				//var_dump($httpParsedResponseAr);
				//exit;

			}
			/*else{
				
				// Request Transaction Details
				$this->GetTransactionDetails();
			}*/
		}
				
		function GetTransactionDetails(){
		
			// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
			// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
			
			$padata = 	'&TOKEN='.urlencode($_GET['token']);
			
			$httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata, $this->CI->config->item('paypal_business_username'), $this->CI->config->item('paypal_business_password'), $this->CI->config->item('paypal_business_signature'), $this->CI->config->item('paypal_mode'));

			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
				
				echo '<br /><b>Stuff to store in database :</b><br /><pre>';
				
				/*
				#### SAVE BUYER INFORMATION IN DATABASE ###
				//see (http://www.sanwebe.com/2013/03/basic-php-mysqli-usage) for mysqli usage
				
				$buyerName = $httpParsedResponseAr["FIRSTNAME"].' '.$httpParsedResponseAr["LASTNAME"];
				$buyerEmail = $httpParsedResponseAr["EMAIL"];
				
				//Open a new connection to the MySQL server
				$mysqli = new mysqli('host','username','password','database_name');
				
				//Output any connection error
				if ($mysqli->connect_error) {
					die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
				}		
				
				$insert_row = $mysqli->query("INSERT INTO BuyerTable 
				(BuyerName,BuyerEmail,TransactionID,ItemName,ItemNumber, ItemAmount,ItemQTY)
				VALUES ('$buyerName','$buyerEmail','$transactionID','$products[0]['ItemName']',$products[0]['ItemNumber'], $products[0]['ItemTotalPrice'],$ItemQTY)");
				
				if($insert_row){
					print 'Success! ID of last inserted record is : ' .$mysqli->insert_id .'<br />'; 
				}else{
					die('Error : ('. $mysqli->errno .') '. $mysqli->error);
				}
				
				*/
				
				echo '<pre>';
				
					print_r($httpParsedResponseAr);
					
				echo '</pre>';
			} 
			else  {
				
				echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				
				echo '<pre>';
				
					print_r($httpParsedResponseAr);
					
				echo '</pre>';

			}
		}
		
		function GetTransactionDetailsInfo(){
		
			// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
			// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
			
			$padata = 	'&TOKEN='.urlencode($_GET['token']);
			
			$httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata, $this->CI->config->item('paypal_business_username'), $this->CI->config->item('paypal_business_password'), $this->CI->config->item('paypal_business_signature'), $this->CI->config->item('paypal_mode'));
				
			return $httpParsedResponseAr;

		}
		
		function PPHttpPost($methodName_, $nvpStr_) {
				
				// Set up your API credentials, PayPal end point, and API version.
				$API_UserName = urlencode($this->CI->config->item('paypal_business_username'));
				$API_Password = urlencode($this->CI->config->item('paypal_business_password'));
				$API_Signature = urlencode($this->CI->config->item('paypal_business_signature'));
				
				$paypalmode = ($this->CI->config->item('paypal_mode')=='sandbox') ? '.sandbox' : '';
		
				$API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
				$version = urlencode('109.0');
			
				// Set the curl parameters.
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
				curl_setopt($ch, CURLOPT_VERBOSE, 1);
				//curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
				
				// Turn off the server and peer verification (TrustManager Concept).
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
			
				// Set the API operation, version, and API signature in the request.
				$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
			
				// Set the request as a POST FIELD for curl.
				curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
			
				// Get response from the server.
				$httpResponse = curl_exec($ch);
			
				if(!$httpResponse) {
					exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
				}
			
				// Extract the response details.
				$httpResponseAr = explode("&", $httpResponse);
			
				$httpParsedResponseAr = array();
				foreach ($httpResponseAr as $i => $value) {
					
					$tmpAr = explode("=", $value);
					
					if(sizeof($tmpAr) > 1) {
						
						$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
					}
				}
			
				if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
					
					exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
				}
			
			return $httpParsedResponseAr;
		}
	}
