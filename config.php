<?php
session_start();
$host = 'localhost';
$dbname = 'mailserver';
$username = 'root';
$password = 'mypassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

function readSecretFile() {
    $users = [];
    if (file_exists('secret.txt')) {
        $lines = file('secret.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode(',', $line);
            if (count($parts) == 2) {
                $users[trim($parts[0])] = trim($parts[1]);
            }
        }
    }
    return $users;
}

function authenticate($username, $password) {
    $users = readSecretFile();
    $hashedPassword = md5($password);
    
    return isset($users[$username]) && $users[$username] === $hashedPassword;
}

function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function generatePasswordHash($password) {
    $salt = bin2hex(random_bytes(8));
    return '{SHA512-CRYPT}' . crypt($password, '$6$' . $salt);
}
?>