<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

error_reporting(E_ERROR | E_PARSE);

$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('party.php accessed');

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}

$party_rpc = array();

if (!isset($_GET['load'])) {
	$user_rpc = new RPC('RetrieveJSON');
	$rpc_request = serialize(array("getUserStore", $_SESSION['username']));
	$response = $user_rpc->call($rpc_request);
	if ($response !== 'E') {
		$logger->info('Successfully got party');
		$logger->debug('Response: ' . $response);
		$party_rpc = unserialize($response);
		$logger->debug($party_rpc);
	} else {
		header('Location: index.php?load=F');
	}
}
?>

<?php include 'html/party_html.php' ?>