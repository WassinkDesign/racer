<?php
$servername = "wassinkdesign.com:3306";
$username = "wdesign_racer_io";
$password = "qH6P0y5^^cLU";
$dbname = "wdesign_racer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>