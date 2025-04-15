<?php
require_once __DIR__ . '/../backend/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["finalize_survey"])) {

    $survey_title = htmlspecialchars($_POST["survey_title"] ?? "Unnamed Survey", ENT_QUOTES, 'UTF-8');
    $survey_gender = htmlspecialchars($_POST["survey_gender"] ?? "Neutral", ENT_QUOTES, 'UTF-8');
    $questions = $_POST["questions"] ?? [];

    try {
        $conn->beginTransaction();

        // Insert survey with title and gender
        $stmt = $conn->prepare("INSERT INTO SURVEYS(SurveyName, SurveyGender) VALUES (?, ?)");
        if ($survey_gender == "Neutral") {
            $genders = ["Female", "Male"];
            foreach ($genders as $gender) {
                $stmt->execute([$survey_title, $gender]);
                $surveyID = $conn->lastInsertId();

                foreach ($questions as $index => $question) {
                    $question_text = htmlspecialchars($question["text"], ENT_QUOTES, 'UTF-8');
                    $question_type = htmlspecialchars($question["type"], ENT_QUOTES, 'UTF-8');

                    $stmtQ = $conn->prepare("INSERT INTO QUESTIONS(SurveyID, QuestionNumber, QuestionType, Question)
                                        VALUES(?, ?, ?, ?)");
                    $stmtQ->execute([$surveyID, $index + 1, $question_type, $question_text]);
                }
            }
        } else {
            $stmt->execute([$survey_title, $survey_gender]);
            $surveyID = $conn->lastInsertId();

            foreach ($questions as $index => $question) {
                $question_text = htmlspecialchars($question["text"], ENT_QUOTES, 'UTF-8');
                $question_type = htmlspecialchars($question["type"], ENT_QUOTES, 'UTF-8');

                $stmtQ = $conn->prepare("INSERT INTO QUESTIONS(SurveyID, QuestionNumber, QuestionType, Question)
                                    VALUES(?, ?, ?, ?)");
                $stmtQ->execute([$surveyID, $index + 1, $question_type, $question_text]);
            }
        }


        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Survey</title>
    <script>
        function addQuestion() {
            let container = document.getElementById("questionsContainer");
            let index = container.children.length;

            let div = document.createElement("div");
            div.className = "question";
            div.innerHTML = `
                <label>Question:</label>
                <input type="text" name="questions[${index}][text]" required>
                <label>Type:</label>
                <select name="questions[${index}][type]">
                    <option value="Yes/No">Yes/No</option>
                    <option value="Short Answer">Short Answer</option>
                </select>
                <button type="button" onclick="removeQuestion(this)">Remove</button>
                <br><br>
            `;
            container.appendChild(div);
        }

        function removeQuestion(button) {
            button.parentElement.remove();
        }

        // Add one default question on page load
        window.onload = function () {
            addQuestion();
        };
    </script>
</head>
<body>
    <h2>Create a Survey</h2>
    <form method="post">
        <label>Survey Title:</label>
        <input type="text" name="survey_title" required>
        <br><br>

        <label>Survey Gender:</label>
        <select name="survey_gender" required>
            <option value="">-- Select Gender Context --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Neutral">Neutral</option>
        </select>
        <br><br>

        <div id="questionsContainer"></div>
        <button type="button" onclick="addQuestion()">Add Question</button>
        <br><br>
        <button type="submit" name="finalize_survey">Create Survey</button>
    </form>
</body>
</html>
