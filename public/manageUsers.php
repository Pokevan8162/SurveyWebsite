<!-- This is for the admin to manage the users on the website -->
<?php
session_start();

try {
    $conn = new PDO("mysql:host=localhost;dbname=survey_db", 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // session check
    if (!isset($_SESSION['user_id'])) {
        echo "<a href=login.php>Please log in to view surveys.</a>";
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
        <!-- Administrators -->
         <h1>Manage Users</h1>
        <div class="form_area">
            <div class="title">Administrative Users</div>
            <?php
                // Database connection
                $conn = new PDO("mysql:host=localhost;dbname=survey_db", 'root', '');

                // Fetch all admins from the Database
                $stmt = $conn->prepare("SELECT * FROM users WHERE UserStatus='admin'");
                $stmt->execute();
                $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Display admins and functionality
                echo "<div class='user'>";
                echo "<input type='email' id='email' value='Email' size='40' readonly>";
                echo "<input type='text' id='password' value='Password' size='20' readonly>";
                echo "<input type='text' id='userStatus' value='User Status' size='10' readonly>";
                echo "<input type='text' id='gender' value='Gender' size='10' readonly>";
                echo "</div>";
                foreach ($admins as $admin) {
                    $Email = htmlspecialchars($admin['Email']);
                    $Password = htmlspecialchars($admin['Password']);
                    $Salt = htmlspecialchars($admin['Salt']);
                    $UserStatus = htmlspecialchars($admin['UserStatus']);
                    $Gender = htmlspecialchars($admin['Gender']);

                    echo "<div class='user'>";
                    echo "<input type='email' id='email' value='$Email' size='40'>";
                    echo "<input type='text' id='password' value='$Password' size='20'>";
                    echo "<input type='text' id='userStatus' value='$UserStatus' size='10'>";
                    echo "<input type='text' id='gender' value='$Gender' size='10'>";
                    echo "</div>";
                }
            ?>
        </div>
    
        <!-- Users -->
        <div class="form_area">
            <div class="title">Users</div>
            <?php
                // Fetch all users from the Database
                $stmt = $conn->prepare("SELECT * FROM users WHERE UserStatus='user'");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<div class='user'>";
                echo "<input type='email' id='email' value='Email' size='40'>";
                echo "<input type='text' id='password' value='Password' size='20'>";
                echo "<input type='text' id='userStatus' value='User Status' size='10'>";
                echo "<input type='text' id='gender' value='Gender' size='10'>";
                echo "</div>";

                foreach ($users as $user) {
                    $Email = htmlspecialchars($user['Email']);
                    $Password = htmlspecialchars($user['Password']);
                    $Salt = htmlspecialchars($user['Salt']);
                    $UserStatus = htmlspecialchars($user['UserStatus']);
                    $Gender = htmlspecialchars($user['Gender']);

                    // Display the functionality for each user, display email, password, userstatus, and gender is editable texts with an update button in a row
                    echo "<div class='user'>";
                    echo "<input type='email' id='email' value='$Email' size='40'>";
                    echo "<input type='text' id='password' value='$Password' size='20'>";
                    echo "<input type='text' id='userStatus' value='$UserStatus' size='10'>";
                    echo "<input type='text' id='gender' value='$Gender' size='10'>";
                    echo "</div>";
            	}
            ?>
	    </div>
        <a href="logout.php" class="logout"><button type="button" class="btn">Logout</button></a>
    </div>
</body>
</html>
