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
					<h6 class="m-0 font-weight-bold text-primary">Reply</h6>
				</div>
				<div class="card-body">
					<div class="content">
						<?php
						$Thread = unserialize($response1);
						echo
							'<h1>' . $Thread['Name'] . '</h1>
							<p>' . $Thread['Content'] . '</p><br>
							<p>Written by ' . $Thread['User'] . ' - ' . $Thread['Timestamp'] . '</p>';

						?>
						<?php
						$unserArr = unserialize($response);
						foreach ($unserArr as $repliesArr){
							echo
							'
							<div class="col-12">
								<div class="card border-left-primary shadow h-50 py-0">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-0">
												<div class="h5 mb-0 font-weight-bold text-gray-800 mb-1">'. $repliesArr['Content'] .'</div>
												<div class="text-s font-weight-regular text-gray-800"><p>'. $repliesArr['User'] . ' - ' . $repliesArr['Timestamp'] .'</p></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							';
						}
						?>
						<br>
						<form action="" id="addReply" method="POST">
							<div class="form-row">
								<label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">Write your reply:</label>
							</div>
							<div class="form-row">
								<textarea name="ReplyContent" form="addReply" required="required" class="form-control" rows="5"></textarea>
							</div>
							<br>
							<div> 
								<input class="btn btn-primary pull-right btn-lg" type="submit" name="createReplySubmit" value="Submit">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php' ?>
