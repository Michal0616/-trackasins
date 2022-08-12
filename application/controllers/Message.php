<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/New_York');
class Message extends CI_Controller
{
  public function index()
    {         
  	$this->load->view('mailgun-php/sms');
    }
}






