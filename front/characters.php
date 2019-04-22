<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

error_reporting(E_ERROR | E_PARSE);

$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('characters.php accessed');

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}

$characters = array();

if (!isset($_GET['load'])) {
	$user_rpc = new RPC('RetrieveJSON');
	$rpc_request = serialize(array("getCharacters", $_SESSION['username']));
	$response = $user_rpc->call($rpc_request);
	// echo "<h1 style='color: white'>";
	if ($response !== 'E') {
		$logger->info('Successfully got characters');
		$logger->debug('Response: ' . $response);
		$characters = unserialize($response);
		$logger->debug($characters);
	} else {
		header('Location: index.php?load=F');
	}
}

?>




		<?php
		if (isset($_GET['load'])) {
			echo "<script type='text/javascript'>alert('Failed to get characters!');</script>";
		} else {
			// echo '<h2>';
			// print_r($characters);
			// echo '</h2>';

			foreach ($characters as $character) {
				echo
				'<table>
					<tr>
						<td>
							Character Name:' . $character['name'] .
						'</td>
					</tr>
					<tr>
						<td>
							Race: ' . $character['race'] . ' | Class: ' . $character['class'] .
						'</td>
					</tr>
				</table>';
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
					<h6 class="m-0 font-weight-bold text-primary">Character</h6>
					<div class="dropdown no-arrow">
						<a href="createCharacter.php" role="button" aria-haspopup="true" aria-expanded="false">
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