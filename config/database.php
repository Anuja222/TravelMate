<?php
$host = "127.0.0.1";      // or "localhost"
$db   = "travelmate";     // your database name
$user = "root";           // XAMPP default user
$pass = "";               // XAMPP default password (empty)
$charset = 'utf8mb4';
<<<<<<< HEAD
$port = "3307";
=======
$port = "3306";
>>>>>>> 3ae9d687beaa3bed7cd8b0600e2b949001449874

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset"; // create database connection
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
