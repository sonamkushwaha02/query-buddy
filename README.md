QueryBuddy
QueryBuddy is a smart web-based chatbot system designed to streamline and simplify the college enquiry process.

Previously, students had to physically visit college campuses to obtain important information regarding course offerings, fee structures, admission procedures, and more. This traditional method was time-consuming and inconvenient.

QueryBuddy addresses these challenges by providing a fast, accurate, and user-friendly platform where students can easily get answers to their queries related to courses, admissions, eligibility, scholarships, and more — all without having to visit the campus.

Features
Instant Responses: Students can receive real-time answers to common queries.

Admin Dashboard: College staff can easily update or modify system responses.

Automated Enquiry Process: Reduces administrative workload significantly.

Real-Time Updates: Adapts to changing information and updates instantly.

Personalized Interaction: Delivers an interactive and customized experience to users.

Project Domain
Web Application

Technologies Used
Front End:

HTML

CSS

JavaScript

Back End:

PHP (Hypertext Preprocessor)

PHP Mailer (for sending emails)

Cohere.com AI (Bridge between user interactions and database responses)

Database:

MySQL

Server:

XAMPP Server

Installation and Setup
Clone the repository:

bash
Copy
Edit
git clone https://github.com/sonamkushwaha02/query-buddy.git
Start the XAMPP server:

Open XAMPP Control Panel.

Start Apache and MySQL modules.

Import the Database:

Open phpMyAdmin (http://localhost/phpmyadmin).

Create a new database (e.g., querybuddy_db).

Import the provided .sql file.

Configure Database Connection:

Update your database connection details inside db.php or the equivalent file.

Run the Application:

Move the project folder to the htdocs directory (C:/xampp/htdocs).

Open your browser and navigate to:

arduino
Copy
Edit
http://localhost/query-buddy/
Usage
Students:
Visit the application, ask queries related to college admissions, and receive instant responses.

Admin:
Login to the dashboard, update chatbot responses, and manage queries.

Future Enhancements
Add multilingual query support.

Integrate voice-based interaction.

Advanced analytics dashboard for administrators.

License
This project is licensed under the MIT License.

Acknowledgements
Cohere AI

XAMPP Server

PHP Mailer Library
