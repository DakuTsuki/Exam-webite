<?php
$host = "localhost"; // Or the host provided by your web host
$username = "id123456_user"; // Provided by host
$password = "your_new_password"; // Provided by host  
$database = "id123456_school_db"; // Provided by host

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
