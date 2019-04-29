<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

error_reporting(E_ERROR | E_PARSE);

$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('index.php accessed');

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}
?>

<?php include 'html/index_html.php' ?>