<?php
require_once '../vendor/autoload.php';
require_once 'databases/AuthDB.php';
require_once 'rabbit/RabbitMQConnection.php';
require_once 'logging/LogWriter.php';
require_once 'databases/MongoConnector.php';
use PhpAmqpLib\Message\AMQPMessage;
use logging\LogWriter;
use rabbit\RabbitMQConnection;
const CORR_ID = "correlation_id";

$rmq_connection = new RabbitMQConnection('auth_user', 'RegisterExchange', 'authentication');
$rmq_channel = $rmq_connection->getChannel();

//register
$register_callback = function ($request) {
	$mysql_connection = (new AuthDB())->getConnection();
	$logger = new LogWriter('/var/log/dnd/backend.log');
	$logger->info("Registering user...");
	$requestData = unserialize($request->body);
	$username = $requestData[0];
	$pass = $requestData[1];
	$error = "E";
	$success = "S";
	$passhash = password_hash($pass, PASSWORD_DEFAULT);

	$msg = new AMQPMessage (
		$error,
		array(CORR_ID => $request->get(CORR_ID))
	);

	try {
		// calling stored procedure command
		$sql = "CALL createUser(?,?)";

		// prepare for execution of the stored procedure
		$stmt = $mysql_connection->prepare($sql);

		// pass value to the command
		$stmt->bindParam(1, $username, PDO::PARAM_STR);
		$stmt->bindParam(2, $passhash, PDO::PARAM_STR);

		// execute the stored procedure
		$stmt->execute();

		$msg = new AMQPMessage (
			$success,
			array(CORR_ID => $request->get(CORR_ID))
		);

		$logger->info("User $username registered successfully");

		$logger->info("Creating User object for $username");
		MongoConnector::initUserStorage($username);

	} catch (PDOException $e) {
		$logger->error("Error occurred:" . $e->getMessage());
	}

	$request->delivery_info['channel']->basic_publish($msg, '', $request->get('reply_to'));
	$logger->info("Delivered Message");

};

$rmq_channel->basic_qos(null, 1, null);
$rmq_channel->basic_consume($rmq_connection->getQueueName(), '', false, true, false, false, $register_callback);

echo "register.php is starting\n";
while (true) {
	$rmq_channel->wait();
}

echo "register.php is closing\n";
$rmq_connection->close();
?>
