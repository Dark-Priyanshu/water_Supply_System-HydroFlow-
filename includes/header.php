<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../views/login.php");
    exit();
}
require_once __DIR__ . '/../config/config.php';


// DB connection (needed by sidebar for dynamic admin name)
if (!isset($conn)) {
    // Resolve config path regardless of which view includes header
    $config_path = __DIR__ . '/../config/database.php';
    if (!file_exists($config_path)) $config_path = __DIR__ . '/../../config/database.php';
    require_once $config_path;
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>HydroFlow | Precision Water Management</title>
    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_URL ?>assets/images/icon.png" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"/>
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Core & Component Styles (Vanilla CSS Migration) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css?v=1.1">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/components.css?v=1.1">


    <!-- Prevent flash: apply saved theme + scale BEFORE first paint -->
    <script>
        (function(){
            var t = localStorage.getItem('hydroTheme');
            if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
            var s = parseInt(localStorage.getItem('hydroScale') || '100', 10);
            if (s !== 100) document.documentElement.style.fontSize = (s / 100 * 16) + 'px';
        })();
    </script>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
