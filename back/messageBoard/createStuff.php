<?php
require_once '../vendor/autoload.php';
require_once 'databases/ForumsDB.php';
require_once 'databases/MongoConnector.php';
require_once 'rabbit/RabbitMQConnection.php';
require_once 'logging/LogWriter.php';
use PhpAmqpLib\Message\AMQPMessage;
use rabbit\RabbitMQConnection;
use logging\LogWriter;

// TODO Rename this file
$rmq_connection = new RabbitMQConnection('forums_user', 'CreatePostsExchange', 'messageBoard');
$rmq_channel = $rmq_connection->getChannel();

//Create Stuff
$createStuff_callback = function ($request) {
	$logger = new LogWriter('/var/log/dnd/backend.log');
	$logger->info("Sending post to database...");
	$requestData = unserialize($request->body);
	$requestFlow = $requestData[0];
	$requestParams = $requestData[1];
	$success = 'S';
	$error = 'E';

	$msg = new AMQPMessage (
		$error,
		array('correlation_id' => $request->get('correlation_id'))
	);

	$pdo = (new ForumsDB())->getConnection();

	$logger->info("connected to forum database");

	try {
		switch($requestFlow){
			case "createThread":
				$forumID = $requestParams[0];
				$name = $requestParams[1];
				$content = $requestParams[2];
				$user = $requestParams[3];
				$sql = "CALL createThread(?,?,?,?,@tid)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(1, $forumID, PDO::PARAM_INT);
				$stmt->bindParam(2, $name, PDO::PARAM_STR);
				$stmt->bindParam(3, $content, PDO::PARAM_STR);
				$stmt->bindParam(4, $user, PDO::PARAM_STR);
				$stmt->execute();
				$stmt->closeCursor();
				$logger->info("Created Thread");

				$logger->info("Adding $user as a subscriber");
				// Get the thread ID for the thread that we created
				$row = $pdo->query("SELECT @tid AS threadID")->fetch(PDO::FETCH_ASSOC);
				if ($row !== false) {
					$client = (new MongoConnector())->getConnection();
					$threadSubs = $client->site->threadSubs;
					$threadSubs->insertOne(
						array(
							'threadID' => $row['threadID'],
							'subscribers' => array($user)
						)
					);
					$logger->info("$username was successfully added as a subscriber");

					$msg = new AMQPMessage (
						$success,
						array('correlation_id' => $request->get('correlation_id'))
					);
				}
				break;
			case "createReply":
				$logger->debug(serialize($requestParams));
				$threadID = $requestParams[0];
				$content = $requestParams[1];
				$user = $requestParams[2];
				$sql = "CALL createReply(?,?,?)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(1, $threadID, PDO::PARAM_INT);
				$stmt->bindParam(2, $content, PDO::PARAM_STR);
				$stmt->bindParam(3, $user, PDO::PARAM_STR);
				$stmt->execute();
				$stmt->closeCursor();
				$logger->info("Created Reply");

				$sql = "CALL getThread(?)";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(1, $threadID, PDO::PARAM_INT);
				$stmt->execute();
				$threadName = ($stmt->fetch(PDO::FETCH_ASSOC))['Name'];

				// Create notifications for users
				$logger->info('Updating user objects');
				date_default_timezone_set('America/New_York');
				$time = date('F jS Y h:i A');
				$notifObject = array(
					'msg' => "$user replied to $threadName",
					'time' => $time
				);
				$client = (new MongoConnector())->getConnection();
				$threadSubs = $client->site->threadSubs;
				$userCollection = $client->site->users;

				$subsDoc = $threadSubs->findOne(['threadID' => $threadID]);
				$logger->debug('subs array below');
				$logger->debug($subsDoc['subscribers']);
				foreach ($subsDoc['subscribers'] as $subscriber) {
					$logger->debug("Sub is $subscriber");
					$userCollection->updateOne(
						['username' => $user],
						['$push' => ['notifications' => $notifObject]],
						["upsert" => true]
					);
				}

				$msg = new AMQPMessage (
					$success,
					array('correlation_id' => $request->get('correlation_id'))
				);
				$logger->info("Reply created successfully");
				break;
		}
	} catch (PDOException $e) {
		$logger->error('Error occured: ' . $e->getMessage());
	}

	$request->delivery_info['channel']->basic_publish($msg, '', $request->get('reply_to'));
	$logger->info("Delivered Message");
};

$rmq_channel->basic_qos(null, 1, null);
$rmq_channel->basic_consume($queue_name, '', false, true, false, false, $createStuff_callback);

echo "createStuff.php is starting\n";
while (true) {
	$rmq_channel->wait();
}

echo "createStuff.php is closing\n";
$rmq_connection->close();
?>