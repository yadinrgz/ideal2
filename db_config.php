<?php
$host = "localhost";
$username = "root";
$password = "root";
$database = "ideal2";

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>

