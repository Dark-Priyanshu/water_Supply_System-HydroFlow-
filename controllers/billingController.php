<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Bill.php';

$billModel = new Bill($conn);

if (isset($_POST['generate_bill'])) {
    $supply_id = (int)$_POST['supply_id'];
    $customer_id = (int)$_POST['customer_id'];
    $bill_date = date('Y-m-d');
    $total_hours = (double)$_POST['total_hours'];
    $rate = (double)$_POST['rate'];
    $total_amount = (double)$_POST['total_amount'];

    // Check if bill already exists
    $check = $billModel->checkBillExists($supply_id);
    if($check->num_rows > 0) {
        $existing_bill = $check->fetch_assoc();
        header("Location: " . BASE_URL . "views/billing/view_bill.php?id=" . $existing_bill['bill_id']);
        exit();
    }

    $bill_id = $billModel->generateBill($supply_id, $customer_id, $bill_date, $total_hours, $rate, $total_amount);
    
    if ($bill_id) {
        $_SESSION['success_msg'] = __('msg_bill_generated');
        header("Location: " . BASE_URL . "views/billing/view_bill.php?id=" . $bill_id);
        exit();
    } else {
        $_SESSION['error_msg'] = __('err_generating_bill');
        header("Location: " . BASE_URL . "views/supply/supply_history.php");
        exit();
    }
}

if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $status = $_GET['update_status'];
    $id = (int)$_GET['id'];
    if ($billModel->updateBillStatus($id, $status)) {
        header("Location: " . BASE_URL . "views/billing/bill_history.php");
        exit();
    }
}

