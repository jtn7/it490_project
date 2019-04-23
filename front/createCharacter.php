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

<?php include 'header.php' ?>

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4"></div>

	<!-- Content Row -->
	<div class="row">

		<!-- Content Column -->
		<div class="col-12">

			<!-- Project Card -->
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Create your character</h6>
				</div>
				<div class="card-body">
					<div class="content">
						<div class="Character">
							<form action="" id="createCharacter" method="POST">
								<h3>Character Info:</h3>
								Character Name:
								<input type="text" name="characterName">
								<br>
								<br>
								Age:
								<input type="number" name="age">
								&nbsp;
								Sex:
								<select name="sex">
									<option value="Other">Other</option>
									<option value="M">Male</option>
									<option value="F">Female</option>
								</select>&nbsp;
								Height:
								<input type="number" name="height">
								ft
								&nbsp;
								Weight:
								<input type="number" name="weight">
								lbs
								<br>
								<h3>Race:</h3>
								Race:
								<select name="race" id="Race">
								</select>
								&nbsp;
								Subrace:
								<select name="subrace" id="Subrace">
								</select>
								&nbsp;
								Class:
								<select name="class" id="Class">
								</select>
								&nbsp;
								Subclass:
								<select name="subclass" id="Subclass">
								</select>
								<h3>Stats:</h3>
								STR:
								<input type="number" name="statsSTR">
								&nbsp;
								DEX:
								<input type="number" name="statsDEX">
								&nbsp;
								CONST:
								<input type="number" name="statsCONST">
								&nbsp;
								INT:
								<input type="number" name="statsINT">
								&nbsp;
								WIS:
								<input type="number" name="statsWIS">
								&nbsp;
								CHA:
								<input type="number" name="statsCHA">
								<br>
								<h3>Passive Perception:
									<input type="number" name="passivePerception">
								</h3>
								<h3>Saving Throws</h3>
								STR:
								<input type="number" name="sthrowSTR">
								&nbsp;
								DEX:
								<input type="number" name="sthrowDEX">
								&nbsp;
								CONST:
								<input type="number" name="sthrowCONST">
								&nbsp;
								INT:
								<input type="number" name="sthrowINT">
								&nbsp;
								WIS:
								<input type="number" name="sthrowWIS">
								&nbsp;
								CHA:
								<input type="number" name="sthrowCHA">
								<br>
								<h3>Armor Class:
									<input type="number" name="armorClass">
								</h3>
								<h3>Speed:
									<input type="number" name="speed">
								</h3>
								<h3>Max Hit Point:
									<input type="number" name="maxHitPoint">
								</h3>
								<h3>Skills</h3>
								Acrobatics:
								<input type="number" name="acrobatics">
								&nbsp;
								Animal Handling:
								<input type="number" name="animaHandling">
								&nbsp;
								Arcana:
								<input type="number" name="arcana">
								&nbsp;
								Athletic:
								<input type="number" name="athletic">
								&nbsp;
								Deception:
								<input type="number" name="deception">
								&nbsp;
								History:
								<input type="number" name="history">
								&nbsp;
								Insight:
								<input type="number" name="insight">
								&nbsp;
								Investigation:
								<input type="number" name="investigation">
								&nbsp;
								Medicine:
								<input type="number" name="medicine">
								&nbsp;
								Nature:
								<input type="number" name="nature" >
								&nbsp;
								Perception:
								<input type="number" name="perception" >
								&nbsp;
								Performance:
								<input type="number" name="performance" >
								&nbsp;
								Persuation:
								<input type="number" name="persuation" >
								&nbsp;
								Religion:
								<input type="number" name="religion" >
								&nbsp;
								Sleight of Hand:
								<input type="number" name="soHand" >
								&nbsp;
								Stealth:
								<input type="number" name="stealth" >
								&nbsp;
								Survival:
								<input type="number" name="survival" >
								&nbsp;
								Herbalism Kit:
								<input type="number" name="hkit" >
								<br>
								<h3>Equipment</h3>
								<select name="equipment" id="Equipment"></select>
								<h3>Spells:</h3>
								<br>
								<select name="spell" id="Spell"></select>
								<h3>Features and Traits</h3>
								Features:
								<select name="features" id="Features">
								</select>
								&nbsp;
								Traits:
								<select name="traits" id="Traits">
								</select>
								<br>
								<h3>Personality Traits</h3>
								<textarea name="personalityTraits" form="createCharacter"></textarea>
								<h3>Ideals</h3>
								<textarea name="ideals" form="createCharacter"></textarea>
								<br>
								<h3>Bonds</h3>
								<textarea name="bonds" form="createCharacter"></textarea>
								<br>
								<h3>Flaws</h3>
								<textarea name="flaws" form="createCharacter"></textarea>
								<br>
								<h3>Hit Dice</h3>
								<textarea name="hitDice" form="createCharacter"></textarea>
								<br><br>
								<input type="submit" name="createCharacterSubmit" value="Save Character">
							</form>
							<script src="scripts.js"></script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php' ?>