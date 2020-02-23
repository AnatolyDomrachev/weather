<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
  <meta charset="utf-8">
  </head>
  <body>

<h3>Выбор из интервала</h3>
<p>
<form action = show_interval.php method = GET>

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

$query = "select time from ".$table;
$result = $mysqli->query($query) ;
if ( !$result) 
    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;

?>

Выберите начальное время 
<br>
<select name="t1">

<?php
 while ($row = $result->fetch_assoc()) {
        printf ("<option value = %s> %s </option> \n", $row["time"], gmdate("Y.m.d H:i:s", $row["time"]));
    }

    $result->free();

?>
</select>
<br>

<?php
$result = $mysqli->query($query) ;
if ( !$result) 
    echo "Не удалось выполнить запрос (" . $mysqli->errno . ") " . $mysqli->error;

?>

Выберите конечное время 
<br>
<select name="t2">

<?php
 while ($row = $result->fetch_assoc()) {
        printf ("<option value = %s> %s </option> \n", $row["time"], gmdate("Y-m-d H:i:s", $row["time"]));
    }

    $result->free();

?>
</select>
<p>
<input type = submit>
</form>
<p>
<a href = realtime.php> <h3>Прогноз в реальном времени</h3> </a>

</body>
</html>
