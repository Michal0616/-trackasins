<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 */
 
class MY_Controller extends CI_Controller {
	
	public $data = array();

	public function __construct()
	{
		parent::__construct();
		
		$this->data['notif_result'] = $this->common_model->getData('tbl_notification',array('user_id'=>$this->session->userdata('user_id'),'status'=>0), 'id', 'DESC');		
		//print_r($this->db->last_query());
		//print_r($this->data['notif_result']); 
	}
	
	public function customView($mainView, $data = false)
	{
		$this->load->view('common/header', $this->data);
   		$this->load->view($mainView,$this->data);
   		$this->load->view('common/footer', $this->data);
	}
	
	public function customView_wt_footer($mainView, $data = false)
	{
		$this->load->view('common/header', $this->data);
   		$this->load->view($mainView,$this->data);
   		$this->load->view('common/custom_footer', $this->data);
	}
	
	public function setErrorMessage($type=false,$msgVal=false)
	{
		//($type == 'success') ? $msgVal = $msg : $msgVal = $msg;
		if($type=='success'){
		    $this->session->set_flashdata('succMSGType', $msgVal);
		}
		else{
			$this->session->set_flashdata('errMSGType', $msgVal);
		}
		
		
		//$this->session->set_flashdata('errMSGType', $msgVal);
	}
	/*
	public function setErrorMessage($type='',$msg='')
	{
		($type == 'success') ? $msgVal = 'message-green' : $msgVal = 'message-red';
		$this->session->set_flashdata('succMSGType', $msgVal);
		
		$this->session->set_flashdata('errMSGType', $msg);
	}*/
}
