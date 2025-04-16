<?php
    session_start();
    require_once __DIR__ . '/../backend/db.php';
    $userID = $_SESSION['user_id'];
    $surveyID = $_SESSION['SurveyID'];
    $reflectedUserID = $_SESSION['reflectedUserID'];


    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $feeling = $_POST['feeling'];
        $concern = $_POST['concern'];
        $answer = "Feeling: " . $feeling . "\nConcern: " . $concern;
        $stmt = $conn->prepare("INSERT INTO REFLECTIONS (UserID, ReflectedUserID, SurveyID, Answer) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userID, $reflectedUserID, $surveyID, $answer]);
    } else {
        echo "<p>Insertion failed.</p>";
    }
    echo "<p>Thanks!</p>";
    echo "<a href='index.php'>Back Home</a>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Surveys</title>
    <link rel="stylesheet" href="../resources/css/introPages.css"> 
</head>
