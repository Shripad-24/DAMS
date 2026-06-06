<?php
$host = '127.0.0.1';
$user = 'root';     // XAMPP default
$pass = 'root';        // XAMPP default
$db = 'dams_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die('DB connection failed: ' . $conn->connect_error);
}

// $conn->set_charset('utf8mb4');