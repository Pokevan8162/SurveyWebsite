<?php
session_start();
require_once __DIR__ . '/../backend/db.php';

try {
    // session check
    if (!isset($_SESSION['user_id'])) {
        echo "Please log in to view surveys.";
        exit;
    }
    
    $stmt = $conn->prepare("SELECT Gender FROM USERS WHERE UserID = :userID");
    $stmt->bindParam(':userID', $_SESSION['user_id']); // use same session key
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $gender = $user['Gender'];

        // Get surveys that match this gender
        $surveyStmt = $conn->prepare("SELECT * FROM SURVEYS WHERE SurveyGender = :gender");
        $surveyStmt->bindParam(':gender', $gender);
        $surveyStmt->execute();
        $surveys = $surveyStmt->fetchAll();

        echo "<h1>Available Surveys for You</h1>";
        echo "<div class='survey-list'>";

        if (count($surveys) > 0) {
            foreach ($surveys as $survey) {
                $surveyName = htmlspecialchars($survey['SurveyName']);
                $surveyID = (int)$survey['SurveyID'];

                echo "<div class='survey'>";
                echo "<h3>$surveyName</h3>";
                echo "<a href='survey.php?id=$surveyID'>Take Survey</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No surveys available for your profile.</p>";
        }

        echo "</div>";
    } else {
        echo "User not found.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
