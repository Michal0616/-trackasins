<?php
class Supports_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getCurrentUserSupport($userId = null){
        if (is_null($userId)) {
            $userId = $_SESSION['uid'];
        }
        $support = $this->db->query("SELECT * FROM supports where user_id='".$userId."'")->row();
        return $support;
    }

    public function getTotalValue(){
        $total = 0;
        $support = $this->db->query("SELECT * FROM supports where user_id='".$_SESSION['uid']."'")->row();
        if($support){
            $email_support = $this->db->query("SELECT * FROM email_supports where id='".$support->email_support."'")->row();
            if($email_support){
                $total += $email_support->price;
            }
            $track_support = $this->db->query("SELECT * FROM track_supports where id='".$support->track_support."'")->row();
            if($track_support){
                if($track_support->price != "99999"){
                    $total += $track_support->price;
                }
            }
        }

        return $total;
    }

    public function getCurrentTrackSupport(){
        $total = 0;
        $support = $this->db->query("SELECT * FROM supports where user_id='".$_SESSION['uid']."'")->row();
        if($support){
            // $email_support = $this->db->query("SELECT * FROM email_supports where id='".$support->email_support."'")->row();
            // if($email_support){
            //     $total += $email_support->price;
            // }
            $track_support = $this->db->query("SELECT * FROM track_supports where id='".$support->track_support."'")->row();
            if($track_support){
                if($track_support->price != "99999"){
                    $total += $track_support->price;
                }
            }
        }

        return $total;
    }

    public function getCurrentEmailSupport(){
        $total = 0;
        $support = $this->db->query("SELECT * FROM supports where user_id='".$_SESSION['uid']."'")->row();
        if($support){
            $email_support = $this->db->query("SELECT * FROM email_supports where id='".$support->email_support."'")->row();
            if($email_support){
                $total += $email_support->price;
            }
            
        }

        return $total;
    }
    
    

    public function getTotalValueFromAjax($email_support_id, $track_support_id){
        $total = array();
        $email_support = $this->db->query("SELECT * FROM email_supports where id='".$email_support_id."'")->row();
        if($email_support){
            $total['email_support']= $email_support->price;
            $total['email_support_desc']= $email_support->description;
        }
        $track_support = $this->db->query("SELECT * FROM track_supports where id='".$track_support_id."'")->row();
        if($track_support){
            if($track_support->price != "99999"){
                $total['track_support']= $track_support->price;
                $total['track_support_desc']= $track_support->description;
            }
        }
        return $total;
    }
}