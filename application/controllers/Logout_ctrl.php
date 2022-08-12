<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('America/New_York');
class Logout_ctrl extends CI_Controller
{
    public function __construct() {
       
        parent::__construct();
        if (!($this->session->userdata('user_id'))) {
            redirect('login');
        }
        if (!($_SESSION['uid'])){
            redirect('login');
        }
        
        $this->load->helper(array('cookie', 'url'));
        
    }
		public function index()
		{
            
            delete_cookie('track_asins');
            //delete_cookie('uemail');
            $this->session->sess_destroy();
            $this->load->library('user_agent');
            $refer =  $this->agent->referrer();
            if($refer != '') {
                if($refer == base_url('dashboard') || $refer == base_url('settings') ||
                    $refer == base_url('report') || $refer == base_url('notification') ||
                    $refer == base_url('settings/amazon-api-settings') || $refer == base_url('settings/security-settings') ||
                    $refer == base_url('settings/membership-account') ){
                        
                    redirect('login');
                }else {
                    redirect($refer);
                }
            } else {
                
                redirect('login');
            }
		}
}
