<?php

$config = json_decode(file_get_contents('config.json'));
$table = $config->mysql->table;
$tmp = $config->mysql->table."_tmp";

$host = $config->mysql->host;
$user = $config->mysql->user;
$password = $config->mysql->passwd;
$database = $config->mysql->db;

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

foreach($config->cities as $city)
{
	$url = $config->api."/".$city->coord;
	echo"\n\n\n";
	echo $url ;
	echo"\n\n\n";
	$json = file_get_contents($url);
	$obj = json_decode($json);

	$all_data = $obj->hourly->data;

	foreach($all_data as $var)
	{
		$data = (array)$var;
		$query = 'INSERT INTO  '.$tmp;
		$query .= "(city_id, time";

		foreach($config->parameters as $column)
			$query .= " , ".$column;

		$query .= " ) VALUES (";
		$query .= $city->id.", ". $data['time'];

		foreach($config->parameters as $column)
			$query .= ' , "'.$data[$column].'"';

		$query .= " )";

		if ( !$mysqli->query($query) ) {
		    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;
		}
	}
}
		$query = "insert into ".$table." select * from ".$tmp;

		if ( !$mysqli->query($query) ) {
		    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;
		}

		echo"\n\n\n";
		echo $query;
		echo"\n\n\n";

?>
