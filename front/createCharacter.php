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

<?php include 'header.php' ?>

<!-- Sweet Alert for the character creation-->
<?php
	$fullUrl 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($fullUrl, "success=F") == true){
		echo '<script type="text/javascript">swal("Wait a minute!", "Something went wrong while creating a character :(", "error");</script>';
	  }
	
?>

<!-- Content Column -->
<div class="container-fluid col-md-12">

	<!-- Project Card -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
			<h6 class="m-0 font-weight-bold text-primary">Create your character</h6>
		</div>
		<div class="card-body">
			<div class="content">
				<div class="row">
				<div class="col-md-12">
				<link rel="stylesheet" href="createCharacter.css">
				<form action="" id="createCharacterform" method="POST">
					<!-- fieldsets -->
					<fieldset>
						<h2 class="fs-title">Character Background</h2>
						<input type="text" name="characterName" placeholder="Character Name"/>
						<input type="number" name="age" placeholder="Character Age"/>
						<select name="sex" placeholder="Character Sex">
							<option value="">Character Sex</option>
							<option value="Other">Other</option>
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
						<input type="number" name="height" placeholder="Character Height in ft">
						<input type="number" name="weight" placeholder="Character Weight in lb">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Race</h2>
						<select name="race" id="Race" placeholder="Race"></select>
						<select name="subrace" id="Subrace" placeholder="Sub-Race"></select>
						<select name="class" id="Class" placeholder ="Class"></select>
						<select name="subclass" id="Subclass" placeholder="Sub-Class"></select>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Stats</h2>
						<input type="number" name="statsSTR" placeholder="STR">
						<input type="number" name="statsDEX" placeholder="DEX">
						<input type="number" name="statsCONST" placeholder="CONST">
						<input type="number" name="statsINT" placeholder="INT">
						<input type="number" name="statsWIS" placeholder="WIS">
						<input type="number" name="statsCHA" placeholder="CHA">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Passive Perception</h2>
						<input type="number" name="passivePerception" placeholder = "Passive Perception">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Saving Throws</h2>
						<input type="number" name="sthrowSTR" placeholder="STR">
						<input type="number" name="sthrowDEX" placeholder="DEX">
						<input type="number" name="sthrowCONST" placeholder="CONST">
						<input type="number" name="sthrowINT" placeholder="INT">
						<input type="number" name="sthrowWIS" placeholder="WIS">
						<input type="number" name="sthrowCHA" placeholder="CHA">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Armor Class</h2>
						<input type="number" name="armorClass" placeholder="Armor Class">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Speed</h2>
						<input type="number" name="speed" placeholder="Speed">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Max Hit Point</h2>
						<input type="number" name="maxHitPoint" placeholder="Max Hit Point">
					</fieldset>
					<br>								

					<fieldset>
						<h2 class="fs-title">Skills</h2>
						<input type="number" name="acrobatics" placeholder="Acrobatics">
						<input type="number" name="animaHandling" placeholder="Animal Handling">
						<input type="number" name="arcana" placeholder="Arcana">
						<input type="number" name="athletic" placeholder="Athletic">
						<input type="number" name="deception" placeholder="Deception">
						<input type="number" name="history" placeholder="History">
						<input type="number" name="insight" placeholder="Insight">
						<input type="number" name="investigation" placeholder="Investigation">
						<input type="number" name="medicine" placeholder="Medicine">
						<input type="number" name="nature" placeholder="Nature">
						<input type="number" name="perception" placeholder="Perception">
						<input type="number" name="performance" placeholder="Performance">
						<input type="number" name="persuation" placeholder="Persuation">
						<input type="number" name="religion" placeholder="Religion">
						<input type="number" name="soHand" placeholder="Sleight of Hand">
						<input type="number" name="stealth" placeholder="Stealth">
						<input type="number" name="survival" placeholder="Survival">
						<input type="number" name="hkit" placeholder="Hernalism Kit">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Equipment</h2>
						<select name="weapon" id="Weapon"></select>
						<select name="armor" id="Armor"></select>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Spells</h2>
						<select name="spell" id="Spell"></select>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Features and Traits</h2>
						<select name="features" id="Features"></select>
						<select name="traits" id="Traits"></select>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Personality Traits</h2>
						<textarea name="personalityTraits" form="createCharacter"></textarea>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Ideals</h2>
						<textarea name="ideals" form="createCharacter"></textarea>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Bonds</h2>
						<textarea name="bonds" form="createCharacter"></textarea>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Flaws</h2>
						<textarea name="flaws" form="createCharacter"></textarea>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Hit Dice</h2>
						<textarea name="hitDice" form="createCharacter"></textarea>
					</fieldset>
					<br>

					<input type="submit" name="createCharacterSubmit" class="submit action-button" value="Save"/>
				</form>
				</div>
				</div>

				<!-- Script to access D&D 3rd party API -->
				<script src="scripts.js"></script>

			</div>
		</div>
	</div>
</div>	

<?php include 'footer.php' ?>