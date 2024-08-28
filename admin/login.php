<?php
session_start();
include('../config.php');
// Handle login
if (isset($_POST['login'])) {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => 'Invalid username or password.'];
    if ($_POST['username'] === USERNAME && $_POST['password'] === PASSWORD) {
        $_SESSION['LAST_ACTIVITY'] = time();
        $_SESSION['userid'] = 'admin';
        $response = ['success' => true];
        $response['message'] = 'Success';
    } else {
        $response['message'] = 'Invalid username or password.';
    }

    echo json_encode($response);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BRU Open House</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 1rem;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100">
    <nav class="bg-gray-800 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center p-4">
            <a href="#" class="text-xl font-bold">BRU Open House</a>
            <div class="hidden md:flex space-x-4">
                <a href="index.php" class="hover:bg-gray-700 px-4 py-2 rounded">Home</a>
                <a href="setup.php" class="hover:bg-gray-700 px-4 py-2 rounded">Setup</a>
            </div>
            <button id="menu-toggle" class="md:hidden text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div id="menu" class="md:hidden bg-gray-800 text-white p-4 space-y-2 hidden">
            <a href="index.php" class="block hover:bg-gray-700 px-4 py-2 rounded">Home</a>
            <a href="setup.php" class="block hover:bg-gray-700 px-4 py-2 rounded">Setup</a>
        </div>
    </nav>

    <main class="container mx-auto p-6">
        <section class="bg-gray-800 shadow-md rounded-lg p-6 mb-6 login-container">
            <h2 class="text-2xl font-semibold mb-4">Login</h2>
            <div id="alert" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"></div>
            <form id="loginForm" class="space-y-4">
                <input type="text" name="login" hidden></input>
                <div>
                    <label for="username" class="block text-gray-300 mb-1">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>
                <div>
                    <label for="password" class="block text-gray-300 mb-1">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">Login</button>
            </form>
        </section>
    </main>

    <script>
        $('#loginForm').on('submit', function(event) {
            event.preventDefault();

            // Clear previous alert
            $('#alert').addClass('hidden').text('');

            $.ajax({
                url: 'login.php', // Replace with your server-side script
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    
                    if (response.success) {
                        // Redirect on successful login
                        window.location.href = 'index.php';
                    } else {
                        // Show alert on invalid login
                        $('#alert').removeClass('hidden').text(response.message);
                    }
                },
                error: function() {
                    // Handle error
                    $('#alert').removeClass('hidden').text('An error occurred. Please try again.');
                }
            });
        });
    </script>
</body>

</html>