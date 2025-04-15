<?php
$dsn = "mysql:host=localhost;dbname=survey_db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $conn = new PDO($dsn, "root", "root", $options);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
