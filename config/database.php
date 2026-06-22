<?php
// session_start(); // Disabled for Vercel Serverless compatibility

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

// Disable caching for Vercel Edge Cache to prevent redirect loops
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Helper functions (Vercel Serverless Compatible)
function isLoggedIn() {
    return isset($_COOKIE['user_id']);
}

function isAdmin() {
    return isset($_COOKIE['role']) && $_COOKIE['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>
