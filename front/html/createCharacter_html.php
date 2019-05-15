<?php include 'header.php' ?>

<!-- Sweet Alert for the character creation-->
<?php
	$fullUrl 	= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (strpos($fullUrl, "success=F") == true){
		echo '<script type="text/javascript">swal("Wait a minute!", "Something went wrong while creating a character :(", "error");</script>';
	  }

?>

<!-- This is the start of body for createCharacter.php page -->
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
				<link rel="stylesheet" href="css/createCharacter.css">
				<form id="createCharacterForm">
					<!-- fieldsets -->
					<fieldset>
						<h2 class="fs-title">Character Background</h2>
						<input type="text" name="characterName" placeholder="Character Name"/><br>
						Age: <input type="number" min="0" value="0" required name="age" placeholder="Character Age"/><br>
						<select name="sex" placeholder="Character Sex">
							<option value="">Character Sex</option>
							<option value="Other">Other</option>
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select><br>
						Height: <input type="number" min="0" value="0" required name="height" placeholder="Character Height">ft<br>
						Weight: <input type="number" min="0" value="0" required name="weight" placeholder="Character Weight">lbs<br>
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
						<input type="number" min="0" value="0" required name="statsSTR" placeholder="STR">
						<input type="number" min="0" value="0" required name="statsDEX" placeholder="DEX">
						<input type="number" min="0" value="0" required name="statsCONST" placeholder="CONST">
						<input type="number" min="0" value="0" required name="statsINT" placeholder="INT">
						<input type="number" min="0" value="0" required name="statsWIS" placeholder="WIS">
						<input type="number" min="0" value="0" required name="statsCHA" placeholder="CHA">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Passive Perception</h2>
						<input type="number" min="0" value="0" required name="passivePerception" placeholder = "Passive Perception">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Saving Throws</h2>
						<input type="number" min="0" value="0" required name="sthrowSTR" placeholder="STR">
						<input type="number" min="0" value="0" required name="sthrowDEX" placeholder="DEX">
						<input type="number" min="0" value="0" required name="sthrowCONST" placeholder="CONST">
						<input type="number" min="0" value="0" required name="sthrowINT" placeholder="INT">
						<input type="number" min="0" value="0" required name="sthrowWIS" placeholder="WIS">
						<input type="number" min="0" value="0" required name="sthrowCHA" placeholder="CHA">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Armor Class</h2>
						<input type="number" min="0" value="0" required name="armorClass" placeholder="Armor Class">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Speed</h2>
						<input type="number" min="0" value="0" required name="speed" placeholder="Speed">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Hit Points</h2>
						<input type="number" min="0" value="0" required name="hitDiceNum" placeholder="Number of Dice">
						<select name="hitDice" placeholder="Dice Type">
							<option value="">Choose Dice</option>
							<option value="d4">d4</option>
							<option value="d6">d6</option>
							<option value="d8">d8</option>
							<option value="d12">d12</option>
						</select>
						<br>
						<input type="number" min="0" value="0" required name="maxHitPoint" placeholder="Max Hit Point">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Skills</h2>
						<input type="number" min="0" value="0" required name="acrobatics" placeholder="Acrobatics">
						<input type="number" min="0" value="0" required name="animaHandling" placeholder="Animal Handling">
						<input type="number" min="0" value="0" required name="arcana" placeholder="Arcana">
						<input type="number" min="0" value="0" required name="athletic" placeholder="Athletic">
						<input type="number" min="0" value="0" required name="deception" placeholder="Deception">
						<input type="number" min="0" value="0" required name="history" placeholder="History">
						<input type="number" min="0" value="0" required name="insight" placeholder="Insight">
						<input type="number" min="0" value="0" required name="investigation" placeholder="Investigation">
						<input type="number" min="0" value="0" required name="medicine" placeholder="Medicine">
						<input type="number" min="0" value="0" required name="nature" placeholder="Nature">
						<input type="number" min="0" value="0" required name="perception" placeholder="Perception">
						<input type="number" min="0" value="0" required name="performance" placeholder="Performance">
						<input type="number" min="0" value="0" required name="persuation" placeholder="Persuation">
						<input type="number" min="0" value="0" required name="religion" placeholder="Religion">
						<input type="number" min="0" value="0" required name="soHand" placeholder="Sleight of Hand">
						<input type="number" min="0" value="0" required name="stealth" placeholder="Stealth">
						<input type="number" min="0" value="0" required name="survival" placeholder="Survival">
						<input type="number" min="0" value="0" required name="hkit" placeholder="Hernalism Kit">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Equipment</h2>
						<select name="weapon" id="Weapon"></select>
						<select name="armor" id="Armor"></select>
						<select name="equipment" id="Equipment"></select>
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


					<input type="submit" name="createCharacterSubmit" class="submit action-button" value="Save"/>
				</form>
				</div>
				</div>

				<!-- Script to access D&D 3rd party API -->
				<script src="js/createCharacter.js"></script>

			</div>
		</div>
	</div>
</div>
<!-- This is the end of body for createCharacter.php page -->

<?php include 'footer.php' ?>