<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Attendance Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        /* Checkbox-like appearance for buttons */
        .role-button {
            border: 1px solid #d1d5db; /* Light gray border */
            padding: 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
            margin-bottom: 1rem; /* Add margin between buttons */
        }

        .role-button:hover {
            background-color: #f9fafb; /* Lighter background on hover */
            border-color: #9ca3af; /* Darker border on hover */
        }

        /* Transparent effect for the main container */
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
            backdrop-filter: blur(5px); /* Optional blur effect */
        }
    </style>
</head>
<body class="bg-white flex items-center justify-center min-h-screen">
    <div class="container p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h1 class="text-center text-3xl font-bold mb-6 text-black">Attendance Management System</h1>
        <div class="flex justify-center mb-6">
            <img src="images/logo.jpg" alt="logo" class="w-32 h-32" />
        </div>
        <div class="text-center mb-4">
            <p class="text-lg font-semibold text-black">Please select your role</p>
        </div>

        <form action="role_redirect.php" method="POST" class="flex flex-col items-center space-y-4">
            <button type="submit" name="role" value="teacher" class="role-button text-black">
                <i class="fas fa-chalkboard-teacher mr-3"></i>
                <span class="text-lg">Teacher</span>
            </button>
            <button type="submit" name="role" value="student" class="role-button text-black">
                <i class="fas fa-user-graduate mr-3"></i>
                <span class="text-lg">Student</span>
            </button>
        </form>
    </div>
</body>
</html>