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
					<!-- Forum Name -->
					<?php
						$forumsArr = unserialize($responseForums);
						foreach ($forumsArr as $forumResponse){
							if($_SESSION['ForumID'] == $forumResponse['ForumID']){
								echo '<h6 class="m-0 font-weight-bold text-primary">' . $forumResponse['ForumID'] . '</h6>';
							}
						}
					?>
					<div class="dropdown no-arrow">
					<a href="createThread.php" role="button" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-plus fa-sm fa-fw text-gray-400"></i>
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="content">
					<?php
					$unserArr = unserialize($responseThreads);
					foreach ($unserArr as $threadArr){
					echo
					'			
					<div class="col-12">
						<div class="card border-left-primary shadow h-100 py-2">
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
									<div class="h5 mb-0 font-weight-bold text-warning text-uppercase mb-1"><a href="replies.php?threadID=' . $threadArr['ThreadID'] .  '">' . $threadArr['Name'] .'</a></div>
									<div class="text-s font-weight-regular text-gray-800"><p>' . $threadArr['Content'] . '</p></div>
									<div class="text-s font-weight-regular text-gray-800"><p>' . $threadArr['User'] . ' - ' . $threadArr['Timestamp'] . '</p></div>
								</div>
								<div class="col-auto">
									<i class="fas fa-comment-dots fa-2x text-gray-300"></i>
								</div>
								</div>
							</div>
						</div>
					</div>
					';
					}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php' ?>