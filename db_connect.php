<?php
$host = "127.0.0.1"; // Use IP address instead of localhost
$username = "root";
$password = ""; // No password
$database = "school_db";
$port = 3306; // Default MySQL port

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
