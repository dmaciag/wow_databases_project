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
    $missing_items_sql = 
    "SELECT c.hands as 'id'
     FROM   characters ch
     JOIN character_items c
     ON ch.id = c.character_id
     LEFT JOIN   items
     ON     c.hands = items.id
     where items.id IS NULL

     ";

    $items = mysql_query( $missing_items_sql );
    while( $item = mysql_fetch_assoc($items) ){
        $result = getResults("https://us.api.battle.net/wow/item/" . $item["id"] . "?locale=en_US&apikey=guzutx3uf7rny98wrhqj49sk7zvfdg5a");
        insertItem($result);
    }

?>