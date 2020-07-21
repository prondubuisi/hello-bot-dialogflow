<?php 
require 'vendor/autoload.php'; 
use  App\VerifyBot;
use App\ReceivedMessage;
use App\SendMessage;
use DialogFlow\Client;


$verifyBot = new VerifyBot();




 
$input = json_decode(file_get_contents('php://input'), true);




$receivedMessage = new ReceivedMessage($input);
$sendMessage = new SendMessage($input);

 
if(!empty($receivedMessage->textMessage)) {   

	 $response = callDialogflow($receivedMessage->textMessage);
     $sendMessage->text($response);
     
}

function callDialogflow($textMessage) {

	try {

	    $client = new Client('access_token');

	    $query = $client->get('query', [
	        'query' => $textMessage,
	        'sessionId' => time(),
	    ]);

	    $response = json_decode((string) $query->getBody(), true);
	    $response =  $response['result']['fulfillment']['speech'];

	    return $response;


    } catch (\Exception $error) {
    
	    error_log($error);
	}

}



 



 
