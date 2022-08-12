<?php
// USED FOR LOGIN CHECK AND GOING ON LOGIN VIEW//
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('America/New_York');

class Login extends CI_Controller
{


    public function __construct() {
        parent::__construct();
        $this->load->helper(array('cookie', 'url'));
        $this->load->helper('string');
//        if(!($this->session->userdata('user_id')))
//        {
//            redirect('Login');
//
//        }
//        else{
//            redirect('Login');
//        }
    }

    public function index()
    {
        /*pass the site info*/
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'index';

        // Load libraries
//         $this->load->library('src/Cookies.php');
        // $this->load->library('src/Remember.php');
        $getcookie = get_cookie('track_asins');
        if(isset($getcookie)){
            $result = $this->db->query("SELECT * FROM users where ID='".$getcookie."'")->row();
            $data1 = array('user_id' => $result->ID, 'sellerId' => $result->sellerID);
            $this->session->set_userdata($data1);
            $_SESSION['uid'] = $data1['user_id'];
            $_SESSION['unique_salt_id'] = $result->unique_string_id;
            $_SESSION['loggedin_time'] = time();
            redirect('dashboard');
        }
        // Load stuff
        $data['stylesheet'] = 'home';
        $data['javascript'] = 'home';

        $this->load->model('Common_model');
        $error_data = array();
        ////------------------------code of registration form start here--------////
        if (isset($_POST['signup'])) {

            $this->form_validation->set_rules('sl_firstname_signup', 'sl_firstname_signup', 'required');
           // $this->form_validation->set_rules('sl_lastname_signup', 'sl_lastname_signup', 'required');
            $this->form_validation->set_rules('sl_company_name_signup', 'sl_company_name_signup', 'required');
            //$this->form_validation->set_rules('sl_seller_id_signup', 'sl_seller_id_signup', 'required');
            $this->form_validation->set_rules('ta_email_signup', 'ta_email_signup', 'required');
            $this->form_validation->set_rules('ta_email_confirm_signup', 'ta_email_confirm_signup', 'required');
            $this->form_validation->set_rules('ta_password_signup', 'ta_password_signup', 'required');
            $this->form_validation->set_rules('ta_password_confirm_signup', 'ta_password_confirm_signup', 'required');
            $this->form_validation->set_rules('sl_phone_number', 'sl_phone_number', 'required');

            $this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');

            if ($this->form_validation->run() == true) {

                $password = md5($this->input->post('ta_password_signup'));
                $data = array(
                    'fname'       => $this->input->post('sl_firstname_signup'),
                    //'lname'       => $this->input->post('sl_lastname_signup'),
                    'company'     => $this->input->post('sl_company_name_signup'),
                    'sellerID'    => '',//$this->input->post('sl_seller_id_signup'),
                    'email'       => $this->input->post('ta_email_signup'),
                    'password'    => $password,
                    'phone'       => $this->input->post('sl_phone_number'),
                    'global_noti' => 'true',
                    'created_at'  => date('Y-m-d H:i:s'),
                    'activated' => '1',
                    'notification_email'       => $this->input->post('ta_email_signup'),
                    'additional_email'       => $this->input->post('ta_email_signup'),
                    'notification_phone'       => $this->input->post('sl_phone_number'),
                    'unique_string_id' =>$this->checkExistUniqueString()
                );

                $result = $this->common_model->getDataSingleRow('users', $data);

                if ($result) {
                    $this->session->set_flashdata('msg', 'Email and password to not match our records');
                    redirect('login', 'refresh');
                } else {
                    $result_ins = $this->Common_model->insertData('users', $data);
                    $result = $this->common_model->getDataSingleRow('users', ['ID' => $result_ins]);
                    if ($result_ins) {
                        $data1 = array('user_id' => $result_ins, 'sellerId' => $result->sellerID);
                        $this->session->set_userdata($data1);
                        $_SESSION['uid'] = $data1['user_id'];
                        $_SESSION['unique_salt_id'] = $result->unique_string_id;
                        $_SESSION['loggedin_time'] = time();
                        set_cookie('sign_up_msg','sign_up_msg','3600');
                        redirect('dashboard');
                    }
                }
            }


        }

        ////--------------------code of registration form end here----------------///

        ////--------------------code of login form start here---------------------///

        if (isset($_POST['login'])) {
            $this->form_validation->set_rules('sl_email_login', 'sl_email_login', 'required');
            $this->form_validation->set_rules('sl_password_login', 'sl_password_login', 'required');
            $this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');

            if ($this->form_validation->run() == true) {
                $remember_me = $this->input->post('sl_remember_me');
                $password = md5($this->input->post('sl_password_login'));
                $data = array(
                    'email'    => $this->input->post('sl_email_login'),
                    'password' => $password
                );

                $result = $this->common_model->getDataSingleRow('users', $data);

                if ($result) {
                    if(isset($result->unique_string_id) && ($result->unique_string_id == "")){
                        $updateData = array(
                            'unique_string_id' => $this->checkExistUniqueString()
                        );
                        $this->db->where('ID', $result->ID);
                        $this->db->update('users', $updateData);
                    }

                    if($remember_me == 1){
                        //echo $result->email;exit;
                        set_cookie('track_asins',$result->ID,'3600');
                        set_cookie('uemail',$result->email,'3600');
                        
                    } else {
                        delete_cookie('track_asins');
                        delete_cookie('uemail');

                    }
                    $data1 = array('user_id' => $result->ID, 'sellerId' => $result->sellerID);
                    $this->session->set_userdata($data1);
                    $_SESSION['uid'] = $data1['user_id'];
                    $_SESSION['unique_salt_id'] = $result->unique_string_id;
                    $_SESSION['loggedin_time'] = time();
                    if(!isset($result->created_at)){
                        $updateData = array(
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('ID', $_SESSION['uid']);
                        $this->db->update('users', $updateData);
                    }


                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('msg1', 'Email and password to not match our records');
                    redirect('login', 'refresh');

//                    $error_data = array(
//                        'msg' => 'email and password not matched.',
//                    );
                }
            }
        }
        $data['msg1'] = $this->session->flashdata('msg1');
        $data['msg'] =  $this->session->flashdata('msg');
        $this->load->view('header.php', $data);
        $this->load->view('home');
        $this->load->view('templates/footer.php');
    }

    public function checkExistUniqueString(){

        $continue = true;
        while($continue){
            $string =md5(uniqid(rand(), true));
            $checkExistStringCount = $this->db->query("SELECT * FROM users WHERE unique_string_id='".$string."'")->num_rows();
            if($checkExistStringCount !=  1){
                $continue = false;
            }
            return $string;
        }


    }

}
