<?php
// Define the file path where user data is stored
$user_file = 'users.txt';

// Function to redirect back to the form with a status message
function redirect_with_message($status, $message) {
    // URL-encode the message to handle special characters
    $encoded_message = urlencode($message);
    header("Location: index.php?status=$status&message=$encoded_message");
    exit();
}

// 1. Check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_with_message('error', 'Invalid request method.');
}

// 2. Retrieve and sanitize input data
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    redirect_with_message('error', 'Username and password are required.');
}

// --- 3. Server-Side Validation Rules ---

// a) Username must contain only alphabets
if (!ctype_alpha($username)) {
    redirect_with_message('error', 'Username must contain only alphabets.');
}

// b) Password must contain both alphabets and numbers
if (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password)) {
    redirect_with_message('error', 'Password must contain both alphabets and numbers.');
}

// --- 4. Check for Duplicate Username ---
if (file_exists($user_file)) {
    // Read the file content into an array of lines
    $lines = file($user_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Data is stored as: username|password_hash
        list($existing_username, $hash) = explode('|', $line, 2); 
        
        if ($existing_username === $username) {
            redirect_with_message('error', 'Username already exists. Please choose another.');
        }
    }
}

// --- 5. Save Data to File ---

// Hash the password for basic security (essential in real-world apps)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Format the data as "username|password_hash" followed by a newline
$data_to_save = $username . '|' . $hashed_password . "\n";

// Use FILE_APPEND to add data without overwriting existing content
if (file_put_contents($user_file, $data_to_save, FILE_APPEND | LOCK_EX) === false) {
    redirect_with_message('error', 'Failed to save user data. Check file permissions.');
}

// --- 6. Success ---
redirect_with_message('success', 'Registration successful! You can now log in.');

?>
