<?php
require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/session_check.php';

try {
    // session check
    if (!isset($_SESSION['user_id'])) {
        echo "Please log in to view surveys.";
        exit;
    }
    
    $stmt = $conn->prepare("SELECT Gender FROM USERS WHERE UserID = :userID");
    $stmt->bindParam(':userID', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $gender = $user['Gender'];

        // Get surveys that match this gender
        $surveyStmt = $conn->prepare("SELECT * FROM SURVEYS WHERE SurveyGender = :gender");
        $surveyStmt->bindParam(':gender', $gender);
        $surveyStmt->execute();
        $surveys = $surveyStmt->fetchAll();

        echo "<div class='container'>";
        echo "<div class='form_area'>";
        echo "<div class='title'>Available Surveys</div>";
        echo "<div class='survey-list'>";

        if (count($surveys) > 0) {
            foreach ($surveys as $survey) {
                $surveyName = htmlspecialchars($survey['SurveyName']);
                $surveyID = (int)$survey['SurveyID'];

                echo "<div class='survey-card'>";
                echo "<h3 class='survey_title'>$surveyName</h3>";
                echo "<form action='survey_validation.php' method='post'>";
                echo "<input type='hidden' name='csrf_token' value='" . $_SESSION['csrf_token'] . "'>";
                echo "<input type='hidden' name='survey_id' value='$surveyID'>";
                echo "<button type='submit' class='btn'>Take Survey</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No surveys available for your profile.</p>";
        }

        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "User not found.";
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
    <link rel="stylesheet" href="../resources/css/introPages.css"> 
</head>
<body>
<div style="text-align: right; padding: 10px 20px;">
    <form action="logout.php" method="post" style="display: inline;">
        <button type="submit" style="padding: 8px 15px; background-color: #f44336; color: white; border: none; cursor: pointer; border-radius: 5px;">
            Logout
        </button>
    </form>
</div>
    <img src="https://s3-us-west-2.amazonaws.com/scorestream-team-profile-pictures/285522/20181011000648_310_mascot1280Near.png" alt="Logo" class="logo">
</body>
</html>
