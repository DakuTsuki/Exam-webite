<?php
$host = "sql104.infinityfree.com";
$username = "if0_39829817";
$password = "wBXVeQpozKJ1Gc";
$database = "if0_39829817_school_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

