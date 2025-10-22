<?php
// Include the functions file
include('functions.php');

// Start session (if not already started)
session_start();

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher.php");
    exit();
}

// Connect to the database
$conn = getDbConnection();

// Get the student's roll number from the URL
$student_id = $_GET['roll'];

// Fetch student information
$sql_student = "SELECT * FROM students WHERE roll_no = '$student_id'";
$result_student = $conn->query($sql_student);

if ($result_student->num_rows == 1) {
    $row_student = $result_student->fetch_assoc();
    $student_name = $row_student['name'];
    $student_class = $row_student['class'];
    $student_phone = isset($row_student['phone_no']) ? $row_student['phone_no'] : 'N/A';
    $student_image = isset($row_student['Image_path']) ? $row_student['Image_path'] : 'images/default.jpg';
} else {
    echo "Student not found.";
    exit();
}

// Get today's date
$today = date("Y-m-d");

// --- Fetch Today's Attendance for the specific student ---
function getTodayAttendance($conn, $roll_no, $date) {
    $query = "SELECT timestamp FROM attendance_records WHERE roll_no = ? AND date = ? AND status = 'Present'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $roll_no, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $attendance[] = date('H:i:s', strtotime($row['timestamp']));
        }
    }
    return $attendance;
}

$today_attendance_times = getTodayAttendance($conn, $student_id, $today);
$daily_attendance = [];
for ($i = 1; $i <= 7; $i++) {
    $daily_attendance['class ' . $i] = 'Absent';
    if (isset($today_attendance_times[$i - 1])) {
        $daily_attendance['class ' . $i] = 'Present (' . $today_attendance_times[$i - 1] . ')';
    }
}

// --- Fetch Monthly Attendance for the specific student ---
function getMonthlyAttendance($conn, $roll_no, $month, $year) {
    $query = "SELECT COUNT(*) as present_count FROM attendance_records WHERE roll_no = ? AND MONTH(date) = ? AND YEAR(date) = ? AND status = 'Present'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $roll_no, $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['present_count'];
    }
    return 0;
}

function getTotalClassesMonth($conn, $roll_no, $month, $year) {
    $query = "SELECT COUNT(DISTINCT date) as total_days FROM attendance_records WHERE roll_no = ? AND MONTH(date) = ? AND YEAR(date) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $roll_no, $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['total_days'] * 7; // Assuming 7 classes per day
    }
    return 1; // Avoid division by zero
}

$current_month = date('m');
$current_year = date('Y');
$present_classes = getMonthlyAttendance($conn, $student_id, $current_month, $current_year);
$monthly_classes = getTotalClassesMonth($conn, $student_id, $current_month, $current_year);
$attendance_percentage = ($monthly_classes > 0) ? ($present_classes / $monthly_classes) * 100 : 0;

// --- Fetch Weekly Attendance for the specific student ---
function getWeeklyAttendance($conn, $roll_no, $start_date, $end_date) {
    $query = "SELECT date, COUNT(*) as present_count FROM attendance_records WHERE roll_no = ? AND date >= ? AND date <= ? AND status = 'Present' GROUP BY date";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $roll_no, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $weekly_attendance = []; // Initialize as an empty array
    while ($row = $result->fetch_assoc()) {
        $day_of_week = date('l', strtotime($row['date']));
        $weekly_attendance[$day_of_week] = $row['present_count'] . ' class(es)';
    }
    return $weekly_attendance;
}

$monday = date('Y-m-d', strtotime('monday this week'));
$sunday = date('Y-m-d', strtotime('sunday this week'));
$weekly_attendance_data = getWeeklyAttendance($conn, $student_id, $monday, $sunday);
$weekly_attendance = [ // Initialize here as well
    'Monday' => 'Absent',
    'Tuesday' => 'Absent',
    'Wednesday' => 'Absent',
    'Thursday' => 'Absent',
    'Friday' => 'Absent',
    'Saturday' => 'Absent',
    'Sunday' => 'Absent',
];
foreach ($weekly_attendance_data as $day => $status) {
    $weekly_attendance[$day] = $status;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }

        .attendance-box {
            border: 1px solid #d1d5db;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .attendance-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            background-color: #e0f2fe; /* Light blue background */
            color: #1e3a8a; /* Dark blue text */
        }
    </style>
</head>
<body class="bg-gray-100 p-4">
    <div class="container mx-auto max-w-4xl p-6 rounded-lg shadow-lg">
        <div class="text-center font-semibold text-2xl text-black mb-4">
            Welcome to Student Dashboard
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="attendance-box">
                <div class="flex items-center">
                    <img src="<?php echo $student_image; ?>" alt="Student Profile" class="w-20 h-20 rounded-full mr-4">
                    <div>
                        <p class="text-lg font-semibold text-black"><?php echo $student_name; ?></p>
                        <p class="text-sm text-gray-600"><?php echo $student_id; ?></p>
                        <p class="text-sm text-gray-600"><?php echo $student_phone; ?></p>
                        <p class="text-sm text-gray-600"><?php echo $student_class; ?></p>
                    </div>
                </div>
            </div>

            <div class="attendance-box flex flex-col items-center">
                <p class="font-semibold text-black">Monthly overall attendance percentage</p>
                <div class="attendance-circle">
                    <?php echo round($attendance_percentage); ?>%
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="attendance-box">
                <h2 class="text-xl font-semibold mb-2 text-black">Today's Attendance</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="text-left text-black">class</th>
                            <th class="text-left text-black">Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daily_attendance as $class => $status): ?>
                            <tr>
                                <td class="text-left text-black"><?php echo $class; ?></td>
                                <td class="text-left text-black"><?php echo $status; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="attendance-box">
                <h2 class="text-xl font-semibold mb-2 text-black">Monthly overall classes</h2>
                <div class="flex flex-col gap-2 text-sm">
                    <div class="p-2 border rounded">
                        <p class="text-black">Total classes: <?php echo $monthly_classes; ?></p>
                    </div>
                    <div class="p-2 border rounded">
                        <p class="text-black">Attended classes: <?php echo $present_classes; ?></p>
                    </div>
                    <div class="p-2 border rounded">
                        <p class="text-black">Absent classes: <?php echo $monthly_classes - $present_classes; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="attendance-box mt-4">
            <h2 class="text-xl font-semibold mb-2 text-black">Weekly Attendance</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left text-black">Day</th>
                        <th class="text-left text-black">Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($weekly_attendance as $day => $status): ?>
                        <tr>
                            <td class="text-left text-black"><?php echo $day; ?></td>
                            <td class="text-left text-black"><?php echo $status; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-col items-center space-y-4">
            <a href="update_attendance.php?roll=<?php echo $student_id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Update Manually
            </a>
            <a href="logout.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Logout
            </a>
        </div>
    </div>
</body>
</html>