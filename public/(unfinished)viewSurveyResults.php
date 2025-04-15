/* Access via viewSurveyResults.php?survey_id=123, possibly save the survey ID as a session variable in the page before it
Takes in a survey ID and displays results as a data stream with percentages
Results table:
CREATE TABLE RESPONSES (
    Email VARCHAR(50),
    SurveyID INT,
    QuestionNumber INT,
    Answer VARCHAR(250),
    PRIMARY KEY (Email, SurveyID, QuestionNumber),
    FOREIGN KEY (Email) REFERENCES USERS(Email) ON DELETE CASCADE,
    FOREIGN KEY (SurveyID) REFERENCES SURVEYS(SurveyID) ON DELETE CASCADE,
    FOREIGN KEY (SurveyID, QuestionNumber) REFERENCES QUESTIONS(SurveyID, QuestionNumber) ON DELETE CASCADE
);
Use JavaScript + AJAX for live updates (e.g., chart.js or simple polling).
*/

<?php
require_once __DIR__ . '/../backend/db.php';

if (!isset($_GET['survey_id'])) {
    die("Survey ID not specified.");
}

$surveyID = (int) $_GET['survey_id'];

// Fetch all questions
$stmt = $conn->prepare("SELECT QuestionNumber, Question FROM QUESTIONS WHERE SurveyID = ?");
$stmt->execute([$surveyID]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($questions as $question) {
    $qNum = $question['QuestionNumber'];
    $qText = htmlspecialchars($question['Question']);

    echo "<h3>Q{$qNum}: {$qText}</h3>";

    // Get count of each answer for this question
    $stmt = $conn->prepare("
        SELECT Answer, COUNT(*) as count 
        FROM RESPONSES 
        WHERE SurveyID = ? AND QuestionNumber = ? 
        GROUP BY Answer
    ");
    $stmt->execute([$surveyID, $qNum]);
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total responses to calculate percentages
    $stmt = $conn->prepare("SELECT COUNT(*) FROM RESPONSES WHERE SurveyID = ? AND QuestionNumber = ?");
    $stmt->execute([$surveyID, $qNum]);
    $total = $stmt->fetchColumn();

    echo "<ul>";
    foreach ($answers as $answer) {
        $percent = $total > 0 ? round(($answer['count'] / $total) * 100, 2) : 0;
        $ans = htmlspecialchars($answer['Answer']);
        echo "<li>{$ans}: {$percent}% ({$answer['count']} votes)</li>";
    }
    echo "</ul>";
}
?>
