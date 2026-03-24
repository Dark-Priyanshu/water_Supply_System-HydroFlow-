<?php
session_start();
require_once '../config/database.php';
require_once '../models/Bill.php';

$billModel = new Bill($conn);

if (isset($_POST['generate_bill'])) {
    $supply_id = $_POST['supply_id'];
    $customer_id = $_POST['customer_id'];
    $bill_date = date('Y-m-d');
    $total_hours = $_POST['total_hours'];
    $rate = $_POST['rate'];
    $total_amount = $_POST['total_amount'];

    // Check if bill already exists
    $check = $conn->query("SELECT bill_id FROM bills WHERE supply_id = $supply_id");
    if($check->num_rows > 0) {
        $existing_bill = $check->fetch_assoc();
        header("Location: ../views/billing/view_bill.php?id=" . $existing_bill['bill_id']);
        exit();
    }

    $bill_id = $billModel->generateBill($supply_id, $customer_id, $bill_date, $total_hours, $rate, $total_amount);
    
    if ($bill_id) {
        $_SESSION['success_msg'] = "Bill generated successfully!";
        header("Location: ../views/billing/view_bill.php?id=" . $bill_id);
        exit();
    } else {
        $_SESSION['error_msg'] = "Error generating bill.";
        header("Location: ../views/supply/supply_history.php");
        exit();
    }
}

if (isset($_GET['update_status']) && isset($_GET['id'])) {
    $status = $_GET['update_status'];
    $id = (int)$_GET['id'];
    $conn->query("UPDATE bills SET status = '$status' WHERE bill_id = $id");
    header("Location: ../views/billing/bill_history.php");
    exit();
}
?>
