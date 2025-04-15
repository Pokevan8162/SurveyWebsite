# 📝 SurveyWebsite

This is a web-based survey platform built with HTML, CSS, PHP, Java, and more. The application allows users to register, log in, complete a survey, and submit their responses to a backend database. An admin page is also available to edit and monitor surveys. 

---

## 🚀 Features

### ✅ User Authentication
- **Register**: Requires `@myemail.indwes.edu` email. Passwords are encrypted. Gender field included.
- **Login**: Redirects unauthorized users to login. Includes plan for password reset functionality.
- **Logout**: Ends user session.

### ✅ Survey Functionality
- **survey.php**: 
  - Displays all questions using required radio buttons by fetching surveyID chosen in index.php
  - Submits responses to database.
  - Uses `reshuffle.php` (TBD) to show random survey results.
  
### ✅ Admin Capabilities
- Add/edit survey questions and responses.
- Edit user statuses and info
- Handle multiple surveys.
- Store answers/responses as plain text files or in the database.

---

## 🗃️ Database Tables

| Table         | Fields                                    | Description                            |
|---------------|-------------------------------------------|----------------------------------------|
| `users`       | `UserID`, `Email`, `Password`, `Salt`, `gender`, `UserStatus` | Stores user data                      |
| `questions`   | `SurveyID`, `QuestionNumber`, `QuestionType`, `Question`  | Stores answer options                  |
| `surveys`     | `SurveyID`, `SurveyName`,`SurveyGender`   | Stores user responses to surveys       |
| `responses`   | `SurveyID`, `UserID`, `QuestionNumber`, `Answer`       | Stores user responses to surveys       |

---

## ⚙️ Setup Instructions

1. Clone or download this repository.
2. Set up a PHP server (e.g., XAMPP or MAMP).
3. Create a database 'survey_db' and run the SQL file in `database/schema.sql`.
4. Ensure database configuration in backend/db.php matches your database credentials.
5. Run `LogIn.php` in your local server to get started.

---

⚠️ PHP Include vs. Browser Load

PHP require/include = server-side = use __DIR__ or relative filesystem paths
CSS, images, JS = client-side = use browser-relative URLs

---

## 🔒 Security Notes

- Passwords are hashed using Java (`PasswordHash.java` and `PasswordSalt.java`) 
- Input validation and sanitization

---

## ✍️ Authors

Project by Ben Kuehner, Evan Stoller, Daniel Jackson, and Daniel Schager
