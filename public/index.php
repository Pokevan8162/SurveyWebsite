<?php
require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/session_check.php';

try {
    // session check
    if (!isset($_SESSION['user_id'])) {
        echo "Please log in to view surveys.";
        exit;
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Surveys</title>
    <link rel="stylesheet" href="../resources/css/createSurvey.css"> 
</head>
<body>
    <!-- <a href="index.php"><img src="../images/logo.png" alt="Logo" class="logo"></a> -->
    <div class="header">
        <a href="logout.php" class="logout"><button type="button" class="btn">Logout</button></a>
    </div>
    <div class='container'>
    <div class='form_area'>
    <h2>Available Surveys</h2>
        <?php
            $surveyStmt = $conn->prepare("SELECT * FROM SURVEYS");
            $surveyStmt->execute();
            $surveys = $surveyStmt->fetchAll();

            if (count($surveys) > 0) {
                foreach ($surveys as $survey) {
                    $surveyName = htmlspecialchars($survey['SurveyName']);
                    $surveyID = (int)$survey['SurveyID'];

                    echo "<div class='question'>
                        <h3 class='survey_title'>$surveyName</h3>
                        <form action='survey_validation.php' method='post'>
                        <input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>
                        <input type='hidden' name='survey_id' value='$surveyID'>
                        <button type='submit' class='btn'>Take Survey</button>
                        </form></div>";
                }
            } else {
                echo "<p>No surveys available for your profile.</p>";
            }
        ?>
    </div></div>
</body>
</html>
