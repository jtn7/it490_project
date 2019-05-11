<?php
require_once '../vendor/autoload.php';
require_once '../databases/MongoConnector.php';
require_once '../rabbit/RabbitMQConnection.php';
require_once '../logging/LogWriter.php';
use PhpAmqpLib\Message\AMQPMessage;
use rabbit\RabbitMQConnection;
use databases\MongoDB;
use logging\LogWriter;
$rmq_connection = new RabbitMQConnection('storage_user', 'CharacterObjectExchange', 'storage');
$rmq_channel = $rmq_connection->getChannel();
// Character Update: Retrieving
$characterRetrieve_callback = function ($request) {
	$logger = new LogWriter('/var/log/dnd/characterRetrieval.log');
	$client = (new MongoDB())->getConnection();
	$charactersCollection = $client->site->characters;
	$username = $request;
	$error = "E";
	
	try{
	$logger->info("Getting Characters");
	$characterDocument = $charactersCollection->find(['username' => $username]);
	if($characterDocument === NULL){
	throw new Exception('JSON could not be found.');
	}
	$characters = iterator_to_array($characterDocument);
	
	$msg = new AMQPMessage (
	serialize($characters),
	array('correlation_id' => $request->get('correlation_id'))
	);
	$logger->info("document found: " . $characters);
	}catch(Exception $e){
		$msg = new AMQPMessage (
			$error,
			array('correlation_id' => $request->get('correlation_id')));
		$logger->error("Error occurred:" . $e->getMessage());
	};
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
