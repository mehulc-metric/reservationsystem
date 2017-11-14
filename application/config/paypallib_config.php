<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Paypal IPN Class
// ------------------------------------------------------------------------

// Use PayPal on Sandbox or Live
$config['sandbox'] = TRUE; // FALSE for live environment

// PayPal Business Email ID
$config['business'] = 'rupesh.jorkar@c-metric.com';

// If (and where) to log ipn to file
$config['paypal_lib_ipn_log_file'] = BASEPATH . 'logs/paypal_ipn.log';
$config['paypal_lib_ipn_log'] = TRUE;

// Where are the buttons located at 
$config['paypal_lib_button_path'] = 'buttons';

// What is the default currency?
$config['paypal_lib_currency_code'] = 'EUR';

//Return URL 
$config['paypal_return_url'] = base_url('Usershedule/Payinfo/process'); // success url
$config['paypal_cancel_url'] = base_url('Usershedule/Paypal/cancel');
$config['paypal_notify_url'] = ''; //base_url('Usershedule/Paypal/cancel');

if($config['sandbox']) {
// Refund Paramater (Sandbox)
$config['paypal_mode'] = 'sandbox';
$config['paypal_business_username'] = 'rupesh.jorkar_api1.c-metric.com';
$config['paypal_business_password'] = 'TGAJR6GUP5QTCKDG';
$config['paypal_business_signature'] = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AM4weMVQx5zIPA0acbjP1SjaOfK-';
$config['paypal_business_api_endpoint'] = 'https://api-3t.sandbox.paypal.com/nvp';
$config['paypal_refund_version'] = '94.0';
$config['paypal_refund_type'] = 'Full'; //Partial or Full
}else{
// Refund Paramater (Live)
$config['paypal_mode'] = 'live';
$config['paypal_business_username'] = 'rupesh.jorkar_api1.c-metric.com';
$config['paypal_business_password'] = 'TGAJR6GUP5QTCKDG';
$config['paypal_business_signature'] = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AM4weMVQx5zIPA0acbjP1SjaOfK-';
$config['paypal_business_api_endpoint'] = 'https://api-3t.paypal.com/nvp';
$config['paypal_refund_version'] = '94.0';
$config['paypal_refund_type'] = 'Full'; //Partial or Full
}


?>
