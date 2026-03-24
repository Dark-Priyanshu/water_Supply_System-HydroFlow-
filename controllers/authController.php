<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT admin_id, password, role FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../views/dashboard.php");
            exit();
        } else {
            $_SESSION['error_msg'] = "Invalid password.";
            header("Location: ../views/login.php");
            exit();
        }
    } else {
        $_SESSION['error_msg'] = "User not found.";
        header("Location: ../views/login.php");
        exit();
    }
}
?>
