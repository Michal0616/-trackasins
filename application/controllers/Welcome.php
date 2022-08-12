<?php
// USED FOR LOGIN CHECK AND GOING ON LOGIN VIEW//
defined('BASEPATH') OR exit('No direct script access allowed');
class welcome extends CI_Controller {

		public function __construct() {
        parent::__construct();
		}

		public function index()
		{
		
		if(isset($_POST['login']))
		{					

							

							$this->form_validation->set_rules('username', 'username', 'required');
							$this->form_validation->set_rules('password', 'password', 'required');
							
							$this->form_validation->set_error_delimiters('<div style="color:red">', '</div>');
					
							if ($this->form_validation->run() == true)
							{	
									$data= array('email' => $this->input->post('username'),
												 'password' => $this->input->post('password'),
										  		 );
								$result = $this->common_model->getDataSingleRow('users',$data);

								if($result)
								{
									$data1 = array('ID' => $result->ID);
									redirect('dashboard');
									
								}else{
										$error_data = array('msg' => 'this email is already exist.',								
										 					);
										redirect('welcome');
									 }
							}
				
		
		}	




			$this->load->view('header.php');
			$this->load->view('login');
					
	}
}

