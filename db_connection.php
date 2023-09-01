<?php
session_start();
$host = "localhost"; // Your host name
$username = "root"; // Your database username
$password = ""; // Your database password
$database = "diagnostic"; // Your database name

$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
