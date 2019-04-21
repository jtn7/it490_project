<?php
session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}

require_once 'RPC.php';
use rabbit\RPC;

$forums_rpc = new RPC("getPosts");
$getForums = serialize(array("getForums"));
$response = $forums_rpc->call($getForums);
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

			<!-- Project Card Example -->
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Forums</h6>
					<div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="createForum.php" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-plus fa-sm fa-fw text-gray-400"></i>
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="content">
					<?php
					// echo "<h2>";
					// print_r($response);
					// echo "<h2>";
					$unserArr = unserialize($response);

					foreach ($unserArr as $forumArr) {
					echo
					'				
					<div class="col-12">
						<div class="card border-left-primary shadow h-100 py-2">
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
									<div class="h5 mb-0 font-weight-bold text-warning text-uppercase mb-1"><a href="threads.php?forumID=' . $forumArr['ForumID'] . '&forumName=' . $forumArr['Name'] . '">' . $forumArr['Name'] . '</a></div>
									<div class="text-s font-weight-regular text-gray-800"><p>' . $forumArr['Description'] . '</p></div>
								</div>
								<div class="col-auto">
									<i class="fas fa-comments fa-2x text-gray-300"></i>
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