<?php
$dsn = "mysql:host=localhost;dbname=gpuDatabase;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, "root", "Besttracer55!", $options);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}