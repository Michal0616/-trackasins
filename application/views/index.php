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
if(isset($_POST['delete'])){
   
    $cnt=array();
     $cnt=count($_POST['checkbulk1']);
     if(!empty($_POST['checkbulk1'])){
     for($i=0;$i<$cnt;$i++)
      {
         $del_id=$_POST['checkbulk1'][$i];
         $query=$this->db->query("DELETE from amaz_aug where id='$del_id'");
         mysqli_query($query);
      }
    }else{
        echo"<script>alert('Please checked at least on value.');</script>";
    }
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
                    Dashboard
                </h3>
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="">Home</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url();?>index.php/Dashboard/index">Dashboard</a>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-3 text">
                        <h1 class="wow fadeInLeftBig">What ASIN would you like track?</h1>
                        <div class="description wow fadeInLeftBig">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:10px;">
                    <div class="col-sm-6 col-sm-offset-3 r-form-1-box wow fadeInUp">
                        <div class="r-form-1-top">
                            
                        <div class="r-form-1-bottom">
                            <form  action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="sr-only" for="r-form-1-first-name">ASIN links</label>
                                    <input type="text" name="asin" placeholder="Enter ASIN..." class="r-form-1-first-name form-control" id="r-form-1-first-name">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="r-form-1-first-name">Or Upload File</label>
                                    <input type="file" name="file_upload"  class="r-form-1-first-name form-control" id="r-form-1-first-name">
                                </div>
                                <button type="submit" name="submit"  class="btn btn-default" >Submit</button>
                                <!-- <div id="shows" style="display:none;">
                                    some input in here plus the close button
                                </div> -->
                                 
                            <!--    <div>
                            <button  name="1" onclick="show();">click here</button>
                            </div> -->
                           
                           </form>
                        </div>
                        </div>
                    </div>
                
            </div>


        <?php if ($chkdata==1){ ?>
             <form  action="" method="post" enctype="multipart/form-data">
                <div  class="row col-sm-offset-3" style="border:1px solid gray;border-radius:10px !important;" id="shows" >
                
                    <div class="col-sm-3"><img src="<?php if(isset($image)){ echo $image; } ?>" alt="" height="120" width="120">
                     <input type="hidden" name="img" value="<?php if(isset($image)){ echo$image; } ?>" >   
                    </div>
                    <div class="col-sm-3" >
                    <h5 style="font-weight: bold !important;"><?php if(isset($title_name)){ echo$title_name; } ?></h5>
                     <h5 style="font-weight: bold !important;"><input type="hidden" name="title_name" value="<?php if(isset($title_name)){ echo$title_name; } ?>" ></h5>
                   <br>
                    <span><h5 style="font-weight: bold !important;"><?php if(isset($asin)){ echo$asin; } ?></h5>
                    <h5 style="font-weight: bold !important;"><input type="hidden" name="asin" value="<?php if(isset($asin)){ echo$asin; } ?>"></h5></span>
                    </div> 
                    <input type="hidden" name="amznotseller" value="<?php if(isset($amznotseller)){ echo$amznotseller; } ?>" >
<!--stock-->        <input type="hidden" name="stock_url" value="<?php if(isset($stock_url)){ echo$stock_url; } ?>" ><!--stock end-->
                    <input type="hidden" name="sellerstock" value="<?php if(isset($sellerstock)){ echo$sellerstock; } ?>" >
                    <input type="hidden" name="rating" value="<?php if(isset($rating)){ echo$rating; } ?>" >  
                    <input type="hidden" name="reviews" value="<?php if(isset($reviews)){ echo$reviews; } ?>" >  
                    <input type="hidden" name="seller_name" value="<?php if(isset($seller_name)){ echo$seller_name; } ?>" >  
                    <input type="hidden" name="seller_url" value="<?php if(isset($seller_url)){ echo$seller_url; } ?>" > 
                    <input type="hidden" name="seller_url" value="<?php if(isset($seller_url)){ echo$seller_url; } ?>" > 
                    <input type="hidden" name="seller_ids" value="<?php if(isset($seller_ids)){ echo$seller_ids; } ?>" >  
                    <input type="hidden" name="price" value="<?php if(isset($price)){ echo$price; } ?>" >  
                    <input type="hidden" name="shipping" value="<?php if(isset($shipping)){ echo$shipping; } ?>" >  
                    
                    <div class="col-sm-3">
                    
                     Yes <input type="radio" name="ans" value="yes" checked="yes" /><br />
                     
                    </div>
                     <div class="col-sm-3" style="margin-left:-20px ;">
                      No <input type="radio" name="ans" value="no"  /><br />
                    </div>
                    <div class="col-sm-3">
                    <br><br>
                    <button type="submit" class="btn" name="submit1">Submit</button>
                    </div>
                

                    
                </div>
            </form>
    <?php } ?>
            
                    <form  action="" method="post">
                     <div class="table-responsive" style="overflow-x:hidden;">
                             <table class="table table-striped dataTable table-hover table-bordered">
                                <thead>
                                    <tr>
                                      <th>Image</th>
                                      <th>Item Title</th>
                                      <th>ASIN</th>
                                      <th>Tracking</th>
                                      <th>Is Amazon in stock -- ?</th>
                                      <th>Are you in stock?</th>
                                      <th width="130">
                                        <div class="col-xs-12">
                                            Bulk Action
                                        </div>
                                          <div class="checkbox checkbox-warning">
                                            <input  type="checkbox" name="checkbulk" onclick="toggle(this)">
                                            <label></label>
                                            
                                            <button  id="shows_delete" style="display:none;" type="submit" class="btn" name="delete">Delete</button>
                                            <input  type="hidden" name="check_bulkk" id="check_bulk">
                                          
                                          </div>
                                      </th>
                                     </tr>
                                </thead>
                                <!-- <tfoot>
                                    <tr>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td>
                                      <form  action="" method="post" enctype="multipart/form-data">
                                     </form>
                                      </td>
                                </tr>
                                    
                                  </tfoot> -->
                                <tbody>
                                      <?php
	                                          $user_id=$this->session->userdata('user_id');
                                          $query = $this->db->query("SELECT * FROM amaz_aug WHERE `user_id`='$user_id' group by asin order by id desc")->result();
                                           
                                            foreach ($query as $query) { ?>
                                                    <tr>
                                                      <td><?php echo "<img src='".$query->image. "' style='height:50px;width:60px;'/>" ?></td>
                                                      <td><a style="color:black" target="_blank" href="http://amazon.com/dp/<?php echo $query->asin; ?>"><?php echo $query->title_name; ?></a></td>
                                                      <td><?php echo $query->asin; ?></td>
                                                      <td>
                                                      <?php if($query->tracking=="1") { ?>
                                                        <div class="checkbox checkbox-primary">
                                                            <input   type="checkbox" data-role="flipswitch" onchange="chackUncheck(<?php echo$query->id; ?>)" name="switch<?php echo$query->id; ?>" id="switch<?php echo$query->id; ?>" checked>
                                                            <label></label>
                                                        </div>
                                                      
                                                      <?php } else { ?>
                                                        <div class="checkbox checkbox-primary">
                                                            <input  type="checkbox" data-role="flipswitch" onchange="chackUncheck(<?php echo$query->id; ?>)" name="switch<?php echo$query->id; ?>" id="switch<?php echo$query->id; ?>">
                                                            <label></label>
                                                        </div>
                                                      <?php } ?>
                                                      </td>
                                                      <td>
                                                         <?php if($query->amznotseller=="1") { ?>
                                                      	Yes <?php } else { ?> No <?php } ?>	
                                                      </td>
                                                      <td> 
                                                      	<?php if($query->sellerstock=="1") { ?>
                                                      		Yes <?php } else { ?> No <?php } ?>
                                                      
                                                      </td> 
                                                      <td>
                                                      
                                                        <div class="checkbox checkbox-warning">
                                                        <input  type="checkbox" onclick="dele_show()" name="checkbulk1[]" value="<?php echo $query->id; ?>">
                                                        <label></label>
                                                        </div>
                                                      </td> 
                                                    </tr>
                                                    
                                                  <?php
                                                 }
                                                 ?>

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



