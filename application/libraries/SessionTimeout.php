<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class  SessionTimeout {
    public function checkTimeOut(){
        $data = array();
        if(isset($_SESSION["uid"])) {
            if($this->isLoginSessionExpired()) {
                 $data['sessionResult'] = "success";
//                redirect('Login');
            } else {
                $data['sessionResult'] = "failed";
            }
        }
        return $data;
    }


    function isLoginSessionExpired() {
        $login_session_duration = 1800;
        if(isset($_SESSION['loggedin_time']) and isset($_SESSION["uid"])){
            if(((time() - $_SESSION['loggedin_time']) > $login_session_duration)){
                return true;
            } else {
                $_SESSION['loggedin_time'] = time();
                return false;
            }
        }
        return false;
    }
}