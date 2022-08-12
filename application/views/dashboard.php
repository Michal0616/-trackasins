<?php
date_default_timezone_set('America/New_York');
require_once("simple_html_dom.php");

ini_set('max_execution_time', 9999999);
ini_set('memory_limit', '9999M');
error_reporting(0);

$chkdata = 0;

function curl_get_file_contents($URL)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    curl_close($c);

    if ($contents) return $contents;
    else return FALSE;
}

// NEW FUNCTION USING AMAZON API.
function readItem($asin)
{

    // Your AWS Access Key ID, as taken from the AWS Your Account page
    $aws_access_key_id = "AKIAIXQ4QTMGDJGVXUCA";

    // Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
    $aws_secret_key = "tkmLW+C35Nyr6L1+kJmBtFgzQNxTDgG6muNY+Y1o";

    // The region you are interested in
    $endpoint = "webservices.amazon.com";

    $uri = "/onca/xml";

    $params = array(
        "Service" => "AWSECommerceService",
        "Operation" => "ItemLookup",
        "AWSAccessKeyId" => "AKIAIXQ4QTMGDJGVXUCA",
        "AssociateTag" => "baus019-20",
        "ItemId" => $asin,
        "IdType" => "ASIN",
        "ResponseGroup" => "OfferFull",
        "Condition" => "New"
    );

    //echo $params;exit();
    // Set current timestamp if not set
    if (!isset($params["Timestamp"])) {
        $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
    }

    // Sort the parameters by key
    ksort($params);

    $pairs = array();

    foreach ($params as $key => $value) {
        array_push($pairs, rawurlencode($key) . "=" . rawurlencode($value));
    }

    // Generate the canonical query
    $canonical_query_string = join("&", $pairs);

    // Generate the string to be signed
    $string_to_sign = "GET\n" . $endpoint . "\n" . $uri . "\n" . $canonical_query_string;

    // Generate the signature required by the Product Advertising API
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));

    // Generate the signed URL
    $request_url = 'http://' . $endpoint . $uri . '?' . $canonical_query_string . '&Signature=' . rawurlencode($signature);

    // echo "Signed URL: \"".$request_url."\"";
    //echo$request_url;exit;
    $xmlString = curl_get_file_contents($request_url);
    $xml = simplexml_load_string($xmlString);

    $available = $xml->Items->Item->Offers->Offer->Merchant->Name;
    //echo $available;exit;
    return $xml;
}

function getCron()
{
    $http_head = array("Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Language:en-US,en;q=0.8",
        "Connection:keep-alive",
        "Upgrade-Insecure-Requests:1",
        "User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://ezon.org/pr/rms/cron.php'); // Target URL
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $http_head);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

if (!empty($_POST['asin'])) {
    $chkdata = 1;
    $asin = trim($_POST["asin"]);
    unset($_POST["asin"]);
    $main_url = "https://www.amazon.com/gp/offer-listing/{$asin}/ref=dp_olp_new?ie=UTF8&condition=new";
    if (empty($amaz_aug_asin)) {
        $html = getPage($main_url);
        phpQuery::newDocument($html);
        $amznotseller = null;
        $sellerstock = null;

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
                $inStock = "0";
                $stock_url = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                if ($stock_url == "Amazon.com") {
                    $inStock = "1";
                }
                $amount = pq($elements)->find('span.olpOfferPrice')->text();
                $price = filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $ship = pq($elements)->find("span.a-color-secondary")->text();
                $shipp = filter_var($ship, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $shipping = str_replace('+', '', $shipp);
            }
        }

        if ((!isset($image) || !$image)) {
            $main_url = "https://www.amazon.com/dp/{$asin}";
            $html = getPage($main_url);
            phpQuery::newDocument($html);

            $image = pq("#main-image-container")->find('img')->attr('data-old-hires');
            $title_name = pq("span#productTitle")->text();
            $title_name = trim($title_name);
            $rating = pq(".a-icon-star")->eq(0)->text();
            $reviews = pq("#acrCustomerReviewText")->text();
            $reviews = trim($reviews);
            $stock_status = pq('#availability')->text();
            $stock_status = trim($stock_status);
            if (!$stock_status) {
                $stock_status = pq('#outOfStock')->text();
            }
            if ($stock_status == 'Currently Unavailable.') {
                $inStock = 0;
            }
            $sell_name = pq('#merchant-info')->text();
            if (stripos($sell_name, 'amazon.com') !== FALSE) {
                $inStock = 1;
                $seller_name = 'Amazon.com';
            }
            $seller_url = pq('#merchant-info')->find('a')->attr('href');
            $seller_ids = '';
            if ($str = getInBetweenStrings($seller_url, 'seller=', '&')) {
                $seller_ids = $str;
                $seller_name = pq('#merchant-info')->find('a')->text();
            } else if ($str = getInBetweenStrings($seller_url, 'seller=', '')) {
                $seller_ids = $str;
                $seller_name = pq('#merchant-info')->find('a')->text();
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
    } else {
        $image = $amaz_aug_asin->image;
        $title_name = $amaz_aug_asin->title_name;
        $rating = $amaz_aug_asin->rating;
        $reviews = $amaz_aug_asin->review;
        $seller_ids = $amaz_aug_asin->seller_id;

        $title_link = $amaz_aug_asin->seller_url;
        $seller_name = $amaz_aug_asin->seller_name;

        $inStock = "0";
        $price = $amaz_aug_asin->selling_price;
        $shipping = $amaz_aug_asin->shipping_price;

        $requires_rescrape = true;
    }
}




?>


<!DOCTYPE html>
<html>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 25px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 2px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #b65f2b;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(32px);
        -ms-transform: translateX(32px);
        transform: translateX(32px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    th {
        background: white;;
    }
</style>
<body>
<script type="text/javascript">

    </script>
<style>
    td:hover a, td:hover span {
        color: #d27842 !important;
        cursor: pointer;
    }
</style>

<div class="headline-site-color dashboardHeadline" >
    <div class="innerholder container">
        <div class="profile_Pic_left">
            <?php $user_id = ($this->session->userdata('user_id'));
            $result = $this->common_model->getDataSingleRow('users', array('id' => $user_id)); ?>
<!--            <form  method="POST" enctype="multipart/form-data">-->
                <input type="file" id="profilePicture"/>
                <img src="<?php echo base_url(); ?>assets2/user_data/<?php echo $result->profile_pic; ?>" style="height: 90px;" id="profilePicHold"/>

<!--            </form>-->


        </div>
        <div class="informationTextRight text-center col-lg-12" style="">
            <?php
            {
                ?>
                <h3>
                    Welcome
                    <?php
                    echo ($result->company);
                    ?>!
                </h3>
                <?php
            }
            ?>

        </div>
    </div>
</div>
<div class="container mainContainer" style="position: relative; width: 100%; ">
<!--<div class="container mainContainer" style="position: relative;">-->
<div class="wid-80">
    <div class="leftmajor col-lg-12 col-md-12 col-md-12 clearfix">
        <?php
        if(isset($_SESSION['uid'])){
            $user = $this->db->query("SELECT * FROM users WHERE ID='".$_SESSION['uid']."'")->row();
            if(isset($user)){
                if(isset($user->created_at)){
                    $today = date_create(date('Y-m-d'));
                    $created = date_create(substr($user->created_at,0,10));
                    $diff = date_diff($created,$today);
                    $difference_date = $diff->days;
                }
            }
        }
        ?>
        <div class="barOfInfo col-md-12  col-sm-12 col-x-12 clearfix <?php if($difference_date < 14) {?> col-lg-2 col-lg-offset-1 <?php } else { ?>col-lg-3 <?php }?>">
            <div class="wideBar first clearfix">
                <div class="inner clearfix" style="display: flex; align-items: center;">
                    <div class="textMain col-lg-8  col-md-8 col-sm-6 col-xs-6 text-center verticle-middle">
                        <h3>ASINs currently out of stock by Amazon</h3>
                    </div>
                    <div class="numberMain col-lg-4 col-md-4 col-sm-6 col-xs-6" >
                        <?php
                        $user_id = $this->session->userdata('user_id');
                        $query = $this->db->query("SELECT * FROM `amaz_aug` where tracking='1' and amznotseller = '2' AND user_id = '" . $user_id . "' ");
                        {
                            ?>
                            <h3>
                                <?php echo $query->num_rows(); ?>
                            </h3>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="barOfInfo col-md-12 <?php if($difference_date < 14) {?> col-lg-2  <?php } else { ?>col-lg-3  <?php }?>  col-sm-12 col-x-12 clearfix ">
            <div class="wideBar first clearfix" >
                <div class="inner clearfix" style="display: flex; align-items: center;">
                    <div class="textMain col-lg-8  col-md-8 col-sm-6 col-xs-6 text-center verticle-middle">
                        <h3>Items currently tracking for out of stock notifications</h3>
                    </div>
                    <div class="numberMain col-lg-4 col-md-4 col-sm-6 col-xs-6" >
                        <?php
                        $user_id = $this->session->userdata('user_id');
                        $query = $this->db->query("SELECT * FROM `amaz_aug` where tracking = 1 AND user_id = '" . $user_id . "' ");

                        {
                            ?>
                            <h3 id="stockNotificationDiv"><?php echo $query->num_rows(); ?></h3>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="barOfInfo col-md-12 <?php if($difference_date < 14) {?> col-lg-2  <?php } else { ?>col-lg-3 display-none <?php }?> col-sm-12 col-x-12 clearfix">
            <div class="wideBar first clearfix">
                <div class="inner clearfix" style="display: flex; align-items: center;">
                    <div class="textMain col-lg-8  col-md-8 col-sm-6 col-xs-6 text-center verticle-middle">
                        <h3>Days left on your trial</h3>
                    </div>
                    <div class="numberMain col-lg-4 col-md-4 col-sm-6 col-xs-6" >
                        <h3>
                           <?php   if($difference_date < 14) {$remain_date = 14- $difference_date; echo $remain_date;} ?>
                        </h3>

                    </div>
                </div>
            </div>
        </div>
        <div class="barOfInfo col-md-12 <?php if($difference_date < 14) {?> col-lg-2  <?php } else { ?>col-lg-3 <?php }?> col-sm-12 col-x-12 clearfix">
            <div class="wideBar first clearfix">
                <div class="inner clearfix" style="display: flex; align-items: center;">
                    <div class="textMain col-lg-8  col-md-8 col-sm-6 col-xs-6 text-center verticle-middle">
                        <h3>Items currently tracking for back in stock notifications</h3>
                    </div>
                    <div class="numberMain col-lg-4 col-md-4 col-sm-6 col-xs-6" >
                        <?php
                        $user_id = $this->session->userdata('user_id');
                        $query = $this->db->query("SELECT * FROM `amaz_aug` where (stock_noti='true' or stock_noti=1) and user_id = '" . $user_id . "'");
                        {
                            ?>
                            <h3 id="backStockNotificationsDiv">
                                <?php echo $query->num_rows(); ?>
                            </h3>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="barOfInfo col-md-12  <?php if($difference_date < 14) {?> col-lg-2  <?php } else { ?>col-lg-3 <?php }?>  col-sm-12 col-x-12 clearfix">
            <div class="wideBar first clearfix">
                <div class="inner clearfix" style="display: flex; align-items: center;">
                    <div class="textMain col-lg-8  col-md-8 col-sm-6 col-xs-6 text-center verticle-middle">
                        <h3>Items in your list</h3>
                    </div>
                    <div class="numberMain col-lg-4 col-md-4 col-sm-6 col-xs-6" >
                        <?php
                        $user_id = $this->session->userdata('user_id');
                        $query = $this->db->query("SELECT * FROM `amaz_aug` where user_id = '" . $user_id . "'");
                        {
                            ?>
                            <h3>
                                <?php echo $query->num_rows(); ?>
                            </h3>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

    </div> 
    <div class="leftmajor col-lg-12 col-md-12 col-md-12 clearfix">
        <?php if (isset($subscription_expired) && $subscription_expired) { ?>
            <div class="alert alert-warning text-center lead" role="alert">
            Your free trial has ended. Notifications and status updates have been disabled. Please <a href="<?php echo site_url('settings/membership_account')?>">choose</a> a plan to continue tracking ASINS.
        </div>
        <?php } ?>
        <div class="topSearchAsins cont col-lg-12" style="float: none !important; padding-right: 0px !important;padding-left: 0px !important;">
            <div class="topBox text-left" style="padding-left: 20px;">
                <h3>What ASIN would you like to track?</h3>
            </div>
            <div class="bottomContent">
                <div class="formTop clearfix">
                    
                    <form action="<?php echo site_url('Dashboard')?>" method="post" enctype="multipart/form-data" id="asins-search-form"> 
                        <input type="button" name="submit" value="search_value" id="asinsSubmitButton" style="display: none;" >

                        <div class="" >
                            <div class="asinInput inputType col-lg-7 col-md-7 col-sm-7 col-xs-7" style="margin-bottom: 0px;">
                                <input  type="text" placeholder="Enter ASIN, URL or drag to upload a bulk file" name="asin"
                                    style="border-top-right-radius: 0px;border-bottom-right-radius: 0px;" id="asinName"/>
                            </div>
                            
                            <div class="inputType col-lg-5 col-md-5 col-sm-5 col-xs-5 buttons" style="margin-bottom: 0px;">
                                <!--<button class='btn btn-embossed btn-primary btn-wide' id="asinsBulkActionButton">
                                    <i class="fa fa-upload"  aria-hidden="true"></i>
                                </button>-->
                                <div class="inputType col-lg-4 col-md-5 col-sm-6 col-xs-6 buttons" style="margin-bottom: 0px;">
                                    <input type='button' class='btn btn-embossed btn-primary btn-wide' value='Search' id="asinsSearchButton"/>
                                </div>

                                <!-- <div class="inputType col-lg-4 col-md-5 col-sm-6 col-xs-6 buttons" style="margin-bottom: 0px;">
                                    <form method="post" action="<?php echo site_url('Dashboard')?>" enctype="multipart/form-data" id="bulk_upload_form">
                                        <input type='button' class='btn btn-embossed btn-primary btn-wide' value='Choose File' onClick="clickFileUpload()"/>
                                        <input type="file" id="bulk_upload_file" name="file_upload" style="display: none;">
                                        <input type="hidden" name="bulk_upload_message" value="<?php echo isset($message) ? $message : '' ?>">
                                        <input type="hidden" name="bulk_upload_message_type" value="<?php echo isset($message_type) ? $message_type : '' ?>">
                                    </form>
                                </div>

                                <div class="inputType col-lg-4 col-md-5 col-sm-6 col-xs-6 buttons" style="margin-bottom: 0px;">
                                    <input type='button' id="bulk_upload_button" class='btn btn-embossed btn-primary btn-wide' value='Upload' disabled />
                                </div> -->

                                <!--div class="inputType col-lg-4 col-md-5 col-sm-6 col-xs-6 buttons" style="margin-bottom: 0px;">
                                    <input type='button'   class='btn btn-embossed btn-primary btn-wide' value='Search' id="asinsSearchButton"/>
                                </div-->
                                <style>
                                    button, input[type="submit"]{
                                        outline: none !important;
                                    }
                                </style>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="leftmajor col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
        <div class="barOfInfo clearfix col-lg-10" style="float: none !important;margin:0% auto;padding-right: 0px !important;padding-left: 0px !important; display: none;">
            <div class="wideBar first clearfix col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px;">
                <div class="inner clearfix">
                    <div class="textMain col-lg-10 text-center verticle-middle">
                        <h3>ASINs currently out of stock by Amazon</h3>
                    </div>
                    <div class="numberMain col-lg-2" >
                        <?php
                        $user_id = $this->session->userdata('user_id');
			//$query = $this->db->query("SELECT * FROM `amaz_aug` where amznotseller = '1' AND user_id = '" . $user_id . "' ");
			$query = $this->db->query("SELECT * FROM `amaz_aug` where tracking='1' and amznotseller = '2' AND user_id = '" . $user_id . "' ");
                        {
                            ?>
                            <h3>
                                <?php echo $query->num_rows(); ?>
                            </h3>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <div class="wideBar second clearfix col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px;">
                <div class='inner clearfix'>
                    <div class="textMain col-lg-10 text-center verticle-middle">
                        <h3>Sales in the past 30 days</h3>
                    </div>
                    <div class="numberMain col-lg-2">
                        <h3>$0</h3>
                    </div>
                </div>
            </div>
            <div>
                <div class="wideBar first clearfix col-lg-6 col-md-6 col-sm-6 col-xs-6"
                     style="padding-left: 0px;margin-top: 20px;">
                    <div class="inner clearfix">
                        <div class="textMain col-lg-10 text-center verticle-middle">
                            <h3 style="line-height: 1.4;">Items currently tracking for out of stock notifications</h3>
                        </div>
                        <div class="numberMain col-lg-2" style="padding: 28px;">
                            <?php
                            $user_id = $this->session->userdata('user_id');
                            $query = $this->db->query("SELECT * FROM `amaz_aug` where tracking = 1
             AND user_id = '" . $user_id . "' ");

                            {
                                ?>
                                <h3><?php echo $query->num_rows(); ?></h3>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <br/>
                <div class="wideBar second clearfix col-lg-6 col-md-6 col-sm-6 col-xs-6"
                     style="padding-right: 0px;margin-top: 20px;">
                    <div class='inner clearfix'>
                        <div class="textMain col-lg-10 text-center verticle-middle">
                            <h3 style="line-height: 1.4;">Items currently tracking for back in stock notifications</h3>
                        </div>
                        <div class="numberMain col-lg-2" style="padding: 28px;">
                            <?php
                            $user_id = $this->session->userdata('user_id');
                            $query = $this->db->query("SELECT * FROM `amaz_aug` where (stock_noti='true' or stock_noti=1) and user_id = '" . $user_id . "'");
                            {
                                ?>
                                <h3>
                                    <?php echo $query->num_rows(); ?>
                                </h3>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="wideBar first clearfix col-lg-6 col-md-6 col-sm-6 col-xs-6"
                     style="padding-left: 0px;margin-top: 20px;">
                    <div class="inner clearfix">
                        <div class="textMain col-lg-10 text-center verticle-middle">
                            <h3>Days left on your trial</h3>
                        </div>
                        <div class="numberMain col-lg-2">
                            <h3>25</h3>
                        </div>
                    </div>
                </div>
                <div class="wideBar second clearfix col-lg-6 col-md-6 col-sm-6 col-xs-6"
                     style="padding-right: 0px;margin-top: 20px;">
                    <div class='inner clearfix'>
                        <div class="textMain col-lg-10 text-center verticle-middle">
                            <h3>Items in your list</h3>
                        </div>
                        <div class="numberMain col-lg-2">
                            <?php
                            $user_id = $this->session->userdata('user_id');
                            $query = $this->db->query("SELECT * FROM `amaz_aug` where user_id = '" . $user_id . "'");
                            {
                                ?>
                                <h3>
                                    <?php echo $query->num_rows(); ?>
                                </h3>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
	</div>
	<?php if($user->global_noti != 'true'){ ?>
            <div class="longBox red"><p class="text-center" style="color:red">Global notifications are shut off. All Notifications are disabled. To update your global notification settings, please <a href="<?php echo site_url('settings/notification-settings'); ?>">click here</a>.</p></div>
        <?php } ?>
        <div class="rightSide hidden-lg hidden-md hidden-sm hidden-xs" style="margin-bottom: 15px;padding-right: 0px !important;">
            <div class="longBox trial-left">
                <div class="topNumber">
                    <h3 style="font-size: 1.5em;font-weight: 400;">Summary</h3>
                </div>
            </div>
            <br/>
            <div class="longBox trial-left">
                <div class="topNumber">
                    <h3>25</h3>
                </div>
                <div class="bottomContent">
                    <p class="text-center">Days left on your trial</p>
                </div>
            </div>

            <br/>
            <div class="longBox trial-left">
                <div class="topNumber">
                    <?php
                    $user_id = $this->session->userdata('user_id');
                    $query = $this->db->query("SELECT * FROM `amaz_aug` where tracking = 1 AND user_id = '" . $user_id . "' ");

                    {
                        ?>
                        <h3><?php echo $query->num_rows(); ?></h3>
                    <?php } ?>
                </div>
                <div class="bottomContent">
                    <p class="text-center">Items currently tracking for out of stock notifications</p>
                </div>
            </div>
            <br/>

            <div class="longBox trial-left">
                <div class="topNumber">
                    <?php
                    $user_id = $this->session->userdata('user_id');
                    $query = $this->db->query("SELECT * FROM `amaz_aug` where (stock_noti='true' or stock_noti=1) and user_id = '" . $user_id . "'");
                    {
                        ?>
                        <h3>
                            <?php echo $query->num_rows(); ?>
                        </h3>
                    <?php } ?>
                </div>
                <div class="bottomContent">
                    <p class="text-center">Items currently tracking for back in stock notifications</p>
                </div>
            </div>
        </div>
        
        <br/>
        <!--insert form-->
        <?php //if ($chkdata == 1) { ?>
            <div id="confirmAsinDiv" class="topSearchAsins cont" style="display:none">
                

            </div><br/>
        <?php //} ?>
        <div class="topSearchAsins cont" style="">
            <div class="topBox text-left">
                <h3 class="item-list">Items List</h3>
<!--                <div class="search-right-corner">-->
<!--                    <label>Search:<input type="search" class="form-control input-sm" placeholder="" aria-controls="DataTables_Table_0" id="bookSearch"></label>-->
<!--                </div>-->
            </div>
            <div class="bottomContent" id="dashboard_dtable">
                <div class="listHolder">
                    <!-- <form action="" method="post" enctype="multipart/form-data"> -->
                    <div class="table-responsive" id="datata">
                    <!--table 
                        class="mainTable table table-striped table-bordered table-hover individual-item-report dataTable main-table"
			id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info" style="width:100%" data-order="[]"-->
		    <table
                        class="mainTable table table-striped table-bordered table-hover individual-item-report main-table"
                        id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info" style="width:100%" data-order="[]">
                        <thead>
                        <tr role="row" style='margin-top: 15px;'>
                            <th class="text-center a verticle-middle sorting_disabled" data-orderable="false"
                                rowspan="1" colspan="1" aria-label="Image" style="width: 53px;">
                                <div>Image</div>
                            </th>
                            <th class="text-center a t verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Title: activate to sort column ascending" style="width: 200px;">
                                Item Title
                            </th>
                            <th class="text-center a verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="ASIN: activate to sort column ascending" style="width: 110px">
                                ASIN
                            </th>
                            <th class="text-center a t-responsive verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Are you in stock?
                            </th>
                            <th class="text-center a t-responsive verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Is Amazon in stock
                            </th>
                            <th class="text-center a t-responsive  verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Back In Stock Tracking
                            </th>
                            <th class="text-center a t-responsive  verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Out of Stock Tracking
                            </th>
                            
                            <th class="text-center a t-responsive  verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                Email Notification
                            </th>
                            <th class="text-center a t-responsive  verticle-middle sorting" tabindex="0"
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                SMS Notification
                            </th>

                            <th class="text-center a verticle-middle sorting menuListOpen dropbox "
                                aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                aria-label="Report: activate to sort column ascending" >
                                <div class="dropdown-toggle" data-toggle="dropdown">
                                    Bulk Action<br/>
                                    <span class="car" id="bulkActionCar">
                                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <ul class="dropdown-menu dropdown-menu-right drop">
                                
                                    <li><a href="javascript:void(0)" onclick="onSelectAll()">Select All/Deselect All</a></li>
                                
                                    
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('stock_on')">Turn Out of Stock Tracking On</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('stock_off')">Turn Out of Stock Tracking Off</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('back_stock_on')">Turn Back in Stock Tracking On</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('back_stock_off')">Turn Back in Stock Tracking Off</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('email_on')">Turn Email Notifications On</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('email_off')">Turn Email Notifications Off</a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('sms_on')">Turn SMS Notifications On </a></li>
                                    <li><a href="javascript:void(0)" onclick="onChangeTurnOnOff('sms_off')">Turn SMS Notifications Off</a></li>
                                    
<!--                                    <li><a href="javascript:void(0)" style="text-align:center" data-toggle="modal" data-target="#deleteAsinsModal">Delete</a></li>-->
                                    
                                    <li style="text-align:center;">
                                        <button style=" background: none;border: none;font-weight: 200; padding: 3px 20px;" name="delete" data-toggle="modal" data-target="#deleteAsinsModal">Delete</button>
                                    </li>
                                    
                                            <!--                                    <li></li>-->
                                    <!-- <form action="" method="post" enctype="multipart/form-data"> -->
                                    <!-- <button type="submit" class="btn" name="delete">Delete</button> -->
<!--                                    <center>-->
<!--                                        <li>-->
<!--                                            <button style=" background: none;border: none;font-weight: 200;" name="delete" onclick="toggle()">Delete-->
<!--                                            </button>-->
<!--                                        </li>-->
<!--                                    </center>-->
                                    <!-- </form> -->
                                </ul>
                            </th>
                        </tr>
                        </thead>

                        <tbody id="dashboardTbody">
                        <!-- <form action="" method="post" enctype="multipart/form-data"> -->
                        <?php

                        $user_id = $this->session->userdata('user_id');
                        /*$query = $this->db->query("SELECT * FROM amaz_aug WHERE `user_id`='$user_id' group by asin order by status ASC ")->result();*/
                        $query = $this->db->query("SELECT * FROM amaz_aug ORDER BY tracking DESC, amznotseller DESC , sellerstock ASC ")->result();
                       //echo '<pre>'; print_r($query);echo '</pre>';
                    //    foreach ($query as $query) {
                       
                    //     //print_r($query);
                    //     echo "asins--> $query->asin";
                    //     echo " sellersstock-->$query->sellerstock";
                    //     echo " amazonDtock-->$query->amazonstock<br>";
                    //     $are_u_In_stock = "";
                    //     $amz_in_stock = "";
                    //     if($query->sellerstock == '1'  && $query->amazonstock == '1'){
                    //         $are_u_In_stock = "Yes";
                    //         $amz_in_stock = "Yes";
                    //     }elseif($query->sellerstock == '1'  && $query->amazonstock == '0'){
                    //         $are_u_In_stock = "Yes!";//green
                    //         $amz_in_stock = "No";
                    //     }elseif($query->sellerstock == '0'  && $query->amazonstock == '1'){
                    //         $are_u_In_stock = "No";
                    //         $amz_in_stock = "Yes";
                    //     }elseif($query->sellerstock == '0'  && $query->amazonstock == '0'){
                    //         $are_u_In_stock = "No!"; //red
                    //         $amz_in_stock = "No";
                    //     }
                    //    }
                    
                    $are_u_In_stock = "";
                    $amz_in_stock = "";
                    $are_u_In_stock_color = "color:black;";
                    $amz_in_stock_color = "color:black;";
                        foreach ($query as $query) {
                          
                         
                            ?>
                            <tr role="row" class="odd scrape-row">
                                <!--start IMAGE-->
                                <td class="text-center vertical-middle star-wrapper" style="position: relative">

                                    <?php
                                    if ($query->tracking == 1 || $query->stock_noti == 'true' || $query->stock_noti == 1) {
                                        if (($query->amznotseller == "2") && ($query->sellerstock == "1")) {
                                            ?>
                                            <!--                                        <span style="color:green; font-size:20px" class="product-star"><i class="fa fa-circle" aria-hidden="true"></i></span>-->
                                            <div class="green-right-triangle"></div>
                                        <?php } else {
                                            if (($query->amznotseller == "2") && ($query->sellerstock == "0")) { ?>
                                                <!--                                        <span style="color:red; font-size:20px" class="product-star"><i class="fa fa-circle" aria-hidden="true"></i></span>-->
                                                <div class="red-right-triangle"></div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <?php if($query->image != ''){ ?>
                                    <a href="<?php echo $query->image; ?>" data-fancybox="images" data-caption="<?php echo $query->title_name; ?>" class="fancybox">
                                        <?php echo '<img src="' . $query->image . '" class ="img-thumbnail"  style="height:70px;border:0px"/>' ?>
                                    </a>
                                    <?php } ?>
                                </td>
                                <!--END IMAGE-->
                                <!--start TITLE NAME-->
                                <td class="text-center vertical-middle" title='<?php echo $query->title_name; ?>'>
                                    <a style="" target="_blank"
                                       href="http://amazon.com/dp/<?php echo $query->asin; ?>"><?php echo $query->title_name; ?></a>
                                </td>
                                <!--END TITLE NAME-->
                                <!--start ASIN-->
                                <td class="text-center vertical-middle">
                                    <a style="" target="_blank"
                                       href="http://amazon.com/dp/<?php echo $query->asin; ?>"><?php echo $query->asin; ?></a>
                                </td>
                                <!--END ASIN-->
                                <?php if ($query->stock_noti != 1 && $query->stock_noti != "true" && $query->tracking != "1") { ?>
                                    <!--start SELLERSTOCK-->
                                    <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="stock_label_<?php echo $query->id; ?>">Turn tracking on<br> to see stock status</span>
                                    </td>
                                    <!--END SELLERSTOCK-->

                                    <!--start AMZNOTSELLER-->
                                    <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="amznotseller_label_<?php echo $query->id; ?>">Turn tracking on<br> to see stock status</span>
                                    </td>
                                    <!--END AMZNOTSELLER-->
                                <?php } else if (($query->stock_noti == 1 || $query->stock_noti == "true") && $query->tracking == "1" && $query->status == 6 ) { ?>
                                    <!--start SELLERSTOCK-->
                                    <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="stock_label_<?php echo $query->id; ?>">Item no longer exists <br>on Amazon</span>
                                    </td>
                                    <!--END SELLERSTOCK-->

                                    <!--start AMZNOTSELLER-->
                                    <td class="text-center b red verticle-middle">
                                        <span style="color:#aaa; font-size:14px;" id="amznotseller_label_<?php echo $query->id; ?>">Item no longer exists<br> on Amazon</span>
                                    </td>
                                    <!--END AMZNOTSELLER-->
                                <?php } else { ?>
                                    <!--start SELLERSTOCK-->
                                    <?php if (is_null($query->sellerstock) || $query->sellerstock == '') { ?>
                                        <td class="text-center b red verticle-middle">
                                            <span style="color:#aaa; font-size:14px;" id="stock_label_<?php echo $query->id; ?>">Being processed! <br> Will be updated soon</span>
                                        </td>
                                    <?php } else  if($query->sellerstock == '1'  && $query->amazonstock == '1'){
                                                    $are_u_In_stock = "Yes";
                                                    $amz_in_stock = "Yes";
                                                    $are_u_In_stock_color = "color:black;";
                                                    $amz_in_stock_color = "color:black;";
                                                    
                                                }elseif($query->sellerstock == '1'  && $query->amazonstock == '0'){
                                                    $are_u_In_stock = "Yes!";//green
                                                    $amz_in_stock = "No";
                                                    $are_u_In_stock_color = "color:green;";
                                                    $amz_in_stock_color = "color:black;";
                                                }elseif($query->sellerstock == '0'  && $query->amazonstock == '1'){
                                                    $are_u_In_stock = "No";
                                                    $amz_in_stock = "Yes";
                                                    $are_u_In_stock_color = "color:black;";
                                                    $amz_in_stock_color = "color:black;";
                                                }elseif($query->sellerstock == '0'  && $query->amazonstock == '0'){
                                                    $are_u_In_stock = "No!";//red
                                                    $amz_in_stock = "No";
                                                    $are_u_In_stock_color = "color:red;";
                                                    $amz_in_stock_color = "color:black;";
                                                }
                                    ?>

                                    
                                                <td class="text-center b red verticle-middle">
                                                <span style="<?php echo $are_u_In_stock_color; ?> font-size:25px;" id="stock_label_<?php echo $query->id; ?>"><?php echo  $are_u_In_stock; ?></span>
                                                </td>
                                                <td class="text-center b red verticle-middle">
                                                <span style="<?php echo $amz_in_stock_color; ?> font-size:25px;" id="amznotseller_label_<?php echo $query->id; ?>"><?php echo $amz_in_stock; ?></span>
                                                </td>

                                                                <!-- <?
                                    
                                    // if (($query->sellerstock == "1")) {
                                    //     if (($query->amazonstock == "0")) { ?>
                                            <td class="text-center b red verticle-middle">
                                                <span style="color:green; font-size:25px;" id="stock_label_<?php //echo $query->id; ?>">Yes!</span>
                                            </td>
                                        <?php //} else { ?>
                                            <td class="text-center b red verticle-middle">
						
						 <span style="color:black; font-size:25px;" id="stock_label_<?php //echo $query->id; ?>">Yes</span>
                                            </td> -->
                                        <?php /*}
                                    } else if (($query->sellerstock == "0")) {
                                        if (($query->amznotseller == "0")) { ?>
                                            <td class="text-center b red verticle-middle">
                                                <span style="color:red; font-size:25px;" id="stock_label_<?php echo $query->id; ?>">No!</span>
                                            </td>
                                        <?php } else { ?>
                                            <td class="text-center b red verticle-middle">
                                                <span style="color:black; font-size:25px;" id="stock_label_<?php echo $query->id; ?>">No</span>
                                            </td>
                                        <?php }
                                    } */?> 
                                    <!--END SELLERSTOCK-->

                                    <!--start AMZNOTSELLER-->
					<?php //if (($query->amznotseller == "1")) {
					// if (($query->amznotseller  == "1")) {
					// 	if (($query->sellerstock == "1")) {
					 ?>
                                        <!-- <td class="text-center b red verticle-middle">
                                            <span style="color:green; font-size:25px;" id="amznotseller_label_<?php echo $query->id; ?>">Yes!</span>
                                        </td> -->
                                    <?php //}else{
				    ?>
					 <!-- <td class="text-center b red verticle-middle">
                                                <span style="color:black; font-size:25px;" id="amznotseller_label_<?php //echo $query->id; ?>">Yes</span>
                                            </td> -->
					<?php //}} ?>	
				    <?php //if (($query->amznotseller == "0")) {
				    	// if (($query->amznotseller == "0")) {
					    // if (($query->sellerstock == "1")) {
					?>
					<!-- <td class="text-center b red verticle-middle">
                                                <span style="color:black; font-size:25px;" id="amznotseller_label_<?php //echo $query->id; ?>">No</span>
					    </td> -->
					 <?php //}else{
                                    	?>
                                        <!-- <td class="text-center b red verticle-middle">
                                            <span style="color:black; font-size:25px;" id="amznotseller_label_<?php //echo $query->id; ?>">No</span>
                                        </td> -->
                                    <?php //}} ?>
                                    <?php //if (is_null($query->sellerstock) || $query->sellerstock == '') { ?>
                                        <!-- <td class="text-center b red verticle-middle">
                                            <span style="color:#aaa; font-size:14px;" id="amznotseller_label_<?php //echo $query->id; ?>">Being processed! <br> Will be updated soon</span>
                                        </td> -->
                                    <?php //} ?>
                                    <!--END AMZNOTSELLER-->

                                <?php } ?>
                                <!--start STOCK NOTIFICATIION-->
                                <td class="vertical-middle cb text-center">
                                    <?php if ($query->stock_noti == "true" || $query->stock_noti == 1) { ?>

                                        <label class="switch">
                                    <input   type="checkbox" data-role="flipswitch"
                                                   onclick="stockcheck(<?php echo $query->id; ?>, this)"
                                                   name="switch<?php echo $query->id; ?>"
                                                   id="switchstock<?php echo $query->id; ?>"
                                                   value="switch<?php echo $query->id; ?>">
                                            <div class="slider round"></div>
                                        </label>


                                    <?php } else { ?>

                                        <label class="switch">
                                            <input   type="checkbox" data-role="flipswitch"
                                                   onclick="stockcheck(<?php echo $query->id; ?>, this)"
                                                   name="switch<?php echo $query->id; ?>"
                                                   id="switchstock<?php echo $query->id; ?>"
                                                   value="switch<?php echo $query->id; ?>">
                                            <div class="slider round"></div>
                                        </label>

                                    <?php } ?>
                                </td>
                                <!--END STOCK NOTIFICATIION-->
                                <!--start TRACKING-->
                                <td class="vertical-middle cb text-center">
                                    <?php if ($query->tracking == "1") { ?>

                                        <label class="switch">
                                            <input  type="checkbox" data-role="flipswitch"
                                                   onclick="chackUncheck(<?php echo $query->id; ?>, this)"
                                                   name="switch<?php echo $query->id; ?>"
                                                   id="switch<?php echo $query->id; ?>" value="true" checked>
                                            <div class="slider round"></div>
                                        </label>


                                    <?php } else { ?>

                                        <label class="switch">
                                            <input  type="checkbox" data-role="flipswitch"
                                                   onclick="chackUncheck(<?php echo $query->id; ?>, this)"
                                                   name="switch<?php echo $query->id; ?>"
                                                   id="switch<?php echo $query->id; ?>"
                                                   value="switch<?php echo $query->id; ?>">
                                            <div class="slider round"></div>
                                        </label>

                                    <?php } ?>
                                </td>
                                <!--END TRACKING-->
                                
                                
                                <!--start EMAIL NOTIFICATIION-->
                                <td class="vertical-middle cb text-center">
                                    <?php if($query->stock_noti != 1  && $query->stock_noti != "true" && $query->tracking != "1"){ ?>
                                        <label class="switch">
                                            <input  type="checkbox" data-role="flipswitch"
                                                    onclick="emailcheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchid<?php echo $query->id; ?>"
                                                    value="switchEmail<?php echo $query->id; ?>" disabled>
                                            <div class="slider round"></div>
                                        </label>
                                    <?php } else { ?>
                                        <?php if ($query->email_noti == "true") { ?>
                                        <label class="switch">
                                            <input  type="checkbox" data-role="flipswitch"
                                                    onclick="emailcheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchid<?php echo $query->id; ?>"
                                                    value="switchEmail<?php echo $query->id; ?>" checked>
                                            <div class="slider round"></div>
                                        </label>


                                        <?php } else { ?>

                                        <label class="switch">
                                            <input  type="checkbox" data-role="flipswitch"
                                                    onclick="emailcheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchid<?php echo $query->id; ?>"
                                                    value="switchEmail<?php echo $query->id; ?>">
                                            <div class="slider round"></div>
                                        </label>

                                        <?php } ?>
                                    <?php } ?>
                                </td>
                                <!--END EMAIL NOTIFICATIION-->
                                <!--start PHONE NOTIFICATIION-->
                                <td class="vertical-middle cb text-center">
                                    <?php if($query->stock_noti != 1 && $query->stock_noti != "true" && $query->tracking != "1"){ ?>
                                        <label class="switch">
                                                <input  type="checkbox" data-role="flipswitch"
                                                    onclick="phonecheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchphone<?php echo $query->id; ?>"
                                                    value="switch<?php echo $query->id; ?>" disabled>
                                                <div class="slider round"></div>
                                            </label>
                                    <?php } else { ?>
                                        <?php if ($query->phone_noti == "true") { ?>

                                            <label class="switch">
                                                <input  type="checkbox" data-role="flipswitch"
                                                    onclick="phonecheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchphone<?php echo $query->id; ?>"
                                                    value="switch<?php echo $query->id; ?>" checked>
                                                <div class="slider round"></div>
                                            </label>


                                        <?php } else { ?>

                                            <label class="switch">
                                                <input  type="checkbox" data-role="flipswitch"
                                                    onclick="phonecheck(<?php echo $query->id; ?>)"
                                                    name="switch<?php echo $query->id; ?>"
                                                    id="switchphone<?php echo $query->id; ?>"
                                                    value="switch<?php echo $query->id; ?>">
                                                <div class="slider round"></div>
                                            </label>

                                        <?php } ?>
                                    <?php } ?>
                                    <!--END PHONE NOTIFICATIION-->
                                    <!--start BULK ACTION-->
                                <td class="text-center c-hold verticle-middle" id="checkes">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type='checkbox' value="<?php echo $query->id; ?>" name="checkbulk1[]"
                                               class="check"/>
                                        <label for='checkbox1' data-for="checkbox1" class='cb-label'></label>
                                    </form>
                                </td>
                                <!--END BULK ACTION-->
                            </tr>


                        <?php  }?>
                        <!--  </form> -->
                        </tbody>

                    </table>
                    </div>
                    <!-- </form> -->
                    <?php
                    /*$user_id=$this->session->userdata('user_id');
                    $query  = $this->db->query("SELECT * FROM `amaz_aug` where user_id = '".$user_id."' ");
                    $num = $query->num_rows();

                    if(3 <= $num || '3' <= $num)
                    {
                    ?>
                  <div class="loadMoreHolder text-center" style="padding-top: 15px;">
                      <button class="btn btn-wide btn-embossed btn-primary" style='background: #b65f2b'><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                  </div>
                  <?php }*/

                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="rightSide col-lg-2 hidden-sm hidden-xs" style="display: none;">
        <div class="longBox trial-left">
            <div class="topNumber">
                <h3 style="font-size: 1.5em;font-weight: 400;">Summary</h3>
            </div>
        </div>
        <br/>
        <div class="longBox trial-left">
            <div class="topNumber">
                <h3>25</h3>
            </div>
            <div class="bottomContent">
                <p class="text-center">Days left on your trial</p>
            </div>
        </div>
        <br/>
    </div>
</div>
<br/><br/>
<!-- Delete item  modal start -->
<div class="modal face" id="deleteAsinsModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete Asins</h4>
            </div>
            <div class="modal-body">
                <p style="padding-bottom:10px;">Are you sure you want to delete the selected items from your list? </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-embossed btn-success primarycolorbtn" id="deleteAsinsConfirmButton">Yes I am sure</button>
                <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal">No go back</button>
            </div>
        </div>
    </div>
</div>
<!-- Sign Up  modal start -->
<div class="modal face" id="sign_up" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Instructions</h4>
            </div>
            <div class="modal-body">
                <p style="padding-bottom:10px;">Thank you for singing up! </p><br>
                <p style="padding-bottom:10px;">In order to start tracking when Amazon runs in or out of stock on any particular item, please enter an ASIN or product URL in the box below. Or drag and drop a .csv file for bulk upload. Then press 'Search'. </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-embossed btn-success primarycolorbtn" data-dismiss="modal">Go</button>
            </div>
        </div>
    </div>
</div>
<!-- <div id="ajax-modal" class="modal" tabindex="-1" role="dialog" data-backdrop="static"></div> -->
<script language="JavaScript">
    function toggle() {

        var ids = "";
        var divid = 'checkes';
        var checks = document.querySelectorAll('#' + divid + ' input[type="checkbox"]');
        console.log(checks);
        for (var i = 0; i < checks.length; i++) {
            var check = checks[i].checked;

            if (check == false) {

            } else {
                ids += checks[i].defaultValue + ",";
            }
        }
        var url_link = '<?php echo base_url(); ?>Dashboard/delete_checkbox/';
        $.ajax({
            url: url_link,
            data: "ids=" + ids,
            method: 'POST',
            success: function (res) {
                window.location.reload(true);
            }
        })
    }
    
</script>

<!--SCRIPT IS USED TO EMAIL PHONE AND STOCK-->
<script>
    /*$(function () {
     $("#example1").DataTable();
     $('#example2').DataTable({
     "paging": false,
     "lengthChange": false,
     "searching": false,
     "ordering": false,
     "info": false,
     "autoWidth": false
     });
     });

     $(document).ready(function() {
     $('#example1').DataTable( {
     "paging":   false,
     "ordering": false,
     "info":     false
     } );
     } );



     function show_user(user_id){
     jQuery.ajax({
     type:'POST',
     url:'Dashboard.php',
     data:'method=1&user_id='+user_id,
     success:function(res){
     var jsonData = JSON.parse(res);
     // alert(res);
     console.log(jsonData);

     document.getElementById('user_id').value =jsonData.show_user.user_id;
     document.getElementById('img').value =jsonData.show_user.img1;
     document.getElementById('title_name').value =jsonData.show_user.title_name1;
     document.getElementById('asin').value =jsonData.show_user.asin1;
     document.getElementById('amznotseller').src =jsonData.show_user.amznotseller1;
     document.getElementById('stock_url').value =jsonData.show_user.stock_url1;
     document.getElementById('sellerstock').value =jsonData.show_user.sellerstock1;
     document.getElementById('rating').value =jsonData.show_user.rating1;
     document.getElementById('reviews').value =jsonData.show_user.reviews1;
     document.getElementById('seller_name').value =jsonData.show_user.seller_name1;
     document.getElementById('seller_url').value =jsonData.show_user.seller_url1;
     document.getElementById('seller_ids').value =jsonData.show_user.seller_ids1;
     document.getElementById('price').value =jsonData.show_user.price1;
     document.getElementById('shipping').value =jsonData.show_user.shipping1;



     }
     });
     }

     */</script>
</body>
</html>

