<?php

$config = json_decode(file_get_contents('config.json'));
$table = $config->mysql->tables->main;
$tmp = $config->mysql->tables->tmp;
$host = $config->mysql->host;
$user = $config->mysql->user;
$password = $config->mysql->passwd;
$database = $config->mysql->db;
$gmt = $config->GMT*3600;

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$query = "delete from ".$tmp;
if ( !$mysqli->query($query) ) 
    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;

while(true)
{
	foreach($config->cities as $city)
	{
		$url = $config->api."/".$city->coord;
		$json = file_get_contents($url);
		$obj = json_decode($json);

		$all_data = $obj->hourly->data;

		foreach($all_data as $var)
		{
			$data = (array)$var;
			$query = 'INSERT INTO  '.$tmp."(";
			$columns = "city_id, time, ".implode(",", $config->parameters);
			$query .= $columns ;
			$query .= " ) VALUES (";
			$time = $data['time']+$gmt;
			$query .= $city->id.", ". $time;

			foreach($config->parameters as $column)
				$query .= ' , "'.$data[$column].'"';

			$query .= " )";

			if ( !$mysqli->query($query) ) 
			    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;
		}
	}

	$columns = "city_id, time, ".implode(",", $config->parameters);
	$query = "delete from ".$table." where time in (select time from ".$tmp.")";
	if ( !$mysqli->query($query) ) 
	    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;

	$query = "insert into ".$table."(".$columns.") select ".$columns." from ".$tmp;
	if ( !$mysqli->query($query) ) 
	    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;

	sleep($config->interval);
}

?>
