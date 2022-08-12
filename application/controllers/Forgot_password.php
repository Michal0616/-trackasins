<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot_password extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public $res = array();

    public function __construct() {
        parent::__construct();
        $this->load->Model('Forgot_password_model');
    }

    public function index()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'forgot_password';

        // Load stuff
        $data['stylesheet'] = 'forgot_password';
        $data['javascript'] = 'forgot_password';

        // Load header library
        $this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('header.php', $data);
        $this->load->view('home/forgot_password/forgot_password');
        $this->load->view('templates/footer.php');
    }

    /*
     * This will server as the controller function that ajax called to open the change pass page
     */
    public function change_pass($request_id, $unique_id)
    {
        if(!empty($request_id) && !empty($unique_id))
        {

            // make sure this is a valid request
            if($this->Forgot_password_model->checkRequestId($request_id, $unique_id))
            {

                // Pass the site info
                $data['site_info'] = $this->config->item('site_info');
                $data['base_url'] = $this->config->item('base_url');
                $data['site_page'] = 'forgot_password';

                // Load stuff
                $data['stylesheet'] = 'forgot_password';
                $data['javascript'] = 'forgot_password';

                // Load vars
                $data['request_id'] = $request_id;
                $data['unique_id'] = $unique_id;

                // Load header library
                $this->load->library('ForgotPasswordSystem.php');

                // load the view
                $this->load->view('header.php', $data);
                $this->load->view('home/forgot_password/change_pass');
                $this->load->view('templates/footer.php');
            }else
            {
                redirect('/' ,'location');
            }
        }else
        {
            redirect('/' ,'location');
        }
    }

    /*
     * This will server as the controller function that ajax called to open the change pass page
     */
    public function changePassProcess()
    {
        if(isset($_POST['request_id']) && isset($_POST['unique_id']) && isset($_POST['password']) && isset($_POST['confirm_password']))
        {
            // make sure this is a valid request
            if($this->Forgot_password_model->checkRequestId($_POST['request_id'], $_POST['unique_id']))
            {
                $request_id = $this->input->post('request_id');
                $unique_id = $this->input->post('unique_id');
                $password = $this->input->post('password');
                $password_confirm = $this->input->post('confirm_password');

                $this->Forgot_password_model->changePassProcess($request_id, $unique_id, $password, $password_confirm);
            }else
            {
                redirect('/' ,'location');
            }
        }else
        {
            redirect('/' ,'location');
        }
    }
    
    /*
     * For requesting to recover your password
     */
    public function requestPasswordReset()
    {
        if(isset($_POST['username_or_email']))
        {
            $string = $this->input->post('username_or_email');
            if(!empty($string))
            {
                // Send this to the model
                $this->Forgot_password_model->makeRequest($string);
            }else
            {
                $this->res['code'] = 0;
                $this->res['string'] = "Please enter a username or email!";

                echo json_encode($this->res);
                return false;
            }
        }
    }
}