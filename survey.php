<?php
//Could use a foreach($questions as $question) loop to generate the questions from DB as such:
    /*
        <?php foreach ($questions as $question): ?>
                    <div class="form_group">
                        <label for="question<?php echo $question['id']; ?>"><?php echo htmlspecialchars($question['question_text']); ?>:</label>
                        <div class="btn-group">
                            <button type="submit" class="btn" name="question<?php echo $question['id']; ?>" value="Yes">Yes</button>
                            <button type="submit" class="btn" name="question<?php echo $question[f'id']; ?>" value="No">No</button>
                        </div>
                    </div>
        <?php endforeach; ?>
    */



//include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $question1 = $_POST['question1'];
    $question2 = $_POST['question2'];
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session after login/register

    $stmt = $pdo->prepare("INSERT INTO answers (user_id, question1, question2) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $question1, $question2]);

    echo "Your answers have been submitted.";
}
?>

<!DOCTYPE html>
<html lang="en">
<script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.btn-group .btn');
            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const group = this.closest('.btn-group');
                    group.querySelectorAll('.btn').forEach(btn => btn.classList.remove('selected'));
                    this.classList.add('selected');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = this.name;
                    input.value = this.value;
                    group.appendChild(input);
                });
            });
        });
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="survey.css">
    <title>Questionnaire</title>
</head>
<body>
    <div class="container">
        <div class="form_area">
            <div class="title">Please circle Y or N for each question. This survey is anonymous. Your honesty is vital for this process to be meaningful. </div>
            <form method="POST">
                <div class="form_group">
                    <label for="question1">Have you ever compared yourself to another person?</label>
                    <div class="btn-group">
                        <button type="button" class="btn" name="question1" value="Answer1">Yes</button>
                        <button type="button" class="btn" name="question1" value="Answer2">No</button>
                    </div>
                </div>
                <div class="form_group">
                    <label for="question2">Have you ever not gone to an event, meal, or activity because you had no one to go with?</label>
                    <div class="btn-group">
                        <button type="button" class="btn" name="question2" value="Answer1">Yes</button>
                        <button type="button" class="btn" name="question2" value="Answer2">No</button>
                    </div>
                </div>
                <div class="form_group">
                    <label for="question1">Have you ever intentionally skipped meals or starved yourself?</label>
                    <div class="btn-group">
                        <button type="button" class="btn" name="question3" value="Answer1">Yes</button>
                        <button type="button" class="btn" name="question3" value="Answer2">No</button>
                    </div>
                </div>
                <div class="form_group">
                    <label for="question2">Have you ever seen a professional counselor?</label>
                    <div class="btn-group">
                        <button type="button" class="btn" name="question4" value="Answer1">Yes</button>
                        <button type="button" class="btn" name="question4" value="Answer2">No</button>
                    </div>
                </div>
                <div class="form_group">
                    <label for="question1">Have you ever been physically and/or sexually abused?</label>
                    <div class="btn-group">
                        <button type="button" class="btn" name="question1" value="Answer5">Yes</button>
                        <button type="button" class="btn" name="question1" value="Answer5">No</button>
                    </div>
                </div>
                <div class="form_group">
                    <label for="question2">Have you ever been verbally and/or emotionally abused?</label>
                    <div class="btn-group">
                        <button type="button" class="btn" name="question2" value="Answer6">Yes</button>
                        <button type="button" class="btn" name="question2" value="Answer6">No</button>
                    </div>
                </div>
                <button class="submit_btn">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
