<?php
require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/session_check.php';

// Get current userID and last completed SurveyID
$userID = $_SESSION['user_id'];
$surveyID = $_SESSION['SurveyID'];

$stmt = $conn->prepare("SELECT UserID FROM Responses WHERE SurveyID = ? AND UserID != ?");
$stmt->execute([$surveyID, $userID]);
$userIDs = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch a column of userIDs

if (count($userIDs) > 0) {
    $randomIndex = rand(1, count($userIDs) - 1);
    $randomID = $userIDs[$randomIndex];

    // Get a random response from a different user for the same SurveyID
    $stmt = $conn->prepare("
        SELECT * FROM Responses r
        JOIN QUESTIONS q 
        ON r.SurveyID = q.SurveyID 
        AND r.QuestionNumber = q.QuestionNumber
        WHERE r.SurveyID = :surveyID AND r.UserID = :userID
    ");

    // Bind parameters
    $stmt->bindParam(':surveyID', $surveyID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $randomID, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the random response
    $response = $stmt->fetchAll();

    if (count($response) > 0) {
        $_SESSION['reflectedUserID'] = $randomID;

        echo "<div class='container'>";
        echo "<h2>Reflect on This Anonymous Survey:</h2>";
        foreach ($response as $row) {
            echo "<div class='question-block'>";
            echo "<strong>Q{$row['QuestionNumber']}:</strong> {$row['Question']}<br>";
            echo "<em>Answer:</em> {$row['Answer']}<br>";
            echo "</div>";
        }

        // Reflection form
        // Reflection form
        ?>
        <form action="thankyou.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="survey_id" value="<?php echo $surveyID; ?>">
            <label>How did this person's survey make you feel?</label><br>
            <textarea name="feeling" rows="4" cols="50" required></textarea><br><br>

            <label>Is there anything concerning about this person's survey?</label><br>
            <textarea name="concern" rows="4" cols="50" required></textarea><br><br>

            <button type="submit">Submit Reflection</button>
        </form>
        </div>
        <?php
    } else {
        $_SESSION['message'] = "Response empty.";
        header('Location: displayMessage.php');
    }
} else {
    $_SESSION['message'] = "No other responses available to reflect on for this survey.";
    header('Location: displayMessage.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review an Anonymous Survey</title>
    <link rel="stylesheet" href="../resources/css/anonymousSurvey.css"> 
</head>
