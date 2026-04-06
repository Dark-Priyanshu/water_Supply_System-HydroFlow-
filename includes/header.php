<?php
// ── Security Gate: Handles auth, path traversal, session fixation ──
require_once __DIR__ . '/auth_check.php';

// DB connection (needed by sidebar for dynamic admin name)
if (!isset($conn)) {
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
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Core & Component Styles (Vanilla CSS Migration) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css?v=1.1">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/components.css?v=1.1">


    <!-- Prevent flash: apply saved theme + scale + weight BEFORE first paint -->
    <script>
        (function(){
            // Prioritize PHP Session (Database) values if they exist, otherwise fallback to localStorage
            var t = "<?= $_SESSION['theme'] ?? '' ?>" || localStorage.getItem('hydroTheme') || 'light';
            var s = parseInt("<?= $_SESSION['ui_scale'] ?? '' ?>" || localStorage.getItem('hydroScale') || '100', 10);
            var w = "<?= $_SESSION['font_weight'] ?? '' ?>" || localStorage.getItem('hydroWeight') || 'normal';
            
            if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
            if (s !== 100) document.documentElement.style.fontSize = (s / 100 * 16) + 'px';
            if (w === 'bold') {
                document.documentElement.style.fontWeight = '600';
                // Add a small delay to body weight to ensure CSS is loaded
                window.addEventListener('DOMContentLoaded', function() {
                    document.body.style.fontWeight = '500';
                });
            }
            
            // Sync localStorage if different (handles first-time login on new device)
            if (t !== localStorage.getItem('hydroTheme')) localStorage.setItem('hydroTheme', t);
            if (String(s) !== localStorage.getItem('hydroScale')) localStorage.setItem('hydroScale', s);
            if (w !== localStorage.getItem('hydroWeight')) localStorage.setItem('hydroWeight', w);
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

        // ── Session Manager Config (read by session-manager.js) ──
        window.HYDRO_BASE_URL          = '<?= BASE_URL ?>';
        window.HYDRO_SESSION_SECS      = <?= (int)($GLOBALS['_session_timeout_seconds'] ?? 1800) ?>;
        window.HYDRO_REAUTH_REQUIRED   = <?= !empty($GLOBALS['_reauth_required']) ? 'true' : 'false' ?>;
    </script>
    <!-- Session Manager -->
    <script src="<?= BASE_URL ?>assets/js/session-manager.js" defer></script>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
