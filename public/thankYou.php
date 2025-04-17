<?php
    require_once __DIR__ . '/../backend/db.php';
    require_once __DIR__ . '/../backend/session_check.php';
    $userID = $_SESSION['user_id'];
    $surveyID = $_SESSION['SurveyID'];
    $reflectedUserID = $_SESSION['reflectedUserID'];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $feeling = $_POST['feeling'];
        $concern = $_POST['concern'];
        $answer = "Feeling: " . $feeling . "\nConcern: " . $concern;
        $stmt = $conn->prepare("INSERT INTO REFLECTIONS (UserID, ReflectedUserID, SurveyID, Answer) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userID, $reflectedUserID, $surveyID, $answer]);
    } else {
        echo "<p>Insertion failed.</p>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank you for taking a survey!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .message {
            font-size: 18px;
            color: #F44336; /* Red color */
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            font-size: 16px;
            color: #FF5722; /* Darker red color for link */
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        button {
            background-color: #F44336; /* Red button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #E53935; /* Darker red when hovering */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <p>Thanks for your reflection!</p>
        </div>
        <a href="index.php" class="back-link">Back Home</a>
    </div>
</body>
</html>
