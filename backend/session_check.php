<?php

ini_set('session.cookie_secure', 1);  // Use secure cookies (only over HTTPS)
ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session cookie
ini_set('session.cookie_samesite', 'Strict'); // Restrict cross-site cookie access

session_start();

$timeout_duration = 900;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['LAST_ACTIVITY']) &&
    (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Generate a new CSRF token for the session if it doesn't exist
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  // Generate a secure CSRF token
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check the CSRF token during form submission
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // Invalid CSRF token detected
        die('Invalid CSRF token');
    }
}

$_SESSION['LAST_ACTIVITY'] = time();
?>
