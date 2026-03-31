<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../models/Customer.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if (isset($_GET['id'])) {
    $customer_id = (int) $_GET['id'];
    $customerModel = new Customer($conn);

    if ($customerModel->deleteCustomer($customer_id)) {
        $_SESSION['success_msg'] = __('msg_customer_deleted');
    } else {
        $_SESSION['error_msg'] = __('err_updating_customer');
    }
}

header("Location: customer_list.php");
exit();
