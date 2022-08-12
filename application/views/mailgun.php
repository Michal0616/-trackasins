<?php 
//require 'vendor/autoload.php';
//use Mailgun\Mailgun;

# Instantiate the client.
$mgClient = new Mailgun('f14cf94304da5471b926ec3e4487773f');
$domain = "https://api.mailgun.net/v3/trackasins.com";
/*$domain = "https://api.mailgun.net/v3/mailgun.************.com/messages";*/

# Make the call to the client.
$result = $mgClient->sendMessage($domain, array(
    'from'    => 'postmaster@trackasins.com',
    'to'      => 'soren@celavier.com',
    'subject' => 'Hello mail testing through mail testing',
    'text'    => 'can you get my mail...'
));
?>
