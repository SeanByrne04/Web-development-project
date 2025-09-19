<?php

// Database variables
$servername = "localhost";
$username = "root";
$dbpassword = "";
$dbname = "library";


// Create a connection
$conn = new mysqli($servername, $username, $dbpassword, $dbname);

// Spits out error if didnt connect
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>