<?php
require_once __DIR__ . '/../backend/db.php';
session_start();

// Debugging step:
// $debug = $pdo->query("SELECT QuestionNumber, Question FROM QUESTIONS ORDER BY QuestionNumber")->fetchAll();
// echo "<pre>"; print_r($debug); echo "</pre>"; exit;

// Get all questions in proper order
$stmt = $pdo->query("SELECT * FROM QUESTIONS ORDER BY QuestionNumber ASC");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_SESSION['email']; // Using email as the user identifier
    $surveyId = $pdo->query("SELECT SurveyID FROM SURVEYS LIMIT 1")->fetchColumn();
    
    // Prepare statement
    $stmt = $pdo->prepare("INSERT INTO RESPONSES (Email, SurveyID, QuestionNumber, Answer) VALUES (?, ?, ?, ?)");
    
    foreach ($questions as $question) {
        $qNum = $question['QuestionNumber'];
        if (isset($_POST["question$qNum"])) {
            $stmt->execute([$email, $surveyId, $qNum, $_POST["question$qNum"]]);
        }
    }
    
    header("Location: thank_you.php"); // Redirect after submission
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../resources/css/survey.css"> 
    
    <title>Questionnaire</title>
    <style>
        .form_group { margin-bottom: 20px; }
        .btn-group { display: flex; gap: 10px; }
        .btn { padding: 8px 15px; cursor: pointer; }
        .selected { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Please select Yes or No for each question. This survey is anonymous. Your honesty is vital for this process to be meaningful. </div>
        <h1>Please answer all questions</h1>
        <form method="POST">
            <?php foreach ($questions as $question): ?>
                <div class="form_group">
                    <p><?php echo $question['QuestionNumber']. '. ' . htmlspecialchars($question['Question']); ?></p>
                    <div class="btn-group">
                        <button type="button" class="btn" onclick="setAnswer(this, <?php echo $question['QuestionNumber']; ?>)">Yes</button>
                        <button type="button" class="btn" onclick="setAnswer(this, <?php echo $question['QuestionNumber']; ?>)">No</button>
                        <input type="hidden" name="question<?php echo $question['QuestionNumber']; ?>" id="input<?php echo $question['QuestionNumber']; ?>">
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit">Submit Survey</button>
        </form>
    </div>

    <script>
        /*
        const questionsPerPage = 10;
        let currentPage = 1;

        function showPage(page) {
            const questions = document.querySelectorAll('.form_group');
            questions.forEach((q, i) => {
                q.style.display = (i >= (page-1)*questionsPerPage && i < page*questionsPerPage) 
                    ? 'block' : 'none';
            });
        }
        */
        function setAnswer(btn, qNum) {
            // Remove selection from both buttons in this group
            const group = btn.parentNode;
            group.querySelectorAll('button').forEach(b => b.classList.remove('selected'));
            
            // Mark clicked button as selected
            btn.classList.add('selected');
            
            // Set the hidden input value
            document.getElementById(`input${qNum}`).value = btn.textContent;
        }
    </script>
</body>
</html>
