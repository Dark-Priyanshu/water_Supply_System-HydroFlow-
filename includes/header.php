<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

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
    <title>HydroFlow | <?= __('precision_supply') ?></title>
    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_URL ?>assets/images/icon.png" type="image/png">
    <!-- Fonts -->
    <link href="<?= BASE_URL ?>assets/css/fonts.css" rel="stylesheet"/>
    <!-- Icons -->
    <link href="<?= BASE_URL ?>assets/css/material-symbols.css" rel="stylesheet"/>
    
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
        
        // Global DataTables Language Config
        const dtLanguage = {
            "search": "_INPUT_",
            "searchPlaceholder": "<?= __('search_records') ?>",
            "lengthMenu": "<?= __('dt_show') ?> _MENU_ <?= __('dt_entries') ?>",
            "info": "<?= __('dt_showing') ?> _START_ <?= __('dt_to') ?> _END_ <?= __('dt_of') ?> _TOTAL_ <?= __('dt_entries') ?>",
            "infoEmpty": "<?= __('dt_showing') ?> 0 <?= __('dt_to') ?> 0 <?= __('dt_of') ?> 0 <?= __('dt_entries') ?>",
            "infoFiltered": "(<?= __('dt_of') ?> _MAX_ <?= __('dt_entries') ?>)",
            "paginate": {
                "next": "<?= __('dt_next') ?>",
                "previous": "<?= __('dt_prev') ?>",
                "first": "<?= __('dt_first') ?>",
                "last": "<?= __('dt_last') ?>"
            },
            "emptyTable": `<div class="dt-empty-state">
                <span class="material-symbols-outlined dt-empty-icon">folder_off</span>
                <p style="font-size: 0.9375rem; font-weight: 600; color: var(--color-on-surface-variant);"><?= __('dt_no_records') ?></p>
                <p style="font-size: 0.75rem; color: var(--color-outline); margin-top: 0.25rem;"><?= __('dt_no_records_desc') ?></p>
            </div>`,
            "zeroRecords": `<div class="dt-empty-state">
                <span class="material-symbols-outlined dt-empty-icon">search_off</span>
                <p style="font-size: 0.9375rem; font-weight: 600; color: var(--color-on-surface-variant);"><?= __('dt_no_match') ?></p>
                <p style="font-size: 0.75rem; color: var(--color-outline); margin-top: 0.25rem;"><?= __('dt_no_match_desc') ?></p>
            </div>`
        };
    </script>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
