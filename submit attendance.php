<?php
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roll_number = $_POST['roll_number'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    include 'db_connect.php'; // Include your database connection

    // Update the attendance in the database
    $sql = "UPDATE attendance_records SET status = ? WHERE roll_no = ? AND date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $status, $roll_number, $date);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: teacher_student_dashboard.php?roll=" . $roll_number);
        exit();
    } else {
        echo "Error updating attendance: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: teacher_dashboard.php");
    exit();
}
?>