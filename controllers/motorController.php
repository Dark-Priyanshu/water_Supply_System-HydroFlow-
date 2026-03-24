<?php
session_start();
require_once '../config/database.php';
require_once '../models/Motor.php';

$motorModel = new Motor($conn);

if (isset($_POST['add_motor'])) {
    $motor_name = $_POST['motor_name'];
    $horsepower = $_POST['horsepower'];
    $location = $_POST['location'];

    if ($motorModel->createMotor($motor_name, $horsepower, $location)) {
        $_SESSION['success_msg'] = "Motor added successfully!";
        header("Location: ../views/motors/motor_list.php");
        exit();
    } else {
        $_SESSION['error_msg'] = "Error adding motor.";
        header("Location: ../views/motors/add_motor.php");
        exit();
    }
}
?>
