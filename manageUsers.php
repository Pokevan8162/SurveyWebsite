// This is for the admin to manage the users on the website
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
</head>
<body>
    <?php
        // Database connection
        $conn = new PDO("mysql:host=localhost;dbname=survey_db", 'root', '');

        // Fetch all admins from the Database
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserStatus='admin'");
        $stmt->execute();
        $admins = $stmt->fetch();

        // Display admins and functionality
            // echo image
            // echo header
        foreach ($admins as $admin) {
            $Email = $admin['email'];
            $Password = $admin['password'];
            $Salt = $admin['salt'];
            $UserStatus = $admin['UserStatus'];
            $Gender = $admin['Gender'];

            echo "<div><h3>Year: $carYear</h3><h3>Brand: $carBrand</h3><h3>Model: $carModel</h3><h3>Location: $carLocation</h3></div>"; //Example echo line
        }

        // Fetch all users from the Database
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserStatus='user'");
        $stmt->execute();
        $users = $stmt->fetch();

        // Display all users and functionality
    ?>
</body>
</html>
