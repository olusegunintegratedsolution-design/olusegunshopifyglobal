<?php
require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

$type = $_POST['form_type'] ?? '';

// 1. Process Unified Inquiry Contact Form
if ($type === 'contact') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $whatsapp = trim($_POST['whatsapp_num'] ?? '');
    $brand = trim($_POST['brand_name'] ?? '');
    $service = trim($_POST['service'] ?? 'Shopify Store Development');
    $budget = trim($_POST['budget'] ?? 'Under $1,000');
    $deadline = trim($_POST['deadline'] ?? 'Standard (3-4 Weeks)');
    $desc = trim($_POST['description'] ?? '');
    $goals = trim($_POST['goals'] ?? '');

    if (!$fullName || !$email || !$whatsapp) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields with valid details."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (message_type, full_name, email, whatsapp_number, business_name, service_needed, budget_range, project_deadline, project_description, goals_to_achieve) VALUES ('contact', ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fullName, $email, $whatsapp, $brand, $service, $budget, $deadline, $desc, $goals]);

        echo json_encode([
            "status" => "success", 
            "message" => "Inquiry recorded.",
            "redirect_payload" => [
                "full_name" => $fullName,
                "email" => $email,
                "whatsapp" => $whatsapp,
                "brand" => $brand,
                "service" => $service,
                "budget" => $budget,
                "deadline" => $deadline,
                "desc" => $desc,
                "goals" => $goals
            ]
        ]);
        exit;
    } catch (Exception $e) {
        error_log("Contact insertion failed: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Database write error."]);
        exit;
    }
}

// 2. Process SEO Audit Request Form
if ($type === 'seo_audit') {
    $url = filter_var(trim($_POST['url'] ?? ''), FILTER_VALIDATE_URL);
    $business = trim($_POST['business_name'] ?? '');
    $industry = trim($_POST['industry'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

    if (!$url || !$business || !$email) {
        echo json_encode(["status" => "error", "message" => "Please enter a valid URL and contact email."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (message_type, full_name, email, business_name, target_url, industry) VALUES ('seo_audit', 'SEO Request', ?, ?, ?, ?)");
        $stmt->execute([$email, $business, $url, $industry]);
        echo json_encode(["status" => "success", "message" => "SEO Audit requested successfully."]);
        exit;
    } catch (Exception $e) {
        error_log("SEO Audit insertion failed: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Database write error."]);
        exit;
    }
}

// 3. Process Store Conversion Audit Form
if ($type === 'store_audit') {
    $url = filter_var(trim($_POST['store_url'] ?? ''), FILTER_VALIDATE_URL);
    $platform = trim($_POST['platform'] ?? 'Shopify');
    $revenue = trim($_POST['revenue'] ?? '0-5k');
    $challenge = trim($_POST['challenge'] ?? '');

    if (!$url || !$challenge) {
        echo json_encode(["status" => "error", "message" => "Please enter a valid URL and describe your main challenge."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (message_type, full_name, email, target_url, platform_used, monthly_revenue, main_challenge) VALUES ('store_audit', 'Conversion Audit Request', 'audit@customer.com', ?, ?, ?, ?)");
        $stmt->execute([$url, $platform, $revenue, $challenge]);
        echo json_encode(["status" => "success", "message" => "Store Conversion Audit requested successfully."]);
        exit;
    } catch (Exception $e) {
        error_log("Store Audit insertion failed: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Database write error."]);
        exit;
    }
}

echo json_encode(["status" => "error", "message" => "Invalid submission endpoint."]);