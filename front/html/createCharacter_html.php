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
						<label>Name</label>
						<input type="text" name="characterName" placeholder="Character Name"/>
						<br>	  
						<label>Age</label>
						<input type="number" min="0" value="0" required name="age" placeholder="Character Age"/>
						<br>	  
						<label>Gender</label>
						<select name="sex" placeholder="Character Sex">
							<option value="">Character Sex</option>
							<option value="Other">Other</option>
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
						<br>
						<label>Height in ft</label>
						<input type="number" min="0" value="0" required name="height" placeholder="Character Height">
						<br>			
						<label>Weight in lb</label>
						<input type="number" min="0" value="0" required name="weight" placeholder="Character Weight">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Race</h2>
						<label>Race</label>
						<select name="race" id="Race" placeholder="Race"></select>
						<label>Sub-Race</label>
						<select name="subrace" id="Subrace" placeholder="Sub-Race"></select>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Class</h2>
						<label>Class</label>				
						<select name="class" id="Class" placeholder ="Class"></select>
						<label>Sub-Class</label>
						<select name="subclass" id="Subclass" placeholder="Sub-Class"></select>

					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Stats</h2>
						<label>STR</label>
						<input id="statsInput" type="number" min="0" value="0" required name="statsSTR" placeholder="STR">
						<label>DEX</label>
						<input id="statsInput" type="number" min="0" value="0" required name="statsDEX" placeholder="DEX">
						<label>CONST</label>
						<input id="statsInput" type="number" min="0" value="0" required name="statsCONST" placeholder="CONST">
						<label>INT</label>
						<input id="statsInput" type="number" min="0" value="0" required name="statsINT" placeholder="INT">
						<label>WIS</label>
						<input id="statsInput" type="number" min="0" value="0" required name="statsWIS" placeholder="WIS">
						<label>CHA</label>
						<input id="statsInput" type="number" min="0" value="0" required name="statsCHA" placeholder="CHA">
					</fieldset>	
					<br>

					<fieldset>
						<h2 class="fs-title">Passive Perception</h2>
						<label>Passive Perception</label>
						<input type="number" min="0" value="0" required name="passivePerception" placeholder = "Passive Perception">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Saving Throws</h2>
						<label>STR</label>
						<input type="number" min="0" value="0" required name="sthrowSTR" placeholder="STR">
						<label>DEX</label>
						<input type="number" min="0" value="0" required name="sthrowDEX" placeholder="DEX">
						<label>CONST</label>
						<input type="number" min="0" value="0" required name="sthrowCONST" placeholder="CONST">
						<label>INT</label>
						<input type="number" min="0" value="0" required name="sthrowINT" placeholder="INT">
						<label>WIS</label>
						<input type="number" min="0" value="0" required name="sthrowWIS" placeholder="WIS">
						<label>CHA</label>
						<input type="number" min="0" value="0" required name="sthrowCHA" placeholder="CHA">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Armor Class</h2>
						<label>Armor Class</label>
						<input type="number" min="0" value="0" required name="armorClass" placeholder="Armor Class">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Speed</h2>
						<label>Speed</label>
						<input type="number" min="0" value="0" required name="speed" placeholder="Speed">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Hit Points</h2>
						<label>Hit Dice Number</label>
						<input type="number" min="0" value="0" required name="hitDiceNum" placeholder="Number of Dice">
						<select name="hitDice" placeholder="Dice Type">
							<option value="">Choose Dice</option>
							<option value="d4">d4</option>
							<option value="d6">d6</option>
							<option value="d8">d8</option>
							<option value="d12">d12</option>
						</select>
						<br>
						<label>Max Hit Point</label>
						<input type="number" min="0" value="0" required name="maxHitPoint" placeholder="Max Hit Point">
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Skills</h2>
						<label>Acrobatics</label>
						<input type="number" min="0" value="0" required name="acrobatics" placeholder="Acrobatics">
						<label>Animal Handling</label>
						<input type="number" min="0" value="0" required name="animaHandling" placeholder="Animal Handling">
						<label>Arcana</label>
						<input type="number" min="0" value="0" required name="arcana" placeholder="Arcana">
						<label>Athletic</label>
						<input type="number" min="0" value="0" required name="athletic" placeholder="Athletic">
						<label>Deception</label>
						<input type="number" min="0" value="0" required name="deception" placeholder="Deception">
						<label>History</label>
						<input type="number" min="0" value="0" required name="history" placeholder="History">
						<label>Insight</label>
						<input type="number" min="0" value="0" required name="insight" placeholder="Insight">
						<label>Investigation</label>
						<input type="number" min="0" value="0" required name="investigation" placeholder="Investigation">
						<label>Medicine</label>
						<input type="number" min="0" value="0" required name="medicine" placeholder="Medicine">
						<label>Nature</label>
						<input type="number" min="0" value="0" required name="nature" placeholder="Nature">
						<label>Perception</label>
						<input type="number" min="0" value="0" required name="perception" placeholder="Perception">
						<label>Performance</label>
						<input type="number" min="0" value="0" required name="performance" placeholder="Performance">
						<label>Persuation</label>
						<input type="number" min="0" value="0" required name="persuation" placeholder="Persuation">
						<label>Religion</label>
						<input type="number" min="0" value="0" required name="religion" placeholder="Religion">
						<label>Sleight of Hand</label>
						<input type="number" min="0" value="0" required name="soHand" placeholder="Sleight of Hand">
						<label>Stealth</label>
						<input type="number" min="0" value="0" required name="stealth" placeholder="Stealth">
						<label>Survival</label>
						<input type="number" min="0" value="0" required name="survival" placeholder="Survival">
						<label>Hernalism Kit</label>
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
						<label>Spell</label>
						<select name="spell" id="Spell"></select>
					</fieldset>
					<br>

					<fieldset>
						<h2 class="fs-title">Features and Traits</h2>
						<label>Features</label>
						<select name="features" id="Features"></select>
						<label>Traits</label>
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