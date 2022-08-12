<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class register extends CI_Controller {

		public function __construct() {
        parent::__construct();
		}

		public function index()
		{	$this->load->model('Common_model');
			$error_data = array();
		if(isset($_POST['register']))
		{
			$this->form_validation->set_rules('username', 'username', 'required');
			$this->form_validation->set_rules('password', 'password', 'required');
			$this->form_validation->set_rules('passconf', 'passconf', 'required');
			$this->form_validation->set_rules('email',    'email',    'required');
			$this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');
					if ($this->form_validation->run() == true)
							{	
								$data= array('username' => $this->input->post('username'),
											 'email'    => $this->input->post('email'),
											 'password' => $this->input->post('password'),
									  		 'unique_salt_ud' => md5($this->input->post('email')) . rand(10, 1000));
								$result = $this->common_model->getDataSingleRow('users',$data);

								if($result)
								{
									$data1 = array('id' 		=>$result->id,
												   'email' 		=>$result->email,
												   'password'	=>$result->password);
									redirect('register');
									$error_data = array('msg' => 'this email is already exist.',					);
									
								}else
									$this->Common_model->insertData('users',$data);
										{
											//$this->session->set_userdata($data);
											redirect('dashboard');
										}

							}
		}
		$this->load->view('header');
        $this->load->view('top_navigation');
        $this->load->view('register',$error_data);
        $this->load->view('footer');

		}
}
