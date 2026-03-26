<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../views/login.php");
    exit();
}
// Robust dynamic base_url resolution instead of hardcoding
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $host . "/waterS/";

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
    <link rel="icon" href="<?= $base_url ?>assets/images/icon.png" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"/>
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
    <style>
        :root {
          --color-on-primary-container: #f3f7ff;
          --color-surface-container-highest: #e0e3e5;
          --color-on-error: #ffffff;
          --color-background: #f7f9fb;
          --color-on-tertiary-fixed-variant: #054e58;
          --color-on-tertiary-fixed: #001f24;
          --color-secondary-container: #aeeecb;
          --color-tertiary-fixed: #b1ecf8;
          --color-on-tertiary: #ffffff;
          --color-primary-fixed-dim: #94ccff;
          --color-surface-tint: #006399;
          --color-surface-bright: #f7f9fb;
          --color-on-secondary-container: #316e52;
          --color-tertiary-fixed-dim: #95d0dc;
          --color-on-tertiary-container: #e8fbff;
          --color-primary-container: #0077b6;
          --color-on-surface-variant: #404850;
          --color-on-surface: #191c1e;
          --color-outline: #707881;
          --color-error-container: #ffdad6;
          --color-inverse-primary: #94ccff;
          --color-secondary-fixed: #b1f0ce;
          --color-primary: #005d90;
          --color-surface-container-high: #e6e8ea;
          --color-on-secondary: #ffffff;
          --color-outline-variant: #bfc7d1;
          --color-on-secondary-fixed-variant: #0e5138;
          --color-on-background: #191c1e;
          --color-on-primary: #ffffff;
          --color-surface-container: #eceef0;
          --color-inverse-on-surface: #eff1f3;
          --color-surface-container-low: #f2f4f6;
          --color-secondary: #2c694e;
          --color-surface-container-lowest: #ffffff;
          --color-primary-fixed: #cde5ff;
          --color-surface-variant: #e0e3e5;
          --color-surface: #f7f9fb;
          --color-secondary-fixed-dim: #95d4b3;
          --color-tertiary-container: #3f7a85;
          --color-surface-dim: #d8dadc;
          --color-on-primary-fixed-variant: #004b74;
          --color-on-error-container: #93000a;
          --color-error: #ba1a1a;
          --color-tertiary: #23616b;
          --color-inverse-surface: #2d3133;
          --color-on-secondary-fixed: #002114;
          --color-on-primary-fixed: #001d32;
        }

        /* OS dark mode intentionally disabled — use Settings toggle only */

        /* ── Manual (localStorage) dark mode toggle ─────────────── */
        [data-theme="dark"] {
            --color-primary: #94ccff;
            --color-on-primary: #003351;
            --color-primary-container: #004b74;
            --color-on-primary-container: #cde5ff;
            --color-secondary: #95d4b3;
            --color-on-secondary: #003823;
            --color-secondary-container: #0e5138;
            --color-on-secondary-container: #b1f0ce;
            --color-tertiary: #95d0dc;
            --color-on-tertiary: #00363e;
            --color-tertiary-container: #034f59;
            --color-on-tertiary-container: #b1ecf8;
            --color-error: #ffb4ab;
            --color-on-error: #690005;
            --color-error-container: #93000a;
            --color-on-error-container: #ffdad6;
            --color-background: #191c1e;
            --color-on-background: #e0e3e5;
            --color-surface: #191c1e;
            --color-on-surface: #e0e3e5;
            --color-surface-variant: #404850;
            --color-on-surface-variant: #bfc7d1;
            --color-outline: #89929a;
            --color-outline-variant: #404850;
            --color-inverse-surface: #e0e3e5;
            --color-inverse-on-surface: #191c1e;
            --color-inverse-primary: #006399;
            --color-surface-dim: #111416;
            --color-surface-bright: #373a3c;
            --color-surface-container-lowest: #0c0f10;
            --color-surface-container-low: #191c1e;
            --color-surface-container: #1d2022;
            --color-surface-container-high: #272a2c;
            --color-surface-container-highest: #323537;
            --color-surface-tint: #94ccff;
        }
        [data-theme="dark"] .glass-sidebar {
            background: rgba(25, 28, 30, 0.85) !important;
        }
        [data-theme="dark"] .wave-bg {
            background: linear-gradient(180deg, rgba(149, 212, 179, 0) 0%, rgba(149, 212, 179, 0.05) 100%) !important;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
        }
        .wave-bg {
            background: linear-gradient(180deg, rgba(44, 105, 78, 0) 0%, rgba(44, 105, 78, 0.05) 100%);
        }
    </style>
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
