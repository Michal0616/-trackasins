<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/New_York');


class Dashboard extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('user_id'))) {
            redirect('login');
        }
        if (!($_SESSION['uid'])){
            redirect('login');
        }
        $this->load->helper(array('cookie', 'url'));
        $this->load->helper(array('form', 'url'));
        $this->load->Model('StripeSubscriptions_model');
        $this->load->Model('TrackSupports_model');
        $this->load->Model('Supports_model');
        $this->load->Model('Common_model');
        $this->load->library('SessionTimeout');
        $this->load->library('PlanItemsSystem');
//        $this->load->library('AsinsManagementSystem');
        $sessionTimeout = new SessionTimeout();
        $sessionTimeout->checkTimeOut();
        
    }

    public function index()
    {
        
        if (!empty($_FILES["bulk_upload_file"]["name"])) {
            
            $config['upload_path']          = APPPATH . 'uploads/';
            $config['allowed_types']        = 'csv';

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('bulk_upload_file')) {
                $data = array('upload_data' => $this->upload->data());
                $now = date('Y-m-d H:i:s');
                $upload_id = $this->Common_model->insertData('bulk_uploads', [
                    'user_id' => $this->session->userdata('user_id'),
                    'seller_id' => $this->session->userdata('sellerId'),
                    'file_name' => $_FILES['bulk_upload_file']['name'],
                    'file_upload_data' => json_encode($data),
                    'status' => 'PENDING',
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                run_in_background('php -q'.FCPATH.'index.php cron bulk_upload '.$upload_id);
                $data['message_type'] = 'success';
                $data['message'] = 'Bulk upload has started. It will continue to run in background, and you will receive an email once finished.';
                
            } else {
                $data['message_type'] = 'warning';
                $data['message'] = strip_tags($this->upload->display_errors());
                
            }
            echo json_encode($data);
            exit;
        }

        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'dashboard';

        // Title
        $data['title_addition'] = 'Dashboard';

        // Load stuff
        $data['stylesheet'] = 'dashboard';
        $data['javascript'] = 'dashboard';

        $auth_user = $this->Common_model->getDataSingleRow('users', ['id' => $this->session->userdata('user_id')]);
        $data['subscription_expired'] = !($this->is_user_on_trial($auth_user)
            || $this->StripeSubscriptions_model->isSubscriptionActive($auth_user));
        $data['amaz_aug_asin'] = $this->Common_model->getDataSingleRow('amaz_aug', ['asin' => $this->input->post('asin')]);
        //echo '<pre>';print_r($data);exit;
        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('templates/footer.php');
    }

    /*
     * Dashboard asins search
     *
     */

    public function dashboard_dt(){
        $data = array();
        $result = $this->load->view('dashboard_dt', $data);
        //$this->load->view('templates/footer.php');
        return $result;
    }

    /*
     * Dashboard asins search
     *
     */

    public function dashboard_search(){
        // Pass the site info
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'dashboard';

        // Title
        $data['title_addition'] = 'Dashboard';

        // Load stuff
        $data['stylesheet'] = 'dashboard';
        $data['javascript'] = 'dashboard';
        $user_id = $this->session->userdata('user_id');
        $data['user_id'] = $user_id;
        $auth_user = $this->Common_model->getDataSingleRow('users', ['id' => $this->session->userdata('user_id')]);
        $data['subscription_expired'] = !($this->is_user_on_trial($auth_user)
            || $this->StripeSubscriptions_model->isSubscriptionActive($auth_user));
        $data['amaz_aug_asin'] = $this->Common_model->getDataSingleRow('amaz_aug', ['asin' => $this->input->post('asin')]);
        //echo '<pre>';print_r($data);exit;
        //$this->load->view('templates/header.php', $data);
       $result = $this->load->view('dashboard_search', $data);
        //$this->load->view('templates/footer.php');
        return $result;

    }

    /*
     * Bulk asins delete
     *
     */

    public function delete_bulk_asins(){
        $listArray = $_POST['list'];
        if(count($listArray) >0){
            foreach($listArray as $key => $array){
                $query = "DELETE FROM amaz_aug  WHERE id = '".$array."' and user_id ='".$_SESSION['uid']."'";
                $this->db->query($query);
            }
            $data['result'] = 'success';
            $data['show_result'] = $this->onGetDataTableContent();
        } else {
            $data['result'] = 'failed';
            $data['message'] = "You have to select any one item.";
        }
        echo json_encode($data);
        exit;
    }
    /*
     * Bulk action change
     *
     */

    public function change_bulk_notifications(){
        $data = array();
        $listArray = $_POST['list'];
        $type = $_POST['type'];
        if(count($listArray) >0){
            if($type == 'stock_on'){
                $sub_query = " tracking = 1 ";
            } elseif($type =='stock_off'){
                $sub_query = " tracking = 0 ";
            } elseif($type =='back_stock_on'){
                $sub_query = " stock_noti = 'true' ";
            } elseif($type =='back_stock_off'){
                $sub_query = " stock_noti = 'false' ";
            } elseif($type =='email_on'){
                $sub_query = " email_noti = 'true' ";
            } elseif($type =='email_off'){
                $sub_query = " email_noti = 'false' ";
            } elseif($type =='sms_on'){
                $sub_query = " phone_noti = 'true' ";
            } elseif($type =='sms_off'){
                $sub_query = " phone_noti = 'false' ";
            }
            foreach($listArray as $key => $array){
                $query = "UPDATE amaz_aug set ". $sub_query. " WHERE id = '".$array."' and user_id ='".$_SESSION['uid']."'";
                $this->db->query($query);
            }
            $data['result'] = 'success';
            $data['show_result'] = $this->onGetDataTableContent();
        } else {
            $data['result'] = 'failed';
            $data['message'] = "You have to select any one item.";
        }
        echo json_encode($data);
        exit;
    }

    /////////////////////////----------TRACKING--------------------------////////////////
    public function checkAndUncheck($amz_id, $status)
    {
        $ajaxData = array();

        $planItemsSystem = new PlanItemsSystem();
        $planItems = $planItemsSystem->check_expiration_date();
        $selectedItemList = $this->db->query("SELECT * FROM amaz_aug WHERE  id= '".$amz_id."'")->row();
        if($selectedItemList->tracking == 1 && $status ==0){
            $query = $this->db->query("UPDATE `amaz_aug` SET `tracking`='$status', `status`=0 WHERE `id`='$amz_id'");
            if ($query) {
                $selectedItemList = $this->db->query("SELECT * FROM amaz_aug WHERE  id= '".$amz_id."'")->row();
                $resultCount = $this->db->query("SELECT * FROM `amaz_aug` where tracking = 1 AND user_id = '" . $_SESSION['uid'] . "'")->num_rows();
                $ajaxData['result']='success';
                $ajaxData['count'] = $resultCount;
                $ajaxData['show_result'] = $this->onGetDataTableContentRow($selectedItemList);
            } else {
                $ajaxData['result']='failed';
                $ajaxData['message'] ='Update has been failed.';
            }
        }else{
            if(isset($planItems)) {
                if($selectedItemList->stock_noti == 'true' || ($planItems['plan_count'] > $planItems['current_count'] && $planItems['result'] =='success')){
                    $query = $this->db->query("UPDATE `amaz_aug` SET `tracking`='$status', `status`=0 WHERE `id`='$amz_id'");
                    if ($query) {
                        $selectedItemList = $this->db->query("SELECT * FROM amaz_aug WHERE  id= '".$amz_id."'")->row();
                        $resultCount = $this->db->query("SELECT * FROM `amaz_aug` where tracking = 1 AND user_id = '" . $_SESSION['uid'] . "'")->num_rows();
                        $ajaxData['result']='success';
                        $ajaxData['count'] = $resultCount;
                        $ajaxData['show_result'] = $this->onGetDataTableContentRow($selectedItemList);
                    } else {
                        $ajaxData['result']='failed';
                        $ajaxData['message'] ='Update has been failed.';
                    }
                } else {
                    $ajaxData['result'] ='failed';
                    $ajaxData['message'] ='oops! You are attempting to exceed your plan by enabling tracking on too many items. Please upgrade your plan to something more suitable or turn tracking off on some other items in order to free up some space.';
                }
            } else {
                $ajaxData['result']='failed';
                $ajaxData['message'] ='Update has been failed.';
            }
        }
        echo json_encode($ajaxData);
        exit;

    }
///////////////////------------------TRACKING USE END--------------//////////////
    /*********************stock start**********************/
    public function stockinsert($s_id, $stocktatus)
    {
        $ajaxData = array();
        $planItemsSystem = new PlanItemsSystem();
        $planItems = $planItemsSystem->check_expiration_date();
        $selectedItemList = $this->db->query("SELECT * FROM amaz_aug WHERE  id= '".$s_id."'")->row();

        if($selectedItemList->stock_noti == "true" && $stocktatus == "false"){
            $query = $this->db->query("UPDATE `amaz_aug` SET `stock_noti`='$stocktatus', `status`=0 WHERE `id`='$s_id'");
            if ($query) {
                $selectedItemList = $this->db->query("SELECT * FROM amaz_aug WHERE  id= '".$s_id."'")->row();
                $count = $this->db->query("SELECT * FROM amaz_aug WHERE  stock_noti ='true' and user_id='".$_SESSION['uid']."'")->num_rows();
                $ajaxData['result'] = 'success';
                $ajaxData['count'] = $count;
                $ajaxData['show_result'] = $this->onGetDataTableContentRow($selectedItemList);
            } else {
                $ajaxData['result'] = 'failed';
                $ajaxData['message'] = "Can not update it now.";
            }
        } else {
            if(isset($planItems)) {
                if($selectedItemList->amznotseller == 1 || ($planItems['plan_count'] > $planItems['current_count'] && $planItems['result'] =='success')){
                    $query = $this->db->query("UPDATE `amaz_aug` SET `stock_noti`='$stocktatus', `status`=0 WHERE `id`='$s_id'");
                    if ($query) {
                        $selectedItemList = $this->db->query("SELECT * FROM amaz_aug WHERE  id= '".$s_id."'")->row();
                        $count = $this->db->query("SELECT * FROM amaz_aug WHERE  stock_noti ='true' and user_id='".$_SESSION['uid']."'")->num_rows();
                        $ajaxData['result'] = 'success';
                        $ajaxData['count'] = $count;
                        $ajaxData['show_result'] = $this->onGetDataTableContentRow($selectedItemList);
                    } else {
                        $ajaxData['result'] = 'failed';
                        $ajaxData['message'] = "Can not update it now.";
                    }
                } else {
                    $ajaxData['result']='failed';
                    $ajaxData['message'] ='oops! You are attempting to exceed your plan by enabling tracking on too many items. Please upgrade your plan to something more suitable or turn tracking off on some other items in order to free up some space.';
                }
            } else{
                $ajaxData['result']='failed';
                $ajaxData['message'] ='Update has been failed.';
            }
        }



        echo json_encode($ajaxData);
    }
    /**********************stock end**************/

//////////////////------------------EMAIL PHONE AND STOCK CHECK----/////////////
    /***********************EMAIL START********************/
    public function emailinsert($e_id, $e_status)
    {
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'dashboard';
        // Title
        $data['title_addition'] = 'Dashboard';
        // Load stuff
        $data['stylesheet'] = 'dashboard';
        $data['javascript'] = 'dashboard';
        $query = $this->db->query("UPDATE `amaz_aug` SET `email_noti`='$e_status' WHERE `id`='$e_id'");
        if ($query) {
            echo "done";
        } else {
            echo "failed";
        }
        $this->load->view('templates/header.php', $data);
        $this->load->view('dashboard');
        $this->load->view('templates/footer.php');
    }
    /**********************EMAIL END***********************/

    /*********************phone start**********************/
    public function phoneinsert($p_id, $phonestatus)
    {
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'dashboard';
        // Title
        $data['title_addition'] = 'Dashboard';
        // Load stuff
        $data['stylesheet'] = 'dashboard';
        $data['javascript'] = 'dashboard';
        $query = $this->db->query("UPDATE `amaz_aug` SET `phone_noti`='$phonestatus' WHERE `id`='$p_id'");
        if ($query) {
            echo "done";
        } else {
            echo "failed";
        }
        $this->load->view('templates/header.php', $data);
        $this->load->view('dashboard');
        $this->load->view('templates/footer.php');
    }
    /**********************phone end**************/


    /*********************Global start**********************/
    public function globalinsert($g_id, $globalstatus)
    {
        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'dashboard';
        // Title
        $data['title_addition'] = 'Dashboard';
        // Load stuff
        $data['stylesheet'] = 'dashboard';
        $data['javascript'] = 'dashboard';
        $query = $this->db->query("UPDATE `users` SET `global_noti`='$globalstatus' WHERE `id`='$g_id'");
        if ($query) {
            echo "done";
        } else {
            echo "failed";
        }
        $this->load->view('templates/header.php', $data);
        $this->load->view('dashboard');
        $this->load->view('templates/footer.php');
    }
    /**********************Global end**************/
//////////////////------------------EMAIL PHONE AND STOCK CHECK END/////////////
//////////////////--------------function for delete---------------///////////////
    public function delete_checkbox()
    {
        $ids = $this->input->post('ids');
        $mutiid = explode(",", $ids);
        foreach ($mutiid as $valID) {
            $query = $this->db->query("DELETE from amaz_aug where id='$valID'");
            mysqli_query($query);
            echo json_encode("true");
        }
    }
/////////////////---------------delete function end---------------////////////// 

    public function SaveToDB()
    {
        $data1 = array();
        $planItemsSystem = new PlanItemsSystem();
        $planItems = $planItemsSystem->check_expiration_date();
        if(isset($planItems)) {
            if($planItems['plan_count'] > $planItems['current_count'] && $planItems['result'] =='success'){
                $d = date("Y-m-d H:i:s");
                $user_id = $this->session->userdata('user_id');
                $img = $this->input->post('img');
                $title_name = $this->input->post('title_name');
                $asin = $this->input->post('asin');
                $amznotseller = $this->input->post('amznotseller');
                $sellerstock = $this->input->post('sellerstock');
                $rating = $this->input->post('rating');
                $review = $this->input->post('reviews');
                $seller_name = $this->input->post('seller_name');
                $seller_url = $this->input->post('seller_url');
                $seller_id = $this->input->post('seller_ids');
                $selling_price = $this->input->post('price');
                $shipping_price = $this->input->post('shipping');
                $requiresRescrape = $this->input->post('requires_rescrape');
                $res = $this->db->query("SELECT * FROM amaz_aug where user_id=$user_id AND asin = '" . $asin . "'")->result();
                if (($amznotseller == "1") && ($sellerstock == "0")) {
                    $status = 0;/* print_r($status);exit;*/
                } else if (($amznotseller == "1") && ($sellerstock == "1")) {
                    $status = 1;/*print_r($status);exit;*/
                } else if (($amznotseller == "0") && ($sellerstock == "1")) {
                    $status = 2;/*print_r($status);exit;*/
                } else if (($amznotseller == "0") && ($sellerstock == "0")) {
                    $status = 2;/*print_r($status);exit;*/
                } else {
                    $status = 3;
                }

                if ($res) {
                    $data1['result'] = 'failed';
                    $data1['message'] = "This ASIN already exists in your list";
                } else {
                    $user_id = $this->session->userdata('user_id');

                    $data_insert = array(
                        'user_id'        => $user_id,
                        'image'          => $img,
                        'title_name'     => $title_name,
                        'tracking'       => 1,
                        'stock_noti'     => 'true',
                        'email_noti'     => 'true',
                        'asin'           => $asin,
                        'amznotseller'   => $amznotseller,
                        'sellerstock'    => $sellerstock,
                        'date'           => $d,
                        'rating'         => $rating,
                        'review'         => $review,
                        'seller_name'    => $seller_name,
                        'seller_url'     => $seller_url,
                        'seller_id'      => $seller_id,
                        'selling_price'  => $selling_price,
                        'shipping_price' => $shipping_price,
                        'status'         => $status,
                        'requires_rescrape' => $requiresRescrape
                    );
                    if ($this->db->insert('amaz_aug', $data_insert)) {
                        $latestID = $this->db->insert_id();
                        $data1['result'] = 'success';
                        $data1['message'] = "ASIN successfully added to item list.";
                        $data1['show_result'] = $this->onGetDataTableContent();
                    }
                }
            } else {
                $data1['result'] = "failed";
                if ($planItems['message'] == 'You have to upgrade plan.') {
                    $data1['message'] = "oops! You are attempting to exceed your plan by enabling tracking on too many items. Please upgrade your plan to something more suitable or turn tracking off on some other items in order to free up some space.";
                } else {
                    $data1['message'] = $planItems['message'];
                }
            }
        } else {
            $data1['result'] = "failed";
            $data1['message'] = "oops! You are attempting to exceed your plan by enabling tracking on too many items. Please upgrade your plan to something more suitable or turn tracking off on some other items in order to free up some space.";
        }

        echo json_encode($data1);
        exit;

    }



    /**
     *  check current user time
     *
     */

    public function  check_expiration_date(){
        $data = array();
        $planItemsSystem = new PlanItemsSystem();
        $planItems = $planItemsSystem->check_expiration_date();
        if(isset($planItems)) {
            if($planItems['plan_count'] > $planItems['current_count'] && $planItems['result'] =='success'){
                $data['result'] = "success";
            } else {
                $data['result'] = "failed";
                $data['message'] = "oops! You are attempting to exceed your plan by enabling tracking on too many items. Please upgrade your plan to something more suitable or turn tracking off on some other items in order to free up some space.";
            }
        } else {
            $data['result'] = "failed";
            $data['message'] = "oops! You are attempting to exceed your plan by enabling tracking on too many items. Please upgrade your plan to something more suitable or turn tracking off on some other items in order to free up some space.";
        }
        echo json_encode($data);
        exit;

//        $data = array();
//        $difference_date = 0;
//
//        if(isset($_SESSION['uid'])){
//            $user = $this->db->query("SELECT * FROM users WHERE ID='".$_SESSION['uid']."'")->row();
//            if(isset($user)){
//                if(isset($user->created_at)){
//                    $today = date_create(date('Y-m-d'));
//                    $created = date_create(substr($user->created_at,0,10));
//                    $diff = date_diff($created,$today);
//                    $difference_date = $diff->days;
//                }
//            } else {
//                redirect('Login');
//            }
//
//            $stripeSubscription = $this->StripeSubscriptions_model->getSubscription();
//            if(isset($stripeSubscription)) {
//                if($stripeSubscription->ends_at != ""){
//                    $startDate =  date('Y-m-01 00:00:00',strtotime('this month'));
//                    $endDate = date('Y-m-t 23:59:59',strtotime('this month'));
//                    $subscriptionEnds = strtotime($stripeSubscription->ends_at);
//                    $checkStartDate = strtotime($startDate);
//                    $checkEndDate = strtotime($endDate);
//                    if( ($subscriptionEnds >= $checkStartDate) && ($subscriptionEnds <= $checkEndDate) ){
//                        $getSupport = $this->Supports_model->getCurrentUserSupport();
//                        if(isset($getSupport)){
//                            $getTrackSupport = $this->TrackSupports_model->getTrackItem($getSupport->track_support);
//                            if($getTrackSupport->price == "99999"){
//                                $selectedUser = $this->db->query("SELECT * FROM users where ID='".$_SESSION['uid']."'")->row();
//                                $countItem = $selectedUser->track_count;
//                                $currentAsinsCount = $this->getMonthAmazonAsins();
//                                if($countItem*1 <= $currentAsinsCount){
//                                    $data['result'] = "failed";
//                                    $data['message'] = "You have to upgrade plan.";
//                                } else {
//                                    $data['result'] = "success";
//                                    $data['message'] = "";
//                                }
//                            } else {
//                                $countItem = $getTrackSupport->count;
//                                $currentAsinsCount = $this->getMonthAmazonAsins();
//                                if($countItem*1 <= $currentAsinsCount){
//                                    $data['result'] = "failed";
//                                    $data['message'] = "You have to upgrade plan.";
//                                } else {
//                                    $data['result'] = "success";
//                                    $data['message'] = "";
//                                }
//                            }
//                        } else{
//                            $data['result'] = "failed";
//                            $data['message'] = "You have to upgrade plan.";
//                        }
//                    } else {
//                        $data['result'] = "failed";
//                        $data['message'] = "You have to resume subscriptions";
//                    }
//                } else {
//                    //check current enabled
//                    $getSupport = $this->Supports_model->getCurrentUserSupport();
//                    if(isset($getSupport)){
//                        $getTrackSupport = $this->TrackSupports_model->getTrackItem($getSupport->track_support);
//                        if($getTrackSupport->price == "99999"){
//                            $selectedUser = $this->db->query("SELECT * FROM users where ID='".$_SESSION['uid']."'")->row();
//                            $countItem = $selectedUser->track_count;
//                            $currentAsinsCount = $this->getMonthAmazonAsins();
//                            if($countItem*1 <= $currentAsinsCount){
//                                $data['result'] = "failed";
//                                $data['message'] = "You have to upgrade plan.";
//                            } else {
//                                $data['result'] = "success";
//                                $data['message'] = "";
//                            }
//                        } else {
//                            $countItem = $getTrackSupport->count;
//                            $currentAsinsCount = $this->getMonthAmazonAsins();
//                            if($countItem*1 <= $currentAsinsCount){
//                                $data['result'] = "failed";
//                                $data['message'] = "You have to upgrade plan.";
//                            } else {
//                                $data['result'] = "success";
//                                $data['message'] = "";
//                            }
//                        }
//
//                    } else{
//                        $data['result'] = "failed";
//                        $data['message'] = "You have to upgrade plan.";
//                    }
//                }
//            } else {
//                if($difference_date<= 14){
//                    if($this->getAllAmazonAsins() <= 80){
//                        $data['result'] = "success";
//                        $data['message'] = "";
//                    } else {
//                        $data['result'] = "failed";
//                        $data['message'] = "You have to upgrade plan.";
//                    }
//
//                }else {
//                    $data['result'] = "failed";
//                    $data['message'] = "You have to upgrade plan.";
//                }
//            }
//        } else {
//            $data['result'] = "failed";
//            $data['message'] = "Your session has been expired.";
//        }
//
//        echo json_encode($data);
//        exit;
    }

    public function getAsinsResult(){
        $data = array();
        if(isset($_POST['asin'])) {
            $asin = $_POST['asin'];
            $main_url = "https://www.amazon.com/gp/offer-listing/" . $asin . "/ref=dp_olp_new?ie=UTF8&condition=new";
            $check_exist = $this->db->query("SELECT * FROM amaz_aug where asin='".$asin."'")->row();
            if (empty($check_exist)) {
                $amznotseller = get_amazon_not_seller($asin);
                $html = getPage($main_url);
                $html = str_get_html($html);
                echo "123";
                exit;
            }
//            $asinsManagementSystem = new AsinsManagementSystem();
//            $asinsManagementSystem->getAsinsFromNumber($asin);

        }else {
            $data['result'] ='failed';
        }


    }


    public function getAllAmazonAsins(){
        $asinsCount = $this->db->query("SELECT * FROM amaz_aug WHERE user_id ='".$_SESSION['uid']."'")->num_rows();
        return $asinsCount;
    }

    public function getMonthAmazonAsins(){
        $startDate =  date('Y-m-01 00:00:00',strtotime('this month'));
        $endDate = date('Y-m-t 23:59:59',strtotime('this month'));
        $asinsCount = $this->db->query("SELECT * FROM amaz_aug WHERE user_id ='".$_SESSION['uid']."' and date <= '".$startDate."' and date >= '".$endDate."'")->num_rows();
        return $asinsCount;
    }

    public function onGetDataTableContent(){
        $query = "SELECT * FROM amaz_aug WHERE `user_id`='".$_SESSION['uid']."' ORDER BY tracking DESC, amznotseller DESC , sellerstock ASC ";
        $results = $this->db->query($query)->result();
        $show_result = '';
        foreach ($results as $query) {
            $show_result .= '<tr role="row" class="odd scrape-row">';
            $show_result .= $this->onGetDataTableContentRow($query);
            $show_result .= '</tr>';
        }

        return $show_result;
    }

    protected function onGetDataTableContentRow($scrapeEntry)
    {
        $row_html = '<td class="text-center vertical-middle star-wrapper" style="position: relative">';

        if ($scrapeEntry->tracking == 1 || $scrapeEntry->stock_noti == 'true') {
            if (($scrapeEntry->amznotseller == "1") && ($scrapeEntry->sellerstock == "1")) {
                $row_html .= '<div class="green-right-triangle"></div>';
            } else if (($scrapeEntry->amznotseller == "1") && ($scrapeEntry->sellerstock == "0")) {
                $row_html .= '<div class="red-right-triangle"></div>';
            }
        }
        if($scrapeEntry->image != ''){
            $row_html .='<a href="'.$scrapeEntry->image.'" data-fancybox="images" data-caption="'. $scrapeEntry->title_name.'" class="fancybox">
                                            <img src ="'.$scrapeEntry->image.'" class ="img-thumbnail"  style="height:70px;border:0px" />   
                                         </a>';
        }
        $row_html .='</td>';
        $row_html .='<td class="text-center vertical-middle" title="'. $scrapeEntry->title_name .'">
                                <a style="" target="_blank" href="http://amazon.com/dp/'. $scrapeEntry->asin.'">'.$scrapeEntry->title_name.'</a>
                            </td>';

        $row_html .= '<td class="text-center vertical-middle">
                                <a style="" target="_blank" href="http://amazon.com/dp/'. $scrapeEntry->asin.'">'. $scrapeEntry->asin.'</a>
                            </td>';
        if ($scrapeEntry->stock_noti != "true" && $scrapeEntry->tracking != "1") {
            $row_html .= '<td class="text-center b red verticle-middle">
                                    <span style="color:#aaa; font-size:14px;" id="amznotseller_label_'.$scrapeEntry->id.'">Turn tracking on<br> to see stock status</span>
                                </td>
                                <td class="text-center b red verticle-middle">
                                    <span style="color:#aaa; font-size:14px;" id="stock_label_'.$scrapeEntry->id.'">Turn tracking on<br> to see stock status</span>
                                </td>';
        } else {
            if (is_null($scrapeEntry->sellerstock) || $scrapeEntry->sellerstock == '') {
                $row_html .= '<td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="stock_label_'.$scrapeEntry->id.'">Being processed! <br> Will be updated soon</span>
                                    </td>';
            } else if (($scrapeEntry->sellerstock == "1")) {
                if (($scrapeEntry->amznotseller == "1")) {
                    $row_html .='<td class="text-center b red verticle-middle" id="stock_label_'. $scrapeEntry->id . '">
                                        <span style="color:green; font-size:25px;">Yes!</span>
                                    </td>';
                } else {
                    $row_html .='<td class="text-center b red verticle-middle" id="stock_label_'. $scrapeEntry->id . '">
                                        <span style="color:black; font-size:25px;">Yes</span>
                                    </td>';
                }
            } else {
                if (($scrapeEntry->amznotseller == "1")) {
                    $row_html .=' <td class="text-center b red verticle-middle" id="stock_label_'. $scrapeEntry->id . '">
                                        <span style="color:red; font-size:25px;">No!</span>
                                    </td>';
                } else {
                    $row_html .='<td class="text-center b red verticle-middle" id="stock_label_'. $scrapeEntry->id . '">
                                        <span style="color:black; font-size:25px;">No</span>
                                    </td>';
                }
            }
            if (($scrapeEntry->amznotseller == "1")) {
                $row_html .='<td class="text-center b red verticle-middle">
                                <span style="color:green; font-size:25px;" id="amznotseller_label_'. $scrapeEntry->id . '">Yes!</span>
                            </td>';
            }
            if (($scrapeEntry->amznotseller == "0")) {
                $row_html .='<td class="text-center b red verticle-middle" id="amznotseller_label_'. $scrapeEntry->id . '">
                                    <span style="color:black; font-size:25px;">No</span>
                                </td>';
            }
            if (is_null($scrapeEntry->amznotseller) || $scrapeEntry->amznotseller == '') {
                $row_html .='<td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="amznotseller_label_'.$scrapeEntry->id.'">Being processed! <br> Will be updated soon</span>
                                    </td>';
            }
           
        }
        
        $row_html .= '<td class="vertical-middle cb text-center">';
        if ($scrapeEntry->stock_noti == "true") {
            $row_html .='<label class="switch">
                                                <input type="checkbox" data-role="flipswitch"
                                                        onclick="stockcheck('.$scrapeEntry->id.', this)"
                                                        name="switch'.$scrapeEntry->id.'"
                                                        id="switchstock'.$scrapeEntry->id.'"
                                                        value="switch'.$scrapeEntry->id.'" checked>
                                                <div class="slider round"></div>
                                            </label>';
        } else {
            $row_html .= '<label class="switch">
                                                <input type="checkbox" data-role="flipswitch"
                                                        onclick="stockcheck('.$scrapeEntry->id.', this)"
                                                        name="switch'.$scrapeEntry->id.'"
                                                        id="switchstock'.$scrapeEntry->id.'"
                                                        value="switch'.$scrapeEntry->id.'">
                                                <div class="slider round"></div>
                                            </label>';
        }
        $row_html .='</td>';

        $row_html .= '<td class="vertical-middle cb text-center">';
        if ($scrapeEntry->tracking == "1") {
            $row_html .='<label class="switch">
                                                <input type="checkbox" data-role="flipswitch"
                                                       onclick="chackUncheck('.$scrapeEntry->id.', this)"
                                                       name="switch'.$scrapeEntry->id.'"
                                                       id="switch'.$scrapeEntry->id.'" value="true" checked>
                                                <div class="slider round"></div>
                                            </label>';
        } else {
            $row_html .='<label class="switch">
                                                <input type="checkbox" data-role="flipswitch"
                                                       onclick="chackUncheck('.$scrapeEntry->id.', this)"
                                                       name="switch'.$scrapeEntry->id.'"
                                                       id="switch'.$scrapeEntry->id.'"
                                                       value="switch'.$scrapeEntry->id.'">
                                                <div class="slider round"></div>
                                            </label>';
        }
        $row_html .='</td>';
        $row_html .= '<td class="vertical-middle cb text-center">';
        if ($scrapeEntry->stock_noti != "true" && $scrapeEntry->tracking != "1") {
            $row_html .= '<label class="switch">
                                        <input type="checkbox" data-role="flipswitch"
                                               onclick="emailcheck('.$scrapeEntry->id.')"
                                               name="switch'.$scrapeEntry->id.'"
                                               id="switchid'.$scrapeEntry->id.'"
                                               value="switchEmail'.$scrapeEntry->id.'" disabled>
                                        <div class="slider round"></div>
                                    </label>';
        } else {
            if ($scrapeEntry->email_noti == "true") {
                $row_html .= '<label class="switch">
                                        <input type="checkbox" data-role="flipswitch"
                                               onclick="emailcheck('.$scrapeEntry->id.')"
                                               name="switch'.$scrapeEntry->id.'"
                                               id="switchid'.$scrapeEntry->id.'"
                                               value="switchEmail'.$scrapeEntry->id.'" checked>
                                        <div class="slider round"></div>
                                    </label>';
            } else {
                $row_html .= '<label class="switch">
                                        <input type="checkbox" data-role="flipswitch"
                                               onclick="emailcheck('.$scrapeEntry->id.')"
                                               name="switch'.$scrapeEntry->id.'"
                                               id="switchid'.$scrapeEntry->id.'"
                                               value="switchEmail'.$scrapeEntry->id.'">
                                        <div class="slider round"></div>
                                    </label>';
            }
        }
        $row_html .='</td>';
        $row_html .= '<td class="vertical-middle cb text-center">';
        if ($scrapeEntry->stock_noti != "true" && $scrapeEntry->tracking != "1") {
            $row_html .=' <label class="switch">
                                        <input type="checkbox" data-role="flipswitch"
                                               onclick="phonecheck('.$scrapeEntry->id.')"
                                               name="switch'.$scrapeEntry->id.'"
                                               id="switchphone'.$scrapeEntry->id.'"
                                               value="switch'.$scrapeEntry->id.'" disabled>
                                        <div class="slider round"></div>
                                    </label>';
        } else {
            if ($scrapeEntry->phone_noti == "true") {
                $row_html .=' <label class="switch">
                                        <input type="checkbox" data-role="flipswitch"
                                               onclick="phonecheck('.$scrapeEntry->id.')"
                                               name="switch'.$scrapeEntry->id.'"
                                               id="switchphone'.$scrapeEntry->id.'"
                                               value="switch'.$scrapeEntry->id.'" checked>
                                        <div class="slider round"></div>
                                    </label>';
            } else {
                $row_html .=' <label class="switch">
                                        <input type="checkbox" data-role="flipswitch"
                                               onclick="phonecheck('.$scrapeEntry->id.')"
                                               name="switch'.$scrapeEntry->id.'"
                                               id="switchphone'.$scrapeEntry->id.'"
                                               value="switch'.$scrapeEntry->id.'">
                                        <div class="slider round"></div>
                                    </label>';
            }
        }
        $row_html .='</td>';
        $row_html .=' <td class="text-center c-hold verticle-middle" id="checkes">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input type="checkbox" value="'.$scrapeEntry->id.'" name="checkbulk1[]"
                                           class="check"/>
                                    <label for="checkbox1" data-for="checkbox1" class="cb-label"></label>
                                </form>
                            </td>';

        return $row_html;
    }

    

    protected function is_user_on_trial($user)
    {
        $today = date_create(date('Y-m-d'));
        $created = date_create(substr($user->created_at, 0, 10));
        $diff = date_diff($created, $today);
        $difference_date = $diff->days;

        return $difference_date < 14;
    }

}

