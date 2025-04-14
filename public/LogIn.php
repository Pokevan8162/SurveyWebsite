<?php
require_once __DIR__ . '/../backend/db.php';
session_start();

// Initialize error message
$error_message = "";

//if form is submitted with submit button
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputEmail = htmlspecialchars(trim($_POST['Email']));
    $inputPassword = trim($_POST['Password']);

    // Grab the salt from the database
    $stmt = $pdo->prepare("SELECT Salt FROM USERS WHERE Email = :Email");
    $stmt->bindParam(':Email', $inputEmail, PDO::PARAM_STR);
    $stmt->execute();
    $saltRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$saltRow) {
        $error_message = "Invalid email or password.";
    } else { // Passed error, continue as normal
            
    $salt = $saltRow['Salt'];

    // Combine the salt with the password
    $password = $inputPassword . $salt;

    // Hash the password with the salt
    $hashedPassword = shell_exec("java -cp " . __DIR__ . "/../java PasswordHash " . escapeshellarg($password));
    $hashedPassword = trim($hashedPassword); // Trim in case Java prints a new line at the end

    //fetch user data for comparing passwords
    $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = :Email AND Password = :Password");
    $stmt->bindParam(':Email', $inputEmail, PDO::PARAM_STR);
    $stmt->bindParam(':Password', $hashedPassword, PDO::PARAM_STR); // verify password in database
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) { // if the user actually exists with that email and password,
        //start session, store UserID to grab user specific information (ex. Gender for surveys)
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['Email'] = $user['Email'];
        $_SESSION['UserStatus'] = $user['UserStatus'];

        if ($user['UserStatus'] === 'Admin') {
            //redirect to admin dashboard if user is an Admin
            header("Location: adminIndex.php");
        } else {
            //regular dashboard
            header("Location: index.php");
        }
        exit;
    } else {
        $error_message = "Invalid email or password.";
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
    <title>Log In</title>
</head>
<body>
    <img src="https://s3-us-west-2.amazonaws.com/scorestream-team-profile-pictures/285522/20181011000648_310_mascot1280Near.png" alt="Logo" class="logo">   <!--https://media0.giphy.com/media/UWVbIdzSqRVCvJnxFS/source.gif -->
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
