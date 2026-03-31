<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_POST['add_payment'])) {
    require_once __DIR__ . '/../models/Bill.php';
    $billModel = new Bill($conn);
    $bill_id = (int)$_POST['bill_id'];
    $amount = (double)$_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $method = $_POST['method'];

    $stmt = $conn->prepare("INSERT INTO payments (bill_id, amount, payment_date, method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $bill_id, $amount, $payment_date, $method);
    
    if ($stmt->execute()) {
        // Also update bill status to paid
        $billModel->updateBillStatus($bill_id, 'paid');
        $_SESSION['success_msg'] = __('msg_payment_success');
        header("Location: " . BASE_URL . "views/payments/payment_history.php");
        exit();
    } else {
        $_SESSION['error_msg'] = __('err_recording_payment');
        header("Location: " . BASE_URL . "views/payments/add_payment.php");
        exit();
    }
}
?>
