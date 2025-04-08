<!-- This is for the admin to manage the users on the website -->
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../resources/css/introPages.css">
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
        echo '<img src="https://s3-us-west-2.amazonaws.com/scorestream-team-profile-pictures/285522/20181011000648_310_mascot1280Near.png" alt="Logo" class="logo">';
        echo '<a href="logout.php"><button type="button" class="btn">Logout</button></a>';
        echo '<div class="container">';
	        echo '<div class="form_area">';
                echo '<div class="title">Administrative Users</div>';
                foreach ($admins as $admin) {
                    $Email = $admin['email'];
                    $Password = $admin['password'];
                    $Salt = $admin['salt'];
                    $UserStatus = $admin['UserStatus'];
                    $Gender = $admin['Gender'];

                    // Display the functionality for each user, display email, password, userstatus, and gender is editable texts with an update button in a row
                    echo '<div class="user">';
                    echo '<input type="email" id="email" value="$Email" size="25">';
                    echo '<input type="text" id="password" value="$Password" size="25">';
                    echo '<input type="text" id="userStatus" value="$UserStatus" size="25">';
                    echo '<input type="text" id="gender" value="$Gender" size="25">';
                    echo '</div>';
                        
                    //echo "<div><h3>Year: $carYear</h3><h3>Brand: $carBrand</h3><h3>Model: $carModel</h3><h3>Location: $carLocation</h3></div>"; //Example echo line
                    //echo '<h2>' . htmlspecialchars($product['name']) . '</h2>';

                }
            echo '</div>';
	    echo '</div>';
        
        // Fetch all users from the Database
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserStatus='user'");
        $stmt->execute();
        $users = $stmt->fetch();

        // Display all users and functionality
        echo '<div class="container">';
            echo '<div class="form_area">';
                echo '<div class="title">Users</div>';
                foreach ($users as $user) {
                    $Email = $user['email'];
                    $Password = $user['password'];
                    $Salt = $user['salt'];
                    $UserStatus = $user['UserStatus'];
                    $Gender = $user['Gender'];

                    // Display the functionality for each user, display email, password, userstatus, and gender is editable texts with an update button in a row
                    echo '<div class="user">';
                    echo '<input type="email" id="email" value="$Email" size="25">';
                    echo '<input type="text" id="password" value="$Password" size="25">';
                    echo '<input type="text" id="userStatus" value="$UserStatus" size="25">';
                    echo '<input type="text" id="gender" value="$Gender" size="25">';
                    echo '</div>';
                
                    //echo "<div><h3>Year: $carYear</h3><h3>Brand: $carBrand</h3><h3>Model: $carModel</h3><h3>Location: $carLocation</h3></div>"; //Example echo line
		    //echo '<h2>' . htmlspecialchars($product['name']) . '</h2>';
            	}
	        echo '</div>';
	    echo '</div>';
    ?>
</body>
</html>
