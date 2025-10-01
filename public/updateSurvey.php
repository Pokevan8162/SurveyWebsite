<?php
//session start
session_start();
require_once __DIR__ . '/../backend/db.php';
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
    $survey_gender = htmlspecialchars($_POST["survey_gender"] ?? "Neutral", ENT_QUOTES, 'UTF-8');
    $questions = $_POST["questions"] ?? [];
    $oldSurveyID = $_GET['SurveyID'];

    try {
        // Delete all questions from questions and the survey from surveys before inserting it all
        $stmt = $conn->prepare("DELETE FROM surveys WHERE SurveyID = $oldSurveyID");
        $stmt->execute();
        $stmt = $conn->prepare("DELETE FROM questions WHERE SurveyID = $oldSurveyID");
        $stmt->execute();

        // Insert survey with title and gender
        $stmt = $conn->prepare("INSERT INTO surveys(SurveyName, SurveyGender) VALUES (?, ?)");
        if ($survey_gender == "Neutral") {
            $genders = ["Female", "Male"];
            foreach ($genders as $gender) {
                $stmt->execute([$survey_title, $gender]);
                $surveyID = $conn->lastInsertId();

                foreach ($questions as $index => $question) {
                    $question_text = htmlspecialchars($question["text"], ENT_QUOTES, 'UTF-8');
                    $question_type = htmlspecialchars($question["type"], ENT_QUOTES, 'UTF-8');
                    $stmtQ = $conn->prepare("INSERT INTO questions(SurveyID, QuestionNumber, QuestionType, Question) VALUES(?, ?, ?, ?)");
                    $stmtQ->execute([$surveyID, $index + 1, $question_type, $question_text]);
                }
            }
        } else {
            $stmt->execute([$survey_title, $survey_gender]);
            $surveyID = $conn->lastInsertId();

            foreach ($questions as $index => $question) {
                $question_text = htmlspecialchars($question["text"], ENT_QUOTES, 'UTF-8');
                $question_type = htmlspecialchars($question["type"], ENT_QUOTES, 'UTF-8');

                $stmtQ = $conn->prepare("INSERT INTO questions(SurveyID, QuestionNumber, QuestionType, Question) VALUES(?, ?, ?, ?)");
                $stmtQ->execute([$surveyID, $index + 1, $question_type, $question_text]);
            }
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Survey</title>
    <link rel="stylesheet" href="usersCSS.css">
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
    </script>
</head>
<body>
    <a href="adminIndex.php"><img src="logo.png" alt="Logo" class="logo"></a>
    <div class="header">
        <a href="logout.php" class="logout"><button type="button" class="btn">Logout</button></a>
    </div>
    <div class="container">
    <h2>Update Survey</h2>
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

        <?php
            $surveyID = $_GET['SurveyID'];
            $index = 1;
            $stmt = $conn->prepare("SELECT * FROM questions WHERE SurveyID = $surveyID");
            $stmt->execute();
            $questions = $stmt->fetchAll();
            foreach ($questions as $q) {
                echo "<div id='questionsContainer'>";
                echo "<label>Question:</label>";
                echo "<input type='text' name='questions[$index][text]' value='$q[Question]' size='70'>"; 
                echo "<label>Type:</label>";
                echo "<select name='questions[$index][type]' text='$q[QuestionType]'>";
                    echo "<option value='Yes/No'>Yes/No</option>";
                    echo "<option value='Short Answer'>Short Answer</option>";
                echo "</select>";
                echo "<button type='button' onclick='removeQuestion(this)'>Remove</button>";
                echo "<br><br>";
                echo "</div>";
                $index++;
            }
        ?>
        <button type="button" onclick="addQuestion()">Add Question</button>
        <br><br>
        <button type="submit" name="finalize_survey">Update Survey</button>
    </form>
    </div>
</body>
</html>
