<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {   
        show_404();
    }
}
