<?php
session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}

require_once 'RPC.php';
use rabbit\RPC;

$_SESSION['ForumID'] = $_GET['forumID'];

$threads_rpc = new RPC("getPosts");
$getThreads = serialize(array("getThreads", $_SESSION['ForumID']));

$forums_rpc = new RPC("getPosts");
$getForums = serialize(array("getForums"));

$responseThreads = $threads_rpc->call($getThreads);
$responseForums = $forums_rpc->call($getForums);
?>

<?php include 'html/threads_html.php' ?>