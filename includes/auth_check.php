<?php
/**
 * HydroFlow — Central Authentication & Security Guard
 *
 * Include this at the TOP of any protected view (via header.php).
 * Enforces:
 *   1. Login session check
 *   2. Session idle timeout (auto-logout)
 *   3. Path traversal / null-byte injection blocking
 *   4. Session fixation protection
 *   5. Re-authentication flag (set when reauth_interval elapses)
 */

// ── Load Config (defines BASE_URL, starts session, loads translations) ───────
if (!defined('BASE_URL')) {
    $config_levels = [
        __DIR__ . '/../config/config.php',
        __DIR__ . '/../../config/config.php',
        __DIR__ . '/../../../config/config.php',
    ];
    foreach ($config_levels as $path) {
        if (file_exists($path)) { require_once $path; break; }
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── 1. Authentication Check ───────────────────────────────────────────────────
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// ── 2. Session Idle Timeout ───────────────────────────────────────────────────
// Default 30 min if not configured
$timeout_seconds = $_SESSION['session_timeout'] ?? 1800;

if (isset($_SESSION['last_activity'])) {
    $idle_for = time() - $_SESSION['last_activity'];
    if ($idle_for > $timeout_seconds) {
        // Session expired — destroy and redirect
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['error_msg'] = 'Your session expired due to inactivity. Please log in again.';
        header("Location: " . BASE_URL . "login.php?reason=timeout");
        exit();
    }
}
$_SESSION['last_activity'] = time();

// ── 3. Re-Authentication Check ────────────────────────────────────────────────
// After reauth_interval seconds of continuous use, force a password re-check.
// reauth_interval defaults to 2x the session timeout (or 0 = disabled).
$reauth_interval = $_SESSION['reauth_interval'] ?? 0; // 0 = disabled
if ($reauth_interval > 0) {
    $last_reauth = $_SESSION['last_reauth_time'] ?? $_SESSION['login_time'] ?? time();
    if ((time() - $last_reauth) > $reauth_interval) {
        $_SESSION['reauth_required'] = true;
    }
}

// ── 4. Path Traversal & Null-Byte Protection ─────────────────────────────────
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
if (preg_match('/\.\.(\/|\\\\|%2f|%5c)/i', $request_uri) ||
    strpos($request_uri, "\0") !== false ||
    strpos($request_uri, '%00') !== false) {
    http_response_code(403);
    header("Location: " . BASE_URL . "views/404.php");
    exit();
}

// ── 5. Session Fixation Protection ───────────────────────────────────────────
if (!isset($_SESSION['_last_regen'])) {
    $_SESSION['_last_regen'] = time();
} elseif (time() - $_SESSION['_last_regen'] > 900) { // regenerate every 15 min
    session_regenerate_id(true);
    $_SESSION['_last_regen'] = time();
}

// ── Expose PHP config to JS (used by session-manager.js) ─────────────────────
$GLOBALS['_session_timeout_seconds'] = $timeout_seconds;
$GLOBALS['_reauth_required']         = !empty($_SESSION['reauth_required']);
?>
