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
<h1 class="h3 mb-4 text-gray-800">Forums</h1>
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
<!-- /.container-fluid -->

<?php include 'footer.php' ?>