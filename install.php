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

$table .= "( id INT AUTO_INCREMENT PRIMARY KEY, ";
$table .= "city_id INT";
$table .= ", time INT";

foreach($config->parameters as $column)
	$table .= " , ".$column." varchar(50)";

$table_1 = 'CREATE TABLE IF NOT EXISTS ' . $config->mysql->table.$table.")";
$table_tmp = 'CREATE TABLE IF NOT EXISTS ' . $config->mysql->table."_tmp".$table.")";

$table .= ")";

	echo"\n\n\n";
	echo $table_1;
	echo"\n\n\n";
	echo $table_tmp;
	echo"\n\n\n";

if ( !$mysqli->query($table_1) ) {
    echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
}
else
    echo "Удалось создать таблицу\n" ;

if ( !$mysqli->query($table_tmp) ) {
    echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
}
else
    echo "Удалось создать таблицу\n" ;
 
?>
