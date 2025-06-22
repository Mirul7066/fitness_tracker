<?php
$host = 'localhost';
$dbname = 'fitness_tracker';
$username = 'root'; // Default for XAMPP
$password = '';     // Default for XAMPP is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Enable exception mode for errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
