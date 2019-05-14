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

// User Update: Retrieving
$userRetrieve_callback = function ($request) {
	$logger = new LogWriter('/var/log/dnd/backend.log');
	$client = (new MongoConnector())->getConnection();
	$usersCollection = $client->site->users;
	$username = $request->body;
	$error = "E";
	
	try {
		$logger->info("Getting $username's object");
		$userDocument = $usersCollection->findOne(array('username' => $username));
		if($userDocument === null){
			throw new Exception('JSON could not be found.');
		}
			
		$msg = new AMQPMessage (
			serialize($userDocument),
			array('correlation_id' => $request->get('correlation_id'))
		);
		
	} catch(Exception $e) {
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

echo "userRetrieve.php is starting\n";
while (true) {
	$rmq_channel->wait();
}

echo "userRetrieve.php is closing\n";
$rmq_connection->close();
?>
