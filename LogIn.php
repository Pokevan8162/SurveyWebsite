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

//if form is submitted with submit button
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputEmail = htmlspecialchars(trim($_POST['Email']));
    $inputPassword = trim($_POST['Password']);  //no hashing currently

    $hashedPassword = shell_exec("java PasswordHash.java " . escapeshellarg($inputPassword));
    $stmt = $pdo->prepare("SELECT Sat FROM USERS WHERE UserID = session.userID"); // change session userID to what actually works
    $salt = $stmt->execute();
    $hashedPassword += $salt;

    // todolist: update login.php to this, update signin.php to include the password hash, and update passwordhash.java and make
    // salt.java

    //fetch user data for comparing passwords
    $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = :Email AND Password = :Password");
    $stmt->bindParam(':Email', $inputEmail, PDO::PARAM_STR);
    $stmt->bindParam(':Password', $hashedPassword, PDO::PARAM_STR); //check password
    $stmt->bindParam(':Salt', $hashedPassword, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        //start session, store UserID to grab user specific information (ex. Gender for surveys)
        $_SESSION['user_id'] = $user['UserID'];

        if ($user['UserStatus'] === 'Admin') {
            //redirect to admin dashboard if user is an Admin
            header("Location: adminIndex.php");
        } else {
            //regular dashboard
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="introPages.css">
    <title>Log In</title>
</head>
<body>
    <img src="https://s3-us-west-2.amazonaws.com/scorestream-team-profile-pictures/285522/20181011000648_310_mascot1280Near.png" alt="Logo" class="logo">
    <div class="container">
        <div class="form_area">
            <div class="title">Log In</div>
            <div class="sub_title">Email and Password</div>

            <?php if (!empty($error_message)) : ?>
                <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form_group">
                    <input type="email" name="Email" class="form_style" placeholder="Email" required>
                </div>
                <div class="form_group">
                    <input type="password" name="Password" class="form_style" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Log In</button>
            </form>

            <a href="signUp.php" class="link">No account? Sign up</a>
        </div>
    </div>
</body>
</html>
