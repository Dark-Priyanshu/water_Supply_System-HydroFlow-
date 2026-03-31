<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Motor.php';

$motorModel = new Motor($conn);

if (isset($_POST['add_motor'])) {
    $motor_name = $_POST['motor_name'];
    $horsepower = $_POST['horsepower'];
    $location = $_POST['location'];

    if ($motorModel->createMotor($motor_name, $horsepower, $location)) {
        $_SESSION['success_msg'] = __('msg_motor_added');
        header("Location: " . BASE_URL . "views/motors/motor_list.php");
        exit();
    } else {
        $_SESSION['error_msg'] = __('err_adding_motor');
        header("Location: " . BASE_URL . "views/motors/add_motor.php");
        exit();
    }
}

if (isset($_POST['update_motor'])) {
    $motor_id = $_POST['motor_id'];
    $motor_name = $_POST['motor_name'];
    $horsepower = $_POST['horsepower'];
    $location = $_POST['location'];

    if ($motorModel->updateMotor($motor_id, $motor_name, $horsepower, $location)) {
        $_SESSION['success_msg'] = __('msg_motor_updated');
        header("Location: " . BASE_URL . "views/motors/motor_list.php");
        exit();
    } else {
        $_SESSION['error_msg'] = __('err_updating_motor');
        header("Location: " . BASE_URL . "views/motors/edit_motor.php?id=" . $motor_id);
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'toggle_status' && isset($_GET['id'])) {
    $motor_id = $_GET['id'];
    if ($motorModel->toggleStatus($motor_id)) {
        $_SESSION['success_msg'] = __('msg_motor_status');
    } else {
        $_SESSION['error_msg'] = __('err_updating_status');
    }
    header("Location: " . BASE_URL . "views/motors/motor_list.php");
    exit();
}

