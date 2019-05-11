<?php
require_once '../vendor/autoload.php';
require_once '../databases/MongoConnector.php';
require_once '../rabbit/RabbitMQConnection.php';
require_once '../logging/LogWriter.php';
use PhpAmqpLib\Message\AMQPMessage;
use rabbit\RabbitMQConnection;
use databases\MongoDB;
use logging\LogWriter;
$rmq_connection = new RabbitMQConnection('storage_user', 'UserObjectExchange', 'storage');
$rmq_channel = $rmq_connection->getChannel();

// User Update: Retrieving
$userRetrieve_callback = function ($request) {
	$logger = new LogWriter('/var/log/dnd/userRetrieval.log');
	$client = (new MongoConnector())->getConnection();
	$usersCollection = $client->site->users;
	$username = $request;
	$error = "E";
	
	try{
	$logger->info("Getting User doc...");
	$userDocument = $usersCollection->find(['username' => $username]);
		
	if($userDocument === NULL){
	throw new Exception('JSON could not be found.');
	}
		
	$msg = new AMQPMessage (
	$userDocument,
	array('correlation_id' => $request->get('correlation_id'))
	);
	$logger->info("document found: " . $userDocument);
		
	}catch(Exception $e){
		$msg = new AMQPMessage (
			$error,
			array('correlation_id' => $request->get('correlation_id')));	
		$logger->error("Error occurred:" . $e->getMessage());
	}
	
    $request->delivery_info['channel']->basic_publish( $msg, '', $request->get('reply_to'));
    $logger->info("Sent back Message");
};
$rmq_channel->basic_qos(null, 1, null);
$rmq_channel->basic_consume($queue_name, '', false, true, false, false, $userRetrieve_callback);
while (true) {
	$rmq_channel->wait();
}
$rmq_connection->close();
?>
