<?php
require_once '../vendor/autoload.php';
require_once 'databases/MongoConnector.php';
require_once 'rabbit/RabbitMQConnection.php';
require_once 'logging/LogWriter.php';
use PhpAmqpLib\Message\AMQPMessage;
use rabbit\RabbitMQConnection;
use databases\MongoDB;
use logging\LogWriter;

$rmq_connection = new RabbitMQConnection('storage_user', 'UserObjectExchange', 'storage');
$rmq_channel = $rmq_connection->getChannel();

// User Update: Storing
$userStore_callback = function ($request) {
	$logger = new LogWriter('/var/log/dnd/backend.log');
	$client = (new MongoDB())->getConnection();
	$database = $client->site->users;

	$reqArray = unserialize($request->body);
	$stuffID = $reqArray[0];
	$document = $reqArray[1];
	$error = "E";
	$success = "S";
	
	$msg = new AMQPMessage (
		$error,
		array('correlation_id' => $request->get('correlation_id'))
	);

	$logger->info("Updating User doc...");
	$database->updateOne(
		['_id' => $stuffID],
		[$document],
		["upsert" => true]
		);

	$msg = new AMQPMessage (
		$success,
		array('correlation_id' => $request->get('correlation_id'))
		);
	$logger->info("Update success");


	$request->delivery_info['channel']->basic_publish( $msg, '', $request->get('reply_to'));
	$logger->info("Sent back message");
};

echo "UserStore.php is starting\n";
while (true) {
	$rmq_channel->wait();
}

echo "UserStore.php is closing\n";
$rmq_connection->close();
?>
