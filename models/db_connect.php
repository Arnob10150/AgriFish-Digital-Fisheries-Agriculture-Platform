<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "dfap_db";
$conn = new mysqli($servername, $username, $password, $database);
// Removed die() to allow graceful handling of connection errors
?>