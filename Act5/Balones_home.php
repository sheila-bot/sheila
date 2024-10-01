<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.php">
</head>
<body>
    <?php
    // anti-bypass measure! (REQUIREMENT)
    if (!isset($_SESSION['user_id'])) {
        echo '<style>
            .swal2-close {
                color: green; /* Change this to your preferred color */
                font-size: 24px; /* Adjust size if needed */
            }
            .swal2-popup {
                font-family: "Courier New", monospace;
            }
            .swal2-title {
                font-size: 24px;
                color: green; /* Custom color for title */
            }
            .swal2-content {
                font-size: 18px;
                color: #333; /* Custom color for content */
            }
          </style>';    
        echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Uh-oh...",
                        text: "You can\'t go there."
                    }).then(function() {
                        window.location.href = "LoginHere";
                    });
                });
              </script>';
        exit;
    }
    ?>
    
<div class="container">
    <h1 class="welcome-text">Home Page</h1>
    <a href="logout.php">
        <img src="logout.png" alt="Logout" class="logout-image">
    </a>
</div>

</body>
</html>
