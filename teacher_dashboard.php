<?php
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher.php");
    exit();
}

include 'db_connect.php';

$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher's class
$sql_teacher = "SELECT class FROM teachers WHERE id = ?";
$stmt_teacher = $conn->prepare($sql_teacher);
$stmt_teacher->bind_param("i", $teacher_id);
$stmt_teacher->execute();
$result_teacher = $stmt_teacher->get_result();

if ($result_teacher->num_rows == 1) {
    $row_teacher = $result_teacher->fetch_assoc();
    $teacher_class = $row_teacher['class'];

    // Fetch students for the teacher's class
    $sql_students = "SELECT roll_no, name FROM students WHERE class = ?";
    $stmt_students = $conn->prepare($sql_students);
    $stmt_students->bind_param("s", $teacher_class);
    $stmt_students->execute();
    $result_students = $stmt_students->get_result();

    $students = [];
    while ($row_student = $result_students->fetch_assoc()) {
        $students[] = $row_student;
    }

    $stmt_students->close();
} else {
    $students = [];
}

$stmt_teacher->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <style>
        body {
            background-color: white;
            color: black;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .roll-link {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
        .logout-button {
            background-color: black;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
            margin-top: 1rem;
        }
        .logout-button:hover {
            background-color: #333;
        }
    </style>
</head>
<body class="bg-white text-black">
    <div class="container">
        <h1>Teacher Dashboard</h1>

        <?php if (!empty($students)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Roll Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td>
                                <a href="teacher_student_dashboard.php?roll=<?php echo $student['roll_no']; ?>" class="roll-link">
                                    <?php echo $student['roll_no']; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>Total Students: <?php echo count($students); ?></p>
        <?php else : ?>
            <p>No students found for your class.</p>
        <?php endif; ?>

        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>