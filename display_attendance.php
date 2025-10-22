<?php
$file = fopen("attendance.csv", "r");
echo "<table border='1'>";
echo "<tr><th>Name</th><th>Timestamp</th></tr>";
while (($data = fgetcsv($file)) !== FALSE) {
    echo "<tr><td>{$data[0]}</td><td>{$data[1]}</td></tr>";
}
fclose($file);
echo "</table>";
?>