<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
define('upload_path', 'uploads/upload_image');

define('STRIPE_KEY_SK', 'sk_test_ELazZ12erwvhGaSFfpzaAAmB');
define('STRIPE_KEY_PK', 'pk_test_xN0QLDiaKoIQ1RNDwItzw8sk');

define('CUSTOMER_TABLE', 'customers');
define('ADMIN_SITE', 'Admin');

define('USERS_SITE', 'home');
define('PROFILE_SITE', 'profile');
define('SITE_NAME', 'Reservation System');
define('LOG_MASTER', 'log_master');

define('CI_SESSION', 'ci_sessions');


// Added By Mehul Patel
define('GOOGLE_CLIENT_ID', '393168827270-o0m0soav3t5la5376jc2ps30booofa0h.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'DmCMygZBDvHJY3z7ILd6klJk');
define('FROM_EMAIL_ID', 'cmswtest101@gmail.com');
define('SITENAME', 'Reservation System');
define('SITE_NAME_SMALL', 'RS');
//Added by niral
define('GREEN_COLOR', '#96D296');
define('YELLOW_COLOR', '#FBE7AA');
define('RED_COLOR', '#E79696');
// Added By Mehul Patel
define('PROJECT', 'project');

define('SURVEY_TRACKER', 'survey_tracker');
define('SENTIMENT_TRACKER', 'sentiment_tracker');
define('APP_WEBSITE_TRACKER', 'app_website_tracker');
define('ROLES_TABLE', 'roles');
define('ERROR_START_DIV_NEW', '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>');
define('ERROR_END_DIV', '</div>');
define('ERROR_START_DIV', '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>');


define('USER', 'res_user');
define('WEEKLY_TIMESLOT', 'res_weekly_time_slot_reservable');
define('HOURLY_TIMESLOT', 'res_hourly_time_slot');
define('CONFIG_TABLE', 'res_config');
define('WEEKLT_TOTAL_SLOT', 'res_weekly_hourly_total_slot');
define('USER_SHEDULE_TIMESLOT', 'user_shedule_time_slot');
define('USER_CANCEL_SHEDULE_TIMESLOT', 'user_cancel_shedule_time_slot');

define('ROLE_MASTER', 'role_master');
define('AAUTH_PERMS', 'aauth_perms');
define('AAUTH_PERMS_TO_ROLE', 'aauth_perm_to_group');
define('MODULE_MASTER', 'module_master');
define('USER_VISIT', 'user_visit');
define('LANGUAGE_MASTER', 'language_master');
define('GROUP_RESERVATION', 'group_reservation');
define("RESERVATION_CODE", 'RES_00');

define('UPLOAD_ZIP_CODE', 'upload_zip_code');

define('NO_OF_RECORDS_PER_PAGE', '10'); // Added By Maitrak Modi , Dt : 17th Oct 2017

define('RESERVATION_PAYMENT', 'reservation_payment'); // Added By Maitrak Modi , Dt : 30th Oct 2017

define('CANCELLATION_DURATION', '+24 Hour'); // Added By Maitrak Modi , Dt : 2nd Nov 2017 (Note : duration in hours)
