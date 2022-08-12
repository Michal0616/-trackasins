<?php 
    $notification_data = $this->db->query("SELECT * FROM notification ")->result();
                        /*echo "<pre>";*/
                        print_r($notification_data);
                        /*echo "</pre>";*/
                        // exit;
    //                     foreach ($notification_data as $amznoti) {
    //                         echo "string0";
    //                         //$amznot = $amznoti->amznotseller;                        
    //                         //$query = $this->db->query("UPDATE `notification` SET `amzoutofstock`='0' WHERE `amznotseller`='$amznot'");
    //                         //print_r($query);
    //                         /*if($query)
    //                         {
    //                         echo"done";                            
    //                         }else{
    //                         echo"failed";
    //                         }*/

    //                        // if($amznot==0)
    //                         { 
    //                             //echo "string-1";
    //                             //echo "string";
    //                                 $user_id2       = $amznoti->user_id;
    //                                 $asin2          = $amznoti->asin;
    //                                 $image2         = $amznoti->image;
    //                                 $amznotseller2  = $amznoti->amznotseller;
    //                                 $title_name2    = $amznoti->title_name;
    //                                 $tracking2      = $amznoti->tracking;
    //                                 $sellerstock2   = $amznoti->sellerstock;
    //                                 $date2          = $amznoti->date;
    //                                 $data = array(
    //                                             'user_id'       => $user_id2,
    //                                             'image'        => $image2,
    //                                             'title_name'    => $title_name2,
    //                                             'asin'          => $asin2,
    //                                             'sellerstock'   => $sellerstock2,
    //                                             'amznotseller'  => $amznotseller2,
    //                                             'date'          => $date2,
    //                                             'amzoutofstock' => 0,
    //                                             'cronid'        => 1);
    //                                 //echo "string-2";
    //                                     $this->common_model->insertData('notification',$data);
    //                         }
    //                         //echo "string-3";

    // }
require 'vendor/autoload.php';
use Mailgun\Mailgun; 
$mgClient = new Mailgun('key-f14cf94304da5471b926ec3e4487773f');
$domain = "trackasins.com";

# Make the call to the client.
$result = $mgClient->sendMessage("$domain",
      array(			'from'    => '<postmaster@trackasins.com>',
                        'to'      => 'sameer <sameerchouhan7852@gmail.com>',
                        'subject' => 'Hello ',
                        'text'    => 'abcdef'));
if($result)
{
	echo 'mail successfully send...';
}



/*function messageSend($message,$mobileNumber){
	echo 'hi';
//Your authentication key
//$authKey = "75568A0nZox8z9548029cb";
//Sender ID,While using route4 sender id should be 6 characters long.
//$senderId = "DealFz";

//Define route 
//$route = "4";

//Prepare you post parameters
$postData = array(
'user' => 'nateadmin',
'password' => 'eTcZDaXPRFacGY',
'api_id' => '3593336',
'to' => '+919691853143',
'text' => 'hello',
'response'=> 'json',
'ignoreNdnc'=>1
);

//API URL
$url="http/sendmsg";

// init the resource
$ch = curl_init();
curl_setopt_array($ch, array(
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_POST => true,
CURLOPT_POSTFIELDS => $postData
));

//Ignore SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

//get response
$output = curl_exec($ch);

//Print error if any
if(curl_errno($ch)){
echo 'error:' . curl_error($ch);
}

curl_close($ch);

$json = json_decode($output, true);
return $json['type'];
}
*/
/////////////---------------message api------------/////////////

// Create a Clockwork object using your API key
/*$clockwork = new Clockwork( '7d5928a2c1fbad0d28fa4ef757891dcd457243ff' );

// Setup and send a message
$message = array( "to" => "9691853143", "message" => "Hello World" );
$result = $clockwork->send( $message );

// Check if the send was successful
if( $result["success"] ) {
    echo "Message sent - ID: " . $result["id"];
} else {
    echo "Message failed - Error: " . $result["error_message"];
}*/
////////////----------------message and------------////////////

//  require 'vendor/autoload.php';
//  use Mailgun\Mailgun;

// // # Instantiate the client.
//  $mgClient 	= new Mailgun('f14cf94304da5471b926ec3e4487773f');
//  $domain 	= "trackasins.com";
// // /*$domain = "https://api.mailgun.net/v3/mailgun.************.com/messages";*/
// //http://baycodes.net/pr/trackasins/
// // # Make the call to the client.

// $result = $mgClient->sendMessage($domain, array(
//     'from'    => 'mailgun@trackasins.com',
//     'to'      => 'sanjaychouhan512@gmail.com',
//     'subject' => 'Hello mail testing through mail testing',
//     'text'    => 'can you get my mail...'
// ));
/*define('MAILGUN_KEY', 'f14cf94304da5471b926ec3e4487773f');
define('MAILGUN_DOMAIN', 'trackasins.com');

$mailgun = new Mailgun\Mailgun('f14cf94304da5471b926ec3e4487773f');

$mailgun->sendMessage('trackasins.com', [
                'from'      => 'noreply@signstoptt.com',
                'to'        => 'sanjaychouhan512@gmail.com',
                'subject'   => 'Sign Stop mailing list confirmation.',
                'text'      => 'Hello'
                 
            ]);*/

// <!-- curl -s --user 'api:YOUR_API_KEY' \
//     https://api.mailgun.net/v3/YOUR_DOMAIN_NAME/messages \
//     -F from='Excited User <mailgun@YOUR_DOMAIN_NAME>' \
//     -F to=YOU@YOUR_DOMAIN_NAME \
//     -F to=bar@example.com \
//     -F subject='Hello' \
//     -F text='Testing some Mailgun awesomness!' -->
?>

