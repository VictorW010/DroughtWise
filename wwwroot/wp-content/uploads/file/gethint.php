<?php

$q = $_REQUEST["q"];

global $wpdb;

$var = $wpdb -> get_var("SELECT town_rainfall_trend FROM wp_towns WHERE town_id = 1");

echo $var;

?>