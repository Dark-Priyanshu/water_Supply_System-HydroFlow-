<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Customer.php';

$customerModel = new Customer($conn);

if (isset($_POST['add_customer'])) {
    $farmer_name = $_POST['farmer_name'];
    $mobile = $_POST['mobile'];
    $village = $_POST['village'];
    $farm_name = $_POST['farm_name'];
    $connection_no = $_POST['connection_no'];
    $pipe_size = $_POST['pipe_size'];

    if ($customerModel->createCustomer($farmer_name, $mobile, $village, $farm_name, $connection_no, $pipe_size)) {
        $_SESSION['success_msg'] = __('msg_customer_added');
        header("Location: " . BASE_URL . "views/customers/customer_list.php");
        exit();
    } else {
        $_SESSION['error_msg'] = __('err_adding_customer');
        header("Location: " . BASE_URL . "views/customers/add_customer.php");
        exit();
    }
}

if (isset($_POST['update_customer'])) {
    $customer_id = $_POST['customer_id'];
    $farmer_name = $_POST['farmer_name'];
    $mobile = $_POST['mobile'];
    $village = $_POST['village'];
    $farm_name = $_POST['farm_name'];
    $connection_no = $_POST['connection_no'];
    $pipe_size = $_POST['pipe_size'];

    if ($customerModel->updateCustomer($customer_id, $farmer_name, $mobile, $village, $farm_name, $connection_no, $pipe_size)) {
        $_SESSION['success_msg'] = __('msg_customer_updated');
        header("Location: " . BASE_URL . "views/customers/customer_list.php");
        exit();
    } else {
        $_SESSION['error_msg'] = __('err_updating_customer');
        header("Location: " . BASE_URL . "views/customers/edit_customer.php?id=" . $customer_id);
        exit();
    }
}

