<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['add_payment'])) {
    $bill_id = $_POST['bill_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $method = $_POST['method'];

    $stmt = $conn->prepare("INSERT INTO payments (bill_id, amount, payment_date, method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $bill_id, $amount, $payment_date, $method);
    
    if ($stmt->execute()) {
        // Also update bill status to paid
        $conn->query("UPDATE bills SET status = 'paid' WHERE bill_id = $bill_id");
        $_SESSION['success_msg'] = __('msg_payment_success');
        header("Location: ../views/payments/payment_history.php");
        exit();
    } else {
        $_SESSION['error_msg'] = __('err_recording_payment');
        header("Location: ../views/payments/add_payment.php");
        exit();
    }
}
?>
