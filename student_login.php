<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        select, input[type="text"] {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        <form id="studentLoginForm" action="student_login_process.php" method="POST">
            <label for="class">Select Your Class:</label>
            <select name="class" id="class" required>
                <option value="">-- Select Class --</option>
                <option value="AI&DS-1A">AI&DS First Year</option>
                <option value="AI&DS-2A">AI&DS Second Year</option>
                <option value="AI&DS-3A">AI&DS Third Year</option>
                <option value="AI&DS-4A">AI&DS Fourth Year</option>
            </select>

            <label for="roll_no">Enter Your Roll Number:</label>
            <input type="text" name="roll_no" id="roll_no" placeholder="Enter Roll Number" required>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>