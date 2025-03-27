<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["finalize_survey"])) {

    $survey_title = htmlspecialchars($_POST["survey_title"] ?? "Unnamed Survey", ENT_QUOTES, 'UTF-8');
    $questions = $_POST["questions"] ?? [];

    try {
        $pdo->beginTransaction(); // Begin a transaction for atomicity (either want it to fully complete or do nothing at all)

        $stmt = $pdo->prepare("INSERT INTO SURVEYS(SurveyName) VALUES (?)");
        $stmt->execute([$survey_title]);

        // Get the survey ID of the survey that we just posted
        $surveyID = $pdo->lastInsertId();

        foreach ($questions as $index => $question) {
            $question_text = htmlspecialchars($question["text"], ENT_QUOTES, 'UTF-8');
            $question_type = htmlspecialchars($question["type"], ENT_QUOTES, 'UTF-8');

            $stmt = $pdo->prepare("INSERT INTO QUESTIONS(SurveyID, SurveyName, QuestionNumber, QuestionType, Question)
                               VALUES(?, ?, ?, ?, ?)");
            $stmt->execute([$surveyID, $survey_title, $index + 1, $question_type, $question_text]);
        }

        $pdo->commit(); // Finish transaction
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }

    
    // --------------- For creating a survey file if we ever want to do that

    // if (!empty($questions)) {
    //     $filename = "survey.txt";
    //     $file = fopen($filename, "w");
    //     fwrite($file, "Survey Title: " . $survey_title . "\n\n");

    //     foreach ($questions as $index => $question) {
    //         fwrite($file, "Q" . ($index + 1) . ": " . $question["text"] . " (" . $question["type"] . ")\n");
    //     }

    //     fclose($file);
    //     echo "<p>Survey saved successfully! Check <a href='$filename' target='_blank'>$filename</a>.</p>";
    // } else {
    //     echo "<p>No questions were added.</p>";
    // }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            `;
            container.appendChild(div);
        }

        function removeQuestion(button) {
            button.parentElement.remove();
        }
    </script>
</head>
<body>
    <h2>Create a Survey</h2>
    <form method="post">
        <label>Survey Title:</label>
        <input type="text" name="survey_title" required>
        <br><br>
        <div id="questionsContainer"></div>
        <button type="button" onclick="addQuestion()">Add Question</button>
        <br><br>
        <button type="submit" name="finalize_survey">Create Survey</button>
    </form>
</body>
</html>
