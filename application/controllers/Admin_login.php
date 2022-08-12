<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/New_York');

class Admin_login extends CI_Controller
{

    public function index()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'admin';

        // Title
        $data['title_addition'] = 'Admin Dashboard';

        // Load stuff
        $data['stylesheet'] = 'admin_dashboard';
        $data['javascript'] = 'admin_dashboard';

        // load the view
        $this->load->view('admin_login.php', $data);
    }
}

