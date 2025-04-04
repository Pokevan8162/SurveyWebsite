<?php
session_start();

$host = 'localhost';    //host
$dbname = "survey_db";  // db name
$dbUsername = "root";   // db username
$dbPassword = "";       // db password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// Initialize error message
$error_message = "";

//check if submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
    $inputPassword = $_POST['password'];
    $hashedPassword = shell_exec("java PasswordHash.java " . escapeshellarg($inputPassword));
    $passwordSalt = shell_exec("java PasswordSalt.java");
    $fullPassword = $hashedPassword + $passwordSalt;

    //check if email used alr
    $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = :Email");
    $stmt->bindParam(':Email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $error_message = "This email is already registered.";
    } elseif (empty($email) || empty($password) || empty($gender)) {
        $error_message = "All fields are required!";
    } else {
        //insert into db
        $stmt = $pdo->prepare("INSERT INTO users (Email, Password, Gender) VALUES (:Email, :Password, :Gender)");
        $stmt->bindParam(':Email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':Password', $fullPassword, PDO::PARAM_STR); //pw not hashed
        $stmt->bindParam(':Gender', $gender, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            header("Location: login.php");  //redirected to login page now to sign in
            exit;
        } else {
            $error_message = "Something went wrong. Please try again.";
        }
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

            <?php if (!empty($error_message)) : ?>
                <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form_group">
                    <input type="email" name="email" class="form_style" placeholder="Email" required>
                </div>
                <div class="form_group">
                    <input type="password" name="password" class="form_style" placeholder="Password" required>
                </div>
                <div class="radio_group">
                    <label><input type="radio" name="gender" value="Male" required> Male</label>
                    <label><input type="radio" name="gender" value="Female" required> Female</label>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>

            <a href="login.php" class="link">Already have an account? Sign in</a>
        </div>
    </div>
</body>
</html>
