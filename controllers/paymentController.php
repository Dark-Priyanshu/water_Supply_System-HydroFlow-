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
        // Calculate total amount and paid amount
        $bill = $billModel->getBillById($bill_id);
        $total_amount = $bill['total_amount'];
        
        $paymentQuery = $conn->query("SELECT COALESCE(SUM(amount), 0) as paid_amount FROM payments WHERE bill_id = " . $bill_id);
        $paymentData = $paymentQuery->fetch_assoc();
        $paid_amount = $paymentData['paid_amount'];
        
        if ($paid_amount >= $total_amount) {
            $billModel->updateBillStatus($bill_id, 'paid');
        } else {
            $billModel->updateBillStatus($bill_id, 'partial');
        }
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
