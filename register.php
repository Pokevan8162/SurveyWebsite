<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//include 'db.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if (substr($email, -11) !== "@indwes.edu" or substr($email, -19) !== "@myemail.indwes.edu") {
        echo "You must use an IWU email address.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="introPages.css">
    <title>Sign Up</title>
</head>
<body>
    <img src="https://s3-us-west-2.amazonaws.com/scorestream-team-profile-pictures/285522/20181011000648_310_mascot1280Near.png" alt="Logo" class="logo">
    <div class="container">
        <div class="form_area">
            <div class="title">Sign Up</div>
            <div class="sub_title">Create an account</div>
            <form method="POST">
                <div class="form_group">
                    <input type="text" class="form_style" placeholder="Full Name" name = "username" required>
                </div>
                <div class="form_group">
                    <input type="email" class="form_style" placeholder="Email" name = "email" required>
                </div>
                <div class="form_group">
                    <input type="password" class="form_style" placeholder="Password" name = "password" required>
                </div>
                <button type = "submit" class="btn">Register</button>
                <a href="LogIn.html" class="link">Already have an account? Sign in</a>
            </form>
            
        </div>
    </div>
</body>
</html>
