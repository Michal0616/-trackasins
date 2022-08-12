<?php

if (!empty($_POST['asin'])) {
    
    $chkdata = 1;
    $asin = trim($_POST['asin']);
    unset($_POST['asin']);
    //$main_url = "https://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new?ie=UTF8&condition=new&th=1&psc=1";
    $main_url = "https://www.amazon.com/dp/".$asin."/ref=olp_aod_redir_impl1?_encoding=UTF8&aod=1&th=1&psc=1";
    if (empty($amaz_aug_asin)) {
       

        $html = getPage($main_url);
        phpQuery::newDocument($html);

        
        //$amznotseller = array('0'=>1); //get_amazon_not_seller($asin, $html);
        //$amznotseller = get_amazon_not_seller($asin, $html);
        
        //$amznotseller = null;
	//$sellerstock = array('0'=>0);
	$amznotseller = null;
	$sellerstock = null;
	$res = 200;
        $amount = '';
        $price = '';
        $ship = '';
        $shipping = '';
        $rating = '';
        $reviews = '';
        //$sel_name ='';
        $user = $this->db->query("SELECT * FROM users WHERE ID=".$this->session->userdata('user_id'))->row();
	$str = $user->company;
	$str_name = $user->company;
        if ($html !== FALSE) {
            // Extract data from product sellers page
            /*$image = pq('div#olpProductImage')->find("img")->attr("src");
           $title_name = pq('h1.a-size-large.a-spacing-none')->text();
            $title_name = trim($title_name);
           // $rating = pq('i.a-icon-star')->eq(0)->text();
            //$reviews = pq('span.a-size-small')->eq(0)->text();
            //$reviews = trim($reviews);
            $seller_ids =  '';
            $seller_name = '';
            foreach (pq('div#olpOfferList')->find('div.olpOffer') as $elements) {
                // $seller_name = pq($elements)->find("h3.olpSellerName")->find('a')->text();
                // if (empty($seller_name)) {
                //     $seller_name = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                // }
                //if($seller_name == $user->company){
                    
                    //$sel_name = $seller_name;
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
                    
                    //$amount = pq($elements)->find('span.olpOfferPrice')->text();
                   // $price = filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    //$ship = pq($elements)->find("span.a-color-secondary")->text();
                   // $shipp = filter_var($ship, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                   //$shipping = str_replace('+', '', $shipp);

                    
                //}
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
	    }*/
    
            //echo $sellerstock.'<br>';
           // echo $amznotseller; exit;
	//}
        
        //echo'<pre>'; print_r($sellerstock).'<br>';
        //echo print_r($amznotseller);
        //exit;

        // $seller_name = $sel_name;
       // echo 'ddd'.$seller_name.'<br>';
       // echo $seller_ids;exit;
        // $arr = array();
        // if(isset($image) && $image){
        // list($width, $height) = getimagesize($image); 
        // $arr = array('h' => $height, 'w' => $width );
        // }
        
        //if (empty($sel_name)) {
           
        //if ((!isset($image) || !$image) || (!isset($title_name) || !$title_name)) {
           
            //$main_url = "https://www.amazon.com/dp/{$asin}";
            //$html = getPage($main_url);
	    $inStock = 0;
	    $seller_url = '';
            //$sellerstock = 0;
            //$amznotseller = 1;
            //phpQuery::newDocument($html);
	    $page_title = pq('title')->text();
            //echo "pg===".$page_title;exit;
            if (stripos($page_title, 'page not found') !== FALSE) {
		    //return array('res' => 404);
		    $res = 404;
	    }

	    $image = pq("#main-image-container")->find('img')->attr('data-old-hires');
           // $image = pq("#imgTagWrapperId")->find('img')->attr('src');
            $title_name = pq("span#productTitle")->text();
            $title_name = trim($title_name);
            //$rating = pq(".a-icon-star")->eq(0)->text();
           // $reviews = pq("#acrCustomerReviewText")->text();
           // $reviews = trim($reviews);
            /*$stock_status = pq('#availability')->text();
            $stock_status = trim($stock_status);
            if (!$stock_status) {
                $stock_status = pq('#outOfStock')->text();
            }
            if ($stock_status == 'Currently Unavailable.') {
                $inStock = 0;
	    }*/

	    $seller_name = '';

            $stock_status = pq('#availability')->text();

            $stock_status = trim($stock_status);
            if (!$stock_status) {
                $stock_status = pq('#outOfStock')->text();
                $sellerstock = 0;
                //$amznotseller[] = 1;
            }
            if ($stock_status == 'Currently Unavailable.' || stripos($stock_status, 'In stock on') !== FALSE) {
                $sellerstock = 0;
				//$amznotseller[] = 1;
            }
            if ($stock_status == 'In Stock.' || stripos($stock_status, 'in stock') !== FALSE) {
                $sellerstock = 1;
                //$amznotseller[] = 1;
	    }

            $sell_name = pq('#merchant-info')->text();
            if($sell_name != ''){
                if (stripos($sell_name, 'amazon.com') !== FALSE) {
                    $inStock = 1;
                    $seller_name = 'Amazon.com';
                    $amznotseller = 0;
                }else{
                    $amznotseller = 1;
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
            }
	
	    $sell_name_new = pq('#tabular-buybox-truncate-1')->text();
	    if($sell_name_new != ''){
		if (stripos($sell_name_new, 'amazon.com') !== FALSE) {
			$inStock = 1;
			$seller_name = 'Amazon.com';
			$amznotseller = 0;
	        }elseif (stripos($sell_name_new, $str_name) !== FALSE && stripos($str_name, 'amazon.com') === FALSE) {
                        $seller_name = $sell_name_new;
                        $amznotseller = 2;
                }else{
			$seller_name = $sell_name_new;
			$amznotseller = 1;
		}
	    }

	     $sell_name_offered = pq('.mbcMerchantName')->text();
            //echo "offer===".$sell_name_offered;
            if($sell_name_offered != ''){
                 if (stripos($sell_name_offered, 'amazon.com') !== FALSE) {
                    $inStock = 1;
                    $seller_name = 'Amazon.com';
                    $amznotseller = 0;
                 }elseif (stripos($sell_name_offered, $sel_name) !== FALSE && stripos($sel_name, 'amazon.com') === FALSE) {
                         $seller_name = $sell_name_offered;
                    $amznotseller = 2;
                }else{
                         $seller_name = $sell_name_offered;
                        $amznotseller = 1;
                }
            }
            /*$stock_status = pq('#availability')->text();
	
            $stock_status = trim($stock_status);
            if (!$stock_status) {
                $stock_status = pq('#outOfStock')->text();
                $sellerstock = 0;
                //$amznotseller = 1;
	    }
	    if ($stock_status == 'Currently Unavailable.' || stripos($stock_status, 'In stock on') !== FALSE) {
                $inStock = 0;
                $sellerstock = 0;
                //$amznotseller = 1;
	    }
	    if ($stock_status == 'In Stock.' || stripos($stock_status, 'in stock') !== FALSE) {
		$sellerstock = 1;
	    }*/
            //  if($seller_name == $user->company){
                    
            //     $sel_name = $seller_name;
                
            //  }
            // $amount = pq('#priceblock_ourprice')->text();
            // if (!$amount) {
            //     $alt_amount = pq('.price-large')->eq(0)->text();
            //     if ($alt_amount) {
            //         $cents = pq('.price-info-superscript')->eq(0)->text();
            //         $amount = '$'.$alt_amount.".{$cents}";
            //     }
            // }
	    // $price = filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	    if (is_null($sellerstock) || is_null($amznotseller)) {
		    //return array('res' => 400);
		    $res = 404;
            }
        }

        if ((!isset($image) || !$image) || (!isset($title_name)|| !$title_name)) {
            $requires_rescrape = true;
        }
        
        // $seller_name = $sel_name;
        //echo 'ddd'.$seller_name.'<br>';
        //echo $seller_ids;exit;
    } else {
        $res = 200;
        //echo '<pre>';print_r($amaz_aug_asin);exit;
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
<?php
    if(!empty($res) && $res != 404){
?>
<div class="topBox text-left">
                    <!-- <h3>Insert form</h3> -->
                </div>

                <div class="bottomContent" id="shows">
                    <div class="formTop clearfix">
                        <!-- <form action="" method="post" enctype="multipart/form-data"> -->
                        <div class="col-lg-12" id="shows">
                            <div class="inputType col-lg-2 image-div" >
                                <img src="<?php if ( isset($image) && ($image != null) ) { echo str_replace('._SS160_','',$image); } else {  $image = "assets2/images/question-mark.png"; echo $image; } ?>" alt="" title="<?php if (isset($image) && $image != "assets2/user_data/question-mark.png") { echo $image; } else { echo 'Unable to fetch image from Amazon'; } ?>" >
                                <input type="hidden" name="img" id="img_1" value="<?php if (isset($image)) { echo str_replace('._SS160_','',$image); } ?>">
                            </div>
                            <div class="inputType col-lg-5 amazon-content" >
                                <h5 class="amazon-title-name">
                                    <?php if (isset($title_name) && $title_name != null) { echo $title_name; } else { $title_name =  "Cannot fetch title from Amazon at this time"; echo $title_name; }?>
                                </h5>
                                <h5 class="amazon-asin">
                                    <?php if (isset($asin)) {
                                        echo $asin;
                                    } ?>
                                </h5>
                                <h5 style="font-weight: bold !important;text-align: center">
                                    <input type="hidden" name="title_name" id="title_name_1"
                                           value="<?php if (isset($title_name)) {
                                               echo $title_name;
                                           } ?>">
                                </h5>
                                <h5 style="font-weight: bold !important;">
                                    <input type="hidden" name="asin" id="asin_1" value="<?php if (isset($asin)) {
                                        echo $asin;
                                    } ?>">
                                </h5>
                                <div id="yes-submission" class="inputType submit-button-div " >
                                    <!-- <input type='submit' name="submit1"  class='btn btn-embossed btn-primary btn-wide' style="border-top-left-radius: 0px;border-bottom-left-radius: 0px;background: #b65f2b;" value='Submit' /> -->
                                    <?php if  (isset($requires_rescrape) && $requires_rescrape) { ?>
                                        <button onclick="saveTodatabase(true)" class='btn btn-embossed btn-primary btn-wide'>
                                            Submit
                                        </button>
                                    <?php } else { ?>
                                        <button onclick="saveTodatabase()" class='btn btn-embossed btn-primary btn-wide'>
                                            Submit
                                        </button>
                                    <?php } ?>
                                </div>
                                <div id="no-submission" class="inputType submit-button-div" style="display: none">
                                    <!-- <input type='submit' name="submit1"  class='btn btn-embossed btn-primary btn-wide' style="border-top-left-radius: 0px;border-bottom-left-radius: 0px;background: #b65f2b;" value='Submit' /> -->

                                    <button onclick="saveTodatabase(true)" class='btn btn-embossed btn-primary btn-wide'>
                                        Submit Anyway
                                    </button>
                                    <button onclick="clearConfirmAsinDiv()" class='btn btn-embossed btn-primary btn-wide'>
                                        Cancel
                                    </button>
                                </div>
                            </div>
                            <!--<div class="inputType col-lg-2 " style="margin-bottom: 0px;float:left;">

                            </div>-->
                            <input type="hidden" name="user_id" id="user_id_1" value="<?php echo $user_id; ?>">
                            <!-- <input type="hidden" name="id" id="id_1" value="<?php//echo $amaz_aug_asin->id; ?>"> -->
                            <input type="hidden" name="amznotseller" id="amznotseller_1"
                                   value="<?php if (isset($amznotseller)) {
                                       echo $amznotseller;
                                   } ?>">
                            <input type="hidden" name="stock_url" id="stock_url_1" value="<?php if (isset($stock_url)) {
                                echo $stock_url;
                            } ?>"><!--stock end-->
                            <input type="hidden" name="sellerstock" id="sellerstock_1"
                                   value="<?php if (isset($sellerstock)) {
                                       echo $sellerstock;
                                   } ?>">
                            <input type="hidden" name="rating" id="rating_1" value="<?php if (isset($rating)) {
                                echo $rating;
                            } ?>">
                            <input type="hidden" name="reviews" id="reviews_1" value="<?php if (isset($reviews)) {
                                echo $reviews;
                            } ?>">
                            <input type="hidden" name="seller_name" id="seller_name_1"
                                   value="<?php if (isset($seller_name)) {
                                       echo $seller_name;
                                   } ?>">
                            <input type="hidden" name="seller_url" id="seller_url_1"
                                   value="<?php if (isset($seller_url)) {
                                       echo $seller_url;
                                   } ?>">
                            <input type="hidden" name="seller_url" id="seller_url_1"
                                   value="<?php if (isset($seller_url)) {
                                       echo $seller_url;
                                   } ?>">
                            <input type="hidden" name="seller_ids" id="seller_ids_1"
                                   value="<?php if (isset($seller_ids)) {
                                       echo $seller_ids;
                                   } ?>">
                            <input type="hidden" name="price" id="price_1" value="<?php if (isset($price)) {
                                echo $price;
                            } ?>">
                            <input type="hidden" name="shipping" id="shipping_1" value="<?php if (isset($shipping)) {
                                echo $shipping;
                            } ?>">
                            <div class="col-lg-5 amazon-item-div" >
                                <h3  class="amazon-correct-item">Is this the
                                    correct item?</h3>
                                <div class="holder">
                                    <div class="selectMod pull-left" style="margin-right: 5px;">
                                        <h3 class="text-center" style="font-size: 1.2em;">Yes</h3>
                                        <div class="c-hold verticle-middle text-center">
                                            <input type="radio" name="ans" value="yes" checked="yes" />
                                            <label for="checkbox1" data-for="checkbox1" class="cb-label"></label>
<!--                                            <input type='checkbox' value='' id='checkboxall1' data-c="yes"/>-->
<!--                                            <label for='checkboxall1' data-for="checkboxall1"-->
<!--                                                   class='cb-label checkboxall1'></label>-->
                                        </div>
                                    </div>
                                    <div class="selectMod pull-right" style="margin-right: 5px;">
                                        <h3 class="text-center" style="font-size: 1.2em;">No</h3>
                                        <div class="c-hold verticle-middle text-center">
                                            <input type="radio" name="ans" value="no"/>
                                            <label for="checkbox1" data-for="checkbox1" class="cb-label"></label>
<!--                                            <input type='checkbox' value='' id='checkboxall1' data-c="no"/>-->
<!--                                            <label for='checkboxall1' data-for="checkboxall1"-->
<!--                                                   class='cb-label checkboxall1'></label>-->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- </form> -->
                    </div>

                </div>
<?php } else { ?>
   <center><p>Unable to fetch product from Amazon. Please try another ASIN !</p></center>
<?php } ?>
