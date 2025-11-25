<?php
// Start a session to store the username upon successful login
session_start();

$user_file = 'users.txt';
$login_message = '';
$message_class = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $login_message = 'Please enter both username and password.';
        $message_class = 'error';
    } else {
        $user_found = false;

        if (file_exists($user_file)) {
            // Read all lines from the file
            $lines = file($user_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {
                // Data is stored as: username|password_hash
                list($stored_username, $hashed_password) = explode('|', $line, 2); 
                
                // 1. Verify username match
                if ($stored_username === $username) {
                    $user_found = true;
                    // 2. Verify password using PHP's built-in hashing check
                    if (password_verify($password, $hashed_password)) {
                        // Success: Store username in session and redirect
                        $_SESSION['username'] = $username;
                        header("Location: welcome.php");
                        exit();
                    }
                    // Break the loop once the username is found
                    break;
                }
            }
        }

        // If user wasn't found or password verification failed
        $login_message = 'Invalid username or password.';
        $message_class = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 350px; }
        h2 { text-align: center; color: #333; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .message { margin-top: 15px; padding: 10px; border-radius: 4px; text-align: center; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>
        
        <?php if (!empty($login_message)): ?>
            <div class="message <?php echo $message_class; ?>">
                <?php echo htmlspecialchars($login_message); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Log In</button>
        </form>
        <p style="text-align:center; margin-top: 20px;"><a href="index.php">Don't have an account? Register here.</a></p>
    </div>
</body>
</html>