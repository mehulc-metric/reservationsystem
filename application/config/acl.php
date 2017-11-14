<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Base Site URL
  |--------------------------------------------------------------------------
  |
  | URL to your CodeIgniter root. Typically this will be your base URL,
  | WITH a trailing slash:
  |
  | http://example.com/
  |
  | WARNING: You MUST set this value!
  |
  | If it is not set, then CodeIgniter will try guess the protocol and path
  | your installation, but due to security concerns the hostname will be set
  | to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
  | The auto-detection mechanism exists only for convenience during
  | development and MUST NOT be used in production!
  |
  | If you need to allow multiple domains, remember that this file is still
  | a PHP script and you can easily do that on your own.
  |
 */
/**
 * User Module
 * [controller name][action] = array('respective function name for the Controller')
 */
//User
$config['User']['add'] = array('index', 'formValidation', 'phone_valid', 'add','insertData','edit','updateData','sendMailToCustomer','isDuplicateEmail','customerBulkDelete');
$config['User']['delete'] = array('index','deletedata', 'isDuplicateEmail');
$config['User']['edit'] = array('index','edit', 'updatedata', 'isDuplicateEmail','insertData','updateData','sendMailToCustomer');
$config['User']['view'] = array('index', 'userlist', 'view', 'isDuplicateEmail', 'testmail');


$config['Home']['view'] = array('index', 'changeview', 'grantview', 'get_home_header', 'get_home_activity');
$config['Errors']['view'] = array('index');

// Rolemaster Module
$config['Rolemaster']['add'] = array('insertdata', 'add', 'addPermission', 'insertPerms', 'assignPermission', 'addModule', 'insertAssginPerms', 'insertModule', 'checkRoleStatus', 'checkRoleAssignedToUser', 'permissionTab','updateRolebasedUserCreationCount','assignModuleCount','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['delete'] = array('deletedata', 'deletePerms', 'deleteAssignperms', 'deleteModuleData', 'permissionTab','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['edit'] = array('edit', 'updatedata', 'editPerms', 'updatePerms', 'editPermission', 'editModule', 'updateModule', 'insertAssginPerms', 'permissionTab','updateRolebasedUserCreationCount','assignModuleCount','editTimeCheckPurchasedUserLimit','updateTimeCheckUserAvailbility');
$config['Rolemaster']['view'] = array('index', 'role_list', 'view_perms_to_role_list', 'checkRoleStatus', 'checkRoleAssignedToUser', 'permissionTab');

//Module Master
$config['ModuleMaster']['add'] = array('add','insertModule', 'formValidation');
$config['ModuleMaster']['edit'] = array('edit', 'updateModule' ,'formValidation');
$config['ModuleMaster']['delete'] = array('deleteModuleData');
$config['ModuleMaster']['view'] = array('index');

//Timeshedule Module
$config['Timeshedule']['add'] = array('insert');
$config['Timeshedule']['edit'] = array('editRecord', 'update');
$config['Timeshedule']['delete'] = array('deleteEvent');
$config['Timeshedule']['view'] = array('index','getEvents','getTimeSlot');

//Setuphours Module
$config['Setuphours']['add'] = array('add','insert');
$config['Setuphours']['edit'] = array('editHour', 'updateHour');
$config['Setuphours']['delete'] = array('deleteHour');
$config['Setuphours']['view'] = array('index');

//Configs Module
$config['Configs']['add'] = array('update_data','update_payment_data');
$config['Configs']['edit'] = array('update_data','update_payment_data');
$config['Configs']['delete'] = array();
$config['Configs']['view'] = array('index','update_data','update_payment_data');

//Sheduleviewer 
$config['Sheduleviewer']['add'] = array('insert','insertUserSlot','sendMailToUser','checkSlotAvailbilityForGroup','insertSlotsForGroup','insertGroupUserIntoSlot','generateQRcodeForGroup','checkZipCode','isDuplicateEmail','update_for_group','checkSlotAvailbilityForSingleSlotGroup','insertSlotsForSingleSlotGroup','generateQRcodeForSingleSlotGroup','insertGroupForSingleSlotUserIntoSlot','sendMailToUserToPayment');
$config['Sheduleviewer']['edit'] = array('editRecord', 'update','insertUserSlot','sendMailToUser','checkSlotAvailbilityForGroup','insertSlotsForGroup','insertGroupUserIntoSlot','generateQRcodeForGroup','checkZipCode','isDuplicateEmail','update_for_group','checkSlotAvailbilityForSingleSlotGroup','insertSlotsForSingleSlotGroup','generateQRcodeForSingleSlotGroup','insertGroupForSingleSlotUserIntoSlot','sendMailToUserToPayment');
$config['Sheduleviewer']['delete'] = array();
$config['Sheduleviewer']['view'] = array('index','getEvents','getTimeSlot');

$config['ReservedUserList']['add'] = array('index','sendMailToUser','sendEmailToSelectedUser','view','exportToexcel');
$config['ReservedUserList']['edit'] = array('index','sendMailToUser','sendEmailToSelectedUser','view','exportToexcel');
$config['ReservedUserList']['delete'] = array('index','sendMailToUser','sendEmailToSelectedUser','exportToexcel');
$config['ReservedUserList']['view'] = array('index','getEvents','getTimeSlot','view','cancelReservation');


// CancelledReservedList 
$config['CancelledReservedList']['add'] = array('index','view','exportToexcel');
$config['CancelledReservedList']['edit'] = array('index','view','exportToexcel');
$config['CancelledReservedList']['delete'] = array('index','exportToexcel');
$config['CancelledReservedList']['view'] = array('index','view');

// UploadZipCode
$config['UploadZipCode']['add'] = array('index','sendMailToUser','sendEmailToSelectedUser','view','exportToexcel','importCSV','importCSVdata','checkDuplicateZipcode','downloadSampleFile');
$config['UploadZipCode']['edit'] = array('index','edit','sendEmailToSelectedUser','view','exportToexcel','updateData','checkDuplicateZipcode','downloadSampleFile');
$config['UploadZipCode']['delete'] = array('index','sendMailToUser','sendEmailToSelectedUser','exportToexcel','deleteData','deleteZipcode');
$config['UploadZipCode']['view'] = array('index','getEvents','getTimeSlot','view','downloadSampleFile');
