<?php

$db_hostname = "localhost";
$db_username = "root";
$db_password = "root";
$db_db = "db_project";

$connect = mysql_connect($db_hostname, $db_username, $db_password);
$db_connect = mysql_select_db($db_db);

function curlConnect($the_url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $the_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data,true);
}

function getResults($file_url){
    return curlConnect($file_url);
}

function insertClasses($data){
    foreach ($data["classes"] as $key => $value) {
        $name = $value["name"];
        $val  = $value["powerType"];
        $insertClass =   "INSERT INTO character_classes ( name, powerType) 
                        VALUES ( '$name' , '$val')";
        mysql_query($insertClass);
    }
}

function insertRaces($data){
    foreach ($data["races"] as $key => $value) {
        $name = $value["name"];
        $side  = $value["side"];
        if( $side == "horde"){
            $insertRaces =   "INSERT INTO races ( name, faction) 
                        VALUES ( '$name' , 0)";
        }
        else if( $side == "alliance"){
            $insertRaces =   "INSERT INTO races ( name, faction) 
                        VALUES ( '$name' , 1)";
        }
        else{
            $insertRaces =   "INSERT INTO races ( name, faction) 
                        VALUES ( '$name' , 2)";
        }
        mysql_query($insertRaces);
    }
}

function insertCharacter($data){

    $name           = $data["name"];
    $realm          = $data["realm"];
    $battlegroup    = $data["battlegroup"];
    $class          = $data["class"];
    $race           = $data["race"];
    $gender         = $data["gender"];
    $level          = $data["level"];
    $achievePts     = $data["achievementPoints"];
    $faction        = $data["faction"];
    $totalHks       = $data["totalHonorableKills"];

    $insertCharacter = "INSERT INTO characters ( name, realm, battlegroup, class, race, gender, level, achievePts, faction, totalHks) 
                        VALUES ( '$name' ,\"$realm\",\"$battlegroup\",'$class','$race','$gender','$level','$achievePts', '$faction', '$totalHks')";
    mysql_query($insertCharacter);

}

$characters = array(
    'Remoras'       => "Kel'Thuzad",
    'Virael'        => "Kel'Thuzad",
    'Ozzxd'         => "Tichondrius",
    'Verelea'       => "Barthilas",
    'Sleepiihead'   => "Mal'Ganis",
    'Suchii'        => "Illidan",
    'Thugonomicz'   => "Tichondrius",
    'Smokingnasni'  => "Azralon",
    'Dotwin'        => "Durotan",
    'Bobthehefty'   => "Tanaris",
    'Gwz'           => "Illidan",
    'Lew'           => "Mal'Ganis",
    'Cycloneheals'  => "Tichondrius",
    'Fudgeo'        => "Kil'Jaeden",
    'Guccifam'      => "Tichondrius",
    'Werdyh'        => "Ragnaros",
    'Yatorishino'   => "Mannoroth",
    'Solsacra'      => "Sargeras",
    'Unclefista'    => "Frostmourne",
    'Sherebear'     => "Kil'Jaeden",
    'Amy'           => "Stormreaver",
    'Corgibellies'  => "Illidan",
    'Knox'          => "Arthas",
    'Hypedown'      => "Darkspear",
    'Ballzbandit'   => "Mug'Thol",
    'Kulty'         => "Ragnaros",
    'Octaviussnk'   => "Nemesis",
    'Huggrz'        => "Lothar",
    'Eu'            => "Barthilas",
);

function insertCharacterItems($data, $j){
    
    $items = $data["items"];
    
    $head       = $items["head"]["id"];
    $neck       = $items["neck"]["id"];
    $shoulder   = $items["shoulder"]["id"];
    $back       = $items["back"]["id"];
    $chest      = $items["chest"]["id"];
    $wrist      = $items["wrist"]["id"];
    $hands      = $items["hands"]["id"];
    $legs       = $items["legs"]["id"];
    $waist      = $items["waist"]["id"];
    $feet       = $items["feet"]["id"];
    $finger1    = $items["finger1"]["id"];
    $finger2    = $items["finger2"]["id"];
    $trinket1   = $items["trinket1"]["id"];
    $trinket2   = $items["trinket2"]["id"];
    $mainHand   = $items["mainHand"]["id"];
    $offHand    = $items["offHand"]["id"];

    $insertCharacterItems = "INSERT INTO character_items ( character_id, head, neck, shoulder, back, chest, wrist, hands, waist, legs, feet, finger1, finger2, trinket1, trinket2, mainHand, offHand) 
                         VALUES ( '$j', '$head' ,'$neck', '$shoulder', '$back' ,'$chest','$wrist','$hands','$waist', '$legs', '$feet', '$finger1', '$finger2', '$trinket1', '$trinket2', '$mainHand', '$offHand')";
    mysql_query($insertCharacterItems);

}

$items = array(
    135771
);

function insertItem($data){

    $id = $data['id'];
    $description = $data['description'];
    $name = $data['name'];
    $icon = $data['icon'];
    $ilvl = $data['itemLevel'];
    $quality = $data['quality'];

    foreach ($data['bonusStats'] as $bonus) {
        
        $stat   = $bonus["stat"];
        $amount = $bonus["amount"];

        $insertStat = "INSERT INTO stats ( item_id, stat, amount) 
                        VALUES ( '$id' , '$stat', $amount)";
        mysql_query($insertStat);
    }

    $insertItem = "INSERT INTO items ( id, name, description, icon, ilvl, quality) VALUES ( '$id' , \"$name\", \"$description\", '$icon', '$ilvl', '$quality')";
    var_dump($insertItem);
    mysql_query($insertItem);

}

function getInformation($type, $characters = null, $items = null){
    switch($type){
        case 'characterClasses':
            $result = getResults('https://us.api.battle.net/wow/data/character/classes?locale=en_US&apikey=guzutx3uf7rny98wrhqj49sk7zvfdg5a');
            insertClasses($result);
            break;
        case 'races':
            $result = getResults('https://us.api.battle.net/wow/data/character/races?locale=en_US&apikey=guzutx3uf7rny98wrhqj49sk7zvfdg5a');
            insertRaces($result);
            break;
        case 'character':
            foreach($characters as $character => $server){
                $result = getResults("https://us.api.battle.net/wow/character/" . $server . "/" . $character . "?locale=en_US&apikey=guzutx3uf7rny98wrhqj49sk7zvfdg5a");
                insertCharacter($result);
            }
            break;
        case 'characterItems':
            $j = 36;
            foreach($characters as $character => $server){
                $result = getResults("https://us.api.battle.net/wow/character/" . $server . "/" . $character . "?fields=items&locale=en_US&apikey=guzutx3uf7rny98wrhqj49sk7zvfdg5a");
                insertCharacterItems($result, $j++);
            }
            break;
        case 'items':
            foreach ($items as $item) {
                $result = getResults("https://us.api.battle.net/wow/item/" . $item . "?locale=en_US&apikey=guzutx3uf7rny98wrhqj49sk7zvfdg5a");
                insertItem($result);
            }
            break;
        default:
            break;
    }
}

getInformation('items', null, array());

mysql_close($connect);

?>