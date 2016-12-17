var app = angular.module('db_app', []);

app.controller('bodyCtrl', function($scope, $http, $timeout) {

	$scope.querySearchLimit = 20;
	$scope.showClasses = false;
	$scope.showRaces = false;
	$scope.showCharacterSearchResults = false;
	$scope.showCharacterBlock = false;
	$scope.showRightCharacterInformationPane = false;

	$scope.factionUrls = {
		"Alliance" 	: "http://wow.zamimg.com/images/icons/alliance.png",
		"Horde"		: "http://wow.zamimg.com/images/icons/horde.png",
		"Neutral"	: "http://hydra-media.cursecdn.com/wow.gamepedia.com/c/cb/Neutral_15.png"
 	};

	$scope.slots = ['head', 'neck', 'shoulder', 'chest', 'back', 'wrist', 'hands', 'waist', 'legs', 'feet', 'finger1', 'finger2', 'trinket1', 'trinket2', 'mainHand', 'offHand'];
	$scope.qualityPairs = {
		0 : "grey",
		1 : "white",
		2 : "uncommon",
		3 : "rare",
		4 : "epic",
		5 : "legendary",
		6 : "artifact"
	};

	$scope.statPairs =	{
		3 : ["Agility"],
		4 : ["Strength"],
		5 : ["Intellect"],
		7 : ["Stamina"],
		32: ["Crit"],
		36: ["Haste"],
		40: ["Versatility"],
		49: ["Mastery"],
		71: ["Strength", "Agility", "Intellect"],
		72: ["Strength", "Agility"],
		73: ["Agility", "Intellect"],
		74: ["Strength", "Intellect"]
	};

	$scope.gearPieces = {};
	$scope.stats = {};
	$scope.urls = {};
	$scope.gearClasses = {};

	$scope.classesUrls = {
		"Death Knight"	: "http://wow.zamimg.com/images/wow/icons/medium/class_deathknight.jpg",
		"Demon Hunter"	: "http://wow.zamimg.com/images/wow/icons/medium/class_demonhunter.jpg",
		"Druid"			: "http://wow.zamimg.com/images/wow/icons/medium/class_druid.jpg",
		"Rogue"			: "http://wow.zamimg.com/images/wow/icons/medium/class_rogue.jpg",
		"Monk"			: "http://wow.zamimg.com/images/wow/icons/medium/class_monk.jpg",
		"Warrior"		: "http://wow.zamimg.com/images/wow/icons/medium/class_warrior.jpg",
		"Shaman"		: "http://wow.zamimg.com/images/wow/icons/medium/class_shaman.jpg",
		"Paladin"		: "http://wow.zamimg.com/images/wow/icons/medium/class_paladin.jpg",
		"Priest"		: "http://wow.zamimg.com/images/wow/icons/medium/class_priest.jpg",
		"Mage"			: "http://wow.zamimg.com/images/wow/icons/medium/class_mage.jpg",
		"Hunter"		: "http://wow.zamimg.com/images/wow/icons/medium/class_hunter.jpg",
		"Warlock"		: "http://wow.zamimg.com/images/wow/icons/medium/class_warlock.jpg"
	};

	$scope.raceGender = {
		"0" : 	{
					"Human"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "human" + "_" + "male" + ".jpg",
					"Night Elf" : "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "nightelf" + "_" + "male" + ".jpg",
					"Draenei"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "draenei" + "_" + "male" + ".jpg",
					"Dwarf"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "dwarf" + "_" + "male" + ".jpg",
					"Worgen"	: "http://wow.zamimg.com/images/wow/icons/medium/race_worgen_male.jpg",
					"Gnome"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "gnome" + "_" + "male" + ".jpg",
					"Goblin"	: "http://wow.zamimg.com/images/wow/icons/medium/race_goblin_male.jpg",
					"Orc"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "orc" + "_" + "male" + ".jpg",
					"Blood Elf"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "bloodelf" + "_" + "male" + ".jpg",
					"Tauren"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "tauren" + "_" + "male" + ".jpg",
					"Undead"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "undead" + "_" + "male" + ".jpg",
					"Troll"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "troll" + "_" + "male" + ".jpg",
					"Pandaren"	: "http://wow.zamimg.com/images/wow/icons/medium/race_pandaren_male.jpg"
		},
		"1" : 	{
					"Human"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "human" + "_" + "female" + ".jpg",
					"Night Elf" : "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "nightelf" + "_" + "female" + ".jpg",
					"Draenei"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "draenei" + "_" + "female" + ".jpg",
					"Dwarf"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "dwarf" + "_" + "female" + ".jpg",
					"Worgen"	: "http://wow.zamimg.com/images/wow/icons/medium/race_worgen_female.jpg",
					"Gnome"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "gnome" + "_" + "female" + ".jpg",
					"Goblin"	: "http://wow.zamimg.com/images/wow/icons/medium/race_goblin_female.jpg",
					"Orc"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "orc" + "_" + "female" + ".jpg",
					"Blood Elf"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "bloodelf" + "_" + "female" + ".jpg",
					"Tauren"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "tauren" + "_" + "female" + ".jpg",
					"Undead"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "undead" + "_" + "female" + ".jpg",
					"Troll"		: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "troll" + "_" + "female" + ".jpg",
					"Pandaren"	: "http://wow.zamimg.com/images/wow/icons/large/achievement_character_" + "pandaren" + "_" + "female" + ".jpg"
		}
	};

	$scope.raceClassCombos = {};
	$scope.raceClassCombos_Ordered = {};

	$scope.raceFactions = {};

	$scope.populateRaceFactions = function(){
		$http({
			method: 'GET',
			url: './search.php',
			params: 
			{ 
				'search_query' : 'race_factions_search_query',
				'route'	: 'race_factions'
			}
		}).
		success(function(response){
			if( response.race_factions != null && response.race_factions.length > 0 ){
				response.race_factions.forEach(function(race_faction){
					$scope.raceFactions[race_faction.name] = race_faction.faction;
				});
			}
			else console.log(response.race_factions);
		}).
		error(function(response){
			$scope.error = response || 'Failed to get race_factions';
		});
	};

	$scope.populateRaceFactions();

	$scope.populateRaceClassCombos = function(){
		$http({
			method: 'GET',
			url: './search.php',
			params: 
			{ 
				'search_query' : 'race_class_combo_search_query',
				'route'	: 'race_class_combos'
			}
		}).
		success(function(response){
			if(response.race_class_combos != null && response.race_class_combos.length > 0 ){
				for(var race in $scope.raceGender["0"]){
					for(var clas in $scope.classesUrls){
						if($scope.raceClassCombos[race] == undefined) $scope.raceClassCombos[race] = {[clas]:0};
						else $scope.raceClassCombos[race][clas] = 0;
					}
				}

				response.race_class_combos.forEach(function(race_class_combo){
					$scope.raceClassCombos[race_class_combo.race_name][race_class_combo.class_name] =1;
				});
			}
			else console.log('race_class_combos is null or empty');
		}).
		error(function(response){
			$scope.error = response || 'Failed to get race_class_combos';
		});
	};

	$scope.populateRaceClassCombos();

	$scope.submit_search = function(){
		$scope.selectedCharacterIndex = null;
		
		$scope.showCharacterBlock =false;

		$http({
			method: 'GET',
			url: './search.php',
			params: 
			{ 
				'search_query' : $scope.search_query,
				'limit'	: $scope.querySearchLimit,
				'route'	: 'character_list_search'
			}
		}).
		success(function(response){
			delete $scope.characters;
			if( response.characters != null){
				$scope.characters = response.characters;

				$scope.characters.forEach(function(character){
					if(character.faction == "Horde") character.factionUrl = $scope.factionUrls["Horde"];
					else if (character.faction == "Alliance") character.factionUrl = $scope.factionUrls["Alliance"];
					else if (character.faction == "Neutral") character.factionUrl = $scope.factionUrls["Neutral"];
					else character.factionUrl = null;

					character.classUrl = $scope.classesUrls[character.class];
					 character.raceUrl = $scope.raceGender[character.gender][character.race];
				});
				$scope.showCharacterSearchResults = true;
			}
		}).
		error(function(response){
			console.log('err');
			$scope.error = response || 'Failed to get Info';
		});

	};

	$scope.generateCharacterPane = function(name, realm){

		$scope.showRightCharacterInformationPane = false;

		$http({
				method: 'GET',
				url: './search.php',
				params: 
				{ 
					'name' : name,
					'realm': realm,
					'route'	: 'character_detail_search'
				}
			}).
			success(function(response){
				$scope.slots.forEach(function(slot){
					$scope.gearPieces[slot] = response.items[slot].length > 0 ? response.items[slot] :  undefined;
					$scope.urls[slot] = (response.items[slot].length > 0 && response.items[slot][0].name !='') ? "http://wow.zamimg.com/images/wow/icons/large/" + response.items[slot][0].icon + ".jpg" :  "http://wow.zamimg.com/images/wow/icons/large/ability_dualwield.jpg";
					if(response.items[slot][0] != undefined) $scope.gearClasses[slot] = "class-border-" + $scope.qualityPairs[response.items[slot][0].quality];

					if(!response.items[slot].length){
						console.log(name + " is missing " + slot);
						return;
					}

					$scope.gearPieces[slot].stats = {};

					response.stats.forEach(function(stats){
						stats.forEach(function(stat){
							if(stat.item_id === response.items[slot][0].id){
								$scope.statPairs[stat.stat].forEach(function(statString){
									if($scope.gearPieces[slot].stats[statString] === undefined) $scope.gearPieces[slot].stats[statString] = stat.amount;
									else $scope.gearPieces[slot].stats[statString] = parseInt($scope.gearPieces[slot].stats[statString]) + parseInt(stat.amount);
								});
							}
						});
					});
				});

				$scope.showCharacterBlock = true;

			}).
			error(function(response){
				$scope.error = response || 'Failed to get Info';
			});
	};

	$scope.setItemDescriptionData = function(piece){
		$scope.gearInformation = {};
		$scope.urlFocused = "";

		if($scope.gearPieces[piece]){
			$scope.gearInformation.id = $scope.gearPieces[piece][0].id;
			$scope.gearInformation.name = $scope.gearPieces[piece][0].name;
			$scope.gearInformation.description = $scope.gearPieces[piece][0].description;
			$scope.gearInformation.ilvl = $scope.gearPieces[piece][0].ilvl;
			$scope.gearInformation.name = $scope.gearPieces[piece][0].name;
			$scope.gearInformation.stats = $scope.gearPieces[piece].stats;
			$scope.gearInformation.rarityClass = "class-" + $scope.qualityPairs[$scope.gearPieces[piece][0].quality];
			$scope.gearInformation.rarityBorderClass = "class-border-" + $scope.qualityPairs[$scope.gearPieces[piece][0].quality];
			$scope.urlFocused = $scope.urls[piece];
			$scope.showRightCharacterInformationPane = true;
		}
		else{
			$scope.showRightCharacterInformationPane = false;
			delete $scope.gearInformation;
			delete $scope.urlFocused;
		}
	};
 
 	$scope.toggleClasses = function(){
 		$scope.showClasses = !$scope.showClasses;
 	}

 	$scope.toggleRaces = function(){
 		$scope.showRaces = !$scope.showRaces;
 	}

 	$scope.toggleDetails = function(){
 		$scope.showDetails = !$scope.showDetails;
 	}

 	$scope.resetAllDivs = function(){
	 	$scope.querySearchLimit = 20;
		$scope.showClasses = false;
		$scope.showRaces = false;
		$scope.showCharacterSearchResults = false;
		$scope.showCharacterBlock = false;
		$scope.showRightCharacterInformationPane = false;
		$scope.selectedCharacterIndex = null;

		$scope.showDetails = false;
		$scope.showRaces = false;
		$scope.showClasses = false;

		$scope.search_query = '';
 	}

 	$scope.selectCharacterIndex = function(selectedCharacterIndex){
 		$scope.selectedCharacterIndex = selectedCharacterIndex;
 	};

});