<?php
/**
 * Created by IntelliJ IDEA.
 * User: anhnguyen
 * Date: 10/10/16
 * Time: 6:15 PM
 */

require __DIR__ . '/../libraries/phpQuery/phpQuery.php';
require __DIR__ . '/../libraries/dompdf/autoload.inc.php';
require_once APPPATH . 'views/simple_html_dom.php';

function debug($value, $label = null)
{
    $label = get_tracelog(debug_backtrace(), $label);
    echo getdebug($value, $label);
    exit();
}

function getdebug($value, $label = null)
{
    $value = htmlentities(print_r($value, true));
    return "<pre>$label$value</pre>";
}

function get_tracelog($trace, $label = null)
{
    $line = $trace[0]['line'];
    $file = is_set($trace[1]['file']);
    $func = $trace[1]['function'];
    $class = is_set($trace[1]['class']);
    $log = "<span style='color:#FF3300'>-- $file - line:$line - $class-$func()</span><br/>";
    if ($label)
        $log .= "<span style='color:#FF99CC'>$label</span> ";
    return $log;
}

function is_set(&$var, $substitute = null)
{
    return isset($var) ? $var : $substitute;
}

function dump($value, $label = null)
{
    $label = get_tracelog(debug_backtrace(), $label);
    $value = htmlentities(var_export($value, true));
    echo "<pre>$label$value</pre>";
}

function get_amazon_not_seller($asin, $html = null)
{
    $amznotseller = 1;
    if ($html === null) {
        // URL to Amazon page listing sellers for a product ASIN.
        $url = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new");
        $html = getPage($url);
    }

    if ($html !== FALSE) {
        phpQuery::newDocument($html);
        $no_of_pagination_links = pq('.a-pagination')->find('li')->length;
        $pagination_links = [];
        foreach (pq('.a-pagination')->find('li') as $el) {
            $link = pq($el)->find('a')->attr('href');
            if ($link && $link != '#') {
                array_push($pagination_links, $link);
            }
        }
        $paginated = $no_of_pagination_links > 0;
        foreach (pq('h3.olpSellerName') as $idPresent) {
            $stock_url = @pq($idPresent)->find("img")->eq(0)->attr('alt');
            if ($stock_url == 'Amazon.com') {
                $amznotseller = 0;
                break;
            }
        }
        if ($paginated) {
            $any_attempt_failed = false;
            foreach ($pagination_links as $link) {
                $next_page_url = "https://www.amazon.com{$link}";
                $html = getPage($next_page_url);

                if ($html !== FALSE) {
                    phpQuery::newDocument($html);
                    foreach (pq('h3.olpSellerName') as $idPresent) {
                        $stock_url = @pq($idPresent)->find("img")->eq(0)->attr('alt');
                        if ($stock_url == 'Amazon.com') {
                            $amznotseller = 0;
                            break 2;
                        }
                    }
                } else {
                    $any_attempt_failed = true;
                    break;
                }
            }
            if ($any_attempt_failed) {
                return FALSE;
            }
        }
    } 
    // else {
    //     return FALSE;
    // }

    return $amznotseller;
}

function get_seller_stock($asin, $seller)
//function get_seller_stock($asin, $seller = 'A1PFQKIUGA07X8')
{
    // URL to Amazon page listing sellers for a product ASIN and Seller ID.
    $sellersPageUrl = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new&me=$seller");
    $sellerStock = 0;

    $html = getPage($sellersPageUrl);
    if ($html !== FALSE) {
        phpQuery::newDocument($html);
        foreach (pq('h3.olpSellerName') as $idPresent) {
            $sellerLink = @pq($idPresent)->find("a")->eq(0)->attr('href');
            if (getInBetweenStrings($sellerLink, 'seller=', '') == $seller) {
                $sellerStock = 1;
            } else if (getInBetweenStrings($sellerLink, 'seller=', '&') == $seller) {
                $sellerStock = 1;
            }
            
        }
    } 
    // else {
    //     return FALSE;
    // }
    //echo $sellerStock;exit;
    return $sellerStock;
}

function get_stock_new($asin, $sel_name){
    $sellersPageUrl = "https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new";
    //$sellersPageUrl = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new&me=$seller");
    $sellerstock = array('0'=>0);
    $amznotseller = array('0'=>1);

    $html = getPage($sellersPageUrl);

    if ($html !== FALSE) {
	    //echo "test---".__DIR__ . '/../libraries/phpQuery/phpQuery.php';
	    require_once __DIR__ . '/../libraries/phpQuery/phpQuery.php';
	    phpQuery::newDocument($html);
	    //echo "noooo";
	//phpQuery::newDocumentFile($html);
        $sellerstock = 0;
        $amznotseller = 1;
        //if ($html) {
            //$main_url = "https://www.amazon.com/dp/{$scrape->asin}";
            //$html = getPage($main_url);
            $inStock = 0;
            $seller_url = '';
            //phpQuery::newDocument($html);

            $image = pq("#main-image-container")->find('img')->attr('data-old-hires');
            //$image = pq('div#imgTagWrapperId')->find("img")->attr("src");
            $title_name = pq("span#productTitle")->text();
            $title_name = trim($title_name);
            $rating = pq(".a-icon-star")->eq(0)->text();
            $reviews = pq("#acrCustomerReviewText")->text();
            $reviews = trim($reviews);

            $sell_name = pq('#merchant-info')->text();
            if($sell_name != ''){
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
                    $amznotseller = 0;
                } else if ($str = getInBetweenStrings($seller_url, 'seller=', '')) {
                    $seller_ids = $str;
                    $seller_name = pq('#merchant-info')->find('a')->text();
                    $sellerstock = 1;
                    $amznotseller = 0;
                }
            }

            $stock_status = pq('#availability')->text();
            
            $stock_status = trim($stock_status);
            if (!$stock_status) {
                $stock_status = pq('#outOfStock')->text();
                $sellerstock = 0;
                $amznotseller = 1;
            }else{
                $inStock = 1;
                $sellerstock = 1;
                $amznotseller = 0;
            }
            if ($stock_status == 'Currently Unavailable.') {
                $inStock = 0;
                $sellerstock = 0;
                $amznotseller = 1;
            }
            
        //}
    }
    $result = array('sellerStock' => $sellerstock, 'amznotseller' => $amznotseller);
    //print_r($result);exit;
    return $result;
}

function get_stock_new1($asin, $sel_name){
	//$sellersPageUrl = "https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new&th=1&psc=1";
	$sellersPageUrl = "https://www.amazon.com/dp/$asin/ref=olp_aod_redir_impl1?_encoding=UTF8&aod=1&th=1&psc=1";
    echo "\n" . $sellersPageUrl . "\n";
	//$sellersPageUrl = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new&me=$seller");
	//$sellersPageUrl = "https://www.amazon.com/gp/offer-listing/$asin/ref=twister_dp_update?ie=UTF8&psc=1&redirect=true";
    $sellerstock = null;
    $amznotseller = null;
	//echo $sellersPageUrl."\n";
    $html = getPage($sellersPageUrl);
    /*$fp=fopen('u.html', 'a');
    fwrite($fp, $html);
    fclose($fp);*/
    // echo "html===".$html;
    if ($html !== FALSE) {
        phpQuery::newDocument($html);
        //$sellerstock = 0;
        //$amznotseller = 1;
        //if ($html) {
            //$main_url = "https://www.amazon.com/dp/{$scrape->asin}";
            //$html = getPage($main_url);
            $inStock = 0;
            $seller_url = '';
            //phpQuery::newDocument($html);
	    $page_title = pq('title')->text();
	    //echo "pg===".$page_title;
        // exit;
	    if (stripos($page_title, 'page not found') !== FALSE) {
		    return array('res' => 404);
	    }
            $image = pq("#main-image-container")->find('img')->attr('data-old-hires');
            //$image = pq('div#imgTagWrapperId')->find("img")->attr("src");
            $title_name = pq("span#productTitle")->text();
            $title_name = trim($title_name);
            $rating = pq(".a-icon-star")->eq(0)->text();
            $reviews = pq("#acrCustomerReviewText")->text();
            $reviews = trim($reviews);
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
		    //echo "seller==".$seller_name;
	    }
	    
	//print_r($sellerstock);print_r($amznotseller);
	    $sell_name_new = pq('#tabular-buybox-truncate-1')->text();
        echo "sell_name_new: ";
        print_r($sell_name_new);
	    if($sell_name_new != ''){
		 if (stripos($sell_name_new, 'amazon.com') !== FALSE) {
                    $inStock = 1;
                    $seller_name = 'Amazon.com';
                    $amznotseller = 0;
		 }else if (stripos($sell_name_new, $sel_name) !== FALSE && stripos($sel_name, 'amazon.com') === FALSE) {
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
	//echo "sellernew==".$seller_name."<br>";
           /*$stock_status = pq('#availability')->text();
            
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
	    }*/

            /*if (in_array("1", $sellerstock)) {
                $sellerstock = 1;
            } else {
                $sellerstock = 0;
            }
            if (in_array("0", $amznotseller)) {
                $amznotseller = 0;
            } else {
                $amznotseller = 1;
	    }*/
	    //echo "selleramz==".$sellerstock."***".$amznotseller."----".$sel_name;
	    if (is_null($sellerstock) || is_null($amznotseller)) {
                    return array('res' => 400);
            }
        //}
    }else{
	    return array('res' => 404);
    }
    $result = array('sellerStock' => $sellerstock, 'amznotseller' => $amznotseller, 'sellername' => $seller_name);

    return $result;
}
function getStockDetail($asin, $sel_name, $fp){
  
    echo "getStockDetail\n";
	//$sellersPageUrl = "https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new&th=1&psc=1";
	$sellersPageUrl = "https://www.amazon.com/dp/$asin/ref=olp_aod_redir_impl1?_encoding=UTF8&aod=1&th=1&psc=1";
    echo "\n" . $sellersPageUrl . "\n";
    echo "asins=> $asin\n";
    echo "sel_name =>$sel_name\n";
   
    
    
    fwrite($fp, "Asin = $asin\n");
    fwrite($fp, "asin scrap start at : [".date('m-d-y h:i:s')."]: \n");
    fwrite($fp, "sel_name = $sel_name\n");
   
    $html = getPage($sellersPageUrl);

    $isAmazonInBoxStock = null;	// true if the amazon is in buybox and stock available.
	$isSellerInBoxStock = null; // true if the seller is in buybox and stock available.
	$isSellerInNonBoxStock = null; // true if the seller is in stock and not in buybox
    $inStock = null;

    if ($html !== FALSE) {
        $document = phpQuery::newDocument($html);    
        
        $seller_url = '';
	    $page_title = pq('title')->text();
	    // echo "pg===".$page_title;
        // exit;
	    if (stripos($page_title, 'page not found') !== FALSE) {
            fwrite($fp, "page_title = $page_title\n");
            fwrite($fp, "asin scrap end at : [".date('m-d-y h:i:s')."]: \n");
            fwrite($fp, "=========================================================\n");
            fwrite($fp, "\n\n");
		    return array('res' => 404);
	    }
	    $seller_name = '';
	    $stock_status = pq('#availability')->text();
        $stock_status = trim($stock_status);

        // echo "\nstock_status: " . $stock_status;
        // echo "\n===========\n";

        if (!$stock_status) {
            $stock_status = pq('#outOfStock')->text();
            $inStock = 0;
        }
        if ($stock_status == 'In Stock.' || stripos($stock_status, 'in stock.') !== FALSE ||
        stripos($stock_status, 'This item cannot be shipped') !== FALSE ||
        stripos($stock_status, 'beyond seller\'s shipping coverage') !== FALSE ||
        stripos($stock_status, 'Usually ships within') !== FALSE ||
        stripos($stock_status, 'In stock on') !== FALSE ||
        stripos($stock_status, 'left in stock') !== FALSE)
         {
            $inStock = 1;
	    } 
        if (stripos($stock_status, 'In stock soon.') !== FALSE ||
            stripos($stock_status, 'Currently Unavailable') !== FALSE ||
            stripos($stock_status, 'Temporarily out of stock') !== FALSE
           
           ) {
                echo "test1111";
                $inStock = 0;
        }
        if($inStock === 0){
            $isSellerInBoxStock = 0;
            $isAmazonInBoxStock = 0;
        }
        echo "\ninStock: ";
        echo  $inStock;
        fwrite($fp, "inStock = $inStock\n");
        echo "\n===========\n";

        //$sell_name_new = pq('.tabular-buybox-text');
        //$sell_name_new = pq('#sellerProfileTriggerId')->text();
        $sell_name_new = $document->find('.tabular-buybox-text[tabular-attribute-name="Sold by"]')->text();
        //$matches = $document->find('input[data-city="New York"]');
       
        //echo    "test---sellername:".$sell_name_new;  
        //print_r (explode(" ",trim($sell_name_new)));
        //exit;
        //echo "\n===========\n";
        $sell_name_new = trim($sell_name_new);
        //echo "\nsell_name_new: ";
       //print_r($sell_name_new);
      
   

        //$othersellers = pq('#aod-offer-soldBy .a-fixed-left-grid .a-fixed-left-grid-inner .a-fixed-left-grid-col .a-size-small')->text();
        // echo "other sellers <pre>";
        // print_r($othersellers);

       

        // echo "other sellers <pre>";
        // print_r($other_seller_name);
	    
 
        
        if($sell_name_new != ''){
            echo "sell_name_new,$sell_name_new";
            fwrite($fp, "sell_name_new = $sell_name_new\n");
            if(stripos($sell_name_new, 'Amazon') !== FALSE) {
                echo "is amazon stock?";
                fwrite($fp, "is amazon stock?\n");
                if($inStock === 1){
                   
                    $isAmazonInBoxStock = 1;                    
                }else{
                    $isAmazonInBoxStock = 0;
                }
                $isSellerInBoxStock = 0;
                $seller_name = 'Amazon';
            } else if (stripos($sell_name_new, 'Amazon') === FALSE) {
                $seller_name = $sell_name_new;
                echo "is not amazon stock?";
                fwrite($fp, "is not amazon stock?\n");
                if(stripos($sell_name_new, $sel_name) !== FALSE){
                    if($inStock === 1){
                        $isSellerInBoxStock = 1;
                    }else{
                        $isSellerInBoxStock = 0;
                    }
                }else{
                    $isSellerInBoxStock = 0;
                }
               
                $isAmazonInBoxStock = 0;
            } else {
                echo "is not stock?";
                fwrite($fp, "is not stock?\n");
                $seller_name = $sell_name_new;
                $isSellerInBoxStock = 0;
                $isAmazonInBoxStock = 0;
            }
        }
       
        $other_sell_name =  pq('#aod-offer-soldBy')->text();
        // echo "\nseller_name others-----: ";
        // echo $other_sell_name;
        // echo "\n===========\n";

        $other_seller_name = '';
        if($other_sell_name != ''){
            if (stripos($other_sell_name, 'amazon') !== FALSE) {
               $inStock = 1;
               $other_seller_name = 'Amazon.com';
               fwrite($fp, "other_seller_name1 = $other_seller_name\n");
               $isAmazonInBoxStock = 1;
            }
            if (stripos($other_sell_name, $sel_name) !== FALSE) {
                    $other_seller_name = trim($other_sell_name);
                    fwrite($fp, "other_seller_name2 = $other_seller_name\n");
                    $isSellerInBoxStock = 1;
                    $seller_name = $sel_name;
            }
       }



        //Look for the other sellers
        // if($isAmazonInBoxStock === 0 && $isSellerInBoxStock === 0){

        // }
	    	    	
	    if (is_null($isAmazonInBoxStock) || is_null($isSellerInBoxStock)) {
            fwrite($fp, "asin scrap end at : [".date('m-d-y h:i:s')."]: \n");
            fwrite($fp, "=========================================================\n");
            fwrite($fp, "\n\n");
            return array('res' => 400);
        }
    } else {
        fwrite($fp, "asin scrap end at : [".date('m-d-y h:i:s')."]: \n");
        fwrite($fp, "=========================================================\n");
        fwrite($fp, "\n\n");
	    return array('res' => 404);
    }
    $result = array('asin' => $asin, 'isAmazonInBoxStock' => $isAmazonInBoxStock, 'isSellerInBoxStock' => $isSellerInBoxStock, 'sellername' => $seller_name, 'other_seller_name' => $other_seller_name);
    fwrite($fp, "result = 'asin' => $asin, 'isAmazonInBoxStock' => $isAmazonInBoxStock, 'isSellerInBoxStock' => $isSellerInBoxStock, 'sellername' => $seller_name, 'other_seller_name' => $other_seller_name\n");
    fwrite($fp, "asin scrap end at : [".date('m-d-y h:i:s')."]: \n");
    fwrite($fp, "=========================================================\n");
    fwrite($fp, "\n\n");
   
    return $result;
}

function get_stock_simple($asin, $sel_name){
    $sellersPageUrl = "https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new";
    //$sellersPageUrl = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new&me=$seller");
    $sellerstock = array('0'=>0);
    $amznotseller = array('0'=>1);

    $html = getPage($sellersPageUrl);
    /*$fp = fopen('test.html', "w");
    fwrite($fp, $html);
    fclose($fp);*/
    if ($html !== FALSE) {
        phpQuery::newDocument($html);
        $sellerstock = 0;
        $amznotseller = 1;
        //if ($html) {
            //$main_url = "https://www.amazon.com/dp/{$scrape->asin}";
            //$html = getPage($main_url);
            $inStock = 0;
            $seller_url = '';
            //phpQuery::newDocument($html);
            //$e = $html->find('div[id="main-image-container"]');
            //echo $e;exit;
	    $image = pq("#main-image-container")->find('img')->attr('data-old-hires');
	    echo $image;exit;
            //$image = pq('div#imgTagWrapperId')->find("img")->attr("src");
            /*$title_name = pq("span#productTitle")->text();
            $title_name = trim($title_name);
            $rating = pq(".a-icon-star")->eq(0)->text();
            $reviews = pq("#acrCustomerReviewText")->text();
            $reviews = trim($reviews);
            
            $sell_name = pq('#merchant-info')->text();
            if($sell_name != ''){
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
                    $amznotseller = 0;
                } else if ($str = getInBetweenStrings($seller_url, 'seller=', '')) {
                    $seller_ids = $str;
                    $seller_name = pq('#merchant-info')->find('a')->text();
                    $sellerstock = 1;
                    $amznotseller = 0;
                }
            }

            $stock_status = pq('#availability')->text();
            
            $stock_status = trim($stock_status);
            if (!$stock_status) {
                $stock_status = pq('#outOfStock')->text();
                $sellerstock = 0;
                $amznotseller = 1;
            }else{
                $inStock = 1;
                $sellerstock = 1;
                $amznotseller = 0;
            }
            if ($stock_status == 'Currently Unavailable.') {
                $inStock = 0;
                $sellerstock = 0;
                $amznotseller = 1;
            }
            */
        //}
    }
    //$result = array('sellerStock' => $sellerstock, 'amznotseller' => $amznotseller);
    //return $result;
}

function get_stock($asin, $sel_name)
{
    
    $sellersPageUrl = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new");
    //$sellersPageUrl = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new&me=$seller");
    $sellerstock = array('0'=>0);
    $amznotseller = array('0'=>1);

    $html = getPage($sellersPageUrl);

    if ($html !== FALSE) {
        phpQuery::newDocument($html);
        // foreach (pq('h3.olpSellerName') as $idPresent) {
        //     $sellerLink = @pq($idPresent)->find("a")->eq(0)->attr('href');
        //     if (getInBetweenStrings($sellerLink, 'seller=', '') == $seller) {
        //         $sellerStock = 1;
        //         break;
        //     } else if (getInBetweenStrings($sellerLink, 'seller=', '&') == $seller) {
        //         $sellerStock = 1;
        //         break;
        //     }
            
        // }

        foreach (pq('div#olpOfferList')->find('div.olpOffer') as $elements) {
           
            $seller_name = pq($elements)->find("h3.olpSellerName")->find('a')->text();
            if (empty($seller_name)) {
                $seller_name = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
            }
            
            
            if(strcmp($sel_name,$seller_name)==0) {
                $sellerstock[] = 1;
            }  else {
                $sellerstock[] = 0;
                //break;
            }
            $stock_url = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                    
            if ($stock_url == "Amazon.com") {
                $amznotseller[] = 0;
            }  else {
                $amznotseller[] = 1;
            }
            
        }

        $stock_status = pq('#availability')->text();
                    
        $stock_status = trim($stock_status);
        if (!$stock_status) {
            $stock_status = pq('#outOfStock')->text();
            $sellerstock[] = 0;
            $amznotseller[] = 1;
        }else{
            $sellerstock[] = 1;
            $amznotseller[] = 0;
        }
        if ($stock_status == 'Currently Unavailable.') {
            $sellerstock[] = 0;
            $amznotseller[] = 1;
        }
        
        //echo 'sss'.$seller_name;
        //echo 'dddd'.$sellerStock;exit;
        $no_of_pagination_links = pq('.a-pagination')->find('li')->length;
        $pagination_links = [];
        foreach (pq('.a-pagination')->find('li') as $el) {
            $link = pq($el)->find('a')->attr('href');
            if ($link && $link != '#') {
                array_push($pagination_links, $link);
            }
        }
        // foreach (pq('h3.olpSellerName') as $idPresent) {
        //     $stock_url = @pq($idPresent)->find("img")->eq(0)->attr('alt');
        //     if ($stock_url == 'Amazon.com') {
        //         $amznotseller[] = 0;
        //     break;
        //     }
        // }
        $paginated = $no_of_pagination_links > 0;
        if ($paginated) {
            $any_attempt_failed = false;
            foreach ($pagination_links as $link) {
                $next_page_url = "https://www.amazon.com{$link}";
                $html = getPage($next_page_url);

                if ($html !== FALSE) {
                    phpQuery::newDocument($html);
                    foreach (pq('h3.olpSellerName') as $idPresent) {
                        $seller_name = pq($elements)->find("h3.olpSellerName")->find('a')->text();
                        if (empty($seller_name)) {
                            $seller_name = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                        }
                        
                        
                        if(strcmp($sel_name,$seller_name)==0) {
                            $sellerstock[] = 1;
                        }  else {
                            $sellerstock[] = 0;
                            //break;
                        }
                        $stock_url = @pq($idPresent)->find("img")->eq(0)->attr('alt');
                        if ($stock_url == "Amazon.com") {
                            $amznotseller[] = 0;
                        }  else {
                            $amznotseller[] = 1;
                        }
                    }

                    $stock_status = pq('#availability')->text();
                    
                    $stock_status = trim($stock_status);
                    if (!$stock_status) {
                        $stock_status = pq('#outOfStock')->text();
                        $sellerstock[] = 0;
                        $amznotseller[] = 1;
                    }else{
                        $sellerstock[] = 1;
                        $amznotseller[] = 0;
                    }
                    if ($stock_status == 'Currently Unavailable.') {
                        $sellerstock[] = 0;
                        $amznotseller[] = 1;
                    }
                } else {
                    $any_attempt_failed = true;
                  break;
                }
            }
           // if ($any_attempt_failed) {
                //return FALSE;
            //}
        }
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
    //echo $sellerstock.'<br>';
    //echo $amznotseller;exit;

    $result = array('sellerStock' => $sellerstock, 'amznotseller' => $amznotseller);
    return $result;
}


function getPage($url)
{
    try {
        $client = new \GuzzleHttp\Client();
        /*$response = $client->request(
            'GET',
            "https://api.scraperapi.com?key=c3f28aa3667ad3a2d65cf079d741e56f&url={$url}&country_code=us",
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'timeout' => 2
            ]
        );*/
        // date_default_timezone_set("America/New_York");
        // echo "111The time is " . date("h:i:sa");
	$url_encode = urlencode($url);
	//echo "https://app.scrapingbee.com/api/v1/?api_key=RNLU9L1X71OAZ0XXVX2EFZATDDZSU69L4900CMCWNOUJ0HBWV95AXKVLH6706OY4SAGJYDHIG98RGVCU&url={$url_encode}&country_code=us";
        $response = $client->request(
            'GET',
            "https://app.scrapingbee.com/api/v1/?api_key=RNLU9L1X71OAZ0XXVX2EFZATDDZSU69L4900CMCWNOUJ0HBWV95AXKVLH6706OY4SAGJYDHIG98RGVCU&url={$url_encode}&country_code=us",
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );
        // date_default_timezone_set("America/New_York");
        // echo "<br>222The time is " . date("h:i:sa");
        //echo "res===";
        //print_r($response->getStatusCode());
        if ($response->getStatusCode() == 200) {
            return $response->getBody()->getContents();
        } else {
            return FALSE;
        }
        
        
    } catch (Exception $e) {
       // echo "Exception";
        //echo $e;
        return FALSE;
    }
}


function getPageCurl($url) { 
    $http_head = array("Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Language:en-US,en;q=0.8",
        "Connection:keep-alive",
        "Upgrade-Insecure-Requests:1",
        "User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36");
    $scrapperurl = "https://api.scraperapi.com?key=c3f28aa3667ad3a2d65cf079d741e56f&url={$url}&country_code=us";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $scrapperurl); // Target URL
    //curl_setopt($ch, CURLOPT_PROXY, '195.154.161.93:5883'); // Proxy IP:Port
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, FALSE);

    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $http_head);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $result;
}

function get_stock2($asin, $sel_name)
{
    
    $sellersPageUrl = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new");
    //$sellersPageUrl = urlencode("https://www.amazon.com/gp/offer-listing/$asin/ref=dp_olp_new_mbc?ie=UTF8&condition=new&me=$seller");
    $sellerstock = array('0'=>0);
    $amznotseller = array('0'=>1);

    $html = getPage2($sellersPageUrl);

    if ($html !== FALSE) {
        phpQuery::newDocument($html);
        // foreach (pq('h3.olpSellerName') as $idPresent) {
        //     $sellerLink = @pq($idPresent)->find("a")->eq(0)->attr('href');
        //     if (getInBetweenStrings($sellerLink, 'seller=', '') == $seller) {
        //         $sellerStock = 1;
        //         break;
        //     } else if (getInBetweenStrings($sellerLink, 'seller=', '&') == $seller) {
        //         $sellerStock = 1;
        //         break;
        //     }
            
        // }

        foreach (pq('div#olpOfferList')->find('div.olpOffer') as $elements) {
           
            $seller_name = pq($elements)->find("h3.olpSellerName")->find('a')->text();
            if (empty($seller_name)) {
                $seller_name = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
            }
            
            
            if(strcmp($sel_name,$seller_name)==0) {
                $sellerstock[] = 1;
            }  else {
                $sellerstock[] = 0;
                //break;
            }
            $stock_url = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                    
            if ($stock_url == "Amazon.com") {
                $amznotseller[] = 0;
            }  else {
                $amznotseller[] = 1;
            }
            
        }

        
        //echo 'sss'.$seller_name;
        //echo 'dddd'.$sellerStock;exit;
        $no_of_pagination_links = pq('.a-pagination')->find('li')->length;
        $pagination_links = [];
        foreach (pq('.a-pagination')->find('li') as $el) {
            $link = pq($el)->find('a')->attr('href');
            if ($link && $link != '#') {
                array_push($pagination_links, $link);
            }
        }
        // foreach (pq('h3.olpSellerName') as $idPresent) {
        //     $stock_url = @pq($idPresent)->find("img")->eq(0)->attr('alt');
        //     if ($stock_url == 'Amazon.com') {
        //         $amznotseller[] = 0;
        //     break;
        //     }
        // }
        $paginated = $no_of_pagination_links > 0;
        if ($paginated) {
            $any_attempt_failed = false;
            foreach ($pagination_links as $link) {
                $next_page_url = "https://www.amazon.com{$link}";
                $html = getPage($next_page_url);

                if ($html !== FALSE) {
                    phpQuery::newDocument($html);
                    foreach (pq('h3.olpSellerName') as $idPresent) {
                        $seller_name = pq($elements)->find("h3.olpSellerName")->find('a')->text();
                        if (empty($seller_name)) {
                            $seller_name = pq($elements)->find("h3.olpSellerName")->find('img')->attr('alt');
                        }
                        
                        
                        if(strcmp($sel_name,$seller_name)==0) {
                            $sellerstock[] = 1;
                        }  else {
                            $sellerstock[] = 0;
                            //break;
                        }
                        $stock_url = @pq($idPresent)->find("img")->eq(0)->attr('alt');
                        if ($stock_url == "Amazon.com") {
                            $amznotseller[] = 0;
                        }  else {
                            $amznotseller[] = 1;
                        }
                    }
                } else {
                    $any_attempt_failed = true;
                  break;
                }
            }
           // if ($any_attempt_failed) {
                //return FALSE;
            //}
        }
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
    //echo $sellerstock.'<br>';
    //echo $amznotseller;exit;

    $result = array('sellerStock' => $sellerstock, 'amznotseller' => $amznotseller);
    return $result;
}


function getPage2($url)
{
    echo '<--INSIDE THE PROCESS-->';
    try {
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'GET',
            "https://api.scraperapi.com?key=c3f28aa3667ad3a2d65cf079d741e56f&url={$url}&country_code=us",
            [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]
        );
            echo $response->getStatusCode();
            echo"<---><pre>";
            print_r($response);
            echo"<---></pre>";
        if ($response->getStatusCode() == 200) {
            echo"<--Success block-->";
            return $response->getBody()->getContents();
        } else {
            echo '<--STATUS CODE EXCEPTION ELSE-->';
            return FALSE;
        }
    } catch (Exception $e) {
         echo '<--Err block-->';
            print_r($e->getMessage());
            echo '<==//THANK GOD\\==>';
            $response = $e->getResponse();
            print_r($response);
        return FALSE;
    }
}

function getInBetweenStrings($str, $start, $end)
{
    $matches = array();
    $regex = "/$start([a-zA-Z0-9_]*)$end/";
    preg_match_all($regex, $str, $matches);
    return reset($matches[1]);
}

function run_in_background($command, $priority = 0)
{
    if ($priority) {
        $output = exec('bash -c "exec nohup setsid nice -n $Priority '.$command.' > /dev/null 2>&1 &"');
    } else {
        $output = exec('bash -c "exec nohup setsid '.$command.' > /dev/null 2>&1 &"');
    }

    return($output);
}

function send_sms($number, $message)
{
	try {
		//$msg_encode = urlencode($message);
		$msg_encode = $message;
		//$number = '';
		//echo "https://platform.clickatell.com/messages/http/send?apiKey=sl6T7BOgTeymyNk-2RT9Sw==&to=1".$number."&content=".$msg_encode;
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'GET',
            "https://platform.clickatell.com/messages/http/send?apiKey=sl6T7BOgTeymyNk-2RT9Sw==&to=1".$number."&content=".$msg_encode,
            [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]
    );

	//echo $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();
	$responseBody = json_decode($responseBody);
	//print_r($responseBody);
	//echo $responseBody->messages[0]->accepted;
	if (isset($responseBody->messages[0]->accepted) && $responseBody->messages[0]->accepted == 1) {
		return TRUE;
	} else {
            return FALSE;
        }
    } catch (Exception $e) {
        return FALSE;
    }
}

