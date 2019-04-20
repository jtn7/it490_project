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

<!-- Content Row -->
<div class="row">

	<!-- Content Column -->
	<div class="col-lg-6 mb-4">

		<!-- Project Card Example -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Forums</h6>
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
				'<table>
				<tr>
				<td>
				<a href="threads.php?forumID=' . $forumArr['ForumID'] . '&forumName=' . $forumArr['Name'] . '">' . $forumArr['Name'] . '</a>
				</td>
				</tr>
				<tr>
				<td>
					<p>' . $forumArr['Description'] . '</p>
				</td>
				</tr>
				</table>';
				}
				?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php' ?>