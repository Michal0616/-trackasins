<?php

echo file_get_contents("http://api.clickatell.com/http/sendmsg?user=nateadmin&password=eTcZDaXPRFacGY&api_id=3593336&to=+917879450278&text=Hello");
// messageSend();

// function messageSend(){
// //Your authentication key
// $authKey = "nateadmin";
// //Sender ID,While using route4 sender id should be 6 characters long.
// //$senderId = "DealFz";

// //Define route 
// //$route = "4";

// /*http/sendmsg?
// user=nateadmin
// &password=eTcZDaXPRFacGY
// &api_id=3593336
// &to=+919691853143&
// text=Hello*/

// //Prepare you post parameters
// $postData = array(
// 'user' => $authKey,
// 'password' => 'eTcZDaXPRFacGY',
// 'api_id' => '3593336',
// 'to' => '+919981101934',
// 'text' => 'hello',
// 'response'=> 'json',
// 'ignoreNdnc'=>1
// );

// //API URL
// $url="http/sendmsg?user=nateadmin&password=eTcZDaXPRFacGY&api_id=3593336&to=+919981101934&text=Hello Bad boy";
// print_r($url);

// // init the resource
// $ch = curl_init();
// curl_setopt_array($ch, array(
// CURLOPT_URL => $url,
// CURLOPT_RETURNTRANSFER => true,
// CURLOPT_POST => true,
// CURLOPT_POSTFIELDS => $postData
// ));

// //Ignore SSL certificate verification
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// //get response
// $output = curl_exec($ch);

// //Print error if any
// if(curl_errno($ch)){
// echo 'error:' . curl_error($ch);
// }

// curl_close($ch);

// $json = json_decode($output, true);
// return $json['type'];
// }

// /*$url = 'http/sendmsg?user=nateadmin&password=eTcZDaXPRFacGY&api_id=3593336&to=+919691853143&text=Hello';
// $ch = curl_init(); 
// curl_setopt($ch,CURLOPT_URL,$url);
// curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
// $output=curl_exec($ch);
// if(curl_errno($ch))
// {
//     echo 'error:' . curl_error($c);
// }
// print_r($url);
// curl_close($ch);
// */


// 'user' = "nateadmin";
// 'password' = "eTcZDaXPRFacGY";
// 'api_id'  = "3593336"; // Can come from a database
// 'msg'  = "Hello";
// $url  = "http/sendmsg?user=nateadmin&password=eTcZDaXPRFacGY&api_id=3593336&to=+919691853143&text=Hello";
// $xml  = file_get_contents($url);
?>