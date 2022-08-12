<?php
class Settings_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('SettingsSystem');
    }

    public function changeProfilePic($picture)
    {
        $settings = new SettingsSystem();
	$settings->ChangeProfilePicture($picture);
    }

    public function changeBasicInfo($data)
    {
        $settings = new SettingsSystem();
        $settings->changeBasicInfo($data);
        //if (isset($data['seller_id']) && $data['seller_id']) {
           // $this->session->set_userdata(['sellerId' => $data['seller_id']]);
        //}
    }

    public function changePlan($plan)
    {
        $settings = new SettingsSystem();
        $settings->changePlan($plan);
    }

//    public function upgradePlan($token){
//        $stripeSystem = new StripeSystem();
//    }

    public function changeNotificationSettings($notifications, $email, $phone_number)
    {
        $settings = new SettingsSystem();
        $settings->changeNotifications($notifications, $email, $phone_number);
    }

    public function changePasswordProcess($current, $new, $confirm)
    {
        $settings = new SettingsSystem();
       return $settings->changePassword($current, $new, $confirm);
    }

    public function changeSecuritySettings($email, $phone_number)
    {
        $settings = new SettingsSystem();
        $settings->changeSecuritySettings($email, $phone_number);
    }

    public function changeEmail($current_email, $new_email, $confirm_new_email){
        $settings = new SettingsSystem();
        return $settings->changeEmail($current_email, $new_email, $confirm_new_email);
    }

    public function amazonAPIProcess($data)
    {
        $settings = new SettingsSystem();
        $settings->changeAmazonAPI($data);
    }

    public function removePP()
    {
        $settings = new SettingsSystem();
        $settings->removeProfilePic();
    }
}
