<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["role"])) {
    $role = $_POST["role"];

    if ($role == "teacher") {
        header("Location: teacher.php"); // Redirect to teacher login
        exit();
    } elseif ($role == "student") {
        header("Location: student_login.php"); // Redirect to student login
        exit();
    }
}

// If accessed directly, redirect to index.php
header("Location: index.php");
exit();
?>
