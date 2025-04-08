<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surveys</title>
</head>
<body>
    <h1>Take a Survey:</h1>
    <div class="survey-list">
        <?php
            $conn = new PDO("mysql:host=localhost;dbname=survey_db", 'root', '');
            $userGender = $conn->prepare("SELECT Gender FROM USERS WHERE UserID = session.userID"); // change so session actually works
            $data = $conn->prepare("SELECT * FROM SURVEYS WHERE SurveyGender = $userGender");
            $data->execute();
            $records = $data->fetchAll();
        
            foreach ($records as $survey) {
                $carId = $survey['car_id'];
                $carImage = htmlspecialchars($survey['image']);
                $carModel = $survey['model'];
                $carName = $survey['year'] . " " . $survey['brand'] . " " . $carModel;
                $carID = $survey['car_id'];
                echo "<div class='survey'>";
                echo "<img src='$carImage' alt='$carModel'>";
                echo "<h3>$carName</h3>";
                echo "<a href='car.php?id=$carID'><h5>See More</h5></a>"; 
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>
