<?php
//start session
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
    <a href="adminIndex.php"><img src="logo.png" alt="Logo" class="logo"></a>
    <div class="header">
        <a href="logout.php" class="logout"><button type="button" class="btn">Logout</button></a>
    </div>
    <div class="container">
        <?php
            $stmt = $conn->prepare("SELECT * FROM surveys");
            $stmt->execute();
            $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($surveys as $survey) {
                echo "<div class='survey'>";
                echo "<h3>$survey[SurveyName]</h3>";
                echo "<a href='updateSurvey.php?SurveyID=$survey[SurveyID]'><button type='button' class='btn'>Update</button></a>";
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>
