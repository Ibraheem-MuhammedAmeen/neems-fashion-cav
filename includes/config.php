<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "neems_fashion_cave";


$conn = new mysqli($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed:" . mysqli_connect_error());
}
?>