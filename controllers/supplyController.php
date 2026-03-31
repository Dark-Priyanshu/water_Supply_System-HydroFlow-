<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Supply.php';

$supplyModel = new Supply($conn);

if (isset($_POST['record_supply'])) {
    $customer_id = $_POST['customer_id'];
    $motor_id = $_POST['motor_id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $rate = $_POST['rate']; // Default 125 from frontend but user can change

    // Calculate hours in PHP as fallback
    $start = @strtotime($start_time);
    $end = @strtotime($end_time);
    
    // If end time is next day
    if ($end < $start) {
        $end += 86400; // Add 24 hours
    }
    
    $total_hours = ($end - $start) / 3600;
    $total_amount = $total_hours * $rate;

    if ($supplyModel->recordSupply($customer_id, $motor_id, $date, $start_time, $end_time, $total_hours, $rate, $total_amount)) {
        $_SESSION['success_msg'] = "Water supply recorded successfully!";
        header("Location: " . BASE_URL . "views/supply/supply_history.php");
        exit();
    } else {
        $_SESSION['error_msg'] = "Error recording supply.";
        header("Location: " . BASE_URL . "views/supply/add_supply.php");
        exit();
    }
}

