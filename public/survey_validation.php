<?php
require_once __DIR__ . '/../backend/db.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>You must be logged in to take surveys.</p>";
    echo "<a href='index.php'>Back Home</a>";
    exit;
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
