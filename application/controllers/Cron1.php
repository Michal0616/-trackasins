<?php
require_once APPPATH . 'libraries/mailgun-php/vendor/autoload.php';
require_once __DIR__ . '/../helpers/common_helper.php';
require_once APPPATH . 'views/simple_html_dom.php';

use Mailgun\Mailgun;

class Cron1 extends CI_Controller
{
    public $mgClient;
    public $mgDomain = "trackasins.com";
    public $planItemsSystem;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('PlanItemsSystem');
        $this->planItemsSystem = new PlanItemsSystem();
        $this->mgClient = new Mailgun('key-f14cf94304da5471b926ec3e4487773f');
    }

    public function index()
    {     
        $html = '<p>Thank God</p>';
        $email = 'spark.lenin@gmail.com';
        $this->mgClient->sendMessage($this->mgDomain, [
                    'from' => 'TrackASINS <notifications@trackasins.com>',
                    'to' => $email,
                    'subject' => 'TrackASINS - Bulk Upload Report',
                    'html' => $html
                ]);
                exit('done');
         
        $trackingAsins = $this->db->query("SELECT * FROM amaz_aug WHERE (tracking=1 OR stock_noti='true') AND requires_rescrape > -1 AND user_id = 84 and asin = 'B07WMYZV9S'")->result();
        echo '<pre>';print_r($trackingAsins);
       
        foreach ($trackingAsins as $previousScrape) {
            
            print_r($previousScrape);
            
            $user = $this->db->query("SELECT * FROM users WHERE ID=".$previousScrape->user_id)->row();
            $seller_name = $user->company;
            echo $seller_name;
//            exit();
            require_once __DIR__ . '/../helpers/common_helper.php';
            //echo $previousScrape->id.'<br>'; 
            $getstock = get_stock2($previousScrape->asin, $seller_name);
            print_r($getstock);
            exit('</pre>');
            $amzNotSeller = $getstock['amznotseller'];
            $sellerStock = $getstock['sellerStock'];
            
            //echo  $sellerStock;
            //echo  $amzNotSeller;exit;
            //$amzNotSeller = get_amazon_not_seller($previousScrape->asin, $previousScrape->seller_id);
            //$sellerStock = get_seller_stock($previousScrape->asin, $previousScrape->seller_id);
           // echo '<pre>'.print_r($getstock);exit;
            if ($amzNotSeller === FALSE || $sellerStock === FALSE) {
               // return false;
            } else {
                $output['amazon_out_of_stock'] = $amzNotSeller;
                $output['seller_in_stock'] = $sellerStock;
                $output['scrape_id'] = $previousScrape->id;
                $output['user_id'] = $previousScrape->user_id;
                
                $this->updateDatabase($output);
                
            }
          
        }
        //exit;
        
    }

    public function scrape_again()
    {
        $requiredRescrapes = $this->Common_model->queryWhereMultipleJoinResult(
            'amaz_aug',
            ['requires_rescrape' => 1],
            ['users' => 'amaz_aug.user_id = users.id']
        );

        echo "<//----\\>";
        print_r($requiredRescrapes);
        exit('<-THANK GOD->');
        foreach ($requiredRescrapes as $scrape) {
            $user = $this->db->query("SELECT * FROM users WHERE ID=".$scrape->user_id)->row();
            $str = $user->company;
            
            $main_url = "https://www.amazon.com/gp/offer-listing/{$scrape->asin}/ref=dp_olp_new?ie=UTF8&condition=new";
            $html = getPage($main_url);
            //$amznotseller = get_amazon_not_seller($scrape->asin, $html);
           // $sellerstock = get_seller_stock($scrape->asin, $scrape->seller_id);
            phpQuery::newDocument($html);
            $amznotseller = array('0'=>1);;
            $sellerstock = array('0'=>0);

            if ($html && $sellerstock !== FALSE && $amznotseller !== FALSE) {
                // Extract data from product sellers page
               
                $image = pq('div#olpProductImage')->find("img")->attr("src");
                
                $title_name = pq('h1.a-size-large.a-spacing-none')->text();
                $title_name = trim($title_name);
                $rating = pq('i.a-icon-star')->eq(0)->text();
                $reviews = pq('span.a-size-small')->eq(0)->text();
                $reviews = trim($reviews);
                $seller_name ='';
                $seller_ids ='';
                foreach (pq('div#olpOfferList')->find('div.olpOffer') as $elements) {
                    $seller_name = pq($elements)->find("h3.olpSellerName")->find('a')->text();
                    if (empty($seller_name)) {
                        $seller_name = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                    }
                    //if($seller_name == $user->company){
                    
                        //$sel_name = $seller_name;
                    $seller_url = pq($elements)->find('div.olpSellerColumn')->find('a')->attr('href');
                    $ex_sell = explode("seller=", $seller_url);
                    $seller_ids = trim(@$ex_sell[1]);
                    $title_link = pq($elements)->find("h3.olpSellerName")->find('a')->attr('href');
                    $seller_link = 'http://www.amazon.com' . $title_link;

                    if(strcmp($str,$seller_name)==0) {
                        $sellerstock[] = 1;  
                    }  else {
                        $sellerstock[] = 0;
                    }
                    $inStock = "0";
                    $stock_url = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                    
                    if ($stock_url == "Amazon.com") {
                        $inStock = "1";
                        $amznotseller[] = 0;
                    }  else {
                        $amznotseller[] = 1;
                    }
                   
                   
                    $amount = pq($elements)->find('span.olpOfferPrice')->text();
                    $price = filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $ship = pq($elements)->find("span.a-color-secondary")->text();
                    $shipp = filter_var($ship, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $shipping = str_replace('+', '', $shipp);
                   // }   
                }
                if (in_array("1", $sellerstock)) {
                    $sellerstock = 1;
                } else {
                    $sellerstock = 0;
                }
        
                if (in_array("0", $amznotseller)) {
                    $amznotseller = 0;
                } else {
                    $amznotseller = 1;
                }
                // $arr = array();
                // if(isset($image) && $image){
                // list($width, $height) = getimagesize($image); 
                // $arr = array('h' => $height, 'w' => $width );
                // }
                // Fallback scraping attempt
               // echo 'ffff'.$image;exit;
                //if ((!isset($sel_name) || !$sel_name)) {
                if ((!isset($image) || !$image) || (!isset($title_name)|| !$title_name)) {
                    $main_url = "https://www.amazon.com/dp/{$scrape->asin}";
                    $html = getPage($main_url);
                    phpQuery::newDocument($html);

                    $image = pq("#main-image-container")->find('img')->attr('data-old-hires');
                    //$image = pq('div#imgTagWrapperId')->find("img")->attr("src");
                    $title_name = pq("span#productTitle")->text();
                    $title_name = trim($title_name);
                    $rating = pq(".a-icon-star")->eq(0)->text();
                    $reviews = pq("#acrCustomerReviewText")->text();
                    $reviews = trim($reviews);
                    $sell_name = pq('#merchant-info')->text();
                    if (stripos($sell_name, 'amazon.com') !== FALSE) {
                        $inStock = 1;
                        $seller_name = 'Amazon.com';
                        $amznotseller = 0;
                    }
                    $seller_url = pq('#merchant-info')->find('a')->attr('href');
                    $seller_ids = '';
                    if ($str = getInBetweenStrings($seller_url, 'seller=', '&')) {
                        $seller_ids = $str;
                        $seller_name = pq('#merchant-info')->find('a')->text();
                        $sellerstock = 1;
                    } else if ($str = getInBetweenStrings($seller_url, 'seller=', '')) {
                        $seller_ids = $str;
                        $seller_name = pq('#merchant-info')->find('a')->text();
                        $sellerstock = 1;
                    }
                    // if($seller_name == $user->company){
                    
                    //     $sel_name = $seller_name;
                    // }
                    $amount = pq('#priceblock_ourprice')->text();
                    if (!$amount) {
                        $alt_amount = pq('.price-large')->eq(0)->text();
                        if ($alt_amount) {
                            $cents = pq('.price-info-superscript')->eq(0)->text();
                            $amount = '$'.$alt_amount.".{$cents}";
                        }
                    }
                    $price = filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }
                //$seller_name = $sel_name;
                //if ((isset($sel_name) || $sel_name)) {
                if (isset($image) && $image && isset($title_name) && $title_name) {
                    
                    $tracking = $this->getTrackingStatus($scrape->user_id);
                    
                    if (($amznotseller == "1") && ($sellerstock == "0")) {
                        $status = 0;
                    } else if (($amznotseller == "1") && ($sellerstock == "1")) {
                        $status = 1;
                    } else if (($amznotseller == "0") && ($sellerstock == "1")) {
                        $status = 2;
                    } else if (($amznotseller == "0") && ($sellerstock == "0")) {
                        $status = 2;
                    } else {
                        $status = 3;
                    }
                    $data_update = array(
                        'image' => str_replace('._SS160_','',$image),
                        'title_name' => $title_name,
                        'tracking' => $tracking,
                        'email_noti' => 'true',
                        'amznotseller' => $amznotseller,
                        'sellerstock' => $sellerstock,
                        'rating' => $rating,
                        'review' => $reviews,
                        'seller_name' => isset($seller_name) && $seller_name ? $seller_name : '',
                        'seller_url' => $seller_url,
                        'seller_id' => $seller_ids,
                        'selling_price' => $price,
                        'shipping_price' => isset($shipping) && $shipping ? $shipping : '',
                        'status' => $status,
                        'requires_rescrape' => 0
                    );
                    $this->Common_model->updateData('amaz_aug', $data_update, ['id' => $scrape->id]);
                } else {
                    $data_update = array(
                        'requires_rescrape' => 1
                    );
                    $this->Common_model->updateData('amaz_aug', $data_update, ['id' => $scrape->id]); 
                }
            }
        }
    }

    public function bulk_upload()
    {
        
        $queues = $this->Common_model->getData('bulk_uploads', ['status' => 'PENDING']);
        foreach ($queues as $queue){
        $file_data = json_decode($queue->file_upload_data, true);
        $file_data = $file_data['upload_data'];
        if (($handle = fopen($file_data['full_path'], "r")) !== FALSE) {
            $this->Common_model->updateData(
                'bulk_uploads',
                ['status' => 'IN_PROGRESS', 'started_at' => date('Y-m-d H:i:s')],
                ['id' => $queue->id]
            );
            $asin_statuses = [];
            $total = 0;
            $uploaded = 0;
            $rescrape_required = 0;
            $already_exists = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $exists = $this->Common_model->getDataSingleRow('amaz_aug', ['user_id' => $queue->user_id, 'asin' => $data[0]]);
                if (!$exists) {
                    $scrapedData = $this->scrapeAmazonData($data[0], $queue->seller_id, $queue->user_id);
                    $this->Common_model->insertData('amaz_aug', $scrapedData);
                    if ($scrapedData['requires_rescrape'] == 1) {
                        $asin_statuses[$data[0]] = 'PRODUCT_NOT_FOUND';
                        $rescrape_required++;
                    } else {
                        $asin_statuses[$data[0]] = 'UPLOADED';
                        $uploaded++;
                    }
                } else {
                    $asin_statuses[$data[0]] = 'ALREADY_EXISTS';
                    $already_exists++;
                }
                $total++;
            }
            $this->Common_model->updateData(
                'bulk_uploads',
                ['status' => 'COMPLETED', 'completed_at' => date('Y-m-d H:i:s')],
                ['id' => $queue->id]
            );

            $html = "<html>
                        <head>
                            <title>TrackASINS</title>
                        </head>
                        <body>
                            <h1>TrackASINS</h1>
                            <h3>Bulk Upload Report</h3>
                            <p>Total ASINs: {$total}</p>
                            <p>ASINs Uploaded: {$uploaded}</p>
                            <p>ASINs To Be Retried: {$rescrape_required}</p>
                            <p>ASINs Already Exist: {$already_exists}</p>
                            <table border='1' cellpadding='10px'>
                            <tr><th>ASIN</th><th>Upload Status</th></th>";

            foreach ($asin_statuses as $asin => $status) {
                $html .= "<tr><td><a href=\"https://www.amazon.com/dp/{$asin}\">{$asin}</a></td><td>{$status}</td></tr>";
            }

            $html .= "</table><p><b>NOTE: </b>The ASINs with status \"PRODUCT_NOT_FOUND\" are uploaded; however, will be reprocessed to fetch product details.</p><p>Thanks!<br>Track ASINS<br><a href='trackasins.com'>Trackasins.com</a></p></body></html>";
            $user = $this->Common_model->getDataSingleRow('users', ['id' => $queue->user_id]);
            $email = $user->notification_email ? $user->notification_email : $user->email;
            if($user->global_noti == 'true') {
                $this->mgClient->sendMessage("$this->mgDomain", [
                    'from' => 'TrackASINS <notifications@trackasins.com>',
                    'to' => $email,
                    'subject' => 'TrackASINS - Bulk Upload Report',
                    'html' => $html
                ]);
            } 
        }
            
        }
        fclose($handle);
    }

    public function check_trial_expiry()
    {
        
        $users = $this->Common_model->getData('users', 'DATE(users.created_at) = DATE_SUB(CURDATE(), INTERVAL 14 day)');
        //echo '<pre>';print_r($users);exit;
        $select = 'id, tracking, stock_noti';
        foreach ($users as $user) {
            $current_status = $this->Common_model->customQueryResult("SELECT ".$select." FROM amaz_aug WHERE user_id = ".$user->ID);
            $current_statuses = [];
            foreach ($current_status as $status) {
                $current_statuses[$status->id] = $status;
            }
            $this->Common_model->updateData('users', ['user_preferences' => json_encode($current_statuses)], ['ID' => $user->ID]);
            $this->Common_model->updateData('amaz_aug', ['tracking' => 0, 'stock_noti' => 'false'], ['user_id' => $user->ID]);
            $this->Common_model->updateData('users', [ 'global_noti' => 'false'], ['ID' => $user->ID]);
        }
    }

    public function check_subscription_expiry()
    {
        $stripe_subscriptions = $this->Common_model->getData('stripe_subscriptions', 'DATE(ends_at) = CURDATE()');
        $select = 'id, tracking, stock_noti';
        foreach ($stripe_subscriptions as $subscription) {
            $current_status = $this->Common_model->customQueryResult("SELECT $select FROM amaz_aug WHERE user_id = {$subscription->user_id}");
            $current_statuses = [];
            foreach ($current_status as $status) {
                $current_statuses[$status->id] = $status;
            }
            $this->Common_model->deleteData('stripe_subscriptions', ['id' => $subscription->id]);
            $this->Common_model->deleteData('supports', ['user_id' => $subscription->user_id]);
            $this->Common_model->updateData('users', ['user_preferences' => json_encode($current_statuses)], ['ID' => $subscription->user_id]);
            $this->Common_model->updateData('amaz_aug', ['tracking' => 0, 'stock_noti' => 'false'], ['user_id' => $subscription->user_id]);
            $this->Common_model->updateData('users', [ 'global_noti' => 'false'], ['ID' => $user->ID]);
        }
    }

    protected function updateDatabase($scrapeData)
    {
        $now = date('Y-m-d H:i:s');
        $amazonOutOfStock = $scrapeData['amazon_out_of_stock'];
        $sellerInStock = $scrapeData['seller_in_stock'];
        $previousScrape = $this->db
            ->query("SELECT * FROM amaz_aug WHERE id = ".$scrapeData['scrape_id'])->row();
        $user = $this->db
            ->query("SELECT * FROM users WHERE ID = ".$scrapeData['user_id'])->row();
        if (is_string($previousScrape->amznotseller)) {
            $previousScrape->amznotseller = intval($previousScrape->amznotseller);
        }
        if (is_string($previousScrape->sellerstock)) {
            $previousScrape->sellerstock = intval($previousScrape->sellerstock);
        }
        $amazonStockChange = $amazonOutOfStock !== $previousScrape->amznotseller;
        $sellerStockChange = $sellerInStock !== $previousScrape->sellerstock;

        if ($amazonStockChange) {
            // Log stock status change for reports
            $this->common_model->insertData('amz_report', [
                'asin' => $previousScrape->asin,
                'amz_not_seller' => $amazonOutOfStock,
                'date' => date('Y-m-d', strtotime($now))
            ]);
            $this->common_model->updateData(
                'amaz_aug',
                [
                    'amznotseller' => $amazonOutOfStock,
                    'sellerstock' => $sellerInStock
                ],
                [
                    'user_id' => $previousScrape->user_id,
                    'asin' => $previousScrape->asin
                ]
            );
            $notificationData = [
                'user_id' => $previousScrape->user_id,
                'image' => $previousScrape->image,
                'title_name' => $previousScrape->title_name,
                'asin' => $previousScrape->asin,
                'sellerstock' => $sellerInStock,
                'amznotseller' => $amazonOutOfStock,
                'date' => $now,
                'amzoutofstock' => $amazonOutOfStock
            ];
            $this->common_model->insertData('notification', $notificationData);
            $firstStatusChange = $previousScrape->amznotseller === null
                || $previousScrape->amznotseller === '';
            if ($amazonStockChange && !$firstStatusChange) {
                $this->notifySeller($user, $notificationData, $previousScrape);
            }
        } else if ($sellerStockChange) {
            $this->common_model->updateData(
                'amaz_aug',
                [
                    'amznotseller' => $amazonOutOfStock,
                    'sellerstock' => $sellerInStock
                ],
                [
                    'user_id' => $previousScrape->user_id,
                    'asin' => $previousScrape->asin
                ]
            );
        }
    }

    protected function notifySeller($user, $notificationData, $previousScrape)
    {
        if ($user && $user->global_noti == 'true') {
            if ($previousScrape->email_noti == 'true') {
                $this->notifyByEmail($user, $notificationData, $previousScrape);
            }
            if ($previousScrape->phone_noti == 'true') {
                $this->notifyByPhone($user, $notificationData, $previousScrape);
            }
        }
    }

    protected function notifyByEmail($user, $notificationData, $previousScrape)
    {
        $date = new DateTime($notificationData['date'], new DateTimeZone('America/New_York'));
        $timezone = $user->timezone ? $user->timezone : 'est';
        $date->setTimezone(new DateTimeZone(TIMEZONES[$timezone]));
        if($date->format('T') == 'EDT'){
            $m = ' EST';
        }
        if($date->format('T') == 'PDT'){
            $m = ' PST';
        }
        if($date->format('T') == 'CDT'){
            $m = ' CST';
        }
        if($date->format('T') == 'MDT'){
            $m = ' MST';
        }
        if($date->format('T') == 'AKDT'){
            $m = ' AKST';
        }
        if($date->format('T') == 'HDT'){
            $m = ' HST';
        }
        $message = '';
        if ($notificationData['amznotseller'] == '0' && $previousScrape->stock_noti == 'true') {
            $message = "Amazon back in stock on " . $date->format('m/d/Y h:iA').$m;
        } else if ($notificationData['amznotseller'] == '1' && $previousScrape->tracking == 1) {
            $message = "Amazon ran out of stock on " . $date->format('m/d/Y h:iA').$m;
        }
        if ($message) {
            $this->sendEmail($user, $message, $notificationData);
        }
    }

    protected function notifyByPhone($user, $notificationData, $previousScrape)
    {
        $date = new DateTime($notificationData['date'], new DateTimeZone('America/New_York'));
        $timezone = $user->timezone ? $user->timezone : 'est';
        $date->setTimezone(new DateTimeZone(TIMEZONES[$timezone]));
        if($date->format('T') == 'EDT'){
            $m = ' EST';
        }
        if($date->format('T') == 'PDT'){
            $m = ' PST';
        }
        if($date->format('T') == 'CDT'){
            $m = ' CST';
        }
        if($date->format('T') == 'MDT'){
            $m = ' MST';
        }
        if($date->format('T') == 'AKDT'){
            $m = ' AKST';
        }
        if($date->format('T') == 'HDT'){
            $m = ' HST';
        }
        $message = '';
        if ($notificationData['amznotseller'] == '0' && $previousScrape->stock_noti == 'true') {
            $message = "ASIN: www.amazon.com/dp/{$notificationData['asin']}, Title : {$notificationData['title_name']},Notification : Amazon back in stock on " . $date->format('m/d/Y h:iA').$m.", Are you in stock : " . (($notificationData['sellerstock'] == '1') ? 'Yes' : 'No');
        } else if ($notificationData['amznotseller'] == '1' && $previousScrape->tracking == 1) {
            $message = "ASIN: www.amazon.com/dp/{$notificationData['asin']}, Title : {$notificationData['title_name']},Notification : Amazon out of Stock on " . $date->format('m/d/Y h:iA').$m.", Are you in stock : " . (($notificationData['sellerstock'] == '1') ? 'Yes' : 'No');
        }

        if ($message) {
            $phone = $user->notification_phone ? $user->notification_phone : $user->phone;
            if (send_sms($phone, $message)) {
                echo "Seller with email {$user->email} was notified of stock status change".PHP_EOL;
            } else {
                echo 'SMS sending failed.'.PHP_EOL;
            }
        }
    }

    protected function sendEmail($user, $message, $notificationData)
    {
        $html = "
                        <html>
                        <head>
                            <title>TrackASINS</title>
                        </head>
                        <body>
                            <h1>TrackASINS</h1>";
        $html .= "<br/>
                        <img src=\"{$notificationData['image']}\" style= \"width: 50px; height: 60px;\">
                        <p>
                            ASIN  : <b><a href=\"https://www.amazon.com/dp/{$notificationData['asin']}\">{$notificationData['asin']}</a></b> <br/>
                            Title : <b>{$notificationData['title_name']}</b><br>
                            Notification : <b>{$message}</b><br/>
                            Are you in stock : <b>" . (($notificationData['sellerstock'] == '1') ? 'Yes' : 'No') . "</b>
                        </p>
                        <p>Thanks!<br>Track ASINS<br><a href='trackasins.com'>Trackasins.com</a></p>";
        $html .= "</body></html>";

        $email = $user->notification_email ? $user->notification_email : $user->email;
        $result = $this->mgClient->sendMessage("$this->mgDomain", [
            'from' => 'TrackASINS <notifications@trackasins.com>',
            'to' => $email,
            'subject' => 'TrackASINS',
            'html' => $html
        ]);
        if ($result) {
            echo "Seller with email {$user->email} was notified of stock status change".PHP_EOL;
        }
    }

    protected function logException($exceptionMessage, $traceString, $previousScrapeJson, $userJson)
    {
        $now = new DateTime('now', new DateTimeZone(TIMEZONES['est']));
        $this->common_model->insertData('failed_crons', [
            'previous_scrape_record' => $previousScrapeJson,
            'user_record' => $userJson,
            'exception_message' => $exceptionMessage,
            'exception_trace' => $traceString,
            'created_at' => $now->format('Y-m-d H:i:s')
        ]);
    }

    protected function scrapeAmazonData($asin, $sellerId, $user_id)
    {
        $user = $this->db->query("SELECT * FROM users WHERE ID=".$user_id)->row();
        $str = $user->company;

        $main_url = "https://www.amazon.com/gp/offer-listing/{$asin}/ref=dp_olp_new?ie=UTF8&condition=new";
        $html = getPage($main_url);
        //$amznotseller = get_amazon_not_seller($asin, $html);
       // $sellerstock = get_seller_stock($asin, $sellerId);
        $amznotseller = array('0'=>1);
        $sellerstock = array('0'=>0);
        $seller_ids =  '';
        $seller_name = '';
        phpQuery::newDocument($html);

        if ($html && $sellerstock !== FALSE && $amznotseller !== FALSE) {
            // Extract data from product sellers page
            $image = pq('div#olpProductImage')->find("img")->attr("src");
            $title_name = pq('h1.a-size-large.a-spacing-none')->text();
            $title_name = trim($title_name);
            $rating = pq('i.a-icon-star')->eq(0)->text();
            $reviews = pq('span.a-size-small')->eq(0)->text();
            $reviews = trim($reviews);
            foreach (pq('div#olpOfferList')->find('div.olpOffer') as $elements) {
                $seller_url = pq($elements)->find('div.olpSellerColumn')->find('a')->attr('href');
                $ex_sell = explode("seller=", $seller_url);
                $seller_ids = trim(@$ex_sell[1]);
                $title_link = pq($elements)->find("h3.olpSellerName")->find('a')->attr('href');
                $seller_link = 'http://www.amazon.com' . $title_link;
                $seller_name = pq($elements)->find("h3.olpSellerName")->find('a')->text();
                if (empty($seller_name)) {
                    $seller_name = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                }
                if(strcmp($str,$seller_name)==0) {
                    $sellerstock[] = 1;  
                }  else {
                    $sellerstock[] = 0;
                }
                $inStock = "0";
                $stock_url = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                
                if ($stock_url == "Amazon.com") {
                    $inStock = "1";
                    $amznotseller[] = 0;
                }  else {
                    $amznotseller[] = 1;
                }
                $amount = pq($elements)->find('span.olpOfferPrice')->text();
                $price = filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $ship = pq($elements)->find("span.a-color-secondary")->text();
                $shipp = filter_var($ship, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $shipping = str_replace('+', '', $shipp);
            }
            if (in_array("1", $sellerstock)) {
                $sellerstock = 1;
            } else {
                $sellerstock = 0;
            }
    
            if (in_array("0", $amznotseller)) {
                $amznotseller = 0;
            } else {
                $amznotseller = 1;
            }
            // Fallback scraping attempt
            // $arr = array();
            // if(isset($image) && $image){
            // list($width, $height) = getimagesize($image); 
            // $arr = array('h' => $height, 'w' => $width );
            // }
         //if (empty($sel_name)) {
            
         if ((!isset($image) || !$image) || (!isset($title_name)|| !$title_name)) {
                $main_url = "https://www.amazon.com/dp/{$asin}";
                $html = getPage($main_url);
                phpQuery::newDocument($html);

                $image = pq("div#imgTagWrapperId")->find('img')->attr('data-old-hires');
                $title_name = pq("span#productTitle")->text();
                $title_name = trim($title_name);
                $rating = pq(".a-icon-star")->eq(0)->text();
                $reviews = pq("#acrCustomerReviewText")->text();
                $reviews = trim($reviews);
                $sell_name = pq('#merchant-info')->text();
                if (stripos($sell_name, 'amazon.com') !== FALSE) {
                    $inStock = 1;
                    $seller_name = 'Amazon.com';
                    $amznotseller = 0;
                }
                $seller_url = pq('#merchant-info')->find('a')->attr('href');
                $seller_ids = '';
                if ($str = getInBetweenStrings($seller_url, 'seller=', '&')) {
                    $seller_ids = $str;
                    $seller_name = pq('#merchant-info')->find('a')->text();
                    $sellerstock = 1;
                } else if ($str = getInBetweenStrings($seller_url, 'seller=', '')) {
                    $seller_ids = $str;
                    $seller_name = pq('#merchant-info')->find('a')->text();
                    $sellerstock = 1;
                }
                $amount = pq('#priceblock_ourprice')->text();
                if (!$amount) {
                    $alt_amount = pq('.price-large')->eq(0)->text();
                    if ($alt_amount) {
                        $cents = pq('.price-info-superscript')->eq(0)->text();
                        $amount = '$'.$alt_amount.".{$cents}";
                    }
                }
                $price = filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }

            if (isset($image) && $image && isset($title_name) && $title_name) {
            //if (isset($image) && $image) {
                $tracking = $this->getTrackingStatus($user_id);
                if($tracking == 1) {
                    $stock_noti = 'true';
                } else {
                    $stock_noti = 'false';
                }
                if (($amznotseller == "1") && ($sellerstock == "0")) {
                    $status = 0;
                } else if (($amznotseller == "1") && ($sellerstock == "1")) {
                    $status = 1;
                } else if (($amznotseller == "0") && ($sellerstock == "1")) {
                    $status = 2;
                } else if (($amznotseller == "0") && ($sellerstock == "0")) {
                    $status = 2;
                } else {
                    $status = 3;
                }
                return [
                    'asin'           => $asin,
                    'user_id'        => $user_id,
                    'date'           => date('Y-m-d H:i:s'),
                    'image'          => !isset($image) ? '' : str_replace('._SS160_','',$image),
                    'title_name'     => !isset($title_name) ? '' : $title_name,
                    'tracking'       => $tracking,
                    'stock_noti'     => $stock_noti,
                    'email_noti'     => 'true',
                    'amznotseller'   => !isset($image) ? 0 : $amznotseller,
                    'sellerstock'    => !isset($image) ? 0 : $sellerstock,
                    'rating'         => !isset($rating) ? '' : $rating,
                    'review'         => !isset($reviews) ? '' : $reviews,
                    'seller_name'    => !isset($seller_name) ? '' : $seller_name,
                    'seller_url'     => !isset($seller_url)||is_null($seller_url) ? '' : $seller_url,
                    'seller_id'      => !isset($seller_ids)||is_null($seller_ids) ? '' : $seller_ids,
                    'selling_price'  => !isset($price)||is_null($price) ? '' : $price,
                    'shipping_price' => !isset($shipping)||is_null($shipping) ? '' : $shipping,
                    'status'         => !isset($status)||is_null($status) ? '' : $status,
                    'requires_rescrape' => 0
                ];
            } 
        }

        $tracking = $this->getTrackingStatus($user_id);
        if($tracking == 1) {
            $stock_noti = 'true';
        } else {
            $stock_noti = 'false';
        }
        return [
            'asin'           => $asin,
            'user_id'        => $user_id,
            'date'           => date('Y-m-d H:i:s'),
            'image'          => 'assets2/images/question-mark.png',
            'title_name'     => 'Cannot fetch title from Amazon at this time',
            'tracking'       => $tracking,
            'stock_noti'     => $stock_noti,
            'email_noti'     => 'true',
            'amznotseller'   => null,
            'sellerstock'    => null,
            'rating'         => '',
            'review'         => '',
            'seller_name'    => '',
            'seller_url'     => '',
            'seller_id'      => '',
            'selling_price'  => '',
            'shipping_price' => '',
            'status'         => 3,
            'requires_rescrape' => 1
        ];
    }

    protected function getTrackingStatus($user_id)
    {
        $planData = $this->planItemsSystem->check_expiration_date($user_id);
        if (isset($planData)
            && ($planData['result'] =='success')
            && isset($planData['plan_count'])
            && isset($planData['current_count'])
            && ($planData['plan_count'] > $planData['current_count'])
        ) {
            $tracking = 1;
        } else {
            $tracking = 0;
        }

        return $tracking;
    }

    protected function is_user_on_trial($user)
    {
        $today = date_create(date('Y-m-d'));
        $created = date_create(substr($user->created_at, 0, 10));
        $diff = date_diff($created, $today);
        $difference_date = $diff->days;

        return $difference_date < 14;
    }
}
