<?php
session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}

require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use rabbit\RPC;
use logging\LogWriter;

$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('forums.php accessed');

$forums_rpc = new RPC("getPosts");
$getForums = serialize(array("getForums"));
$responseForums = $forums_rpc->call($getForums);

// Testing Purpose Only
// $logger->debug($responseForums);
?>

<?php include 'html/forums_html.php' ?>