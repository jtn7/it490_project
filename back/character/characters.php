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
// Character Update: Storing
$characterStore_callback = function ($request) {
	$logger = new LogWriter('/var/log/dnd/backend.log');
	$client = (new MongoDB())->getConnection();
	$characters = $client->site->characters;
	$reqArray = unserialize($request->body);
	$logger->info("Character CRUD operation");
	$logger->debug($reqArray);
	$operation = $reqArray[0];
	$document = $reqArray[1];
	$error = "E";
	$success = "S";
	$msg = new AMQPMessage (
		$error,
		array('correlation_id' => $request->get('correlation_id'))
	);
	try {
		switch ($operation) {
			case 'createCharcter':
				$logger->info("Updataing character");
				$characters->insertOne($document);
				break;
			case 'getCharacters':
				$logger->info("Getting character");
				$characters->find();
				break;
		}
	} catch (\Throwable $th) {
		//throw $th;
	}
	
	$msg = new AMQPMessage (
		$success,
		array('correlation_id' => $request->get('correlation_id'))
	);
	$logger->info("Update success");
	$request->delivery_info['channel']->basic_publish( $msg, '', $request->get('reply_to'));
	$logger->info("Sent back message");
};
while (true) {
	$rmq_channel->wait();
}
$rmq_connection->close();
?>
