<?php
require_once APPPATH . 'libraries/mailgun-php/vendor/autoload.php';
defined('BASEPATH') OR exit('No direct script access allowed');
use Mailgun\Mailgun;
class Help extends CI_Controller
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

    public $mgClient;
    public $mgDomain = "trackasins-master.com";

    private $res = array();

    public function __construct()
    {
        parent::__construct();
        //$this->load->Model('Help_model');
        $this->load->Model('EmailSupports_model');
        $this->load->Model('TrackSupports_model');
        $this->load->Model('Supports_model');
        $this->load->library('SessionTimeout');
	$this->load->helper(array('cookie', 'url'));
	$this->mgClient = new Mailgun('key-ea0f1a943eae0a7166d10288f09169ea');
    }
    public function index(){
        redirect('/help/about-us');
    }
    public function about_us()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'help';

        // Title
        $data['title_addition'] = 'About Us';

        // Load stuff
        $data['stylesheet'] = 'help';
        $data['javascript'] = 'help';

        // Load header library
        //$this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('home/help/index');
        $this->load->view('templates/footer.php');
    }

    public function how_it_works()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'help';

        // Title
        $data['title_addition'] = 'How it works';

        // Load stuff
        $data['stylesheet'] = 'help';
        $data['javascript'] = 'help';

        // Load header library
        //$this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('home/help/how_it_works');
        $this->load->view('templates/footer.php');
    }

    public function pricing()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'help';

        // Title
        $data['title_addition'] = 'Pricing';

        // Load stuff
        $data['stylesheet'] = 'help';
        $data['javascript'] = 'help';
        $data['email_supports'] = $this->EmailSupports_model->getAllEmailSupports();
        $data['track_supports'] = $this->TrackSupports_model->getAllTrackSupports();
        // Load header library
        //$this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('home/help/pricing');
        $this->load->view('templates/footer.php');
    }

    public function faq()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'help';

        // Title
        $data['title_addition'] = 'Faq';

        // Load stuff
        $data['stylesheet'] = 'help';
        $data['javascript'] = 'help';

        // Load header library
        //$this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('home/help/faq');
        $this->load->view('templates/footer.php');
    }

    public function policies()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'help';

        // Title
        $data['title_addition'] = 'Policies';

        // Load stuff
        $data['stylesheet'] = 'help';
        $data['javascript'] = 'help';

        // Load header library
        //$this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('home/help/policies');
        $this->load->view('templates/footer.php');
    }

    public function contact_us()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'help';

        // Title
        $data['title_addition'] = 'Contact Us';

        // Load stuff
        $data['stylesheet'] = 'help';
        $data['javascript'] = 'help';

        // Load header library
        //$this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('home/help/contact_us');
        $this->load->view('templates/footer.php');
    }

    public function documentation() {
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'help';

        // Title
        $data['title_addition'] = 'Documentation';

        // Load stuff
        $data['stylesheet'] = 'help';
        $data['javascript'] = 'help';

        // Load header library
        //$this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('home/help/documentation');
        $this->load->view('templates/footer.php');
    }
    public function terms()
    {
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'help';

        // Title
        $data['title_addition'] = 'Contact Us';

        // Load stuff
        $data['stylesheet'] = 'help';
        $data['javascript'] = 'help';

        // Load header library
        //$this->load->library('ForgotPasswordSystem.php');

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('home/help/terms');
        $this->load->view('templates/footer.php');
    }

    /*
     * AJAX CALLS
     */
    public function change_profile_picture()
    {
        if(isset($_FILES['profile_picture_file']))
        {
            $this->Settings_model->changeProfilePic($_FILES['profile_picture_file']);
        }else{
            $this->_res['code'] = 0;
            $this->_res['string'] = "Invalid Request";

            echo $this->_res;
        }
    }
    
    public function change_basic_information()
    {
        if(isset($_POST['firstname'])  && isset($_POST['email']) && isset($_POST['company_name']) && isset($_POST['seller_id']) && isset($_POST['phone_number']) && isset($_POST['location'])) {
            $firstname = $this->input->post('firstname');
            
            $company = $this->input->post('company_name');
            $seller_id = $this->input->post('seller_id');
            $phone = $this->input->post('phone_number');
            $email = $this->input->post('email');
            $location = $this->input->post('location');

            if (!empty($firstname) && !empty($seller_id) && !empty($company) && !empty($phone) && !empty($email))
            {
                // Wrap info in this array
                $data = array('firstname' => $firstname, 'company' => $company, 'seller_id' => $seller_id, 'phone' => $phone, 'email' => $email, 'location' => $location);

                // Now call function
                $this->Settings_model->changeBasicInfo($data);
            } else {
                $this->res['code'] = 0;
                $this->res['string'] = "Please fill in all of the fields!";

                echo json_encode($this->res);
                return false;
            }
        }else{
            $this->_res['code'] = 0;
            $this->_res['string'] = "Invalid Request";

            echo json_encode($this->_res);
        }
    }

    public function upgrade_plan_process()
    {
        if(isset($_POST['plan_select']))
        {
            $plan = $this->input->post('plan_select');
            
            if($plan != "")
            {
                $this->Settings_model->changePlan($plan);
            }else {
                $this->res['code'] = 0;
                $this->res['string'] = "Please select your new plan!";

                echo json_encode($this->res);
                return false;
            }
        }else{
            $this->_res['code'] = 0;
            $this->_res['string'] = "Invalid Request";

            echo json_encode($this->_res);
        }
    }

    public function change_notification_settings()
    {
        if(isset($_POST['enable_notifications']) && isset($_POST['email']) && isset($_POST['phone_number']))
        {
            $notifications = $this->input->post('enable_notifications');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone_number');
            
            if(!empty($notifications) && !empty($email) && !empty($phone))
            {
                $this->Settings_model->changeNotificationSettings($notifications, $email, $phone);
            }else{
                $this->res['code'] = 0;
                $this->res['string'] = "Please make sure all fields are checked and filled it!";

                echo json_encode($this->res);
                return false;
            }
        }else{
            $this->_res['code'] = 0;
            $this->_res['string'] = "Invalid Request";

            echo json_encode($this->_res);
        }
    }

    public function changePasswordProcess()
    {
        if(isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_new_password']))
        {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            $confirm_new_password = $this->input->post('confirm_new_password');
            
            if(!empty($current_password) && !empty($new_password) && !empty($confirm_new_password))
            {
                $this->Settings_model->changePasswordProcess($current_password, $new_password, $confirm_new_password);
            }else{
                $this->res['code'] = 0;
                $this->res['string'] = "Please make sure all fields are checked and filled it!";

                echo json_encode($this->res);
                return false;
            }
        }else{
            $this->_res['code'] = 0;
            $this->_res['string'] = "Invalid Request";

            echo json_encode($this->_res);
        }
    }

    public function amazon_api_update()
    {
        if(isset($_POST['api_connection']) && isset($_POST['seller_id']) && isset($_POST['marketplace_id']) && isset($_POST['associate_tag']) && isset($_POST['dev_account_number']) && isset($_POST['access_key_id']) && isset($_POST['secret_key']))
        {
            $api_connection = $this->input->post('api_connection');
            $seller_id = $this->input->post('seller_id');
            $marketplace_id = $this->input->post('marketplace_id');
            $associate_tag = $this->input->post('associate_tag');
            $dev_account_number = $this->input->post('dev_account_number');
            $access_key_id = $this->input->post('access_key_id');
            $secret_key = $this->input->post('secret_key');

            if($api_connection != "" && $seller_id != "" && $marketplace_id != "" && $associate_tag != "" && $dev_account_number != "" && $access_key_id != "" && $secret_key != "")
            {
                $data = array('api_connection' => $api_connection, 'seller_id' => $seller_id, 'marketplace_id' => $marketplace_id, 'associate_tag' => $associate_tag, 'dev_account_number' => $dev_account_number, 'access_key_id' => $access_key_id, 'secret_key' => $secret_key);
                $this->Settings_model->amazonAPIProcess($data);
            }else
            {
                $this->_res['code'] = 0;
                $this->_res['string'] = "Invalid Request";

                echo json_encode($this->_res);
            }
        }else
        {
            $this->_res['code'] = 0;
            $this->_res['string'] = "Invalid Request";

            echo json_encode($this->_res);
        }
    }
    
    public function change_security_settings()
    {
        
    }


    // public function get_total_value()
    // {
    //     $data = array();
    //     if(isset($_POST['email_support_id'])){
    //         $email_support_id = $_POST['email_support_id'];
    //     }
    //     if(isset($_POST['track_support_id'])){
    //         $track_support_id = $_POST['track_support_id'];
    //     }
    //     $totalValue = $this->Supports_model->getTotalValueFromAjax($email_support_id, $track_support_id);
    //     $data['status'] = "success";
    //     $data['total'] =  $totalValue;

    //     echo json_encode($data);
    // }
    public function get_total_value()
    {
        $data = array();
        if(isset($_POST['email_support_id'])){
            $email_support_id = $_POST['email_support_id'];
        }
        if(isset($_POST['track_support_id'])){
            $track_support_id = $_POST['track_support_id'];
        }
        $totalValue = $this->Supports_model->getTotalValueFromAjax($email_support_id, $track_support_id);
        $data['status'] = "success";
        $data['email_support'] =  $totalValue['email_support'];
        $data['track_support'] =  $totalValue['track_support'];
        $data['email_support_desc']= $totalValue['email_support_desc'];
        $data['track_support_desc']= $totalValue['track_support_desc'];
        $data['total'] = $data['track_support'] + $data['email_support'];
        //echo '<pre>';print_r( $data['total']);exit;
        echo json_encode($data);
    }
    
    public function check_session(){
       if($this->session->userdata('user_id') !='') {
        $dataArray['sessionResult'] = 'failed';
       } else {
        delete_cookie('track_asins'); 
        $dataArray['sessionResult'] = 'success';
            
       }
        echo json_encode($dataArray);
        exit;
    }

   public function contact_form_process()
   {
	   //print_r($_POST);exit;
        if (isset($_POST['your_name']) && isset($_POST['your_email']) && isset($_POST['subject']) && isset($_POST['message_body'])) {
            $contact_name = $this->input->post('your_name');
            $contact_email = $this->input->post('your_email');
            $contact_subject = $this->input->post('subject');
            $contact_msg = $this->input->post('message_body');

            if (!empty($contact_name) && !empty($contact_email) && !empty($contact_subject) && !empty($contact_msg)) {
                $html = '<html>
                <head>
                    <title>TrackASINS</title>
                </head>
                <body>
                    <p>Dear Admin</p>
                    <p>This email is from TrackASINS Contact Form and following are the details: </p>
                    <p>
                        Name: '.$contact_name.'<br>
                        Email: '.$contact_email.'<br>
                        Subject: '.$contact_subject.'<br>
                        Message: '.$contact_msg.'
                    </p><br>';
                    $html .= "</body></html>";

                    $email = $this->config->item('contact_email');
                    $subject = $this->config->item('contact_subject');
                    $this->mgClient->sendMessage("$this->mgDomain", [
                        'from' => 'TrackASINS Contact Form<contact_us@trackasins.com>',
                        'to' => $email,
                        'subject' => $subject,
                        'html' => $html
			]);

		    $res['code'] = 1;
            	    $res['string'] = "Your request has been sent successfully to TrackASINS!";

		    echo json_encode($res);exit;

            } else {
                $res['code'] = 0;
                $res['string'] = "Please make sure all fields are filled!";

                echo json_encode($res);exit;
            }
        } else {
            $res['code'] = 0;
            $res['string'] = "Invalid Request";

            echo json_encode($res);exit;
        }
    }
}


