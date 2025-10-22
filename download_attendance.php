<?php
$conn = new mysqli("localhost", "root", "", "attendance_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=attendance_records.csv');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Name', 'Timestamp', 'Image Path'));

$sql = "SELECT * FROM attendance_records ORDER BY timestamp DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
?>