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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["finalize_survey"])) {

    $survey_title = htmlspecialchars($_POST["survey_title"] ?? "Unnamed Survey", ENT_QUOTES, 'UTF-8');
    $questions = $_POST["questions"] ?? [];
    $oldSurveyID = $_GET['SurveyID'];

    try {
        // Delete all questions from questions and the survey from surveys before inserting it all
        $stmt = $conn->prepare("DELETE FROM surveys WHERE SurveyID = $oldSurveyID");
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM questions WHERE SurveyID = $oldSurveyID");
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM responses WHERE SurveyID = $oldSurveyID");
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM reflections WHERE SurveyID = $oldSurveyID");
        $stmt->execute();

        // Insert survey with title and gender
        $stmt = $conn->prepare("INSERT INTO surveys(SurveyName) VALUES (?)");
        $stmt->execute([$survey_title]);
        $surveyID = $conn->lastInsertId();

        foreach ($questions as $index => $question) {
            $question_text = htmlspecialchars($question["text"], ENT_QUOTES, 'UTF-8');
            $question_type = htmlspecialchars($question["type"], ENT_QUOTES, 'UTF-8');
            $question_gender = htmlspecialchars($question["gender"], ENT_QUOTES, 'UTF-8');
            $stmtQ = $conn->prepare("INSERT INTO questions(SurveyID, QuestionNumber, QuestionType, Question, QuestionGender) VALUES(?, ?, ?, ?, ?)");
            $stmtQ->execute([$surveyID, $index + 1, $question_type, $question_text, $question_gender]);
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_survey"])) {
    $oldSurveyID = $_GET['SurveyID'];
    $stmt = $conn->prepare("DELETE FROM surveys WHERE SurveyID = $oldSurveyID");
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM questions WHERE SurveyID = $oldSurveyID");
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM responses WHERE SurveyID = $oldSurveyID");
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM reflections WHERE SurveyID = $oldSurveyID");
        $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Survey</title>
    <link rel="stylesheet" href="../resources/css/createSurvey.css">
    <script>
        function addQuestion() {
            let container = document.getElementById("questionsContainer");
            let index = container.children.length;

            let div = document.createElement("div");
            div.className = "question";
            div.innerHTML = `
                <label>Question:</label>
                <input type="text" name="questions[${index}][text]" size="70" required>
                <label>Type:</label>
                <select name="questions[${index}][type]">
                    <option value="Yes/No">Yes/No</option>
                    <option value="Short Answer">Short Answer</option>
                </select>
                <label>Gender:</label>
                <select name="questions[${index}][gender]">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Neutral" selected>Neutral</option>
                </select>
                <button type="button" onclick="removeQuestion(this)">Remove</button>
                <br><br>
            `;
            container.appendChild(div);
        }

        function removeQuestion(button) {
            button.parentElement.remove();
        }
    </script>
</head>
<body>
    <a href="adminIndex.php"><img src="../resources/images/logo.png" alt="Logo" class="logo"></a>
    <div class="header">
        <a href="logout.php" class="logout"><button type="button" class="btn">Logout</button></a>
    </div>
    <div class="container">
    <h2>Update Survey</h2>
    <form method="post">
        <label>Survey Title:</label>
        <input type="text" name="survey_title" required>
        <br><br>

        <div id="questionsContainer">
        <?php
            $surveyID = $_GET['SurveyID'];
            $index = 1;
            $stmt = $conn->prepare("SELECT * FROM questions WHERE SurveyID = $surveyID");
            $stmt->execute();
            $questions = $stmt->fetchAll();
            foreach ($questions as $q) {
                echo "
                <div class='question'>
                    <label>Question:</label>
                    <input type='text'
                        name='questions[$index][text]'
                        value=\"" . htmlspecialchars($q['Question'], ENT_QUOTES) . "\"
                        size='70' required>
                    <label>Type:</label>
                    <select name='questions[$index][type]'>
                        <option value='Yes/No' " . ($q['QuestionType'] === 'Yes/No' ? 'selected' : '') . ">Yes/No</option>
                        <option value='Short Answer' " . ($q['QuestionType'] === 'Short Answer' ? 'selected' : '') . ">Short Answer</option>
                    </select>
                    <label>Gender:</label>
                    <select name='questions[$index][gender]'>
                        <option value='Male' " . ($q['QuestionGender'] === 'Male' ? 'selected' : '') . ">Male</option>
                        <option value='Female' " . ($q['QuestionGender'] === 'Female' ? 'selected' : '') . ">Female</option>
                        <option value='Neutral' " . ($q['QuestionGender'] === 'Neutral' ? 'selected' : '') . ">Neutral</option>
                    </select>
                    <button type='button' onclick='removeQuestion(this)'>Remove</button>
                </div>";
                $index++;
            }
        ?>
        </div>

        <button type="button" onclick="addQuestion()">Add Question</button>
        <button type="submit" name="finalize_survey">Update Survey</button>
        <button type="submit" name="delete_survey">Delete Survey</button>
    </form>
    </div>
</body>
</html>
