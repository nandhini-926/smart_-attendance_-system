<!DOCTYPE html>
<html>
<head>
    <title>Live Attendance Dashboard</title>
    <style>
        table { width: 80%; border-collapse: collapse; margin: 20px auto; }
        th, td { padding: 10px; border: 1px solid black; text-align: center; }
        img { width: 100px; height: 100px; }
        .live-update { color: green; font-weight: bold; }
    </style>
    <meta http-equiv="refresh" content="10"> </head>
<body>
    <h2 align="center">📌 Live Attendance Records</h2>
    <p align="center" class="live-update">✅ This page updates every 10 seconds</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Timestamp</th>
            <th>Image</th>
        </tr>

        <?php
        $conn = new mysqli("localhost", "root", "", "attendance_db");
        if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

        $sql = "SELECT * FROM attendance_records ORDER BY timestamp DESC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row["id"]}</td>
                <td>{$row["name"]}</td>
                <td>{$row["timestamp"]}</td>
                <td><img src='{$row["image_path"]}'></td>
            </tr>";
        }
        $conn->close();
        ?>
    </table>

    <div style="text-align: center; margin-top: 20px;">
        <a href="download_attendance_csv.php">
            <button>Download CSV</button>
        </a>
    </div>
</body>
</html>