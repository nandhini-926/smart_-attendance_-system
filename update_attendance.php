<?php
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher.php");
    exit();
}

include 'db_connect.php';

$student_id = $_GET['roll']; // Get roll_no from URL
$sql = "SELECT * FROM students WHERE roll_no = '$student_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    echo "Student not found.";
    exit();
}

// You might want to fetch the current monthly and present classes here to pre-fill the form
$monthly_classes = 30; // Example
$present_classes = 20; // Example

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Attendance</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
        }

        .input-box {
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 5px;
            margin-top: 5px;
            width: 200px;
            text-align: center;
        }

        .submit-button, .logout-box {
            background-color: #f9f9f9;
            color: #333;
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: block;
            width: 200px;
            margin: 10px auto;
        }

        .submit-button:hover, .logout-box:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Update Attendance</h1>

        <form action="update_attendance_process.php" method="POST">
            <input type="hidden" name="roll_number" value="<?php echo $student_id; ?>">

            <div class="input-group">
                <label for="monthly_classes">Monthly Classes:</label>
                <input type="number" name="monthly_classes" id="monthly_classes" class="input-box" value="<?php echo $monthly_classes; ?>" required>
            </div>

            <div class="input-group">
                <label for="present_classes">Present Classes:</label>
                <input type="number" name="present_classes" id="present_classes" class="input-box" value="<?php echo $present_classes; ?>" required>
            </div>

            <button type="submit" class="submit-button">Submit</button>
        </form>

        <a href="logout.php" class="logout-box">Logout</a>
    </div>
</body>
</html>