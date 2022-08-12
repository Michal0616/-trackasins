<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PlanItemsSystem {

    private $_CI;
    public function __construct()
    {
        $this->_CI =& get_instance();

        $this->_CI->load->helper(array('form', 'url'));
        $this->_CI->load->database();
        $this->_CI->load->Model('StripeSubscriptions_model');
        $this->_CI->load->Model('TrackSupports_model');
        $this->_CI->load->Model('Supports_model');
    }


    public function  check_expiration_date($userId = null){
        $data = array();
        $difference_date = 0;
        if ($userId === null) {
            $userId = $_SESSION['uid'];
        }
       
        if(isset($userId) && $userId) {
            $user = $this->_CI->db->query("SELECT * FROM users WHERE ID='" . $userId . "'")->row();
            if (isset($user)) {
                if (isset($user->created_at)) {
                    $today = date_create(date('Y-m-d'));
                    $created = date_create(substr($user->created_at, 0, 10));
                    $diff = date_diff($created, $today);
                    $difference_date = $diff->days;
                }
            } else {
                redirect('Login');
            }

            $stripeSubscription = $this->_CI->StripeSubscriptions_model->getSubscription($userId);
            if (isset($stripeSubscription)) {
                if($stripeSubscription->ends_at != ""){
                    $getSupport = $this->_CI->Supports_model->getCurrentUserSupport($userId);
                    if(isset($getSupport)) {
                        $getTrackSupport = $this->_CI->TrackSupports_model->getTrackItem($getSupport->track_support);
                        if ($getTrackSupport->price == "99999") {
                            $selectedUser = $this->_CI->db->query("SELECT * FROM users where ID='" . $userId . "'")->row();
                            $countItem = $selectedUser->track_count;
                            $currentAsinsCount = $this->getAllAmazonAsins($userId);
                            if ($countItem * 1 <= $currentAsinsCount) {
                                $data['result'] = "failed";
                                $data['message'] = "You have to upgrade plan.";
                                $data['subscription'] = "cancelled";
                                $data['plan_count'] = $countItem;
                                $data['current_count'] = $currentAsinsCount;
                            } else {
                                $data['result'] = "success";
                                $data['message'] = "";
                                $data['subscription'] = "cancelled";
                                $data['plan_count'] = $countItem;
                                $data['current_count'] = $currentAsinsCount;
                            }
                        } else {
                            $countItem = $getTrackSupport->count;
                            $currentAsinsCount = $this->getAllAmazonAsins($userId);
                            if ($countItem * 1 <= $currentAsinsCount) {
                                $data['result'] = "failed";
                                $data['message'] = "You have to upgrade plan.";
                                $data['subscription'] = "cancelled";
                                $data['plan_count'] = $countItem;
                                $data['current_count'] = $currentAsinsCount;
                            } else {
                                $data['result'] = "success";
                                $data['message'] = "";
                                $data['subscription'] = "cancelled";
                                $data['plan_count'] = $countItem;
                                $data['current_count'] = $currentAsinsCount;
                            }
                        }
                    } else {
                            $data['result'] = "failed";
                            $data['message'] = "You have to upgrade plan.";
                    }
                } else {
                    $getSupport = $this->_CI->Supports_model->getCurrentUserSupport($userId);
                    if(isset($getSupport)){
                        $getTrackSupport = $this->_CI->TrackSupports_model->getTrackItem($getSupport->track_support);
                        if($getTrackSupport->price == "99999"){
                            $selectedUser = $this->_CI->db->query("SELECT * FROM users where ID='".$userId."'")->row();
                            $countItem = $selectedUser->track_count;
                            $currentAsinsCount = $this->getAllAmazonAsins($userId);
                            if($countItem*1 <= $currentAsinsCount){
                                $data['result'] = "failed";
                                $data['message'] = "You have to upgrade plan.";
                                $data['subscription'] = "enable";
                                $data['plan_count'] = $countItem;
                                $data['current_count']  =$currentAsinsCount;
                            } else {
                                $data['result'] = "success";
                                $data['message'] = "";
                                $data['subscription'] = "enable";
                                $data['plan_count'] = $countItem;
                                $data['current_count']  =$currentAsinsCount;
                            }
                        } else {
                            $countItem = $getTrackSupport->count;
                            $currentAsinsCount = $this->getAllAmazonAsins($userId);
                            if($countItem*1 <=  $currentAsinsCount) {
                                $data['result'] = "failed";
                                $data['message'] = "You have to upgrade plan.";
                                $data['subscription'] = "cancelled";
                                $data['plan_count'] = $countItem;
                                $data['current_count']  =$currentAsinsCount;
                            } else {
                                $data['result'] = "success";
                                $data['message'] = "";
                                $data['subscription'] = "cancelled";
                                $data['plan_count'] = $countItem;
                                $data['current_count']  =$currentAsinsCount;
                            }
                        }
                    } else {
                        $data['result'] = "failed";
                        $data['message'] = "You have to upgrade plan.";
                    }
                }

            } else {
                $currentAsinsCount = $this->getAllAmazonAsins($userId);
                if($difference_date<= 14){
                    if( $currentAsinsCount<= 80){
                        $data['result'] = "success";
                        $data['message'] = "";
                        $data['subscription'] = "trial";
                        $data['plan_count'] = 80;
                        $data['current_count']  =$currentAsinsCount;
                    } else {
                        $data['result'] = "failed";
                        $data['message'] = "Trial allows tracking of upto 80 ASINs. You have to upgrade plan.";
                        $data['subscription'] = 'trial';
                        $data['plan_count'] = 80;
                        $data['current_count']  =$currentAsinsCount;
                    }
                } else {
                    $data['result'] = "failed";
                    $data['message'] = "Your trial subscription has expired! Please subscribe to a plan to continue to add and track ASINs.";
                    $data['subscription'] = "trial";
                    $data['plan_count'] = 80;
                    $data['current_count']  =$currentAsinsCount;
                }
            }
//            if(isset($stripeSubscription)) {
//                if($stripeSubscription->ends_at != ""){
//                    $startDate =  date('Y-m-01 00:00:00',strtotime('this month'));
//                    $endDate = date('Y-m-t 23:59:59',strtotime('this month'));
//                    $subscriptionEnds = strtotime($stripeSubscription->ends_at);
//                    $checkStartDate = strtotime($startDate);
//                    $checkEndDate = strtotime($endDate);
//                    if( ($subscriptionEnds >= $checkStartDate) && ($subscriptionEnds <= $checkEndDate) ){
//                        $getSupport = $this->_CI->Supports_model->getCurrentUserSupport();
//                        if(isset($getSupport)){
//                            $getTrackSupport = $this->_CI->TrackSupports_model->getTrackItem($getSupport->track_support);
//                            if($getTrackSupport->price == "99999"){
//                                $selectedUser = $this->_CI->db->query("SELECT * FROM users where ID='".$_SESSION['uid']."'")->row();
//                                $countItem = $selectedUser->track_count;
//                                $currentAsinsCount = $this->getMonthAmazonAsins();
//                                if($countItem*1 <  $currentAsinsCount){
//                                    $data['result'] = "failed";
//                                    $data['message'] = "You have to upgrade plan.";
//                                    $data['subscription'] = "cancelled";
//                                    $data['plan_count'] = $countItem;
//                                    $data['current_count']  =$currentAsinsCount;
//                                } else {
//                                    $data['result'] = "success";
//                                    $data['message'] = "";
//                                    $data['subscription'] = "cancelled";
//                                    $data['plan_count'] = $countItem;
//                                    $data['current_count']  =$currentAsinsCount;
//                                }
//                            } else {
//                                $countItem = $getTrackSupport->count;
//                                $currentAsinsCount = $this->getMonthAmazonAsins();
//                                if($countItem*1 <  $currentAsinsCount){
//                                    $data['result'] = "failed";
//                                    $data['message'] = "You have to upgrade plan.";
//                                    $data['subscription'] = "cancelled";
//                                    $data['plan_count'] = $countItem;
//                                    $data['current_count']  =$currentAsinsCount;
//                                } else {
//                                    $data['result'] = "success";
//                                    $data['message'] = "";
//                                    $data['subscription'] = "cancelled";
//                                    $data['plan_count'] = $countItem;
//                                    $data['current_count']  =$currentAsinsCount;
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
//                    $getSupport = $this->_CI->Supports_model->getCurrentUserSupport();
//                    if(isset($getSupport)){
//                        $getTrackSupport = $this->_CI->TrackSupports_model->getTrackItem($getSupport->track_support);
//                        if($getTrackSupport->price == "99999"){
//                            $selectedUser = $this->_CI->db->query("SELECT * FROM users where ID='".$_SESSION['uid']."'")->row();
//                            $countItem = $selectedUser->track_count;
//                            $currentAsinsCount = $this->getMonthAmazonAsins();
//                            if($countItem*1 < $currentAsinsCount){
//                                $data['result'] = "failed";
//                                $data['message'] = "You have to upgrade plan.";
//                                $data['subscription'] = "enable";
//                                $data['plan_count'] = $countItem;
//                                $data['current_count']  =$currentAsinsCount;
//                            } else {
//                                $data['result'] = "success";
//                                $data['message'] = "";
//                                $data['subscription'] = "enable";
//                                $data['plan_count'] = $countItem;
//                                $data['current_count']  =$currentAsinsCount;
//                            }
//                        } else {
//                            $countItem = $getTrackSupport->count;
//                            $currentAsinsCount = $this->getMonthAmazonAsins();
//                            if($countItem*1 < $currentAsinsCount){
//                                $data['result'] = "failed";
//                                $data['message'] = "You have to upgrade plan.";
//                                $data['subscription'] = "enable";
//                                $data['plan_count'] = $countItem;
//                                $data['current_count']  =$currentAsinsCount;
//                            } else {
//                                $data['result'] = "success";
//                                $data['message'] = "";
//                                $data['subscription'] = "enable";
//                                $data['plan_count'] = $countItem;
//                                $data['current_count']  =$currentAsinsCount;
//                            }
//                        }
//
//                    } else{
//                        $data['result'] = "failed";
//                        $data['message'] = "You have to upgrade plan.";
//                    }
//                }
//
//            } else {
//                if($difference_date<= 14)
//                    $currentAsinsCount = $this->getAllAmazonAsins();
//                    if( $currentAsinsCount<= 80){
//                        $data['result'] = "success";
//                        $data['message'] = "";
//                        $data['subscription'] = "trial";
//                        $data['plan_count'] = 80;
//                        $data['current_count']  =$currentAsinsCount;
//                    } else {
//                        $data['result'] = "failed";
//                        $data['message'] = "You have to upgrade plan.";
//                        $data['subscription'] = 'trial';
//                        $data['plan_count'] = 80;
//                        $data['current_count']  =$currentAsinsCount;
//                    }
//
//            }

        } else {
            redirect('Login');
            $data['result'] = "failed";
            $data['message'] = "Your session has been expired.";
        }

        return $data;
    }


    public function getAllAmazonAsins($userId = null){
        if (is_null($userId)) {
            $userId = $_SESSION['uid'];
        }
        $query = "SELECT * FROM amaz_aug WHERE user_id ='$userId' AND (tracking = 1 OR stock_noti = 'true')";
        $asinsCount = $this->_CI->db->query($query)->num_rows();
        return $asinsCount;
    }

//    public function getAllAmazonAsins(){
//        $asinsCount = $this->_CI->db->query("SELECT * FROM amaz_aug WHERE user_id ='".$_SESSION['uid']."'")->num_rows();
//        return $asinsCount;
//    }

}
