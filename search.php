<?php

error_reporting(E_ERROR | E_PARSE);

$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : null;
$route = isset($_GET['route']) ? $_GET['route'] : null;
$limit = isset($_GET['limit']) ? $_GET['limit'] : null;
$name = isset($_GET['name']) ? $_GET['name'] : null;
$realm = isset($_GET['realm']) ? $_GET['realm'] : null;

if(!empty($route) && !is_null($route)){
	
    $search_query = sanitize($search_query);
    $name = sanitize($name);
    $realm = sanitize($realm);
    $limit = sanitize($limit);

	if((!empty($search_query) && !is_null($search_query)) ||
        (!empty($name) && !is_null($name) && !empty($realm) && !is_null($realm))
    ){

		switch($route){
			case 'character_list_search':
				echo character_list_search($search_query, $limit);
				break;
            case 'character_detail_search':
                echo character_detail_search($name, $realm);
                break;
            case 'race_class_combos':
                echo race_class_combos();
                break;
            case 'race_factions':
                echo race_factions();
                break;
			default:
				die('bad redirect routing parameters');
				break;
		}

	}
	else{
		die('why are you putting bad parameters');
	}
}
else{
	die('wrong setup routing parameters');
}

function sanitize($sanitized = null){
    $sanitized = mysql_real_escape_string($sanitized);
	return $sanitized;
}

function race_factions(){

    require './config.php';
    $connect = mysql_connect($db_hostname, $db_username, $db_password);

    if( !$connect ) die('Connection to mysql failed, error : ' . mysql_error());
    if( !mysql_select_db($db_db) ) die('Cannot connect to db : $db_db, ' . mysql_error());

    $race_factions_sql = 
    "SELECT r.name as 'name' , f.name as 'faction'
     FROM races r
     JOIN factions f
     ON f.faction_id = r.faction
     LIMIT 13";

    $race_factions = mysql_query( $race_factions_sql );
    while( $race_faction = mysql_fetch_assoc($race_factions) ){
        $json_race_faction['name'] = $race_faction["name"];
        $json_race_faction['faction'] = $race_faction["faction"];
        $json_race_faction_response[]  = $json_race_faction;
    }
    $race_factions_result = array("race_factions" => $json_race_faction_response);

    return json_encode($race_factions_result);

}

function race_class_combos(){

    require './config.php';
    $connect = mysql_connect($db_hostname, $db_username, $db_password);

    if( !$connect ) die('Connection to mysql failed, error : ' . mysql_error());
    if( !mysql_select_db($db_db) ) die('Cannot connect to db : $db_db, ' . mysql_error());

    $race_class_combos_sql = 
    "SELECT rc.race_id as 'race_id', rc.class_id as 'class_id', r.name as 'race_name', c.name as 'class_name'
     FROM race_classes rc
     JOIN races r
     ON rc.race_id = r.id
     JOIN classes c 
     ON rc.class_id = c.id";

    $race_class_combos = mysql_query( $race_class_combos_sql );
    while( $race_class_combo = mysql_fetch_assoc($race_class_combos) ){
        $json_race_class_combo['class_id'] = $race_class_combo["class_id"];
        $json_race_class_combo['race_id'] = $race_class_combo["race_id"];
        $json_race_class_combo['race_name'] = $race_class_combo["race_name"];
        $json_race_class_combo['class_name'] = $race_class_combo["class_name"];
        $json_race_class_combo_response[]  = $json_race_class_combo;
    }
    $race_class_combos_result = array("race_class_combos" => $json_race_class_combo_response);

    return json_encode($race_class_combos_result);

}

function character_detail_search($name = null, $realm = null){

    require './config.php';
    $connect = mysql_connect($db_hostname, $db_username, $db_password);

    if( !$connect ) die('Connection to mysql failed, error : ' . mysql_error());
    if( !mysql_select_db($db_db) ) die('Cannot connect to db : $db_db, ' . mysql_error());

    $detail_sql = 
    "SELECT ci.head as 'head', 
            ci.neck as 'neck', 
            ci.shoulder as 'shoulder', 
            ci.back as 'back', 
            ci.chest as 'chest', 
            ci.wrist as 'wrist', 
            ci.hands as 'hands', 
            ci.waist as 'waist', 
            ci.legs as 'legs', 
            ci.feet as 'feet', 
            ci.finger1 as 'finger1', 
            ci.finger2 as 'finger2', 
            ci.trinket1 as 'trinket1', 
            ci.trinket2 as 'trinket2', 
            ci.mainHand as 'mainHand', 
            ci.offHand as 'offHand'
     FROM   characters c
     JOIN   character_items ci
     ON     c.id = ci.character_id
     WHERE  name = \"$name\"
     AND    realm =\"$realm\"
     ";

    $item_ids = mysql_query( $detail_sql );

    $items = array();
    $stats = array();

    while( $item_result = mysql_fetch_assoc($item_ids) ){
        foreach ($item_result as $slot_name => $slot_id) {
            $item_sql = 
            "SELECT id, name, description, icon, ilvl, quality
             FROM   items 
             WHERE  id = $slot_id
             ";

            $item = mysql_query( $item_sql );

            $items_details = array();
            while( $item_details = mysql_fetch_assoc($item) ){
                $json_item_details['id'] = $item_details['id'];
                $json_item_details['name'] = $item_details['name'];
                $json_item_details['description'] = $item_details['description'];
                $json_item_details['icon'] = $item_details['icon'];
                $json_item_details['ilvl'] = $item_details['ilvl'];
                $json_item_details['quality'] = $item_details['quality'];
                $items_details[] = $json_item_details;
            }

            $items[$slot_name] = $items_details;

            $stat_sql = 
            "SELECT item_id, stat, amount
             FROM   stats
             WHERE  item_id = $slot_id
             ";

            $stat = mysql_query( $stat_sql );

            $stats_details = array();
            while( $stat_details = mysql_fetch_assoc($stat) ){
                $json_stat_details['item_id'] = $stat_details['item_id'];
                $json_stat_details['stat'] = $stat_details['stat'];
                $json_stat_details['amount'] = $stat_details['amount'];
                $stats_details[] = $json_stat_details;
            }

            $stats[] = $stats_details;
        }
    }

    $character_detail_search = array("items" => $items, "stats" => $stats);

    return json_encode($character_detail_search);

}

function character_list_search($search_param = null, $limit){

    require './config.php';
	$connect = mysql_connect($db_hostname, $db_username, $db_password);

	if( !$connect ) die('Connection to mysql failed, error : ' . mysql_error());
	if( !mysql_select_db($db_db) ) die('Cannot connect to db : $db_db, ' . mysql_error());

    $races = array();
    $classes = array();

    $search_param = '%' . $search_param . '%';

	$characters_sql = 
    "SELECT c.name as'name', c.realm as 'realm', f.name as 'faction', c.battlegroup as 'battlegroup', cl.name as 'class', r.name as 'race', c.level as 'level', c.gender as 'gender'
     FROM   characters c
     JOIN   races r
     ON     c.race = r.id
     JOIN   classes cl
     ON     c.class = cl.id
     JOIN   factions f
     ON     c.faction = f.faction_id
     WHERE  c.name LIKE '$search_param'
     OR c.faction LIKE '$search_param'
     OR c.realm LIKE \"$search_param\"
     OR c.battlegroup LIKE '$search_param'
     OR r.name LIKE '$search_param'
     OR c.level LIKE '$search_param'
     OR cl.name LIKE '$search_param'
     OR f.name LIKE '$search_param'
     LIMIT  $limit";

    $characters = mysql_query( $characters_sql );
    while( $character = mysql_fetch_assoc($characters) ){
        $json_character['name'] = $character["name"];
        $json_character['realm'] = $character["realm"];
        $json_character['battlegroup'] = $character["battlegroup"];
        $json_character['class'] = $character["class"];
        $json_character['race'] = $character["race"];
        $json_character['faction'] = $character["faction"];
        $json_character['gender'] = $character["gender"];
        $json_character['level'] = $character["level"];
        $json_character_response[]  = $json_character;
    }
    $character_list_search = array("characters" => $json_character_response);

	return json_encode($character_list_search);
}


?>