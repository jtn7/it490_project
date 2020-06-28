<?php
namespace models;

class Models {

	public static function getDefaultCharacter(): array {

		return array(
			"username" => $_SESSION['username'],
			"characterName" => "",
			"age" => 0,
			"sex" => "",
			"height" => "",
			"weight" => 0,
			"race" => "",
			"subrace" => "",
			"class" => "",
			"subclass" => "",
			"level" => 0,
			"background" => "",
			"alignment" => "",
			"exp_points" => 0,
			"stats" => array(
				"strength" => 0,
				"dexterity" => 0,
				"constitution" => 0,
				"intelligence" => 0,
				"wisdom" => 0,
				"charisma" => 0
			),
			"hitDice" => array(
				"count" => 0,
				"value" => ""
			),
			"passivePerception" => 0,
			"saving_throws" => array(
				"strength" => 0,
				"dexterity" => 0,
				"constitution" => 0,
				"intelligence" => 0,
				"wisdom" => 0,
				"charisma" => 0
			),
			"armorClass" => 0,
			"speed" => 0,
			"max_hit_points" => 0,
			"skills" => array(
				"acrobatics" => 0,
				"animalHandling" => 0,
				"arcana" => 0,
				"athletic" => 0,
				"deception" => 0,
				"history" => 0,
				"insight" => 0,
				"investigation" => 0,
				"medicine" => 0,
				"nature" => 0,
				"perception" => 0,
				"performance" => 0,
				"persuation" => 0,
				"religion" => 0,
				"soHand" => 0,
				"stealth" => 0,
				"survival" => 0,
				"herbalism_kit" => 0
			),
			"spells" => array(),// Array of arrays
			"equipment" => array (), // Array of arrays
			"features" => array(),// Array of arrays
			"traits" => array(),// Array of arrays
			"languages" => array(),// Array of arrays
			"personalityTraits" => "",
			"ideals" => "",
			"bonds" => "",
			"flaws" => "",
		);
	}
}

?>