<?php
session_start();
require 'config.php';

// form submit
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
    $password = $_POST['password']; 

    if (empty($login) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        // check if email or username
        $sql = "SELECT id, username, password FROM balones_act2 WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $stored_password);
            $stmt->fetch();

            if ($password === $stored_password) 
                {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['attempt'] = 0;
                header("Location: HomePage");
                exit;
            } else {
                $error_message = 'Incorrect username or password.';
                handle_failed_attempt();
            }
        } else {
            $error_message = 'Incorrect username or password.';
            handle_failed_attempt();
        }
        $stmt->close();
        $conn->close();
    }
}

function handle_failed_attempt() {
    global $max_attempts;

    $_SESSION['attempt']++;
    
    if ($_SESSION['attempt'] >= $max_attempts) {
        $_SESSION['locked'] = true;
        $_SESSION['lock_time'] = time();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.php">
</head>
<body>

<div class="login-container">
    <h2>Username</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form id="loginForm" action="login_process.php" method="POST">
        <input type="text" name="login" placeholder="Username" required>
     <h2>Password</h2>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" style="display: none;"></button>
        <img src="logimage.png" alt="Login" class="login-image" onclick="document.getElementById('loginForm').submit()">
    </form>
</div>


</body>
</html>
