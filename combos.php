<?php

// 1 Human              1 Hunter
// 2 Orc                2 Rogue
// 3 Dwarf              3 Warrior
// 4 N Elf              4 Paladin
// 5 Undead             5 Shaman
// 6 Tauraen            6 Mage
// 7 Gnome              7 Priest
// 8 Troll              8 Death Knight
// 9 Goblin             9 Druid
// 10 Blood Elf         10 Demon Hunter
// 11 Draenai           11 Warlock
// 12 Worgen            12 Monk
// 13 Pandaren

$db_hostname = "localhost";
$db_username = "root";
$db_password = "root";
$db_db = "db_project";

$connect = mysql_connect($db_hostname, $db_username, $db_password);
$db_connect = mysql_select_db($db_db);

$raceClassCombos = array(
    1 => array(7,2,3,6,1,11,4,8,12),
    2 => array(2,3,6,1,11,5,8,12),
    3 => array(1,2,3,4,5,6,7,8,11,12),
    4 => array(1,2,3,6,7,8,9,10,12),
    5 => array(1,2,3,6,7,8,11,12),
    6 => array(7,3,9,1,5,4,8,12),
    7 => array(1,2,3,6,7,8,11,12),
    8 => array(1,2,3,5,6,7,8,9,11,12),
    9 => array(1,2,3,6,7,11,5,8),
    10 => array(7,2,3,6,1,11,4,8,11,10),
    11 => array(7,3,6,1,5,4,8,12),
    12 => array(1,2,3,6,7,8,9,11),
    13 => array(7,2,3,6,1,5,12)
);

foreach ($raceClassCombos as $race_id => $class_array) {
    foreach ($class_array as $class_id) {
        $sql = "INSERT INTO race_classes (race_id, class_id) VALUES ($race_id, $class_id)";
        mysql_query($sql);
    }
}

?>