<?php
if (!isset($pdo)) {
    return; // Safety fallback
}

try {
    // Generate a secure, hashed representation of the visitor's IP address (GDPR compliant)
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $ip_hash = hash('sha256', $ip_address . date('Y-m-d'));
    
    $user_agent = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255);
    $today = date('Y-m-d');

    // Attempt to log the daily unique visitor. Ignores duplicates automatically.
    $stmt = $pdo->prepare("INSERT IGNORE INTO visitors (ip_hash, user_agent, visit_date) VALUES (?, ?, ?)");
    $stmt->execute([$ip_hash, $user_agent, $today]);
} catch (Exception $e) {
    error_log("Visitor logging failed: " . $e->getMessage());
}