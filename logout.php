<?php
require_once __DIR__ . '/config/config.php';

// Show appropriate message based on logout reason
$reason = $_GET['reason'] ?? 'manual';

session_unset();
session_destroy();

// Restart session to store the flash message
session_start();

switch ($reason) {
    case 'timeout':
        $_SESSION['error_msg'] = 'You were automatically logged out due to inactivity.';
        break;
    case 'manual':
    default:
        $_SESSION['error_msg'] = ''; // No message for normal logout
        break;
}

header("Location: " . BASE_URL . "login.php");
exit();
?>
