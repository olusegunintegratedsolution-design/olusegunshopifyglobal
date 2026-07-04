<?php
// Prevent execution of this script directly
if (count(get_included_files()) == 1) exit("Direct access not permitted.");

// 1. Error Reporting Configuration (Production settings: hide errors, write to log)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error_log.txt');

// 2. Production Security Headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// 3. Secure Session Configurations
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    
    // Check if SSL is active on the server
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    if ($isSecure) {
        ini_set('session.cookie_secure', 1);
    }
    
    session_start();
}

// 4. Database Credentials
// Update these values inside /config/db.php
define('DB_HOST', 'b8xm9uevuuq51no00yp8-mysql.services.clever-cloud.com'); 
define('DB_USER', 'your_clever_cloud_username'); // Provided in Clever Cloud panel
define('DB_PASS', 'your_clever_cloud_password'); // Provided in Clever Cloud panel
define('DB_NAME', 'b8xm9uevuuq51no00yp8');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log("Database Connection Failed: " . $e->getMessage());
    die("Database Connection Error. Please verify your connection settings.");
}

// 5. Cross-Site Request Forgery (CSRF) Utilities
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

// 6. Security Sanitization Helpers
function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function check_auth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}