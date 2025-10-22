<?php
session_start();
if (!isset($_SESSION['teacher'])) {
    header("Location: teacher.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "attendance");

if (isset($_GET['roll_no'])) {
    $roll_no = $_GET['roll_no'];
    $query = "SELECT * FROM students WHERE roll_no='$roll_no'";
    $result = $conn->query($query);
    $student = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $section = $_POST['section'];

    $update_query = "UPDATE students SET name='$name', class='$class', section='$section' WHERE roll_no='$roll_no'";
    if ($conn->query($update_query)) {
        echo "Updated Successfully!";
    }
}
?>

<form method="post">
    Name: <input type="text" name="name" value="<?= $student['name'] ?>" required><br>
    Class: <input type="text" name="class" value="<?= $student['class'] ?>" required><br>
    Section: <input type="text" name="section" value="<?= $student['section'] ?>" required><br>
    <button type="submit">Submit</button>
</form