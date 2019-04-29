<?php
session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}

require_once 'RPC.php';
use rabbit\RPC;

$replies_rpc = new RPC("getPosts");
$_SESSION['ThreadID'] = $_GET['threadID'];
$getReplies = serialize(array("getReplies", $_SESSION['ThreadID']));
$response = $replies_rpc->call($getReplies);

$threads_rpc = new RPC("getPosts");
$getThread = serialize(array("getThread", $_SESSION['ThreadID']));
$response1 = $threads_rpc->call($getThread);

if(!empty($_POST)){
	$createReplies_rpc = new RPC("createPosts");
	$replyINFO = array($_SESSION['ThreadID'], $_POST['ReplyContent'], $_SESSION['username']);
	$createReplies = serialize(array("createReply", $replyINFO));
	$response2 = $createReplies_rpc->call($createReplies);
	if ($response2==="S"){
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