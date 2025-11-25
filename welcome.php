<?php
session_start();

// Check if the username is set in the session (user is logged in)
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect them back to the login page
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #5f7b8dff; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .welcome-box { background-color: #54a0a0ff; padding: 50px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); text-align: center; }
        h1 { color: #2a28a7ff; margin-bottom: 20px; font-size: 2.5em; }
        p { color: #0b0a0aff; font-size: 1.2em; }
        .logout-btn { padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 5px; text-decoration: none; margin-top: 30px; display: inline-block; transition: background-color 0.3s; }
        .logout-btn:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <div class="welcome-box">
        <h1>Hey there, Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>You have successfully logged into the system.</p>
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
</body>
</html>