<?php
require_once __DIR__ . '/../backend/db.php';
session_start();

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure token
}


// Initialize error message
$error_message = "";

//check if submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $gender = $_POST['gender'];
    $inputPassword = $_POST['password'];
    $userStatus = "User";

    if (empty($email) || empty($inputPassword) || empty($gender)) {
    $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } elseif (!preg_match('/@(myemail\.indwes\.edu|indwes\.edu)$/i', $email)) {
        $error_message = "Email must be an @myemail.indwes.edu or @indwes.edu address.";
    } else { // Passed

    // Find out if email already exists. If not, execute insert into database.
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = :Email");
    $stmt->bindParam(':Email', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) { // if the email exists in the database,
        $error_message = "This email is already registered.";
    } else {
        $salt = shell_exec("java -cp \"" . __DIR__ . "/../java\" PasswordSalt");
        $salt = trim($salt); // trim in case java adds an extra line to the end
        $password = $inputPassword . $salt;
        $hashedPassword = shell_exec("java -cp \"" . __DIR__ . "/../java\" PasswordHash " . escapeshellarg($password));
        $hashedPassword = trim($hashedPassword); // trim in case java adds an extra line to the end
    
        //insert into db
        $stmt = $conn->prepare("INSERT INTO users (Email, Password, Gender, UserStatus, Salt) VALUES (:Email, :Password, :Gender, :UserStatus, :Salt)");
        $stmt->bindParam(':Email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':Password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':Salt', $salt, PDO::PARAM_STR);
        $stmt->bindParam(':Gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':UserStatus', $userStatus, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            header("Location: login.php");  //redirect to login page now to sign in
            exit;
        } else {
            $error_message = "Something went wrong. Please try again.";
        }
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../resources/css/introPages.css">
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
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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
