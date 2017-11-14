<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'] = array(
    'class' => 'App_hooks',
    'function' => 'save_requested',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks',
    'params' => ''
);

// Allows us to perform good redirects to previous pages.
$hook['post_controller_constructor'] = array(
    'class' => 'App_hooks',
    'function' => 'prep_redirect',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks',
    'params' => ''
);

// Load Config from DB
$hook['post_controller_constructor'] = array(
    'class' => '',
    'function' => 'load_config',
    'filename' => 'App_config.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'] = array(
    'class' => 'ACL',
    'function' => 'auth',
    'filename' => 'App_acl.php',
    'filepath' => 'hooks'
);