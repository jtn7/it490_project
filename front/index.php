<?php
session_start();
require_once 'RPC.php';
require_once 'logging/LogWriter.php';
use logging\LogWriter;
use rabbit\RPC;

error_reporting(E_ERROR | E_PARSE);

$logger = new LogWriter('/var/log/dnd/frontend.log');
$logger->info('index.php accessed');

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
		header('Location index.php?load=F');
	}
}

?>

<?php include 'header.php' ?>

<div class="body">
	<div class="content">
		<h1>Character Dashboard</h1>
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
	</div>
</div>

<?php include 'footer.php' ?>