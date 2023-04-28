<?php

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "silaturahmi";

$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);

if($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;
