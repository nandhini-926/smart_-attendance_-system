<?php

function getDbConnection() {
    $servername = "localhost"; // e.g., localhost
    $username = "root";     // e.g., root
    $password = "newpassword";     // your database password
    $dbname = "attendance";         // the name of your database

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function getDailyAttendance($conn, $roll_no, $date) {
    $query = "SELECT * FROM attendance_records WHERE roll_no = ? AND date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $roll_no, $date); // Assuming roll_no is a string (VARCHAR)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // No record found
    }
}

?>