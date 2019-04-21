<?php
session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}

require_once 'RPC.php';
use rabbit\RPC;

$threads_rpc = new RPC("getPosts");
$_SESSION['ForumID'] = $_GET['forumID'];
$getThreads = serialize(array("getThreads", $_SESSION['ForumID']));
$response = $threads_rpc->call($getThreads);
?>



		<?php
		$ForumName = $_GET['forumName'];
		echo '<h1>' . $ForumName . '</h1>';
		?>
		<a href="createThread.php">Create a Forum Thread</a><br>
		<?php
		$unserArr = unserialize($response);
		foreach ($unserArr as $threadArr){
			echo
			'<table>
				<tr>
					<td>
                        <a href="replies.php?threadID=' . $threadArr['ThreadID'] .  '">' . $threadArr['Name'] .
                        '</a><br>' . $threadArr['Content'] .
                    '</td>
				</tr>
				<tr>
					<td>'
						 . $threadArr['User'] . ' - ' . $threadArr['Timestamp'] .
					'</td>
				</tr>
			</table>';



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
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Forums</h6>
					<a href="createThread.php">
						<i class="fas fa-plus fa-sm fa-fw text-gray-400"></i>
					</a>
				</div>
				<div class="card-body">
					<div class="content">
					<?php
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