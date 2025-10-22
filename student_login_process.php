<?php
session_start();

include 'db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class = $_POST['class'];
    $roll_no = $_POST['roll_no'];

    // Replace with your actual database query to verify student login
    $sql = "SELECT * FROM students WHERE class = '$class' AND roll_no = '$roll_no'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['student_id'] = $roll_no; // Store the student's roll number in the session
        header("Location: student_dashboard.php"); // Redirect to the student dashboard
        exit();
    } else {
        echo "Invalid class or roll number."; // Display an error message
    }

    $conn->close(); // Close the database connection
} else {
    // If the page is accessed without a POST request, redirect to the login page
    header("Location: student_login.php");
    exit();
}
?>