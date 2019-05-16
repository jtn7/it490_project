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
$logger->info('replies.php accessed');

$logger->info('get replies RPC called');
$replies_rpc = new RPC("getPosts");
$_SESSION['ThreadID'] = $_GET['threadID'];
$getReplies = serialize(array("getReplies", $_SESSION['ThreadID']));
$responseGetReplies = $replies_rpc->call($getReplies);

$logger->info('get thread RPC called');
$threads_rpc = new RPC("getPosts");
$getThread = serialize(array("getThread", $_SESSION['ThreadID']));
$responseGetThread = $threads_rpc->call($getThread);

if(!empty($_POST)){
	$logger->info('creating the post');
	$createReplies_rpc = new RPC("createPosts");
	$replyINFO = array($_SESSION['ThreadID'], $_POST['ReplyContent'], $_SESSION['username']);
	$createReplies = serialize(array("createReply", $replyINFO));
	$responseCreateReply = $createReplies_rpc->call($createReplies);
	
	if ($responseCreateReply==="S"){
		// $logger->info('creating the notification');
		// date_default_timezone_set('America/New_York');
		// $time = date('F jS Y h:i A');
		// $createNotifications_rpc = new RPC("createNotifications");
		// $message = $_SESSION['username'] . " replied to " . $Thread['Name'];
		// $replyINFO = array($_SESSION['ThreadID'], $message, $time);
		// $notificationContent = serialize($replyINFO);
		// $createNotifications_rpc->call($notificationContent);
		header('Refresh:0');
	}
	else {
		header('Location: replies.php?threadID=' . $_SESSION['ThreadID'] . '&success=F');
	}
}

if(isset($_GET['success']) && $_GET === 'F'){
	echo "<script type='text/javascript'>alert('There was an error in creating a thread. Try Again.');</script>";
}
?>

<?php include 'html/replies_html.php' ?>