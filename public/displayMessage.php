<?php
    require_once __DIR__ . '/../backend/db.php';
    require_once __DIR__ . '/../backend/session_check.php';

    // Define a function to display messages
    function displayMessage() {
        echo "<div class='message'>{$_SESSION['message']}</div>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message</title>
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
        <?php
            // Display the message passed from the POST request
            displayMessage(); // Use the displayMessage function
        ?>
        <a href="index.php" class="back-link">Back Home</a>
    </div>
</body>
</html>
