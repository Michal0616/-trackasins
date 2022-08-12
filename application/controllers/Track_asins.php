<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Track_asins extends CI_Controller {

		public function __construct() {
        parent::__construct();
		}

		public function index()
		{
		$this->load->view('header');
        $this->load->view('top_navigation');
        $this->load->view('track_asins');
        $this->load->view('footer');
		}
        
}