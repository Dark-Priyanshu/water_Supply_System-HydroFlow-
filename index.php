<?php
require_once __DIR__ . '/config/config.php';


if (isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "views/dashboard.php");
} else {
    header("Location: " . BASE_URL . "views/landing.php");
}
exit();

