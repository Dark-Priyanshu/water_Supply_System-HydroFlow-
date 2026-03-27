<?php
session_start();
// Automatically route the user from the project root based on authentication status
if (isset($_SESSION['admin_id'])) {
    header("Location: views/dashboard.php");
} else {
    header("Location: views/landing.php");
}
exit();
?>
