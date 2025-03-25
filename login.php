<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    $_SESSION['username'] = $username;

    if (isset($_POST['remember'])) {
        setcookie('username', $username, time() + 86400, "/"); // Valid for 1 day
    }

    header("Location: welcome.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Website Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label><input type="checkbox" name="remember">Remember Me</label>
        <button type="submit">Login</button>
    </form>
</body>
</html>