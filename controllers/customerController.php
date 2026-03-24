<?php
session_start();
require_once '../config/database.php';
require_once '../models/Customer.php';

$customerModel = new Customer($conn);

if (isset($_POST['add_customer'])) {
    $farmer_name = $_POST['farmer_name'];
    $mobile = $_POST['mobile'];
    $village = $_POST['village'];
    $farm_name = $_POST['farm_name'];
    $connection_no = $_POST['connection_no'];
    $pipe_size = $_POST['pipe_size'];

    if ($customerModel->createCustomer($farmer_name, $mobile, $village, $farm_name, $connection_no, $pipe_size)) {
        $_SESSION['success_msg'] = "Customer added successfully!";
        header("Location: ../views/customers/customer_list.php");
        exit();
    } else {
        $_SESSION['error_msg'] = "Error adding customer. Connection number might already exist.";
        header("Location: ../views/customers/add_customer.php");
        exit();
    }
}
?>
