<?php
    //session start
    session_start();
    require_once __DIR__ . '/../backend/db.php';
    try {
        // session check
        if (!isset($_SESSION['user_id'])) {
            echo "<a href=login.php>Please log in.</a>";
            exit;
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../resources/css/usersCSS.css">
</head>
<body>
    <a href="adminIndex.php"><img src="logo.png" alt="Logo" class="logo"></a>
    <div class="header">
        <a href="logout.php" class="logout"><button type="button" class="btn">Logout</button></a>
    </div>
    <div class="container">
    <?php
        echo "<div class='user'>";
        echo "<input type='text' value='number' size='10' readonly>";
        echo "<input type='text' value='Yes' size='10' readonly>";
        echo "<input type='text' value='No' size='10' readonly>";
        echo "</div>";

        $surveyID = $_GET['SurveyID'];
        $stmt = $conn->prepare("SELECT DISTINCT QuestionNumber FROM responses WHERE SurveyID = :surveyID");
        $stmt->execute([':surveyID' => $surveyID]);
        $numbers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($numbers as $row) {
            $qnum = $row['QuestionNumber'];
        
            // Count YES responses
            $stmtY = $conn->prepare("SELECT COUNT(*) as YesCount FROM responses WHERE SurveyID = :surveyID AND QuestionNumber = :qnum AND Answer = 'Yes'");
            $stmtY->execute([':surveyID' => $surveyID, ':qnum' => $qnum]);
            $ycount = $stmtY->fetch(PDO::FETCH_ASSOC)['YesCount'];
        
            // Count NO responses
            $stmtN = $conn->prepare("SELECT COUNT(*) as NCount FROM responses WHERE SurveyID = :surveyID AND QuestionNumber = :qnum AND Answer = 'No'");
            $stmtN->execute([':surveyID' => $surveyID, ':qnum' => $qnum]);
            $ncount = $stmtN->fetch(PDO::FETCH_ASSOC)['NCount'];
        
            // Output the results
            echo "<div class='user'>";
            echo "<input type='text' name='qnum[]' value='$qnum' size='10' readonly> ";
            echo "<input type='text' name='yes[]' value='$ycount' size='10' readonly> ";
            echo "<input type='text' name='no[]' value='$ncount' size='10' readonly>";
            echo "</div>";
        }
    ?>
    </div>
</body>
</html>
