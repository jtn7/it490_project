<?php
session_start();
require_once 'RPC.php';
require_once 'models/Models.php';
require_once 'logging/LogWriter.php';
use rabbit\RPC;
use models\Models;
use logging\LogWriter;

$logger = new LogWriter('/var/log/dnd/frontend.log');
if(!empty($_POST)) {
	$characters_rpc = new RPC("Characters");
	$character = Models::getDefaultCharacter();

	$_POST['class'] = json_decode($_POST['class'], true);
	$logger->debug($_POST);

	$resp = 'E';

	// foreach ($character as $key => $value) {
	// 	if (isset($_POST[$key])) {
	// 		$character[$key] = $_POST[$key];
	// 	}
	// }

	// $createCharacter = serialize(array('createCharacter', $character));

	// $resp = $characters_rpc->call($createCharacter);

	if ($resp ==="S"){
		header('Location: characters.php?success=S');
	}
	else {
		header('Location: createCharacter.php?success=F');
	}
}
?>

<?php include 'html/createCharacter_html.php' ?>