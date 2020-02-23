<?php

$config = json_decode(file_get_contents('config.json'));

foreach($config->cities as $city)
{
	$url = $config->api."/".$city->coord;
	echo"\n\n\n";
	echo $url ;
	echo"\n\n\n";
	$json = file_get_contents($url);
	$obj = json_decode($json);
	print_r($obj->hourly->data);
}
?>
