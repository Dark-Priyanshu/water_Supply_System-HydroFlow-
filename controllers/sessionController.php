<?php
/**
 * HydroFlow — Session Controller (AJAX)
 * Handles: keep-alive, re-auth verify, session-timeout config
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

// All actions require a logged-in session
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'reason' => 'unauthenticated']);
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── 1. Keep-Alive: update last_activity to prevent timeout ──────────────────
if ($action === 'keep_alive') {
    $_SESSION['last_activity'] = time();
    echo json_encode(['success' => true, 'ts' => time()]);
    exit();
}

// ── 2. Verify Re-Auth Password ───────────────────────────────────────────────
if ($action === 'verify_reauth') {
    $password_input = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT password FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row && password_verify($password_input, $row['password'])) {
        // Reset both activity timer and reauth timer
        $_SESSION['last_activity']     = time();
        $_SESSION['last_reauth_time']  = time();
        unset($_SESSION['reauth_required']);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'reason' => 'wrong_password']);
    }
    exit();
}

// ── 3. Save Session Timeout Preference ──────────────────────────────────────
if ($action === 'save_timeout') {
    $timeout = (int)($_POST['timeout_minutes'] ?? 30);
    $allowed = [15, 30, 60, 120, 240];
    if (!in_array($timeout, $allowed)) $timeout = 30;

    // Persist to DB (settings table)
    require_once __DIR__ . '/../models/Setting.php';
    $settingModel = new Setting($conn);
    $settingModel->set('session_timeout_minutes', $timeout);

    // Also update live session
    $_SESSION['session_timeout'] = $timeout * 60;

    echo json_encode(['success' => true, 'timeout_minutes' => $timeout]);
    exit();
}

// ── 4. Get current session status (remaining time etc.) ─────────────────────
if ($action === 'get_status') {
    $timeout    = $_SESSION['session_timeout'] ?? 1800;
    $last       = $_SESSION['last_activity']   ?? time();
    $remaining  = max(0, ($last + $timeout) - time());

    echo json_encode([
        'success'            => true,
        'remaining_seconds'  => $remaining,
        'timeout_seconds'    => $timeout,
        'reauth_required'    => !empty($_SESSION['reauth_required']),
    ]);
    exit();
}

echo json_encode(['success' => false, 'reason' => 'unknown_action']);
exit();
?>
