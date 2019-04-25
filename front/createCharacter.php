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



<!-- Content Column -->
<div class="container-fluid col-md-12">

	<!-- Project Card -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
			<h6 class="m-0 font-weight-bold text-primary">Create your character</h6>
		</div>
		<div class="card-body">
			<div class="content">
				<!-- Adding the CSS specialized for this page -->
				<link rel="stylesheet" href="createCharacterCSS.css">

				<!-- MultiStep Form -->
				<div class="row">
				<div class="col-md-12">
				<form action="" id="msform" method="POST">
					<!-- progressbar -->
					<ul id="progressbar">
						<li class="active">Background</li>
						<li>Race</li>
						<li>Stats</li>
						<li>Passive Perception</li>
						<li>Saving Throws</li>
						<li>Armor Class</li>
						<li>Speed</li>
						<li>Max Hit Point</li>
						<li>Skills</li>
						<li>Equipment</li>
						<li>Spells</li>
						<li>Features and Traits</li>
						<li>Personality Traits</li>
						<li>Ideals</li>
						<li>Bonds</li>
						<li>Flaws</li>
						<li>Hit Dice</li>
					</ul>

					<!-- fieldsets -->
					<fieldset>
						<h2 class="fs-title">Character Background</h2>
						<h3 class="fs-subtitle">Let's begin!</h3>
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
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>

					<fieldset>
						<h2 class="fs-title">Race</h2>
						<h3 class="fs-subtitle">Race, Sub-Race, Class & Sub-Class</h3>
						<select name="race" id="Race" placeholder="Race"></select>
						<select name="subrace" id="Subrace" placeholder="Sub-Race"></select>
						<select name="class" id="Class" placeholder ="Class"></select>
						<select name="subclass" id="Subclass" placeholder="Sub-Class"></select>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>

					<fieldset>
						<h2 class="fs-title">Stats</h2>
						<h3 class="fs-subtitle">STR, DEX, CONST, INT, WIS & CHA</h3>
						<input type="number" name="statsSTR" placeholder="STR">
						<input type="number" name="statsDEX" placeholder="DEX">
						<input type="number" name="statsCONST" placeholder="CONST">
						<input type="number" name="statsINT" placeholder="INT">
						<input type="number" name="statsWIS" placeholder="WIS">
						<input type="number" name="statsCHA" placeholder="CHA">
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>

					<fieldset>
						<h2 class="fs-title">Passive Perception</h2>
						<h3 class="fs-subtitle">Let's select passive perception!</h3>
						<input type="number" name="passivePerception" placeholder = "Passive Perception">
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>

					<fieldset>
						<h2 class="fs-title">Saving Throws</h2>
						<h3 class="fs-subtitle">Saving Throws . . .</h3>
						<input type="number" name="sthrowSTR" placeholder="STR">
						<input type="number" name="sthrowDEX" placeholder="DEX">
						<input type="number" name="sthrowCONST" placeholder="CONST">
						<input type="number" name="sthrowINT" placeholder="INT">
						<input type="number" name="sthrowWIS" placeholder="WIS">
						<input type="number" name="sthrowCHA" placeholder="CHA">
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>

					<fieldset>
						<h2 class="fs-title">Armor Class</h2>
						<h3 class="fs-subtitle">Suit up!</h3>
						<input type="number" name="armorClass" placeholder="Armor Class">
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>

					<fieldset>
						<h2 class="fs-title">Speed</h2>
						<h3 class="fs-subtitle">Let's determine the movement per round!</h3>
						<input type="number" name="speed" placeholder="Speed">
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>

					<fieldset>
						<h2 class="fs-title">Max Hit Point</h2>
						<h3 class="fs-subtitle">Let's determine the maximum hit points!</h3>
						<input type="number" name="maxHitPoint" placeholder="Max Hit Point">
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>										

					<fieldset>
						<h2 class="fs-title">Skills</h2>
						<h3 class="fs-subtitle">You can't survive without a few skills!</h3>
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
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>		

					<fieldset>
						<h2 class="fs-title">Equipment</h2>
						<h3 class="fs-subtitle">What kind of equipment do you want?</h3>
						<select name="equipment" id="Equipment"></select>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>	

					<fieldset>
						<h2 class="fs-title">Spells</h2>
						<h3 class="fs-subtitle">Learn a few spells!</h3>
						<select name="spell" id="Spell"></select>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>	

					<fieldset>
						<h2 class="fs-title">Features and Traits</h2>
						<h3 class="fs-subtitle">Let's add a few features and traits. . .</h3>
						<select name="features" id="Features"></select>
						<select name="traits" id="Traits"></select>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>	

					<fieldset>
						<h2 class="fs-title">Personality Traits</h2>
						<h3 class="fs-subtitle">Personality traits comes next. . .</h3>
						<textarea name="personalityTraits" form="createCharacter"></textarea>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>	

					<fieldset>
						<h2 class="fs-title">Ideals</h2>
						<h3 class="fs-subtitle">Note a few ideals. . .</h3>
						<textarea name="ideals" form="createCharacter"></textarea>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>	

					<fieldset>
						<h2 class="fs-title">Bonds</h2>
						<h3 class="fs-subtitle">Note a few bonds. . .</h3>
						<textarea name="bonds" form="createCharacter"></textarea>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>

					<fieldset>
						<h2 class="fs-title">Flaws</h2>
						<h3 class="fs-subtitle">Note a few flaws. . .</h3>
						<textarea name="flaws" form="createCharacter"></textarea>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="button" name="next" class="next action-button" value="Next"/>
					</fieldset>	

					<fieldset>
						<h2 class="fs-title">Hit Dice</h2>
						<h3 class="fs-subtitle">Hit Dice Set-Up</h3>
						<textarea name="hitDice" form="createCharacter"></textarea>
						<input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
						<input type="submit" name="createCharacterSubmit" class="next action-button" value="Save"/>
					</fieldset>
				</form>
				</div>
				</div>
				<!-- /.MultiStep Form -->

				<!-- Script to access D&D 3rd party API -->
				<script src="scripts.js"></script>

				<script>
				//jQuery time
				var current_fs, next_fs, previous_fs; //fieldsets
				var left, opacity, scale; //fieldset properties which we will animate
				var animating; //flag to prevent quick multi-click glitches

				$(".next").click(function(){
					if(animating) return false;
					animating = true;
					
					current_fs = $(this).parent();
					next_fs = $(this).parent().next();
					
					//activate next step on progressbar using the index of next_fs
					$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
					
					//show the next fieldset
					next_fs.show(); 
					//hide the current fieldset with style
					current_fs.animate({opacity: 0}, {
						step: function(now, mx) {
							//as the opacity of current_fs reduces to 0 - stored in "now"
							//1. scale current_fs down to 80%
							scale = 1 - (1 - now) * 0.2;
							//2. bring next_fs from the right(50%)
							left = (now * 50)+"%";
							//3. increase opacity of next_fs to 1 as it moves in
							opacity = 1 - now;
							current_fs.css({
						'transform': 'scale('+scale+')',
						'position': 'absolute'
					});
							next_fs.css({'left': left, 'opacity': opacity});
						}, 
						duration: 500, 
						complete: function(){
							current_fs.hide();
							animating = false;
						}, 
						//this comes from the custom easing plugin
						easing: 'easeInOutBack'
					});
				});

				$(".previous").click(function(){
					if(animating) return false;
					animating = true;
					
					current_fs = $(this).parent();
					previous_fs = $(this).parent().prev();
					
					//de-activate current step on progressbar
					$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
					
					//show the previous fieldset
					previous_fs.show(); 
					//hide the current fieldset with style
					current_fs.animate({opacity: 0}, {
						step: function(now, mx) {
							//as the opacity of current_fs reduces to 0 - stored in "now"
							//1. scale previous_fs from 80% to 100%
							scale = 0.8 + (1 - now) * 0.2;
							//2. take current_fs to the right(50%) - from 0%
							left = ((1-now) * 50)+"%";
							//3. increase opacity of previous_fs to 1 as it moves in
							opacity = 1 - now;
							current_fs.css({'left': left});
							previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
						}, 
						duration: 500, 
						complete: function(){
							current_fs.hide();
							animating = false;
						}, 
						//this comes from the custom easing plugin
						easing: 'easeInOutBack'
					});
				});

				$(".submit").click(function(){
					return false;
				})
				</script>
				<!-- /.Script -->

			</div>
		</div>
	</div>


<?php include 'footer.php' ?>