<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Single efficient query to fetch credentials and UI preferences
    $stmt = $conn->prepare("SELECT admin_id, password, role, language, theme, ui_scale, font_weight FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Prevent session fixation: regenerate ID on login
            session_regenerate_id(true);

            $_SESSION['admin_id']   = $user['admin_id'];
            $_SESSION['role']       = $user['role'];

            // Set user preferences with fallback
            $_SESSION['lang']        = $user['language'] ?? 'en';
            $_SESSION['theme']       = $user['theme'] ?? 'light';
            $_SESSION['ui_scale']    = $user['ui_scale'] ?? 100;
            $_SESSION['font_weight'] = $user['font_weight'] ?? 'normal';

            // ── Session Security Timestamps ─────────────────────────────────
            $now = time();
            $_SESSION['login_time']       = $now;
            $_SESSION['last_activity']    = $now;
            $_SESSION['last_reauth_time'] = $now;
            $_SESSION['_last_regen']      = $now;

            // ── Load saved session timeout from DB ──────────────────────────
            require_once __DIR__ . '/../models/Setting.php';
            $settingModel = new Setting($conn);

            $saved_timeout = $settingModel->get('session_timeout_minutes');
            $timeout_min   = ($saved_timeout && is_numeric($saved_timeout)) ? (int)$saved_timeout : 30;
            $_SESSION['session_timeout'] = $timeout_min * 60;

            $saved_reauth = $settingModel->get('reauth_interval_minutes');
            $reauth_min   = ($saved_reauth && is_numeric($saved_reauth)) ? (int)$saved_reauth : 0;
            $_SESSION['reauth_interval'] = $reauth_min * 60; // 0 = disabled

            // ── Redirect ────────────────────────────────────────────────────
            $redirect = $_SESSION['redirect_after_login'] ?? '';
            unset($_SESSION['redirect_after_login']);

            header("Location: " . BASE_URL . "dashboard.php");
            exit();
        } else {
            $_SESSION['error_msg'] = __('err_invalid_password');
            header("Location: " . BASE_URL . "login.php");
            exit();
        }
    } else {
        $_SESSION['error_msg'] = __('err_user_not_found');
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}
?>
