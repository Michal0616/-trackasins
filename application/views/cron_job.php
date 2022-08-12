<?php
require("simple_html_dom.php");
date_default_timezone_set('America/New_York');

ini_set('max_execution_time', 9999999);
ini_set('memory_limit', '9999M');
error_reporting(0);

$user_id=$this->session->userdata('user_id');
$date = date(" H:i:s d-m-Y") ;
/*user_id*/  //print_r($user_id);
/*date*/      //print_r($d);
/*Curl FUNCTION*/
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
function readItem($asin) {

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
        array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
    }

    // Generate the canonical query
    $canonical_query_string = join("&", $pairs);

    // Generate the string to be signed
    $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

    // Generate the signature required by the Product Advertising API
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));

    // Generate the signed URL
    $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

    // echo "Signed URL: \"".$request_url."\"";
    //echo$request_url;exit;
    $xmlString = curl_get_file_contents($request_url);
    $xml = simplexml_load_string($xmlString);

    $available = $xml->Items->Item->Offers->Offer->Merchant->Name;
    return $xml;
}

function getPage($url) { 
    $http_head = array("Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Language:en-US,en;q=0.8",
        "Connection:keep-alive",
        "Upgrade-Insecure-Requests:1",
        "User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // Target URL
    //curl_setopt($ch, CURLOPT_PROXY, '195.154.161.93:5883'); // Proxy IP:Port
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, FALSE);

    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $http_head);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function chk_satus($value){
            $asin = $value;          
            /*asin*/    // print_r($asin);
            $main_url="https://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new?ie=UTF8&condition=new";
            $xml = readItem($asin);
            $merchant = $xml->Items->Item->Offers->Offer->Merchant->Name;
            $html=getPage($main_url);
            $html=str_get_html($html);

                 if(!empty($html)){

                    foreach($html->find("div[id=olpOfferList] div[class=olpOffer]") as $elements){                
                                $image=$html->find("div[id=olpProductImage] img",0)->src; 
                                $title_name=$html->find("h1[class=a-size-large a-spacing-none]",0)->plaintext; 
                                $title_name=trim($title_name);
                                $rating=$html->find("i[class=a-icon-star]",0)->plaintext; 
                                $reviews=$html->find("span[class=a-size-small]",0)->plaintext; 
                                $seller_url=@$elements->find("div[class=olpSellerColumn] a",0)->href; 
                                $ex_sell=explode("seller=",$seller_url);
                                $seller_ids=trim(@$ex_sell[1]); 
                                $reviews=trim($reviews);
                                                    
                                $title_link=@$elements->find("h3[class=olpSellerName] a",0)->href;  
                                $seller_link='http://www.amazon.com'.$title_link;   
                                $seller_name=@$elements->find("h3[class=olpSellerName] a",0)->plaintext;
                                /*name take from here*/         //print_r($title_name);                             
                                /*image take from here*/        //print_r($image);    
                                if(empty($seller_name)){    
                                    $seller_name=@$elements->find("h3[class=olpSellerName] img",0)->alt; 
                                }   
                                $stock_url=@$elements->find("h3[class=olpSellerName] img",0)->alt; 

                                    if($stock_url=="Amazon.com"){
                                        return $amznotseller = "0";
                                        break;
                                    }else{
                                         return $amznotseller = "1";
                                        break;
                                     }
                    }

                }
                                $amount=$elements->find("span[class=olpOfferPrice]",0)->plaintext;  
                                 $price=filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);   
                                    
                                $ship=$elements->find("span[class=a-color-secondary]",0)->plaintext;    
                                $shipp=filter_var($ship, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); //$ships=trim($ship); //$shipp=str_replace('                      shipping','',$ships); //$shipping=str_replace('+ ','',$shipp);                             
                                $shipping=str_replace('+','',$shipp); 
                                //print_r($data);
                                return $data;
}  
/*GET DATA FROM HERE From AMAZ_AUG TABLE */
echo "123123";

$res = $this->db->query("SELECT * FROM amaz_aug where tracking=1 AND user_id=$user_id  ")->result();
    {   
    //print_r($res);  
                    foreach ($res as $r) { 
                       //print_r($r);                        
                        $result_amz_status=chk_satus($r->asin);
                        //print_r($result_amz_status);
                        //print_r($r->amznotseller);
                        if($r->amznotseller!=$result_amz_status)
                        {   
                            //print_r($r->amznotseller);
                            //print_r($result_amz_status);
                            if($r->amznotseller == 0 || $r->amznotseller == "0")
                            {
                                //echo " amzoutofstock1";
                                $amzoutofstock = 0;
                                
                            }
                            if($r->amznotseller == 1 || $r->amznotseller == "1")
                            {
                                //echo "amzoutofstock0";
                                $amzoutofstock = 1;
                            }
                                    //echo "string";exit;
                                     //print_r($r);    
                                    $user_id1       = $r->user_id;
                                    $asin1          = $r->asin;
                                    $image1         = $r->image;
                                    $amznotseller1  = $r->amznotseller;
                                    $title_name1    = $r->title_name;
                                    $tracking1      = $r->tracking;
                                    $sellerstock1   = $r->sellerstock;
                                    $date1          = $r->date;
                                    $data = array(
                                                'user_id'       => $user_id1,
                                                'image'         => $image1,
                                                'title_name'    => $title_name1,
                                                'asin'          => $asin1,
                                                'sellerstock'   => $sellerstock1,
                                                'amznotseller'  => $amznotseller1,
                                                'date'          => $date1,
                                                'amzoutofstock' => $amzoutofstock
                                                );
                                        $this->common_model->insertData('notification',$data);
                        }
            }        
}
///////////////////////------------mail start---------------------------//////////////////                            
                            require 'mailgun-php/vendor/autoload.php';
                            use Mailgun\Mailgun;
                            $global_get = $this->db->query("SELECT * FROM users where ID=$user_id  ")->result();
                                {   
                                foreach ($global_get as $global_g) {
                                    $global_check = $global_g->global_noti;
                                    
                                }
                            }
                            
                            $rescheck = $this->db->query("SELECT * FROM amaz_aug where user_id=$user_id  ")->result();
                                {   
                                foreach ($rescheck as $resch) {

                                    $email_check    = $resch->email_noti;
                                    $phone_check    = $resch->phone_noti;
                                    //$global_check   = $resch->global_noti;
                                    //print_r($email_check);
                                    if($email_check == "true" && $phone_check == "true" && $global_check == "true" )
                                    {
                                        $mailissend  = 1;
                                      
                                    }else if ($phone_check == "true" && $global_check == "true") {
                                        $onlyphone = 1;
                                        echo "only phone notification is on";
                                        exit;
                                    }
                                    else 
                                    {
                                        $mailisnotsend = 0;
                                    }

                                }
                            } 
                            if($mailissend == 1 )
                            {
                            /*echo "string";*/
                            $mgClient = new Mailgun('key-f14cf94304da5471b926ec3e4487773f');
                            $domain = "trackasins.com";
                            $get_d = $this->db->query("SELECT * FROM notification where user_id=$user_id ")->result();
                            //print_r($get_d);

                            foreach ($get_d as $get_noti_data) {
                                //echo "hi";exit;
                                //print_r($get_noti_data);
                                $noti_image         = $get_noti_data->image;
                                $noti_title_name    = $get_noti_data->title_name;
                                $noti_asin          = $get_noti_data->asin;
                                $noti_sellerstock   = $get_noti_data->sellerstock;
                                $noti_amznotseller  = $get_noti_data->amznotseller;
                                $noti_date          = $get_noti_data->date;
                                $noti_amzoutofstock = $get_noti_data->amzoutofstock;
                                //print_r($get_noti_data);

                            $html .= '
                                <html>
                                <head>
                                    <title>Trackasins</title>
                                </head>
                                <body>
                                    <h1>Trackasins</h1>
                                    <table border="2" cellspacing="2" >
                                        <thead>
                                            <tr>
                                                <th>
                                                    IMAGE
                                                </th>
                                                <th>
                                                    TITLE NAME
                                                </th>
                                                <th>
                                                    ASIN
                                                </th>
                                                <th>
                                                    DATE
                                                </th>                    
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <img src="'.$noti_image.'" style= "width: 50px; height: 60px; ">
                                                </td>
                                                <td>
                                                    '.$noti_title_name.'
                                                </td>
                                                <td>
                                                    '.$noti_asin.'
                                                </td>
                                                <td>
                                                    '.$noti_date.'
                                                </td>                    
                                            </tr>
                                        </tbody>
                                    </table>
                                </body>
                                </html>';

                            }

                            $query_get_data = $this->db->query("SELECT * FROM users where ID='$user_id' ")->result();
                            //print_r($query);
                            foreach ($query_get_data as $que) { 
                                $email_send = ($que->email);
                                $msg_send   = ($que->phone);
                                //print_r($msg_send);

                            $result = $mgClient->sendMessage("$domain",
                                  array(            'from'    => '<postmaster@trackasins.com>',
                                                    'to'      => $email_send,
                                                    'subject' => 'Trackasins ',
                                                    'html'    => $html
                                        ));
                            if($result)
                            {
                             echo 'mail successfully send using cron script...';
                            }
                            //////////////////////-------------mail ends-----------------------------///////////////


                            /////////////////////-------------sms start------------------------------///////////////

                            echo file_get_contents("http://api.clickatell.com/http/sendmsg?user=nateadmin&password=eTcZDaXPRFacGY&api_id=3593336&to=".$msg_send."&text=Hello");
                            ////////////////////--------------sms end--------------------------------///////////////
                             }
                         }else{
                            echo "please enable the notification";
                         }
                           
?>

