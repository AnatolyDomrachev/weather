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

foreach((array)$config->mysql->tables as $table)
{
	$create = "( id INT AUTO_INCREMENT PRIMARY KEY, ";
	$create .= "city_id int, time int, ".implode(" varchar(50),", $config->parameters);
	$create .= " varchar(50))";
	$create = 'CREATE TABLE IF NOT EXISTS '. $table . $create;

		echo"\n\n\n";
		echo $create;
		echo"\n\n\n";

	if ( !$mysqli->query($create) ) {
	    echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	else
	    echo "Удалось создать таблицу\n" ;
}	 
?>
