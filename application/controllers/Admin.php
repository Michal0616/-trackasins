<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/New_York');

class Admin extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('user_id'))) {
            redirect('Admin_login');
        }
    }

    public function index()
    {
        $this->load->view('templates/admin_header.php');
        $this->load->view('admin_dashboard.php');
        $this->load->view('templates/admin_footer.php');
    }
}

