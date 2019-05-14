<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

error_reporting(E_ERROR | E_PARSE);

$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('characters.php accessed');

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}

$characters = array();

if (!isset($_GET['load'])) {
	$user_rpc = new RPC('Characters');
	$rpc_request = serialize(array("getCharacters", $_SESSION['username']));
	$response = $user_rpc->call($rpc_request);
	// echo "<h1 style='color: white'>";
	if ($response === 'E') {
		header('Location: index.php?load=F');
	} else {
		$logger->info('Successfully got characters');
		$logger->debug('Response: ' . $response);
		$characters = unserialize($response);
		$logger->debug($characters);
	}
}
?>

<?php include 'html/characters_html.php' ?>