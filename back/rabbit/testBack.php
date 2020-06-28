<?php
require_once '../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConnection {

	private $connection;
	private $channel;
	private $queue_name;

	public function __construct($user, $exchange, $vhost) {
		$connection = new AMQPStreamConnection (
			'rabbit', // host
			5672, // port
			$user, // user
			'pass', // pass
			$vhost  // virtual host
		);
		$this->channel  = $connection->channel();

		$this->channel->exchange_declare($exchange, 'direct', false, false, false);
		list($this->queue_name, ,) = $this->channel->queue_declare('', false, false, true, false);
		$this->channel->queue_bind($this->queue_name, $exchange, $exchange . '_req');
	}

	public function getQueueName() {
		return $this->queue_name;
	}

	public function getChannel() {
		return $this->channel;
	}

	public function close() {
		$this->channel->close();
		$this->connection->close();
	}
}

$rmq_connection = new RabbitMQConnection('auth_user', 'RegisterExchange', 'authentication');
$rmq_channel = $rmq_connection->getChannel();

//register
$register_callback = function ($request) {
	echo $request->body;
	$msg = new AMQPMessage (
		"goodbye",
		array('correlation_id' => $request->get('correlation_id'))
	);

	$request->delivery_info['channel']->basic_publish( $msg, '', $request->get('reply_to'));
};

$rmq_channel->basic_qos(null, 1, null);
$rmq_channel->basic_consume($rmq_connection->getQueueName(), '', false, true, false, false, $register_callback);

echo 'testBack.php started';
while (true) {
	$rmq_channel->wait();
}

echo 'Closing rabbit connection';
$rmq_connection->close();