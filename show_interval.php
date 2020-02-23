<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
  <meta charset="utf-8">
  </head>
  <body>

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
?>


<?php foreach($config->cities as $city): ?>
<?php echo "<h3>".$city->name."</h3>"; ?>
<table border = 1>
<tr>
<td>Time
<?php foreach($config->parameters as $column): ?>

<?php echo "<td>".$column."</td>"; ?>

<?php endforeach; ?>

</table>

<?php endforeach; ?>

<?php
$t1 = $_GET['t1'];
$t2 = $_GET['t2'];

$query = "select * from ".$table." where time >= ".$t1." and time <= ".$t2;
$result = $mysqli->query($query) ;
if ( !$result) 
    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;


?>

