<?php
// teacher.php (Login Page)

session_start();

if (isset($_SESSION['teacher_id'])) {
    header("Location: teacher_dashboard.php");
    exit();
}

include 'db_connect.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT id, password FROM teachers WHERE username = ?"; // Removed 'email'
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Prepare failed: " . $conn->error;
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->errno) {
        echo "Execute failed: " . $stmt->error;
    }
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($_POST['password'], $row['password'])) {
            $_SESSION['teacher_id'] = $row['id'];
            $_SESSION['teacher_name'] = $username;
            // Removed $_SESSION['email'] line
            header("Location: teacher_dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial--scale=1.0">
    <title>Teacher Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }

        input, select {
            border: 1px solid #d1d5db;
            padding: 0.75rem;
            border-radius: 0.375rem;
            width: 100%;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #6b7280;
            outline: none;
        }

        button {
            background-color: #111827;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #374151;
        }
    </style>
</head>
<body class="bg-white flex items-center justify-center min-h-screen">
    <div class="container p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-black text-center">Teacher Login</h2>
        <?php if (!empty($error)) : ?>
            <p class="text-red-500 mb-4 text-center"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-black mb-2">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-black mb-2">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="w-full">Login</button>
            <p class="mt-4 text-center">
                <a href="teacher_signup.php" class="text-blue-500 hover:underline">First Time? Sign Up</a>
            </p>
        </form>
    </div>
</body>
</html>