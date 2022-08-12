<?php

class Final_login extends CI_Model {
	
	
	public function __construct(){
        parent::__construct();
		
    }
	
	public function validate_login($email,$password){		
		$this->db->where('username', $email);
		$this->db->where('password', $password);

		$query = $this->db->get('masteradmin');
		$result=$query->row();
		$count=count($result);
		if($count == 1) {	
			return $query->row();
		}else{
			return FALSE;
		}
	}
}