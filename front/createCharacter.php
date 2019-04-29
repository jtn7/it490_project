<?php
session_start();
require_once 'RPC.php';
require_once 'models/Models.php';
use rabbit\RPC;
use models\Models;

if(!empty($_POST)) {
	$createCharacter_rpc = new RPC("storeCharacter");
	$character = Models::getDefaultCharacter();

	foreach ($character as $key => $value) {
		if (isset($_POST[$key])) {
			$character[$key] = $_POST[$key];
		}
	}

	$createCharacterMSG = serialize(array("updateCharacter", $character));

	$response = $createCharacter_rpc->call($createCharacterMSG);

	if ($response==="S"){
		header('Location: characters.php?success=S');
	}
	else {
		header('Location: createCharacter.php?success=F');
	}
}
?>

<?php include 'html/createCharacter_html.php' ?>