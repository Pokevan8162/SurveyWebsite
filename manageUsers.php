// This is for the admin to manage the users on the website
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="introPages.css">
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
        echo "<img src="https://s3-us-west-2.amazonaws.com/scorestream-team-profile-pictures/285522/20181011000648_310_mascot1280Near.png" alt="Logo" class="logo">";
        <a href="logout.php"><button type="button" class="btn">Logout</button></a>
        <div class="container">
		    <div class="form_area">
		    <div class="title">Administrative Users</div>
			foreach ($admins as $admin) {
                $Email = $admin['email'];
                $Password = $admin['password'];
                $Salt = $admin['salt'];
                $UserStatus = $admin['UserStatus'];
                $Gender = $admin['Gender'];

                // Display the functionality for each user
                echo "<div class="user">"
                echo "</div>"
                
                echo "<div><h3>Year: $carYear</h3><h3>Brand: $carBrand</h3><h3>Model: $carModel</h3><h3>Location: $carLocation</h3></div>"; //Example echo line
            }
		    </div>
	    </div>
        

        // Fetch all users from the Database
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserStatus='user'");
        $stmt->execute();
        $users = $stmt->fetch();

        // Display all users and functionality
        
    ?>
</body>
</html>
