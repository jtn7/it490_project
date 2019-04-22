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

<?php include 'header.php' ?>

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
</div>

	<!-- Content Row -->
	<div class="row">

		<!-- Content Column -->
		<div class="col-12">

			<!-- Project Card -->
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Create a Thread !</h6>
				</div>
				<div class="card-body">
					<div class="content">
						<form action="" id="createThread" method="POST">
							Title:
							<input type="text" name="Name" required>
							<br><br>
							Content:<br> <textarea name="Content" form="createThread" required></textarea>
							<br><br>
							<input class="btn btn-primary" type="submit" name="createThreadSubmit" value="Create Thread">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php' ?>