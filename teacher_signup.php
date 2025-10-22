<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        /* ... your styles ... */
    </style>
</head>
<body class="bg-white flex items-center justify-center min-h-screen">
    <div class="container p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-black text-center">Teacher Signup</h2>
        <form method="POST" action="teacher_signup_process.php">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-black mb-2">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-black mb-2">Enter Your Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="mb-4">
                <label for="class" class="block text-sm font-medium text-black mb-2">Enter Your Class:</label>
                <input type="text" name="class" id="class" required>
            </div>
            <div class="mb-4">
                <label for="branch" class="block text-sm font-medium text-black mb-2">Enter Your Branch:</label>
                <input type="text" name="branch" id="branch" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-black mb-2">Set a Password (Min 8 chars, 1 Cap, 1 Num, 1 Symbol):</label>
                <input type="password" name="password" id="password" required pattern="^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]).{8,}$" title="Password must be at least 8 characters long and include one uppercase letter, one number, and one symbol.">
            </div>
            <button type="submit" class="w-full">Sign Up</button>
            <p class="mt-4 text-center">
                <a href="teacher.php" class="text-blue-500 hover:underline">Login</a>
            </p>
        </form>
    </div>
</body>
</html>