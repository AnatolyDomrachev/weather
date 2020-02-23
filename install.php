<?php

$config = json_decode(file_get_contents('config.json'));


$host = $config->mysql->host;
$user = $config->mysql->user;
$password = $config->mysql->passwd;
$database = $config->mysql->db;

$mysqli = new mysqli($host, $user, $password, $database);
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else
    echo "Удалось подключиться к MySQL \n" ;

$table = 'CREATE TABLE IF NOT EXISTS ' . $config->mysql->table;
$table .= "( id INT AUTO_INCREMENT PRIMARY KEY, ";
$table .= "city_id INT";

foreach($config->parameters as $column)
	$table .= " , ".$column." varchar(20)";
/*
$table .= ")";

	echo"\n\n\n";
	echo $table;
	echo"\n\n\n";
 */

if ( !$mysqli->query($table) ) {
    echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
}
else
    echo "Удалось создать таблицу\n" ;

?>
