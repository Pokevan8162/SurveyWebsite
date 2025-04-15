<?php
    //session start
    session_start();
    require_once __DIR__ . '/../backend/db.php';
    try {
        $conn = new PDO("mysql:host=localhost;dbname=survey_db", 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // session check
        if (!isset($_SESSION['user_id'])) {
            echo "<a href=login.php>Please log in.</a>";
            exit;
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="usersCSS.css">
</head>
<body>
    <img src="https://s3-us-west-2.amazonaws.com/scorestream-team-profile-pictures/285522/20181011000648_310_mascot1280Near.png" alt="Logo" class="logo">
    <div class="header">
        <a href="logout.php" class="logout"><button type="button" class="btn">Logout</button></a>
    </div>
    <div class="container">

    <?php
        $conn = new PDO("mysql:host=localhost;dbname=survey_db", 'root', '');

        // Handle Admin Updates
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_admins']) && isset($_POST['admins'])) {
                foreach ($_POST['admins'] as $user) {
                    $inputPassword = $user['password'];
                    $salt = shell_exec("java -cp " . __DIR__ . "/../java PasswordSalt");
                    $salt = trim($salt); // trim in case java adds an extra line to the end
                    $password = $inputPassword . $salt;
                    $hashedPassword = shell_exec("java -cp " . __DIR__ . "/../java PasswordHash " . escapeshellarg($password));
                    $hashedPassword = trim($hashedPassword); // trim in case java adds an extra line to the end

                    $stmt = $conn->prepare("UPDATE users SET Email = :email, Password = :password, Salt = :salt, UserStatus = :userStatus, Gender = :gender WHERE Email = :email");
                    $stmt->bindParam(':email', $user['email']);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':salt', $salt);
                    $stmt->bindParam(':userStatus', $user['userStatus']);
                    $stmt->bindParam(':gender', $user['gender']);
                    $stmt->execute();
                }
                echo "<p style='color:green;'>Admin users updated!</p>";
            }

            // Handle Regular User Updates
            if (isset($_POST['update_users']) && isset($_POST['users'])) {
                foreach ($_POST['users'] as $user) {
                    $inputPassword = $user['password'];
                    $salt = shell_exec("java -cp " . __DIR__ . "/../java PasswordSalt");
                    $salt = trim($salt); // trim in case java adds an extra line to the end
                    $password = $inputPassword . $salt;
                    $hashedPassword = shell_exec("java -cp " . __DIR__ . "/../java PasswordHash " . escapeshellarg($password));
                    $hashedPassword = trim($hashedPassword); // trim in case java adds an extra line to the end

                    $stmt = $conn->prepare("UPDATE users SET Email = :email, Password = :password, Salt = :salt, UserStatus = :userStatus, Gender = :gender WHERE Email = :email");
                    $stmt->bindParam(':email', $user['email']);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':salt', $salt);
                    $stmt->bindParam(':userStatus', $user['userStatus']);
                    $stmt->bindParam(':gender', $user['gender']);
                    $stmt->execute();
                }
                echo "<p style='color:green;'>Regular users updated!</p>";
            }
        }
    ?>

    <!-- Admin Section -->
    <form method="POST">
        <div class="form_area">
            <div class="title">Administrative Users</div>
            <div class="user">
                <input type="email" value="Email" size="40" readonly>
                <input type="text" value="Password" size="20" readonly>
                <input type="text" value="User Status" size="10" readonly>
                <input type="text" value="Gender" size="10" readonly>
            </div>

            <?php
                $stmt = $conn->prepare("SELECT * FROM users WHERE UserStatus='Admin'");
                $stmt->execute();
                $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($admins as $index => $admin) {
                    echo "<div class='user'>";
                    echo "<input type='email' name='admins[$index][email]' value='".htmlspecialchars($admin['Email'])."' size='40' readonly>";
                    echo "<input type='text' name='admins[$index][password]' size='20'>";
                    echo "<input type='text' name='admins[$index][userStatus]' value='".htmlspecialchars($admin['UserStatus'])."' size='10'>";
                    echo "<input type='text' name='admins[$index][gender]' value='".htmlspecialchars($admin['Gender'])."' size='10'>";
                    echo "</div>";
                }
            ?>
            <button type="submit" name="update_admins" class="btn">Update All Admins</button>
        </div>
    </form>

    <!-- User Section -->
    <form method="POST">
        <div class="form_area">
            <div class="title">Users</div>
            <div class="user">
                <input type="email" value="Email" size="40" readonly>
                <input type="text" value="Password" size="20" readonly>
                <input type="text" value="User Status" size="10" readonly>
                <input type="text" value="Gender" size="10" readonly>
            </div>

            <?php
                $stmt = $conn->prepare("SELECT * FROM users WHERE UserStatus='User'");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as $index => $user) {
                    echo "<div class='user'>";
                    echo "<input type='email' name='users[$index][email]' value='".htmlspecialchars($user['Email'])."' size='40' readonly>";
                    echo "<input type='text' name='users[$index][password]' size='20'>";
                    echo "<input type='text' name='users[$index][userStatus]' value='".htmlspecialchars($user['UserStatus'])."' size='10'>";
                    echo "<input type='text' name='users[$index][gender]' value='".htmlspecialchars($user['Gender'])."' size='10'>";
                    echo "</div>";
                }
            ?>
            <button type="submit" name="update_users" class="btn">Update All Users</button>
        </div>
    </form>

    </div>
</body>
</html>
