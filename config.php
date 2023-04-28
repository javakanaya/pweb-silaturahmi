<?php

$server="localhost";
$user="root";
$password="";
$namadb="silaturahmi";

$db = mysqli_connect($server,$user,$password,$namadb);

if(! $db){
    die("Gagal terhubung dengan database: " .msqli_connect_error());
}

?>