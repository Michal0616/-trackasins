<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notifications extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!($this->session->userdata('user_id'))) {
            redirect('Login');
        }
        if (!($_SESSION['uid'])){
            redirect('Login');
        }
        $this->load->helper(array('form', 'url'));
        $this->load->database();
        $this->load->library('SessionTimeout');
        $sessionTimeout = new SessionTimeout();
        $sessionTimeout->checkTimeOut();
    }

    public function index()
    {
		$data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'notifications';

        // Title
        $data['title_addition'] = 'Notifications';

        // Load stuff
        $data['stylesheet'] = 'notifications';
        $data['javascript'] = 'notifications';

        // Fetch View Data
        $user_id=$this->session->userdata('user_id');
        $data['query'] = $this->db->query("SELECT * FROM `notification` where user_id = $user_id ORDER BY cron_id DESC LIMIT 100 ")->result();
        $data['user'] = $this->db->query("SELECT * FROM `users` where ID = {$user_id}")->row();

		$this->load->view('templates/header',$data);
        $this->load->view('notification');
        $this->load->view('templates/footer');

    }

    public function datatable_ssp()
    {
        $user_id=$this->session->userdata('user_id');
        $user = $this->db->query("SELECT * FROM `users` where ID = {$user_id}")->row();
        $table = 'notification';
        $primaryKey = 'cron_id';
        $columns = array(
            array(
                'db'        => 'image',
                'dt'        => 0,
                'formatter' => function( $d, $row ) {
                    return '<a href="'.$d.'" data-fancybox="images" data-caption="'.$row['title_name'].'">
                            <img src="'.$d.'" style="width:60px;"></a>';
                },
                'orderable' => 'false'
            ),
            array(
                'db'        => 'title_name',
                'dt'        => 1,
                'formatter' => function( $d, $row ) {
                    return '<a target="_blank" href="http://amazon.com/dp/'.$row['asin'].'">'.$d.'</a>';
                },
                'orderable' => 'false'
            ),
            array(
                'db'        => 'asin',
                'dt'        => 2,
                'formatter' => function( $d, $row ) {
                    return '<a target="_blank" href="http://amazon.com/dp/'.$d.'">'.$d.'</a>';
                },
                'orderable' => 'false'
            ),
            array(
                'db'        => 'amznotseller',
                'dt'        => 3,
                'formatter' => function( $d, $row ) {
                    $yes = '<span style="color:green; font-size:25px;margin-left: -20px;">Yes</span>';
                    $no = '<span style="color:black; font-size:25px;margin-left: -20px;">No</span>';
                    return $d ? $yes : $no;
                },
                'orderable' => 'false'
            ),
            array(
                'db'        => 'sellerstock',
                'dt'        => 4,
                'formatter' => function( $d, $row ) {
                    $yes = '<span style="color:green; font-size:25px;margin-left: -20px;">Yes</span>';
                    $no = '<span style="color:black; font-size:25px;margin-left: -20px;">No</span>';
                    return $d ? $yes : $no;
                },
                'orderable' => 'false'
            ),
            array('db' => 'amznotseller', 'dt' => '98', 'orderable' => 'false'),
            array(
                'db'        => 'date',
                'dt'        => 99,
                'formatter' => function( $d, $row ) use ($user) {
                    $date = new DateTime($d, new DateTimeZone('America/New_York'));
                    $timezone = $user->timezone ? $user->timezone : 'est';
                    $date->setTimezone(new DateTimeZone(TIMEZONES[$timezone]));
                    return $date->format('m/d/Y h:iA T');;
                },
                'orderable' => 'true',
                'order' => 'desc'
            )
        );
        $whereResult = array("user_id = $user_id");
        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
        );

        $this->load->library('ssp');

        echo json_encode(
            Ssp::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereResult)
        );
    }
}
