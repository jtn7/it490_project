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
		header('Location: index.php');
	}
	else {
		header('Location: createCharacter.php?success=F');
	}
}

if (isset($_GET['success']) && $_GET['success'] === 'F') {
	echo "<script type='text/javascript'>alert('There was an error in creating a character. Try Again.');</script>";
}

?>


<?php include 'header.php';?>
<?php include 'html/character_form.html';?>;
<?php include 'footer.php';?>