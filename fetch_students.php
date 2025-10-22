nt_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();

    // Fetch attendance data
    $attendance_stmt = $conn->prepare("SELECT class, attendance FROM attendance WHERE student_id = ?");
    $attendance_stmt->bind_param("i", $student_id);
    $attendance_stmt->execute();
    $attendance_result = $attendance_stmt->get_result();
    
    $weekly_attendance = [];
    while ($row = $attendance_result->fetch_assoc()) {
        $weekly_attendance[] = $row;
    }

    // Prepare response
    $response = [
        'name' => $student['name'],
        'rollNumber' => $student['roll_number'],
        'phoneNumber' => $student['phone_number'],
        'className' => $student['class_name'],
        'monthlyPercentage' => $student['monthly_percentage'],
        'classAttendance' => [
            'total' => $student['total_classes'],
            'present' => $student['present_classes'],
            'absent' => $student['absent_classes']
        ],
        'weeklyAttendance' => $weekly_attendance
    ];

    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(["message" => "Student not found"]);
}

// Close connections
$stmt->close();
$attendance_stmt->close();
$conn->close();
?>
