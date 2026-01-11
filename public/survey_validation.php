<?php
require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/session_check.php';

try {
    // session check
    if (!isset($_SESSION['user_id'])) {
        echo "<a href=login.php>Please log in.</a>";
    	exit;
    }
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["survey_id"])) {
    $_SESSION["SurveyID"] = $_POST["survey_id"];
}

// Make sure SurveyID is set
if (!isset($_SESSION['SurveyID'])) {
    echo "<p>No survey selected.<p>";
    echo "<a href='index.php'>Back Home</a>";
    exit;
} else {
    header("Location: survey.php");
}
?>
