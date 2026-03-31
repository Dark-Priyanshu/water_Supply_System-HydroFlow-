<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../models/Motor.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if (isset($_GET['id'])) {
    $motor_id = (int)$_GET['id'];
    $motorModel = new Motor($conn);
    
    if ($motorModel->deleteMotor($motor_id)) {
        $_SESSION['success_msg'] = __('msg_motor_deleted');
    } else {
        $_SESSION['error_msg'] = __('err_updating_status'); // Generic error
    }
}

header("Location: motor_list.php");
exit();
