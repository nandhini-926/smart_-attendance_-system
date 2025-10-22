# smart_-attendance_-system
AI-based Smart Classroom Attendance System using Python, OpenCV, and MySQL. Captures student faces via webcam, recognizes them in real time, and marks attendance automatically in the database. Managed through a WAMP Server dashboard for easy viewing and editing by teachers.
## Smart Classroom Attendance Management System
# Project Overview
The Smart Classroom Attendance Management System is an automated solution to manage classroom attendance efficiently. It uses face recognition to capture student images, stores data in a MySQL database, and provides a dashboard interface for students and teachers to view and manage attendance records.
## Features
- Capture student images for attendance.
- Store student details (roll number, name, ID card, phone number) in MySQL with timestamps.
- Dashboard interface for both students and teachers.
- Students can check their own attendance records.
- Teachers can view and modify student attendance.
- Runs locally on WAMP server.
## Technology Stack
- *Frontend:* HTML, CSS, JavaScript  
- *Backend:* PHP  
- *Database:* MySQL (via phpMyAdmin)  
- *Server:* WAMP Server  
## Project Flow
1. Capture Student Images  
   Student images are captured and stored in the database with timestamps.
2. Dashboard (index.php)  
   The first page allows users to select *Student* or *Teacher*.
3. Student Access (student.php)  
   Students can view their attendance records.
4. Teacher Access (teacher.php)  
   Teachers can view student information (roll number, name, ID card, phone number).  
   Teachers can add, modify, or update attendance.
## Installation & Setup
1. Install WAMP Server  
   Download and install WAMP from [https://www.wampserver.com/](https://www.wampserver.com/).  
2. Copy Project Files
   Place your entire project folder inside:
3. Start WAMP 
   Launch WAMP and ensure the system tray icon turns *green*.  
4. Setup Database  
  Open [phpMyAdmin](http://localhost/phpmyadmin).  
  Create a database (e.g., attendance_db).  
  Import attendance_db.sql from your project folder.  
  Update database connection in PHP files if needed:
  php
  $servername = "localhost";
  $username = "root";      // default for WAMP
  $password = "";          // default for WAMP
  $dbname = "attendance_db";
  $conn = new mysqli($servername, $username, $password, $dbname);
 ## Run the Project
 Open a browser and go to:
 http://localhost/<your-project-folder>/index.php
 Select Student → student.php
Select Teacher → teacher.php
## Usage
	•	Student: View personal attendance records.
	•	Teacher: Manage student data and update attendance.
	•	Attendance is automatically logged when student images are captured.
