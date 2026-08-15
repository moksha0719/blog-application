<?php

// Auto-detect if running locally or on server
$is_local = ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1');

if ($is_local) {
    // Localhost - XAMPP
    $host = "localhost";
    $dbname = "blog_app";
    $username = "root";
    $password = "";
} else {
    // InfinityFree - Hosted
    $host = "sql102.infinityfree.com";
    $dbname = "if0_42663744_blog_app";
    $username = "if0_42663744";
    $password = "PM7193iyu";
}

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>