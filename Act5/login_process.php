<?php
session_start();
require 'config.php';

// initialize variables
if (!isset($_SESSION['attempt'])) {
    $_SESSION['attempt'] = 0;
    $_SESSION['locked'] = false;
    $_SESSION['lock_time'] = 0;
}

// anti-brute force measure (REQUIREMENT)
$max_attempts = 5;
$lockout_time = 15 * 60;

// lockout check
if ($_SESSION['locked'] && (time() - $_SESSION['lock_time'] < $lockout_time)) {
    die("You are temporarily locked out. Try again later.");
} elseif ($_SESSION['locked'] && (time() - $_SESSION['lock_time'] >= $lockout_time)) {
    $_SESSION['attempt'] = 0;
    $_SESSION['locked'] = false;
    $_SESSION['lock_time'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // anti SQL injection measure (REQUIREMENT)
    $_POST['login'] = htmlspecialchars($_POST['login']);
    $_POST['password'] = htmlspecialchars($_POST['password']);

    $login = trim(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
    $password = $_POST['password']; // Plaintext password

    if (isset($_GET['error']) && $_GET['error'] == 'empty') {
        echo "<script>alert('Please enter both username and password.');</script>";
    }
    // Check if login is username or email
    $sql = "SELECT id, username, password FROM balones_act2 WHERE username  = ?";
    $stmt = $conn->prepare($sql);

    // Check if prepare() failed
    if ($stmt === false) {
        // If prepare fails, output the error message
        die('Prepare failed: ' . $conn->error);
    }

    // Bind parameters and execute the query
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $stored_password);
        $stmt->fetch();
        // Handle login logic here
    } else {
        // No matching user found
        echo "Invalid username.";
    }

    // Close the statement
    $stmt->close();

    // check if the input password matches the stored password
    // anti SQL injection measure (REQUIREMENT)
    $encrypted_password = md5($password);
    if ($encrypted_password === $stored_password) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['attempt'] = 0;
        echo "<script>alert('Login successful! You will be redirected to the home page.');</script>";
        header("refresh:1; url=HomePage");
        exit;
    } else {
        handle_failed_attempt();
        echo "<script>alert('Invalid password. Please try again.');</script>";
        header("Location: LoginHere?error=incorrect");
        exit;
    }
} else {
    handle_failed_attempt();
    echo "<script>alert('Invalid password. Please try again.');</script>";
    header("Location: LoginHere?error=incorrect");
    exit;
}

$stmt->close();
$conn->close();

// anti-brute force --- max login attempt (REQUIREMENT)
function handle_failed_attempt() {
    global $max_attempts;

    $_SESSION['attempt']++;
    
    if ($_SESSION['attempt'] >= $max_attempts) {
        $_SESSION['locked'] = true;
        $_SESSION['lock_time'] = time();
    }
}
?>