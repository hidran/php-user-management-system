<?php


$config = require 'config.php';

//print_r($config);
$mysqli = new mysqli(
    $config['mysql_host'],
    $config['mysql_user'],
    $config['mysql_password'],
    $config['mysql_db']
);

unset($config);

if ($mysqli->connect_error) {
    die($mysqli->connect_error);
}
