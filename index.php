<?php
require_once __DIR__ . '/config/config.php';

// Automatically route the user from the project root based on authentication status
if (isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "views/dashboard.php");
} else {
    header("Location: " . BASE_URL . "views/landing.php");
}
exit();

