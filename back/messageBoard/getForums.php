<?php
require_once '../vendor/autoload.php';
require_once 'databases/ForumsDB.php';
require_once 'rabbit/RabbitMQConnection.php';
require_once 'logging/LogWriter.php';
use PhpAmqpLib\Message\AMQPMessage;
use rabbit\RabbitMQConnection;
use logging\LogWriter;

$rmq_connection = new RabbitMQConnection('forums_user','GetPostsExchange', 'messageBoard');
$rmq_channel = $rmq_connection->getChannel();

//get forums
$GetForums_callback = function ($request) {
	$logger = new LogWriter('/var/log/dnd/backend.log');
	$db_connection = (new ForumsDB())->getConnection();

	$requestData = unserialize($request->body);
	$reqStr = $requestData[0];
	$id = $requestData[1];
	$error = 'E';

	try {

		switch ($reqStr) {
			case "getForums":
				$userReq = "CALL getForums()";
				$stmt = $db_connection->prepare($userReq);
				$logger->info("getting forums");
				$stmt->execute();
				$db_response = $stmt->fetchAll();
				$logger->debug($db_response);
				break;
			case "getThreads":
				$logger->info("getting threads for user");
				$userReq = "CALL getThreads(?)";
				$stmt = $db_connection->prepare($userReq);
				$stmt->bindParam(1, $id, PDO::PARAM_INT);
				$stmt->execute();
				$db_response = $stmt->fetchAll();
				break;
			case "getThread":
				$logger->info("getting thread for given ID");
				$userReq = "CALL getThread(?)";
				$stmt = $db_connection->prepare($userReq);
				$stmt->bindParam(1, $id, PDO::PARAM_INT);
				$stmt->execute();
				$db_response = $stmt->fetch(PDO::FETCH_ASSOC);
				break;
			case "getReplies":
				$logger->info("getting replies for given thread");
				$userReq = "CALL getReplies(?)";
				$stmt = $db_connection->prepare($userReq);
				$stmt->bindParam(1, $id, PDO::PARAM_INT);
				$stmt->execute();
				$db_response = $stmt->fetchAll();
				break;
			default:
				$msg = new AMQPMessage (
					$error,
					array('correlation_id' => $request->get('correlation_id'))
				);
		}


		$serialized_array=serialize($db_response);

		$msg = new AMQPMessage (
			$serialized_array,
			array('correlation_id' => $request->get('correlation_id'))
		);

	} catch (PDOException $e) {
		$logger->error("Error occurred:" . $e->getMessage());
	}

	$request->delivery_info['channel']->basic_publish($msg, '', $request->get('reply_to'));
	$logger->info("Sent back Message");
};


$rmq_channel->basic_qos(null, 1, null);
$rmq_channel->basic_consume($queue_name, '', false, true, false, false, $GetForums_callback);

echo "getForums.php is starting\n";
while (true) {
	$rmq_channel->wait();
}

echo "getForums.php is closing\n";
$rmq_connection->close();
?>