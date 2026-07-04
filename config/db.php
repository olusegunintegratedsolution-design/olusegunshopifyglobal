<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");

// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// HYBRID CONFIGURATION (Detects local XAMPP vs. Live Clever Cloud automatically)
if (getenv('MYSQL_ADDON_HOST')) {
    // Live Server Credentials (Injected automatically by Clever Cloud)
    define('DB_HOST', getenv('MYSQL_ADDON_HOST'));
    define('DB_USER', getenv('MYSQL_ADDON_USER'));
    define('DB_PASS', getenv('MYSQL_ADDON_PASSWORD'));
    define('DB_NAME', getenv('MYSQL_ADDON_DB'));
    define('DB_PORT', getenv('MYSQL_ADDON_PORT'));
} else {
    // Local XAMPP Credentials
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'olusegun_cms');
    define('DB_PORT', '3306');
}

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Database Connection Error. Please verify your connection settings.");
}

function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token): bool {
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function check_auth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}