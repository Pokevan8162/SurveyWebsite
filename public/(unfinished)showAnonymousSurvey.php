<?php
session_start();
require_once __DIR__ . '/../backend/db.php';

// Get current user's email and last completed SurveyID
$userEmail = $_SESSION['email'];
$surveyID = $_SESSION['last_survey_id']; // Make sure this was saved after survey submission

// Get a random response from a different user for the same SurveyID
$stmt = $conn->prepare("
    SELECT r.Email, q.QuestionNumber, q.Question, r.Answer
    FROM RESPONSES r
    JOIN QUESTIONS q ON r.SurveyID = q.SurveyID AND r.QuestionNumber = q.QuestionNumber
    WHERE r.SurveyID = ? AND r.Email != ?
    ORDER BY RAND()
    LIMIT 5
");
$stmt->bind_param("is", $surveyID, $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h2>Reflect on This Anonymous Survey:</h2>";
    $displayedEmail = null;

    while ($row = $result->fetch_assoc()) {
        // Save the other user's email in session for logging this interaction if needed
        if (!$displayedEmail) {
            $displayedEmail = $row['Email'];
            $_SESSION['reflection_target_email'] = $displayedEmail;
        }
        echo "<strong>Q{$row['QuestionNumber']}:</strong> {$row['Question']}<br>";
        echo "<em>Answer:</em> {$row['Answer']}<br><br>";
    }

    // Reflection form
    echo <<<HTML
    <form action="submit_reflection.php" method="post">
        <input type="hidden" name="survey_id" value="{$surveyID}">
        <label>How did this person's survey make you feel?</label><br>
        <textarea name="feeling" rows="4" cols="50" required></textarea><br><br>

        <label>Is there anything concerning about this person's survey?</label><br>
        <textarea name="concern" rows="4" cols="50" required></textarea><br><br>

        <button type="submit">Submit Reflection</button>
    </form>
HTML;
} else {
    echo "No other responses available to reflect on for this survey.";
}
?>
