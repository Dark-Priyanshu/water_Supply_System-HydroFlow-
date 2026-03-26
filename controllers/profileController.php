<?php
// Profile Update Controller
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$action   = $_POST['action'] ?? '';
$admin_id = $_SESSION['admin_id'];

// ── Get current admin info ────────────────────────────────────────
if ($action === 'get') {
    $stmt = $conn->prepare("SELECT username FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    echo json_encode(['success' => true, 'username' => $row['username'] ?? '']);
    exit;
}

// ── Update username ───────────────────────────────────────────────
if ($action === 'update_username') {
    $new_username = trim($_POST['username'] ?? '');

    if (strlen($new_username) < 3) {
        echo json_encode(['success' => false, 'message' => 'Username must be at least 3 characters long.']);
        exit;
    }
    // Check duplicate
    $chk = $conn->prepare("SELECT admin_id FROM admins WHERE username = ? AND admin_id != ?");
    $chk->bind_param("si", $new_username, $admin_id);
    $chk->execute();
    if ($chk->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'This username is already taken.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE admins SET username = ? WHERE admin_id = ?");
    $stmt->bind_param("si", $new_username, $admin_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Username updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $conn->error]);
    }
    exit;
}

// ── Update password ───────────────────────────────────────────────
if ($action === 'update_password') {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass     = $_POST['new_password']     ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (strlen($new_pass) < 6) {
        echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters long.']);
        exit;
    }
    if ($new_pass !== $confirm_pass) {
        echo json_encode(['success' => false, 'message' => 'New password and confirm password do not match.']);
        exit;
    }

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!password_verify($current_pass, $row['password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }

    $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
    $stmt2  = $conn->prepare("UPDATE admins SET password = ? WHERE admin_id = ?");
    $stmt2->bind_param("si", $hashed, $admin_id);
    if ($stmt2->execute()) {
        echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $conn->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
?>
