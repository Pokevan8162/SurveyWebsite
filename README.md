# 📝 SurveyWebsite

This is a web-based survey platform built with HTML, CSS, PHP, Java, and more. The application allows users to register, log in, complete a survey, and submit their responses to a backend database. An admin page is also available to edit and monitor surveys. 

---

---

## 🚀 Features

### ✅ User Authentication
- **Register**: Requires `@myemail.indwes.edu` email. Passwords are encrypted. Gender field included.
- **Login**: Redirects unauthorized users to login. Includes plan for password reset functionality.
- **Logout**: Ends user session.

### ✅ Survey Functionality
- **survey.php**: 
  - Displays all questions using required radio buttons.
  - Submits responses to database.
  - Uses `reshuffle.php` (not yet implemented) to show random survey results.
  
### ✅ Admin Capabilities (Planned)
- Add/edit survey questions and responses.
- Handle multiple surveys.
- Store answers/responses as plain text files or in the database.

---

## 🗃️ Database Tables (Planned)

| Table         | Fields                                    | Description                            |
|---------------|-------------------------------------------|----------------------------------------|
| `users`       | `username`, `password (encrypted)`, `gender`, `survey_id` | Stores user data                      |
| `answers`     | `survey_id`, `location`                   | Stores answer options                  |
| `responses`   | `survey_id`, `username`                   | Stores user responses to surveys       |

---

## ⚙️ Setup Instructions

1. Clone or download this repository.
2. Set up a PHP server (e.g., XAMPP or MAMP).
3. Import the SQL file in `database/schema.sql` to your local MySQL server.
4. Ensure file paths in your HTML/PHP files align with this new structure.
5. Run `index.html` to get started.

---

## 🔒 Security Notes

- Passwords are hashed using Java (`PasswordHash.java`) — this should be integrated server-side or replaced with PHP’s `password_hash()` function.
- Input validation and sanitization are recommended for production environments.

---

## ✍️ Author

Project by Jeremies (developer: [Your Name Here])
