<?php
session_start();

$host = 'localhost';
$dbname = 'bank_ide_db';
$username = 'root';
$password = '';

// Vercel / Supabase integration (PostgreSQL)
$dbUrl = getenv('DATABASE_URL');

try {
    if ($dbUrl) {
        $dbopts = parse_url($dbUrl);
        $dsn = "pgsql:host=" . $dbopts["host"] . ";port=" . $dbopts["port"] . ";dbname=" . ltrim($dbopts["path"],'/');
        $pdo = new PDO($dsn, $dbopts["user"], $dbopts["pass"]);
    } else {
        // Fallback to local MySQL
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>
