# SurveyWebsite
for jeremies


Db.php
	Save queries for software development team
	WHAT IS THIS FOR??????
Register.php
	Username must include @myemail.indwes.edu 
	Password must be good
	Encrypt password
	Include a standard admin account
	Include gender
Login.php
	If the user is not logged in, they should get booted to this page
	Include a way for users to change password, Probably a separate PHP file
Logout.php


Survey.php
	All in one page
	Radio buttons
	All questions are required
	After submitted, send to database, refer to reshuffle.php
Reshuffle.php	
	Grab a random survey
	Display survey results to user
	Grab response from user
index.php


Admin needs to be able to add questions, including text responses
Maybe allow for multiple surveys and multiple responses
3 tables Database has: 
username, encrypted password, gender, survey id. 	//User
survey id, location 						//for answers
Survey id, username 					//for responses
Surveys are txt documents that contain only answers
	Another txt document contains questions that admin has editing access
	Another txt document contains the response


