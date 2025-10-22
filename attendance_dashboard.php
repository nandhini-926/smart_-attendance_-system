<!DOCTYPE html>
<html>
<head>
    <title>Live Attendance Dashboard</title>
    <style>
        body { font-family: sans-serif; }
        .container { max-width: 1000px; margin: 20px auto; }
        h2 { text-align: center; color: #333; }
        .live-update { color: green; font-weight: bold; text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        img { width: 80px; height: 80px; display: block; margin: 0 auto; }
        .error-message { color: red; text-align: center; margin-top: 20px; }
        .download-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .download-button:hover { background-color: #3e8e41; }
    </style>
    <meta http-equiv="refresh" content="10">
</head>
<body>
    <div class="container">
        <h2>Live Attendance Records</h2>
        <p class="live-update">✅ This page updates every 10 seconds</p>

        <?php
        $conn = new mysqli("localhost", "root", "newpassword", "attendance"); // Replace with your password
        if ($conn->connect_error) {
            echo "<p class='error-message'>Connection failed: " . $conn->connect_error . "</p>";
        } else {
            $sql = "SELECT * FROM attendance_records ORDER BY timestamp DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Timestamp</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row["attendance_id"]}</td>
                            <td>{$row["name"]}</td>
                            <td>{$row["timestamp"]}</td>
                            <td><img src='{$row["image_path"]}'></td>
                        </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p class='error-message'>No attendance records found.</p>";
            }

            $conn->close();
        }
        ?>

        <a href="download_attendance_csv.php">
            <button class="download-button">Download CSV</button>
        </a>
    </div>
</body>
</html>