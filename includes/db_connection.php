<?php
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$database = "camp_finder";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
