<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

$logger = new LogWriter('/var/log/dnd/frontend.log');

$logger->info('sign up page accessed');
if(!empty($_POST)){
	$logger->info('POST recieved');
	$logger->info($_POST);
	$signup_rpc = new RPC("register");
	$user = $_POST['signUN'];
	$usernamepasswd = serialize(array($user, $_POST['signPW']));
	
	$response = $signup_rpc->call($usernamepasswd);
	if ($response==="S"){
		$logger->info('Successful Registration');
		header("Location: login.php?signup=S");
	}
	else {
		header("Location: signup.php?signup=F");
	}
}
?>

<?php include 'html/signup_html.php' ?>