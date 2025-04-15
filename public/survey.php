<?php
require_once __DIR__ . '/../backend/db.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to take surveys.";
    exit;
}

$userID = $_SESSION['user_id'];
$surveyID = $_GET['id'] ?? null;

if (!$surveyID) {
    echo "No survey selected.";
    exit;
}

// Fetch only questions for this specific survey by ID
$stmt = $pdo->prepare("SELECT * FROM QUESTIONS WHERE SurveyID = ? ORDER BY QuestionNumber ASC");
$stmt->execute([$surveyID]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("INSERT INTO RESPONSES (UserID, SurveyID, QuestionNumber, Answer) VALUES (?, ?, ?, ?)");

    foreach ($questions as $question) {
        $qNum = $question['QuestionNumber'];
        if (isset($_POST["question$qNum"])) {
            $stmt->execute([$userID, $surveyID, $qNum, $_POST["question$qNum"]]);
        }
    }

    header("Location: thank_you.php");
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
        function setAnswer(btn, qNum) {
            const group = btn.parentNode;
            group.querySelectorAll('button').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById(`input${qNum}`).value = btn.textContent;
        }
    </script>
</body>
</html>
