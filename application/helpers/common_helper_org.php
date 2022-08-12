<?php
/**
 * Created by IntelliJ IDEA.
 * User: anhnguyen
 * Date: 10/10/16
 * Time: 6:15 PM
 */

require __DIR__ . '/../libraries/phpQuery/phpQuery.php';
require __DIR__ . '/../libraries/dompdf/autoload.inc.php';

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


function getPage($url)
{
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
        if ($response->getStatusCode() == 200) {
            return $response->getBody()->getContents();
        } else {
            return FALSE;
        }
    } catch (Exception $e) {
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
        $client = new \GuzzleHttp\Client();
        $response = $client->request(
            'GET',
            "https://platform.clickatell.com/messages/http/send?apiKey=sl6T7BOgTeymyNk-2RT9Sw==&to=1".$number."&content=".$message,
            [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]
        );
        $responseBody = $response->getBody()->getContents();
        $responseBody = json_decode($responseBody);
        if (isset($responseBody->error) && $responseBody->error === null) {
            return TRUE;
        } else {
            return FALSE;
        }
    } catch (Exception $e) {
        return FALSE;
    }
}
