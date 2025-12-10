<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Drop database
$sql = "DROP DATABASE IF EXISTS studi_kasus_pbl";
if ($conn->query($sql) === TRUE) {
    echo "Database dropped successfully\n";
} else {
    echo "Error dropping database: " . $conn->error . "\n";
}

// Create database
$sql = "CREATE DATABASE studi_kasus_pbl";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

$conn->close();
?>
