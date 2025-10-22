$today = date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));
$startOfMonth = date('Y-m-01');
$endOfMonth = date('Y-m-t');

// 1. Get monthly total classes and present count
$monthlySql = "SELECT COUNT(*) AS total, 
                      SUM(status = 'Present') AS present 
               FROM attendance_records 
               WHERE roll_no = '$student_id' 
               AND date BETWEEN '$startOfMonth' AND '$endOfMonth'";
$monthlyResult = $conn->query($monthlySql)->fetch_assoc();
$monthly_classes = $monthlyResult['total'] ?? 0;
$present_classes = $monthlyResult['present'] ?? 0;

// 2. Daily attendance (today's subjects)
$daily_attendance = [];
$dailySql = "SELECT class_name, status 
             FROM attendance_records 
             WHERE roll_no = '$student_id' AND date = '$today'";
$dailyResult = $conn->query($dailySql);
while ($row = $dailyResult->fetch_assoc()) {
    $daily_attendance[$row['class_name']] = $row['status'];
}

// 3. Weekly attendance (Mon to Sun)
$weekly_attendance = [];
$weeklySql = "SELECT DATE(date) AS date, status 
              FROM attendance_records 
              WHERE roll_no = '$student_id' 
              AND date BETWEEN '$startOfWeek' AND '$endOfWeek'";
$weeklyResult = $conn->query($weeklySql);
while ($row = $weeklyResult->fetch_assoc()) {
    $day = date('l', strtotime($row['date']));
    $weekly_attendance[$day] = $row['status']; // shows last status for that day
}

// Optional: Fill missing days
$allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
foreach ($allDays as $day) {
    if (!isset($weekly_attendance[$day])) {
        $weekly_attendance[$day] = 'N/A';
    }
}