<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content='IP Address' name='description' />
    <meta content='IP Address' name='keyword' />
    <link rel="shortcut icon" href="favicon.ico">
    <title>IP Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
<?php
function getClientIP() {
    $ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($forwarded_ips[0]);
    }

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

$visitor_ip = getClientIP();
echo "Your IP address is: " . htmlspecialchars($visitor_ip, ENT_QUOTES, 'UTF-8');
?>
</body>
</html>
