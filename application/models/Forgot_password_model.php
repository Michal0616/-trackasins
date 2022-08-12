<?php
class Forgot_password_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ForgotPasswordSystem');
    }

    public function makeRequest($string)
    {
        $password = new ForgotPasswordSystem();
        $password->makePasswordRequest($string);
    }
    
    public function checkRequestId($request_id, $unique_id)
    {
        $password = new ForgotPasswordSystem();
        $check = $password->checkId($request_id, $unique_id);
        
        return $check;
    }

    public function changePassProcess($request_id, $unique_id, $password, $confirm_password)
    {
        $change = new ForgotPasswordSystem();
        $change->changePass($request_id, $unique_id, $password, $confirm_password);
    }
}