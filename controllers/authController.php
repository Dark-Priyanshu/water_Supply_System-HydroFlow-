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
        
        // Match hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['role'] = $user['role'];
            
            // Set user preferences with fallback to en/light/100/normal
            $_SESSION['lang'] = $user['language'] ?? 'en';
            $_SESSION['theme'] = $user['theme'] ?? 'light';
            $_SESSION['ui_scale'] = $user['ui_scale'] ?? 100;
            $_SESSION['font_weight'] = $user['font_weight'] ?? 'normal';

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

