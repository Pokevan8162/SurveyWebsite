<!-- This is the homepage for the admin 
 From here the admin should be able to 
 manage users (update usernames, passwords, etc.), 
 manage surveys (update questions, see results, and post new questions, create new surveys).
-->
<?php
require_once __DIR__ . '/../backend/db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administrator Home Page</title>
	<link rel="stylesheet" href="../resources/css/introPages.css">
</head>
<body>	
	<img src="https://s3-us-west-2.amazonaws.com/scorestream-team-profile-pictures/285522/20181011000648_310_mascot1280Near.png" alt="Logo" class="logo">
	<div class="container">
		<div class="form_area">
		<div class="title">Welcome to the Administrator Page</div>
			<a href="manageUsers.php"><button type="button" class="btn">Manage Users</button></a>
			<a href="viewSurveyResults.php"><button type="button" class="btn">See Survey Results</button></a>
			<a href="viewSurvey.php"><button type="button" class="btn">Update Surveys</button></a>
			<a href="createSurvey.php"><button type="button" class="btn">Create Survey</button></a>
			<a href="logout.php"><button type="button" class="btn">Logout</button></a>
		</div>
	</div>
</body>	
