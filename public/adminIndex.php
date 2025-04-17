<?php
//start session
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
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administrator Home Page</title>
	<link rel="stylesheet" href="introPages.css">
</head>
<body>	
	<a href="adminIndex.php"><img src="logo.png" alt="Logo" class="logo"></a>
	<div class="container">
		<div class="form_area">
		<div class="title">Welcome to the Administrator Page</div>
			<a href="manageUsers.php"><button type="button" class="btn">Manage Users</button></a>
			<a href="listSurveys.php"><button type="button" class="btn">See Survey Results</button></a>
			<a href="viewSurveys.php"><button type="button" class="btn">Update Surveys</button></a>
			<a href="createSurvey.php"><button type="button" class="btn">Create Survey</button></a>
			<a href="logout.php"><button type="button" class="btn">Logout</button></a>
		</div>
	</div>
</body>	
