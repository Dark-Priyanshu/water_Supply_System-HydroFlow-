<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT admin_id, password, role FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $stmt = $conn->prepare("SELECT language, theme, ui_scale, font_weight FROM admins WHERE admin_id = ?");
            $stmt->bind_param("i", $user['admin_id']);
            $stmt->execute();
            $settings = $stmt->get_result()->fetch_assoc();

            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['lang'] = $settings['language'] ?? 'en';
            $_SESSION['theme'] = $settings['theme'] ?? 'light';
            $_SESSION['ui_scale'] = $settings['ui_scale'] ?? 100;
            $_SESSION['font_weight'] = $settings['font_weight'] ?? 'normal';

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

