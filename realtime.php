<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
  <meta charset="utf-8">
  </head>
  <body>

<?php

$config = json_decode(file_get_contents('config.json'));
$tmp = $config->mysql->tables->last;
$table = $config->mysql->tables->last;
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

?>

<?php foreach($config->cities as $city): ?>

<?php

$query = "select * from ".$table." where city_id = ".$city->id." and time >= ".time();
$result = $mysqli->query($query) ;
if ( !$result) 
    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;

?>


<?php echo "<h3>".$city->name."</h3>"; ?>
<table border = 1>
<tr>
<td>Time
<?php 

foreach($config->parameters as $column)
	echo "<td>".$column."</td>"; 

while ($row = $result->fetch_assoc()) {
	echo "<tr>";
        printf ("<td>%s \n", gmdate("Y.m.d H:i:s", $row["time"]));
	foreach($config->parameters as $column)
		printf ("<td>%s \n", $row[$column]);
    }

?>

</table>

<?php endforeach; ?>



