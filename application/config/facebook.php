<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Facebook API Configuration
| -------------------------------------------------------------------
|
| To get an facebook app details you have to create a Facebook app
| at Facebook developers panel (https://developers.facebook.com)
|
|  facebook_app_id               string   Your Facebook App ID.
|  facebook_app_secret           string   Your Facebook App Secret.
|  facebook_login_type           string   Set login type. (web, js, canvas)
|  facebook_login_redirect_url   string   URL to redirect back to after login. (do not include base URL)
|  facebook_logout_redirect_url  string   URL to redirect back to after logout. (do not include base URL)
|  facebook_permissions          array    Your required permissions.
|  facebook_graph_version        string   Specify Facebook Graph version. Eg v2.6
|  facebook_auth_on_load         boolean  Set to TRUE to check for valid access token on every page load.
*/

if($_SERVER['HTTP_HOST'] == 'localhost')
{
	$config['facebook_app_id']              = '1661252784183187';
	$config['facebook_app_secret']          = 'b6de1b7d5d464288d2bf0e4220025ea8';
	$config['facebook_login_type']          = 'web';
	$config['facebook_login_redirect_url']  = 'Login';
	$config['facebook_logout_redirect_url'] = 'Login/logout';
	$config['facebook_permissions']         = array('email');
	$config['facebook_graph_version']       = 'v2.9';
	$config['facebook_auth_on_load']        = TRUE;
	
}else{
	$config['facebook_app_id']              = '115937592320049';
	$config['facebook_app_secret']          = '72f0dac8b3623628e04c7baf02034436';
	$config['facebook_login_type']          = 'web';
	$config['facebook_login_redirect_url']  = 'Login';
	$config['facebook_logout_redirect_url'] = 'Login/logout';
	$config['facebook_permissions']         = array('email');
	$config['facebook_graph_version']       = 'v2.9';
	$config['facebook_auth_on_load']        = TRUE;
}
