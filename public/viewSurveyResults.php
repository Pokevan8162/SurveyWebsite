<?php
    require_once __DIR__ . '/../backend/db.php';
    require_once __DIR__ . '/../backend/session_check.php';

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
    <a href="adminIndex.php"><img src="../resources/images/logo.png" alt="Logo" class="logo"></a>
    <div class="header">
        <a href="logout.php" class="logout"><button type="button" class="btn">Logout</button></a>
    </div>
    <div class="container">
    <?php
        $surveyID = $_GET['SurveyID'];
        $stmt = $conn->prepare("SELECT SurveyName FROM surveys WHERE SurveyID = :surveyID");
        $stmt->execute([':surveyID' => $surveyID]);
        $surveyName = $stmt->fetch(PDO::FETCH_COLUMN);

        $sortOptions = [
            'Question' => 'QuestionNumber',
            'Yes' => 'YesCount',
            'No' => 'NoCount',
            'Gender' => 'QuestionGender'
        ];
        $orderBy = $sortOptions[$_GET['sort']] ?? 'QuestionNumber';

        $sortOrder = [
            'Asc' => 'ASC',
            'Desc' => 'DESC'
        ];
        $sortBy = $sortOrder[$_GET['order']] ?? 'ASC';
        $direction  = $sortBy == 'DESC' ? 'Asc' : 'Desc';

        $stmt = $conn->prepare("SELECT r.QuestionNumber, SUM(CASE WHEN Answer = 'Yes' THEN 1 ELSE 0 END) AS YesCount, SUM(CASE WHEN Answer = 'No' THEN 1 ELSE 0 END) AS NoCount, q.QuestionGender FROM responses r JOIN questions q ON r.questionNumber = q.questionNumber WHERE r.SurveyID = :surveyID GROUP BY QuestionNumber ORDER BY $orderBy $sortBy");
        $stmt->execute([':surveyID' => $surveyID]);
        $numbers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>$surveyName</h2>";
        echo "<table>
            <thead>
                <tr>
                    <th><a href=?SurveyID=$surveyID&sort=Question&order=$direction>Number</a></th>
                    <th><a href=?SurveyID=$surveyID&sort=Yes&order=$direction>Yes</a></th>
                    <th><a href=?SurveyID=$surveyID&sort=No&order=$direction>No</a></th> 
                    <th><a href=?SurveyID=$surveyID&sort=Gender&order=$direction>Gender</a></th>
                </tr>
            </thead>";

        foreach ($numbers as $row) {
            $qnum = $row['QuestionNumber'];
            $YesCount = $row['YesCount'];
            $NoCount = $row['NoCount'];
            $Gender = $row['QuestionGender'];

            echo "<tr>
                    <td>$qnum</td>
                    <td>$YesCount</td>
                    <td>$NoCount</td>
                    <td>$Gender</td>
                </tr>";
        }
        echo "</table>";
    ?>
    </div>
</body>
</html>
