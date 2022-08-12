<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Reports extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!($this->session->userdata('user_id'))) {
            redirect('Login');
        }
        if (!($_SESSION['uid'])){
            redirect('Login');
        }

        $this->load->library('SessionTimeout');
        $sessionTimeout = new SessionTimeout();
        $sessionTimeout->checkTimeOut();
    }

    public function index()
    {
        $user_id = ($this->session->userdata('user_id'));

        $data['site_info'] = $this->config->item('site_info');
        $data['base_url'] = $this->config->item('base_url');
        $data['site_page'] = 'reports';

        // Title
        $data['title_addition'] = 'Reports';

        //Get product
        $data['products'] = $this->db
            ->from('amaz_aug')
            ->where('user_id', $user_id)
            ->get()
            ->result();
        
        // Load stuff
        $data['stylesheet'] = 'reports';
        $data['javascript'] = 'reports';

        // load the view
        $this->load->view('templates/header.php', $data);
        $this->load->view('report', $data);
        $this->load->view('templates/footer.php');
    }

    public function export($type = 1)
    {
        $user_id = ($this->session->userdata('user_id'));
        $startDate = $this->input->get('startDate', TRUE);
        $endDate = $this->input->get('endDate', TRUE);
        $asins = $this->input->get('asin', TRUE);
        $data = array();
        $data['user'] = $this->db->query("SELECT * FROM `users` where ID = {$user_id}")->row();

        $asins = implode("','", explode(',', $asins));
        if($startDate) $startDate = date('Y-m-d', strtotime($startDate));
        else $startDate = false;
        if(!$endDate) $endDate = date('Y-m-d');

        $query = "
            SELECT t.image, t.title_name, t.asin, t.sellerstock, t.amznotseller, t.date as back_in_stock,
                t2.amznotseller as prev_amznotseller, t2.date as out_of_stock
            FROM notification t
            LEFT JOIN notification t2
                ON t2.cron_id = 
                (
                    SELECT t3.cron_id
                    FROM notification t3
                    WHERE t.date > t3.date AND t3.user_id = $user_id AND t3.asin = t.asin
                    ORDER BY t3.date DESC
                    LIMIT 1
                )
            WHERE t.amznotseller = 0 AND t.user_id = $user_id AND t.asin IN ('$asins')
        ";
        if ($endDate) {
            $query .= " AND t.date BETWEEN '$startDate' AND '$endDate'";
        }
        $query .= ' ORDER BY t.asin, t.cron_id DESC';
        //echo $query;exit;
        $reports = $this->db->query($query)->result();

        if ($type == 2) {
            $filename = 'technical_report_'.date('Ymd');
            $dompdf = new Dompdf();
            $pdfContents = $this->load->view('report_pdf', array('reports' => $reports, 'user' => $data['user']), true);
            $dompdf->loadHtml($pdfContents);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream($filename);
        } else if ($type == 3) {
            $filename = 'technical_report_'.date('Ymd').'.csv';
            header('Content-Type: text/csv; charset=utf-8');
            //header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");

            $file = fopen('php://output', 'w');
            $header = array("Title","ASIN", 'Duration Amazon was out of stock',"Out of Stock Date","Back In Stock Date");
            fputcsv($file, $header);
            foreach ($reports as $key=>$line){
                $same_status = $line->amznotseller == $line->prev_amznotseller;
                $datetime1 = date_create($line->out_of_stock);
                $datetime2 = date_create($line->back_in_stock);
                $interval = ($line->out_of_stock === null || $line->back_in_stock === null)
                    ? false
                    : date_diff($datetime1, $datetime2);
                $timezone = $data['user']->timezone ? $data['user']->timezone : 'est';
                $timezoneObj = new DateTimeZone(TIMEZONES[$timezone]);
                $defaultTimezoneObj = new DateTimeZone('America/New_York');
                $outOfStock = new DateTime($line->out_of_stock, $defaultTimezoneObj);
                $outOfStock->setTimezone($timezoneObj);
                $backInStock = new DateTime($line->back_in_stock, $defaultTimezoneObj);
                $backInStock->setTimezone($timezoneObj);
                fputcsv($file,array(
                    $line->title_name,
                    $line->asin,
                    (!$same_status) ? ($interval ? $interval->format('%h hours %i minutes') : 'N/A') : 'Missed status change because tracking was turned off',
                    (!$same_status) ? ($line->out_of_stock ? str_replace("D","S",$outOfStock->format('m/d/Y h:iA T')) : 'N/A') : 'Missed status change because tracking was turned off',
                    $line->back_in_stock ? str_replace("D","S",$backInStock->format('m/d/Y h:iA T')) : 'N/A'
                ));
            }
            fclose($file);
        } else {
            //Render views
            $data['site_info'] = $this->config->item('site_info');
            $data['base_url'] = $this->config->item('base_url');
            $data['site_page'] = 'reports';

            // Title
            $data['title_addition'] = 'Report results';
            // Load stuff
            $data['stylesheet'] = 'reports';
            $data['javascript'] = 'reports';
            $data['reports'] = $reports;

            // load the view
            $this->load->view('templates/header.php', $data);
            $this->load->view('report_result', $data);
            $this->load->view('templates/footer.php');
        }
    }
}
