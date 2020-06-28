<?php
require_once '../vendor/autoload.php';
use MongoDB\Client;

class MongoConnector {
	private $connection;

	private $host = 'mongodb:27017';
	private $user = 'root';
	private $pass = 'pass';

	public function __construct(){
		$this->connection = new Client('mongodb://' . $this->host,
		array (
			'username' => $this->user,
			'password' => $this->pass
		),
		array (
			'typeMap' => array (
				'document' => 'array',
				'root' => 'array'
			)
		)
	);
	}
	public function getConnection(){
		return $this->connection;
	}

	public static function initUserStorage($username) {
		$mongo_client = (new MongoConnector())->getConnection();
		$userObjects = $mongo_client->site->users;

		$newObject = array (
			'username' => $username,
			'characters' => array(),
			'parties_in' => array(),
			'parties_managed' => array(),
			'notifications' => array()
		);

		$userObjects->insertOne($newObject);
	}
}
?>