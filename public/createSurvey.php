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

    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["finalize_survey"])) {

        $survey_title = htmlspecialchars($_POST["survey_title"] ?? "Unnamed Survey", ENT_QUOTES, 'UTF-8');
        $questions = $_POST["questions"] ?? [];
        if (count($questions) == 0) {
            $errorMessage = "Cannot submit an empty survey!";
        } else {

        try {
        // Insert survey with title and gender
        $stmt = $conn->prepare("INSERT INTO SURVEYS(SurveyName) VALUES (?)");
        $stmt->execute([$survey_title]);
        $surveyID = $conn->lastInsertId();

        foreach ($questions as $index => $question) {
            $question_text = htmlspecialchars($question["text"], ENT_QUOTES, 'UTF-8');
            $question_type = htmlspecialchars($question["type"], ENT_QUOTES, 'UTF-8');
            $question_gender = htmlspecialchars($question["gender"], ENT_QUOTES, "UTF-8");

            $stmtQ = $conn->prepare("INSERT INTO QUESTIONS(SurveyID, QuestionNumber, QuestionType, Question, QuestionGender) VALUES(?, ?, ?, ?, ?)");
            $stmtQ->execute([$surveyID, $index + 1, $question_type, $question_text, $question_gender]);
        }
        $surveyID = $conn->lastInsertId();
        $successMessage = "Survey submitted successfully.";
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Survey</title>
    <style>
        .message {
            font-size: 18px;
            color: #F44336;
            margin-bottom: 20px;
        }
    </style>
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
    <?php if (!empty($errorMessage)): ?>
        <div class="message"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
    <?php if (!empty($successMessage)): ?>
        <div class="message"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    </div>
</body>
</html>
