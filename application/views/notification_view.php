<?php
require("simple_html_dom.php");
date_default_timezone_set('America/New_York');

ini_set('max_execution_time', 9999999);
ini_set('memory_limit', '9999M');
error_reporting(0);

$chkdata=0;

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
    //echo $xml;exit;
    return $xml;
}



function storeAjax($brand,$page) {
        $http_head=array("Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                         "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3",
                         "Accept-Language:en-US,en;q=0.8",
                         "Connection:keep-alive");  
            //$brand='a';
            $fields = array('marketplaceID' => 'ATVPDKIKX0DER',
                'seller' => 'A1PFQKIUGA07X8', 
                'productSearchRequestData' => '{"marketplace":"ATVPDKIKX0DER","seller":"A1PFQKIUGA07X8","url":"/sp/ajax/products","pageSize":20,"lowPrice":"100","searchKeyword":"'.$brand.'","extraRestrictions":{},"pageNumber":"'.$page.'"}');
            $fields_string = '';
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            //echo $fields_string;
            rtrim($fields_string, '&');$ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.amazon.com/sp/ajax/products');
            curl_setopt($ch,CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, FALSE);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $http_head);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $result = curl_exec($ch);
            curl_close($ch);
            //echo $result;exit;
            return $result;
    } 
function getCron() { 
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
    $r = $result;
    //print_r($result);exit;
    curl_close($ch);

    return $result;
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

if(!empty($_FILES["file_upload"]["name"])){
    $moving_file_name='uploads/'.$_FILES["file_upload"]["name"];
    if (move_uploaded_file($_FILES["file_upload"]["tmp_name"],'uploads/'.$_FILES["file_upload"]["name"])) {
      $row = 1;
        if (($handle = fopen($moving_file_name, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                $old_ch = mysqli_fetch_array(mysqli_query("select * from amaz_aug_queue 
                    where asin = '".mysqli_real_escape_string(trim($data[0]))."' limit 1"));
                
                if(empty($old_ch)){
                    
                    $qry = "INSERT INTO amaz_aug_queue SET asin = '".mysqli_real_escape_string(trim($data[0]))."'";
                    mysqli_query($qry);      
                }
                    
            }
            fclose($handle);
            echo '<script>alert("Scrap will run in background.it will display here once scraping done.")</script>';
            
          getCron();

        }  
    }
}
else if(!empty($_POST['asin'])){
    //print_r($g);exit;
    //echo $r;exit;
    $chkdata=1;
    $asin = $_POST["asin"]; 
    $main_url="https://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new?ie=UTF8&condition=new";
    $xml = readItem($asin);
    //print_r($xml);exit;
    $merchant = $xml->Items->Item->Offers->Offer->Merchant->Name;
    $check_exist = mysqli_fetch_array(mysqli_query("select * from amaz_aug where asin='" . mysqli_real_escape_string($asin) . "'"));
    
    if (empty($check_exist)) 
     {

        $html=getPage($main_url);
        $html=str_get_html($html);

        $results=storeAjax($asin,1);
        $jd=json_decode($results);

        if(!empty($jd->products)){
                $sellerstock=1;
        } else{
            $sellerstock=0;
        }

                $amznotseller = "1"; 
        
        if(!empty($html)){

            foreach($html->find("div[id=olpOfferList] div[class=olpOffer]") as $elements){

                if($amznotseller == "1"){                
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
                if(empty($seller_name)){    
                    $seller_name=@$elements->find("h3[class=olpSellerName] img",0)->alt; 
                }   
                
                $amznotseller = "1";

                $stock_url=@$elements->find("h3[class=olpSellerName] img",0)->alt; 
                
                if($stock_url=="Amazon.com"){
                    $amznotseller = "0";
                }
                }
                $amount=$elements->find("span[class=olpOfferPrice]",0)->plaintext;  
                 $price=filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);   
                    
                $ship=$elements->find("span[class=a-color-secondary]",0)->plaintext;    
                $shipp=filter_var($ship, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); //$ships=trim($ship); //$shipp=str_replace('                      shipping','',$ships); //$shipping=str_replace('+ ','',$shipp);                             
                $shipping=str_replace('+','',$shipp); 

                //print_r($xml->Items->Item->Offers->Offer);
                // exit;



                                    
               
                 
                  }  
                                            
                } 
                                
                                                                
            }  
}

if(isset($_POST['submit1'])){

     $answer = $_POST['ans'];
     $asin = $_POST['asin'];
     $image = $_POST['img'];
     $amznotseller = $_POST['amznotseller'];
     $title_name = $_POST['title_name'];
     $sellerstock = $_POST['sellerstock'];
     $d = $_POST['d'];
     $rating = $_POST['rating'];
     $reviews = $_POST['reviews'];
     $seller_name = $_POST['seller_name'];
     $seller_url = $_POST['seller_url'];
     $seller_url = $_POST['seller_url'];
     $price = $_POST['price'];
     $shipping = $_POST['shipping'];
    if ($answer == "yes") {           
            //echo 'Correct';  
    $d = date("Y-m-d H:i:s") ;

    //$ID = $this->db->query("SELECT * FROM users ")->row()->ID;
    //echo $ID;exit;user_id = '".$ID."',
     $res = $this->db->query("SELECT * FROM amaz_aug where asin = '".$asin."'")->result();
       
    // print_r($res);
    // exit; 

    if($res)
    {
        echo 'This ASIN Track is Already present';
        $chkdata=0;
    }else{
        $user_id=$this->session->userdata('user_id');
        //print_r($user_id);exit;
        print_r($contents);exit;
        $qry =$this->db->query( "INSERT INTO amaz_aug SET asin = '".$asin."',
        image = '".$image."',
        amznotseller = '".$amznotseller."',
        title_name = '".$title_name."',
        tracking = '0',
        sellerstock = '".$sellerstock."',
        date= '".$d."',
        rating= '".$rating."',
        review= '".$reviews."',
        seller_name= '".$seller_name."',
        seller_url = '".$seller_url."',
        seller_id = '".$seller_url."',
        selling_price = '".$price."',
        shipping_price = '".$shipping."',
        user_id='".$user_id."'");
         $chkdata=0;
         }
     }else {
        echo 'Incorrect';
         $chkdata=0;
        //exit;
        }
}
function all()
{
print_r($contents);exit;  
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<body class="page-header-fixed page-quick-sidebar-over-content ">
<div class="clearfix"></div>
<div class="page-container animsition">
   <?php $this->load->view('side_navigation'); ?>
    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- Top content -->
        <div class="top-content">
            
                <h3 class="page-title">
                    Notification
                </h3>
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="">Home</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url();?>index.php/Dashboard/index">Notification</a>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-5 text">
                        <h1 class="wow fadeInLeftBig">Notification</h1>
                        <div class="description wow fadeInLeftBig">
                        </div>
                    </div>
                </div>


    <div class="row">
        <form action="" method="post" enctype="multipart/form-data">
        <div class="col-sm-8 col-sm-offset-5 text">
            <input type="text"  placeholder="Search" name="q">
            
                <button type="submit" name="s" style="border:none !important; background:none;"><i class="fa fa-search"></i></button>
            
        </div>
        </form>
    </div>
 

    <!-- <form  action="" method="post" enctype="multipart/form-data">
        <div  class="row col-sm-offset-1" style="border:1px solid gray;border-radius:10px !important;" >
            <div class="col-sm-1">
                <img src="smiley.jpg" style="height:20px;width:35px;" >
            </div>
            <div class="col-sm-3" >
                <h5 style="font-weight: bold !important;">Nikon COOLPIX S33 Waterproof Digital Camera (White)
                </h5>
            </div>
            <div class="col-sm-2" >
                <span>
                    <h5 style="font-weight: bold !important;">B00T85PKD0</h5>
                </span>
            </div>
            <div class="col-sm-2">
                    
                     Yes<input type="radio" name="ans" value="yes" checked="yes" /><br />
                     
            </div>
            <div class="col-sm-2" >
                      No <input type="radio" name="ans" value="no"  /><br />
            </div>
            
            <div class="col-sm-2" >
            <input  type="checkbox" name="check" >
                                            
            </div> 
        </div>
    </form> --> 
   
            
<form  action="" method="post">
    <div class="table-responsive" style="overflow-x:hidden;">
        <table class="table table-striped dataTable table-hover" >
            <thead >
                <tr>
                    <th  style="border :none !important">Image</th>
                    <th  style="border :none !important">Item Title</th>
                    <th  style="border :none !important">ASIN</th>
                    <th  style="border :none !important">Is Amazon in stock?</th>
                    <th  style="border :none !important">Are you in stock?</th>
                    <th  style="border :none !important">
                        <div >
                        Bulk Action
                        </div>
                    </th>
                </tr>
            </thead >
            <tbody>
                <tr style="border:1px solid gray;border-radius:10px !important;">
                    <td><img src='".$query->image. "' style=''/>
                    </td>
                    <td>
                    title name
                    </td>
                    <td>ASIN
                    </td>
                    <td>
                    yes
                    </td>
                    <td>
                    no
                    </td>
                    <td>
                    <input type="checkbox">
                    </td>
                </tr>
            </tbody>
            <!-- <button type="button" class="btn btn-default">Delete</button> --> 
        </table>
    </div>
</form>
            </div> 
                    
              
     </div>

            </div>

        </div>
    </div>
</div>
<!-- <div id="ajax-modal" class="modal" tabindex="-1" role="dialog" data-backdrop="static"></div> -->
<script language="JavaScript">
    function toggle(source) {
        var ids;
       document.getElementById('shows_delete').style.display='block'; 
      checkboxes = document.getElementsByName('checkbulk1[]');
        
        for(var i=0, n=checkboxes.length;i<n;i++) {
            ids += checkboxes[i].value+",";
            checkboxes[i].checked = source.checked;
            

          }
          document.getElementById('check_bulk').value = ids;
        
    }



</script>
<script type="text/javascript">
    function show() {
    if(document.getElementById('shows').style.display=='none') {
      document.getElementById('shows').style.display='block';
    }
}

function dele_show(){
    document.getElementById('shows_delete').style.display='block'; 
}

function chackUncheck(userIp){
            var url_link;
             if (document.getElementById('switch'+userIp).checked) {
                   url_link = '<?php echo base_url(); ?>Dashboard/checkAndUncheck/'+userIp+'/1';
                } else {
                   
                    url_link = '<?php echo base_url(); ?>Dashboard/checkAndUncheck/'+userIp+'/0';
                }
            $.ajax({
                url:url_link,
                success:function(res){
                    console.log(res);
                }
            })
        }


</script>


</body>
</html>



