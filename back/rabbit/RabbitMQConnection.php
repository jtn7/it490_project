<?php
namespace rabbit;
require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnection {

	private $connection;
	private $channel;
	private $queue_name;

	public function __construct($user, $exchange, $vhost) {
		// Create a cURL handle
		$ch = curl_init('http://rabbitNode/health');
		$connection = null;

		// Execute
		curl_exec($ch);

		// Check HTTP status code
		if (!curl_errno($ch)) {
			$this->connection = new AMQPStreamConnection(
				'rabbitNode', // host
				5672, // port
				$user, // username
				'pass', // password
				$vhost, //vhost
			);
		} else {
			$this->connection = new AMQPStreamConnection(
				'rabbitNode2', // host
				5672, // port
				$user, // username
				'pass', // password
				$vhost, //vhost
			);
		}
		curl_close($ch);

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