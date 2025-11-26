<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: dashboard.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail Manager - Email Administration System</title>
    <meta name="description" content="Email administration system for managing domains, email accounts, and aliases.">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <noscript>
        <div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
            <h1>Mail Manager</h1>
            <p>Redirecting to login page...</p>
            <p>If you are not redirected automatically, <a href="login.php">click here</a>.</p>
        </div>
    </noscript>
    
    <script>
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 1000);
    </script>
</body>
</html>