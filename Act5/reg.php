<?php
session_start();
require 'config.php';

// Function to encrypt password
function encrypt_password($password) {
    $hashed_password = md5($password);
    return $hashed_password;
}

// Function to register user
function register_user($username, $password) {
    global $conn;

    // Check if username already exists
    $sql = "SELECT * FROM balones_act2 WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return 'Username already exists.';
    }

    // Encrypt password
    $hashed_password = encrypt_password($password);

    // Insert user into database
    $sql = "INSERT INTO balones_act2 (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return 'Registration successful.';
    } else {
        return 'Registration failed.';
    }
}

// Handle registration form submission
if (isset($_POST['submit'])){
    //Get form values
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    // reCAPTCHA secret key 
    $secretKey = '6LcLpFMqAAAAAGhheA-8l2F-yJU-qonD-1KOCSP-';

    //Get the reCAPTCHA response from the form
    $recaptchaResponse =  $_POST['g-recaptcha-response'];
    $remoteIp = $_SERVER['REMOTE_ADDR'];

    // Verify the reCAPTCHA response with Google's API
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse,
        'remoteip' => $remoteIp
    ];

    // Send a POST request to Google 
    $options = [
        'http' => [
            'header'=> "Content-type: application/x-www-form-urlencoded \r\n",
            'method'=>'POST',
            'content' => http_build_query($data),
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $responseKeys = json_decode($result, true);

    // Check if the reCAPTCHA was verified successfully
    if ($responseKeys["success"]) {
        // If reCAPTCHA is valid, insert the user into the database
        if (empty($username) || empty($password) || empty($confirm_password)) {
            $error_message = 'Please fill in all fields.';
        } elseif ($password !== $confirm_password) {
            $error_message = 'Passwords do not match.';
        } else {
            $result = register_user($username, $password);
            if ($result == 'Registration successful.') {
                echo '<script>alert("Registration successful! You will be redirected to the login page.")</script>';
                header("refresh:2; url=LoginHere");
                exit;
            } else {
                $error_message = $result;
            }
        }
    } else {
        // If reCAPTCHA failed, show an error message
        echo "<script>alert('reCAPTCHA verification failed. Please try again.')</script>";
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="style.php">
</head>
<body>

<div class="login-container">
    <h2>Register</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder=" Username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password" required><br><br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required><br><br>
        <div class="g-recaptcha" data-sitekey="6LcLpFMqAAAAAKhDdifiBvKWfhUfYD3WysDiG0II"></div>
        <button type="submit" name="submit">Register</button>
    </form>
</div>

</body>
</html>