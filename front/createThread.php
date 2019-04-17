<?php
session_start();
require_once 'RPC.php';
use rabbit\RPC;

if(!empty($_POST)){
	$createThreads_rpc = new RPC("createPosts");
	$threadINFO = array($_SESSION['ForumID'], $_POST['Name'], $_POST['Content'], $_SESSION['username']);
	$createThreads = serialize(array("createThread", $threadINFO));
	$response = $createThreads_rpc->call($createThreads);
	if ($response==="S"){
		header('Location: threads.php?forumID='.$_SESSION['ForumID']);
	}
	else {
		header('Location: createThread.php?success=F');
	}
}

if(isset($_GET['success'])){
	if($_GET['success']==="F"){
		echo "<script type='text/javascript'>alert('There was an error in creating a thread. Try Again.');</script>";
	}
}

?>

<?php include 'header.php';?>

<!-- Begin Page Content -->
<div class="container-fluid">
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Create a thread !</h1>
	<div class="content">
		<h1>Create a Forum Thread</h1>
		<form action="" id="createThread" method="POST">
			Title:
			<input type="text" name="Name" required>
			<br><br>
			Content:<br> <textarea name="Content" form="createThread" required></textarea>
			<br><br>
			<input type="submit" name="createThreadSubmit" value="Create Thread">
		</form>
	</div>
</div>
<!-- /.container-fluid -->

<?php include 'footer.php';?>
