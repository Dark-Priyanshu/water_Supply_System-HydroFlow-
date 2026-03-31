<?php
require_once __DIR__ . '/../config/config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}
include '../includes/header.php';
include '../includes/sidebar.php';
?>


<!-- Settings Page -->
<div class="settings-page">

    <!-- Page Header -->
    <div class="settings-header">
        <div class="settings-hero">
            <div class="settings-hero-icon">
                <span class="material-symbols-outlined">settings</span>
            </div>
            <div>
                <h1 class="settings-title"><?= __('settings') ?></h1>
                <p class="settings-subtitle"><?= __('settings_subtitle') ?></p>
            </div>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="settings-grid">

        <!-- ╔══════════════════════════════╗
             ║   CARD 0 – PROFILE           ║
             ╚══════════════════════════════╝ -->
        <div class="settings-card profile-full-card" id="profileCard">
            <div class="settings-card-header" onclick="toggleSettingsCard('profileCard')" style="cursor: pointer;">
                <div class="settings-card-icon profile-icon">
                    <span class="material-symbols-outlined">manage_accounts</span>
                </div>
                <div style="flex: 1;">
                    <h2 class="settings-card-title"><?= __('profile') ?></h2>
                    <p class="settings-card-desc"><?= __('profile_desc') ?></p>
                </div>
                <span class="material-symbols-outlined settings-chevron">expand_more</span>
            </div>

            <div class="settings-card-body">
                <div class="profile-grid">

                    <!-- Avatar + Current Info -->
                    <div class="profile-avatar-section">
                        <div class="profile-avatar-wrap">
                            <div id="profileAvatar" class="avatar-initials profile-avatar-img" style="font-size: 2.5rem;" aria-label="Admin Avatar">AD</div>
                            <div class="profile-avatar-badge">
                                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">verified</span>
                            </div>
                        </div>
                        <div class="profile-info-text">
                            <p id="profileDisplayName" class="profile-name">Loading…</p>
                            <p class="profile-role"><?= __('sys_admin') ?></p>
                            <span class="profile-badge">Admin</span>
                        </div>
                    </div>

                    <!-- Forms Column -->
                    <div class="profile-forms">

                        <!-- Change Username -->
                        <div class="profile-form-section">
                            <p class="profile-form-title">
                                <span class="material-symbols-outlined">person_edit</span>
                                <?= __('username_change') ?>
                            </p>
                            <div class="profile-input-group">
                                <label for="newUsername"><?= __('new_username') ?></label>
                                <div class="profile-input-wrap">
                                    <span class="material-symbols-outlined profile-input-icon">person</span>
                                    <input type="text" id="newUsername" class="profile-input" placeholder="<?= __('placeholder_new_username') ?>" autocomplete="off">
                                </div>
                            </div>
                            <button class="profile-save-btn" onclick="updateUsername()">
                                <span class="material-symbols-outlined">save</span> <?= __('save_username') ?>
                            </button>
                        </div>

                        <div class="profile-section-divider"></div>

                        <!-- Change Password -->
                        <div class="profile-form-section">
                            <p class="profile-form-title">
                                <span class="material-symbols-outlined">lock_reset</span>
                                <?= __('password_change') ?>
                            </p>
                            <div class="profile-input-group">
                                <label for="currentPass"><?= __('current_password') ?></label>
                                <div class="profile-input-wrap">
                                    <span class="material-symbols-outlined profile-input-icon">lock</span>
                                    <input type="password" id="currentPass" class="profile-input" placeholder="<?= __('placeholder_current_password') ?>">
                                    <button type="button" class="pass-eye-btn" onclick="togglePasswordVisibility('currentPass', this)">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <div class="profile-input-group">
                                <label for="newPass"><?= __('new_password') ?></label>
                                <div class="profile-input-wrap">
                                    <span class="material-symbols-outlined profile-input-icon">lock_open</span>
                                    <input type="password" id="newPass" class="profile-input" placeholder="<?= __('placeholder_new_password') ?>">
                                    <button type="button" class="pass-eye-btn" onclick="togglePasswordVisibility('newPass', this)">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <div class="profile-input-group">
                                <label for="confirmPass"><?= __('confirm_new_password') ?></label>
                                <div class="profile-input-wrap">
                                    <span class="material-symbols-outlined profile-input-icon">lock_open</span>
                                    <input type="password" id="confirmPass" class="profile-input" placeholder="<?= __('placeholder_confirm_password') ?>">
                                    <button type="button" class="pass-eye-btn" onclick="togglePasswordVisibility('confirmPass', this)">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Password strength indicator -->
                            <div class="pass-strength-wrap" id="passStrengthWrap" style="display:none">
                                <div class="pass-strength-bar">
                                    <div class="pass-strength-fill" id="passStrengthFill"></div>
                                </div>
                                <p class="pass-strength-label" id="passStrengthLabel">Weak</p>
                            </div>
                            <button class="profile-save-btn profile-save-danger" onclick="updatePassword()">
                                <span class="material-symbols-outlined">key</span> <?= __('password_update') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ╔══════════════════════════════╗
             ║   CARD 1 – APPEARANCE        ║
             ╚══════════════════════════════╝ -->
        <div class="settings-card" id="appearanceCard">
            <div class="settings-card-header" onclick="toggleSettingsCard('appearanceCard')" style="cursor: pointer;">
                <div class="settings-card-icon appearance-icon">
                    <span class="material-symbols-outlined">palette</span>
                </div>
                <div style="flex: 1;">
                    <h2 class="settings-card-title"><?= __('appearance') ?></h2>
                    <p class="settings-card-desc"><?= __('appearance_desc') ?></p>
                </div>
                <span class="material-symbols-outlined settings-chevron">expand_more</span>
            </div>

            <div class="settings-card-body">
                <!-- Dark Mode -->
                <div class="settings-row">
                    <div class="settings-row-info">
                        <span class="material-symbols-outlined settings-row-icon" id="darkModeIcon">dark_mode</span>
                        <div>
                            <p class="settings-row-label"><?= __('dark_mode') ?></p>
                            <p class="settings-row-hint"><?= __('dark_mode_hint') ?></p>
                        </div>
                    </div>
                    <label class="toggle-switch" for="darkModeToggle" title="Toggle dark mode">
                        <input type="checkbox" id="darkModeToggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-divider"></div>

                <!-- Language Selection -->
                <div class="settings-row">
                    <div class="settings-row-info">
                        <span class="material-symbols-outlined settings-row-icon">language</span>
                        <div>
                            <p class="settings-row-label"><?= __('language') ?></p>
                            <p class="settings-row-hint"><?= __('lang_hint') ?></p>
                        </div>
                    </div>
                    <div class="flex" style="gap: 0.5rem;">
                        <button class="scale-preset-btn <?= $_SESSION['lang'] == 'en' ? 'active' : '' ?>" style="width: auto; padding: 0.5rem 1rem;" onclick="setLanguage('en')">English</button>
                        <button class="scale-preset-btn <?= $_SESSION['lang'] == 'hi' ? 'active' : '' ?>" style="width: auto; padding: 0.5rem 1rem;" onclick="setLanguage('hi')">हिन्दी</button>
                    </div>
                </div>

                <div class="settings-divider"></div>

                <!-- UI Scale -->
                <div class="settings-row settings-row-col">
                    <div class="settings-row-info" style="margin-bottom: 1rem;">
                        <span class="material-symbols-outlined settings-row-icon">text_fields</span>
                        <div>
                            <p class="settings-row-label"><?= __('ui_scale') ?></p>
                            <p class="settings-row-hint"><?= __('ui_scale_hint') ?></p>
                        </div>
                    </div>
                    <div class="scale-control">
                        <div class="scale-labels">
                            <span>80%</span>
                            <span id="scaleValueBadge" class="scale-badge">100%</span>
                            <span>130%</span>
                        </div>
                        <input type="range" id="uiScaleSlider" min="80" max="130" step="5" value="100" class="scale-slider">
                        <div class="scale-presets">
                            <button class="scale-preset-btn" style="flex:1" onclick="setScale(80)"><?= __('scale_80') ?></button>
                            <button class="scale-preset-btn active" style="flex:1" onclick="setScale(100)" id="scaleDefault"><?= __('scale_100') ?></button>
                            <button class="scale-preset-btn" style="flex:1" onclick="setScale(115)"><?= __('scale_115') ?></button>
                            <button class="scale-preset-btn" style="flex:1" onclick="setScale(130)"><?= __('scale_130') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ╔══════════════════════════════╗
             ║   CARD 2 – EXPORT TO EXCEL   ║
             ╚══════════════════════════════╝ -->
        <div class="settings-card" id="exportCard">
            <div class="settings-card-header" onclick="toggleSettingsCard('exportCard')" style="cursor: pointer;">
                <div class="settings-card-icon export-icon">
                    <span class="material-symbols-outlined">download</span>
                </div>
                <div style="flex: 1;">
                    <h2 class="settings-card-title"><?= __('export_data') ?></h2>
                    <p class="settings-card-desc"><?= __('export_data_desc') ?></p>
                </div>
                <span class="material-symbols-outlined settings-chevron">expand_more</span>
            </div>

            <div class="settings-card-body">
                <div class="export-grid">

                    <div class="export-item">
                        <div class="export-item-info">
                            <span class="material-symbols-outlined export-item-icon customer-col">agriculture</span>
                            <div>
                                <p class="export-item-title"><?= __('customers') ?></p>
                                <p class="export-item-hint"><?= __('hint_customers') ?></p>
                            </div>
                        </div>
                        <button class="export-btn" onclick="exportToExcel('customers','HydroFlow_Customers')">
                            <span class="material-symbols-outlined">download</span> <?= __('export') ?>
                        </button>
                    </div>

                    <div class="export-item">
                        <div class="export-item-info">
                            <span class="material-symbols-outlined export-item-icon motor-col">water_pump</span>
                            <div>
                                <p class="export-item-title"><?= __('motors') ?></p>
                                <p class="export-item-hint"><?= __('hint_motors') ?></p>
                            </div>
                        </div>
                        <button class="export-btn" onclick="exportToExcel('motors','HydroFlow_Motors')">
                            <span class="material-symbols-outlined">download</span> <?= __('export') ?>
                        </button>
                    </div>

                    <div class="export-item">
                        <div class="export-item-info">
                            <span class="material-symbols-outlined export-item-icon supply-col">waves</span>
                            <div>
                                <p class="export-item-title"><?= __('supply_record') ?></p>
                                <p class="export-item-hint"><?= __('hint_supply') ?></p>
                            </div>
                        </div>
                        <button class="export-btn" onclick="exportToExcel('supply','HydroFlow_Supply')">
                            <span class="material-symbols-outlined">download</span> <?= __('export') ?>
                        </button>
                    </div>

                    <div class="export-item">
                        <div class="export-item-info">
                            <span class="material-symbols-outlined export-item-icon bill-col">receipt_long</span>
                            <div>
                                <p class="export-item-title"><?= __('billing_invoices') ?></p>
                                <p class="export-item-hint"><?= __('hint_bills') ?></p>
                            </div>
                        </div>
                        <button class="export-btn" onclick="exportToExcel('bills','HydroFlow_Bills')">
                            <span class="material-symbols-outlined">download</span> <?= __('export') ?>
                        </button>
                    </div>

                    <div class="export-item">
                        <div class="export-item-info">
                            <span class="material-symbols-outlined export-item-icon payment-col">payments</span>
                            <div>
                                <p class="export-item-title"><?= __('payments') ?></p>
                                <p class="export-item-hint"><?= __('hint_payments') ?></p>
                            </div>
                        </div>
                        <button class="export-btn" onclick="exportToExcel('payments','HydroFlow_Payments')">
                            <span class="material-symbols-outlined">download</span> <?= __('export') ?>
                        </button>
                    </div>

                    <div class="export-item export-all-item">
                        <div class="export-item-info">
                            <span class="material-symbols-outlined export-item-icon all-col">folder_zip</span>
                            <div>
                                <p class="export-item-title"><?= __('export_all') ?></p>
                                <p class="export-item-hint"><?= __('hint_export_all') ?></p>
                            </div>
                        </div>
                        <button class="export-btn export-all-btn" onclick="exportAllSheets()">
                            <span class="material-symbols-outlined">download</span> <?= __('export_all') ?>
                        </button>
                    </div>

                </div>

                <!-- Export Progress -->
                <div id="exportProgress" class="export-progress hidden">
                    <div class="export-progress-bar">
                        <div class="export-progress-fill" id="exportProgressFill"></div>
                    </div>
                    <p id="exportProgressLabel" class="export-progress-label">Preparing data…</p>
                </div>
            </div>
        </div>

        <!-- ╔══════════════════════════════╗
             ║   CARD 3 – IMPORT            ║
             ╚══════════════════════════════╝ -->
        <div class="settings-card" id="importCard">
            <div class="settings-card-header" onclick="toggleSettingsCard('importCard')" style="cursor: pointer;">
                <div class="settings-card-icon import-icon">
                    <span class="material-symbols-outlined">upload</span>
                </div>
                <div style="flex: 1;">
                    <h2 class="settings-card-title"><?= __('import_data') ?></h2>
                    <p class="settings-card-desc"><?= __('import_data_desc') ?></p>
                </div>
                <span class="material-symbols-outlined settings-chevron">expand_more</span>
            </div>

            <div class="settings-card-body">
                <!-- Import Target -->
                <div class="import-controls">
                    <div class="import-target-wrap">
                        <label class="settings-row-label" for="importTableSelect"><?= __('import_into') ?>:</label>
                        <select id="importTableSelect" class="import-select">
                            <option value="customers"><?= __('customers') ?></option>
                            <option value="motors"><?= __('motors') ?></option>
                        </select>
                    </div>

                    <!-- Drop Zone -->
                    <div id="dropZone" class="drop-zone">
                        <div class="drop-zone-content" id="dropZoneContent">
                            <span class="material-symbols-outlined drop-zone-icon">upload_file</span>
                            <p class="drop-zone-label"><?= __('drop_file') ?></p>
                            <p class="drop-zone-hint"><?= __('click_browse') ?></p>
                            <p class="drop-zone-types"><?= __('supported_formats') ?></p>
                        </div>
                        <input type="file" id="excelImportFile" accept=".xlsx,.xls" style="display:none;">
                    </div>

                    <!-- Template Download -->
                    <div class="import-template-row">
                        <span class="material-symbols-outlined" style="font-size:18px;color:var(--color-primary)">info</span>
                        <span class="import-template-text"><?= __('need_template') ?> </span>
                        <button class="link-btn" onclick="downloadTemplate()"><?= __('download_sample') ?></button>
                    </div>
                </div>

                <!-- Preview Table -->
                <div id="importPreviewSection" class="import-preview hidden">
                    <div class="import-preview-header">
                        <p class="import-preview-title">
                            <span class="material-symbols-outlined">table_view</span>
                            <?= __('preview') ?> — <span id="importRowCount">0</span> <?= __('rows_found') ?>
                        </p>
                        <button class="btn-ghost-sm" onclick="clearImport()">
                            <span class="material-symbols-outlined">close</span> <?= __('clear') ?>
                        </button>
                    </div>
                    <div class="import-preview-table-wrap">
                        <table id="importPreviewTable" class="import-preview-table">
                            <thead id="importPreviewHead"></thead>
                            <tbody id="importPreviewBody"></tbody>
                        </table>
                    </div>
                    <div class="import-actions">
                        <p class="import-warning">
                            <span class="material-symbols-outlined">warning</span>
                            <?= __('import_warning') ?>
                        </p>
                        <button class="btn-import" onclick="confirmImport()" id="confirmImportBtn">
                            <span class="material-symbols-outlined">check_circle</span> <?= __('confirm_import') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- end settings-grid -->
</div><!-- end settings-page -->

<!-- ─── Toast Notification ─────────────────────────────────────── -->
<div id="settingsToast" class="settings-toast hidden" role="alert">
    <span class="material-symbols-outlined" id="toastIcon">check_circle</span>
    <span id="toastMsg"><?= __('toast_done') ?></span>
</div>

<!-- ─── STYLES (Settings-specific) ───────────────────────────────── -->
<style>
/* ─── Page Layout ───────────────────────────────────────────────── */
.settings-page { padding: 0 0 3rem; }
.settings-header { margin-bottom: 2rem; }
.settings-hero {
    display: flex; align-items: center; gap: 1.25rem;
    padding: 1.75rem 2rem;
    background: linear-gradient(135deg, var(--color-primary) 0%, #0077b6 100%);
    border-radius: 1.5rem;
    color: white;
    box-shadow: 0 8px 32px rgba(0,93,144,0.18);
}
.settings-hero-icon {
    width: 56px; height: 56px;
    background: rgba(255,255,255,0.18);
    border-radius: 1rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.settings-hero-icon .material-symbols-outlined { font-size: 2rem; color: #fff; }
.settings-title { font-size: 1.5rem; font-weight: 700; font-family: var(--font-headline, sans-serif); color: #fff; margin:0; }
.settings-subtitle { font-size: 0.85rem; color: rgba(255,255,255,0.75); margin: 0.25rem 0 0; }

/* ─── Grid ───────────────────────────────────────────────────────── */
.settings-grid { display: grid; gap: 1.5rem; }
@media (min-width: 1200px) {
    .settings-grid {
        grid-template-columns: minmax(340px, 1fr) minmax(340px, 1.2fr);
    }
    #importCard { grid-column: 1 / -1; }
    .profile-full-card { grid-column: 1 / -1; }
}

/* ─── Profile Card ───────────────────────────────────────────────── */
.profile-icon { background: rgba(106,60,148,0.1); }
.profile-icon .material-symbols-outlined { color: #6a3c94; }
.profile-grid {
    display: grid;
    gap: 2rem;
    align-items: start;
}
@media (min-width: 720px) {
    .profile-grid { grid-template-columns: 220px 1fr; }
}
.profile-avatar-section {
    display: flex; flex-direction: column; align-items: center;
    text-align: center; gap: 1rem;
    padding: 1.25rem 1rem;
    background: var(--color-surface-container-low, #f2f4f6);
    border-radius: 1.25rem;
}
.profile-avatar-wrap { position: relative; display: inline-block; }
.profile-avatar-img {
    width: 96px; height: 96px; border-radius: 50%;
    border: 3px solid var(--color-primary);
    box-shadow: 0 4px 20px rgba(0,93,144,0.2);
    object-fit: cover;
}
.profile-avatar-badge {
    position: absolute; bottom: 2px; right: 2px;
    width: 24px; height: 24px; border-radius: 50%;
    background: var(--color-primary); color: white;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid var(--color-surface-container-lowest, #fff);
}
.profile-avatar-badge .material-symbols-outlined { font-size: 0.8rem; color: white; }
.profile-name { font-size: 1.05rem; font-weight: 700; color: var(--color-on-surface); margin: 0; }
.profile-role { font-size: 0.78rem; color: var(--color-on-surface-variant); margin: 0.2rem 0 0; }
.profile-badge {
    display: inline-block; margin-top: 0.5rem;
    padding: 0.2rem 0.75rem;
    background: rgba(106,60,148,0.12);
    color: #6a3c94;
    border-radius: 999px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.05em;
}
.profile-forms { display: flex; flex-direction: column; gap: 1.25rem; }
.profile-form-section { display: flex; flex-direction: column; gap: 0.75rem; }
.profile-form-title {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.88rem; font-weight: 700; color: var(--color-on-surface);
    margin: 0;
}
.profile-form-title .material-symbols-outlined { font-size: 1.1rem; color: var(--color-primary); }
.profile-input-group { display: flex; flex-direction: column; gap: 0.3rem; }
.profile-input-group label { font-size: 0.76rem; font-weight: 600; color: var(--color-on-surface-variant); }
.profile-input-wrap { position: relative; display: flex; align-items: center; }
.profile-input-icon {
    position: absolute; left: 0.875rem;
    font-size: 1.1rem; color: var(--color-outline);
    pointer-events: none;
}
.profile-input {
    width: 100%;
    padding: 0.6rem 2.5rem 0.6rem 2.75rem;
    border: 1.5px solid var(--color-outline-variant, #bfc7d1);
    border-radius: 0.75rem;
    background: var(--color-surface-container-lowest, #fff);
    color: var(--color-on-surface);
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.profile-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(0,93,144,0.12);
}
.pass-eye-btn {
    position: absolute; right: 0.75rem;
    background: none; border: none; cursor: pointer;
    color: var(--color-outline); padding: 0;
    display: flex; align-items: center;
}
.pass-eye-btn .material-symbols-outlined { font-size: 1.1rem; }
.profile-section-divider {
    height: 1px; background: var(--color-outline-variant, #e0e3e5);
    margin: 0.25rem 0;
}
.profile-save-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.55rem 1.25rem;
    background: var(--color-primary, #005d90);
    color: white;
    border: none; border-radius: 0.75rem;
    font-size: 0.82rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s;
    align-self: flex-start;
}
.profile-save-btn:hover { opacity: 0.88; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,93,144,0.22); }
.profile-save-btn .material-symbols-outlined { font-size: 1rem; }
.profile-save-danger { background: linear-gradient(135deg, #7c3c8e 0%, #6a3c94 100%); }
.profile-save-danger:hover { box-shadow: 0 4px 12px rgba(106,60,148,0.3); }

/* Password strength */
.pass-strength-wrap { margin-top: 0.25rem; }
.pass-strength-bar {
    height: 5px; background: var(--color-outline-variant,#e0e3e5);
    border-radius: 999px; overflow: hidden; margin-bottom: 0.3rem;
}
.pass-strength-fill {
    height: 100%; border-radius: 999px;
    width: 0%; transition: width 0.35s, background 0.35s;
}
.pass-strength-label { font-size: 0.72rem; font-weight: 600; margin: 0; }

/* ─── Card ───────────────────────────────────────────────────────── */
.settings-card {
    background: var(--color-surface-container-lowest, #fff);
    border: 1px solid var(--color-outline-variant, #e0e3e5);
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s;
}
.settings-card:hover { box-shadow: 0 6px 24px rgba(0,93,144,0.08); }
.settings-card-header {
    display: flex; align-items: center; gap: 1rem;
    padding: 1.5rem 1.75rem 1.25rem;
    border-bottom: 1px solid var(--color-outline-variant, #e0e3e5);
}
.settings-card-icon {
    width: 48px; height: 48px; border-radius: 0.875rem;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.settings-card-icon .material-symbols-outlined { font-size: 1.5rem; }
.appearance-icon { background: rgba(0,93,144,0.1); color: var(--color-primary); }
.appearance-icon .material-symbols-outlined { color: var(--color-primary); }
.export-icon { background: rgba(44,105,78,0.1); }
.export-icon .material-symbols-outlined { color: #2c694e; }
.import-icon { background: rgba(35,97,107,0.1); }
.import-icon .material-symbols-outlined { color: #23616b; }
.settings-card-title { font-size: 1.1rem; font-weight: 700; color: var(--color-on-surface, #191c1e); margin:0; }
.settings-card-desc { font-size: 0.8rem; color: var(--color-on-surface-variant, #404850); margin: 0.2rem 0 0; }
.settings-card-body { padding: 1.5rem 1.75rem; }

/* ─── Settings Row ───────────────────────────────────────────────── */
.settings-row {
    display: flex; align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.5rem 0;
}
.settings-row-col { flex-direction: column; align-items: flex-start; }
.settings-row-info { display: flex; align-items: center; gap: 0.875rem; }
.settings-row-icon { font-size: 1.35rem; color: var(--color-primary); }
.settings-row-label { font-size: 0.925rem; font-weight: 600; color: var(--color-on-surface); margin: 0; }
.settings-row-hint { font-size: 0.775rem; color: var(--color-on-surface-variant); margin: 0.15rem 0 0; }
.settings-divider {
    height: 1px;
    background: var(--color-outline-variant, #e0e3e5);
    margin: 1.25rem 0;
}

/* ─── Toggle Switch ───────────────────────────────────────────────── */
.toggle-switch {
    position: relative; display: inline-flex;
    width: 52px; height: 28px;
    cursor: pointer; flex-shrink: 0;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0;
    background: var(--color-outline-variant, #bfc7d1);
    border-radius: 999px;
    transition: background 0.3s;
}
.toggle-slider::before {
    content: ''; position: absolute;
    width: 22px; height: 22px;
    left: 3px; top: 3px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.18);
}
.toggle-switch input:checked + .toggle-slider::before {
    transform: translateX(24px);
    box-shadow: 0 2px 8px rgba(0,93,144,0.35);
}

/* ─── Collapsible Cards ────────────────────────────────────────── */
.settings-chevron {
    transition: transform 0.3s ease;
    color: var(--color-on-surface-variant);
}
.settings-card.collapsed .settings-chevron {
    transform: rotate(-90deg);
}
.settings-card-body {
    transition: max-height 0.4s ease, opacity 0.3s ease, padding 0.4s ease;
    max-height: 2000px; /* Large enough for content */
    overflow: hidden;
    opacity: 1;
}
.settings-card.collapsed .settings-card-body {
    max-height: 0;
    opacity: 0;
    padding-top: 0;
    padding-bottom: 0;
    pointer-events: none;
}
.settings-card-header {
    user-select: none;
}

/* ─── Scale Slider ───────────────────────────────────────────────── */
.scale-control { width: 100%; margin-top: 0.75rem; }
.scale-labels {
    display: flex; justify-content: space-between;
    font-size: 0.75rem; color: var(--color-on-surface-variant);
    margin-bottom: 0.375rem;
}
.scale-badge {
    background: var(--color-primary);
    color: white;
    padding: 0.1rem 0.5rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.75rem;
}
.scale-slider {
    width: 100%;
    -webkit-appearance: none;
    height: 6px;
    border-radius: 999px;
    background: var(--color-outline-variant, #bfc7d1);
    outline: none;
    cursor: pointer;
    transition: background 0.2s;
}
.scale-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 22px; height: 22px;
    border-radius: 50%;
    background: var(--color-primary);
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,93,144,0.3);
    transition: transform 0.15s;
}
.scale-slider::-webkit-slider-thumb:hover { transform: scale(1.15); }
.scale-slider::-moz-range-thumb {
    width: 22px; height: 22px;
    border: none; border-radius: 50%;
    background: var(--color-primary);
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,93,144,0.3);
}
.scale-presets {
    display: flex; gap: 0.5rem; margin-top: 0.875rem; flex-wrap: wrap;
}
.scale-preset-btn {
    padding: 0.3rem 0.85rem;
    border-radius: 999px;
    border: 1.5px solid var(--color-outline-variant, #bfc7d1);
    background: transparent;
    font-size: 0.78rem;
    font-weight: 500;
    color: var(--color-on-surface-variant);
    cursor: pointer;
    transition: all 0.2s;
}
.scale-preset-btn:hover, .scale-preset-btn.active {
    background: var(--color-primary);
    border-color: var(--color-primary);
    color: white;
}

/* ─── Export Grid ─────────────────────────────────────────────────── */
.export-grid { display: flex; flex-direction: column; gap: 0.875rem; }
.export-item {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 0.875rem 1.125rem;
    background: var(--color-surface-container-low, #f2f4f6);
    border-radius: 1rem;
    gap: 1rem;
    transition: background 0.2s;
}
.export-item:hover { background: var(--color-surface-container, #eceef0); }
.export-item-info { display: flex; align-items: center; gap: 0.875rem; }
.export-item-icon { font-size: 1.4rem; }
.customer-col { color: #2c694e; }
.motor-col { color: #005d90; }
.supply-col { color: #23616b; }
.bill-col { color: #7d5700; }
.payment-col { color: #6b4fa8; }
.all-col { color: #555; }
.export-item-title { font-size: 0.9rem; font-weight: 600; color: var(--color-on-surface); margin: 0; }
.export-item-hint { font-size: 0.75rem; color: var(--color-on-surface-variant); margin: 0.1rem 0 0; }
.export-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.45rem 1rem;
    background: var(--color-primary, #005d90);
    color: white;
    border: none;
    border-radius: 0.625rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
    flex-shrink: 0;
}
.export-btn:hover { opacity: 0.88; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,93,144,0.25); }
.export-btn:active { transform: translateY(0); }
.export-btn .material-symbols-outlined { font-size: 1rem; }
.export-all-item { 
    background: linear-gradient(135deg, rgba(0,93,144,0.06) 0%, rgba(44,105,78,0.06) 100%);
    border: 1.5px dashed var(--color-primary, #005d90);
}
.export-all-btn {
    background: linear-gradient(135deg, var(--color-primary) 0%, #2c694e 100%);
}

/* Export Progress */
.export-progress { margin-top: 1.25rem; }
.export-progress.hidden { display: none; }
.export-progress-bar {
    height: 6px; background: var(--color-outline-variant,#e0e3e5);
    border-radius: 999px; overflow: hidden; margin-bottom: 0.5rem;
}
.export-progress-fill {
    height: 100%;
    background: var(--color-primary);
    border-radius: 999px;
    width: 0%;
    transition: width 0.4s ease;
}
.export-progress-label {
    font-size: 0.78rem; color: var(--color-on-surface-variant);
    text-align: center; margin: 0;
}

/* ─── Import ─────────────────────────────────────────────────────── */
.import-controls { display: flex; flex-direction: column; gap: 1rem; }
.import-target-wrap { display: flex; align-items: center; gap: 0.875rem; }
.import-select {
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    border: 1.5px solid var(--color-outline-variant, #bfc7d1);
    background: var(--color-surface-container-low, #f2f4f6);
    color: var(--color-on-surface);
    font-size: 0.9rem;
    cursor: pointer;
    outline: none;
    flex:1; max-width: 220px;
}
.import-select:focus { border-color: var(--color-primary); }
.drop-zone {
    border: 2px dashed var(--color-outline-variant, #bfc7d1);
    border-radius: 1.25rem;
    padding: 2.5rem 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s;
    background: var(--color-surface-container-low, #f2f4f6);
}
.drop-zone:hover, .drop-zone.dragover {
    border-color: var(--color-primary);
    background: rgba(0,93,144,0.04);
}
.drop-zone-icon { font-size: 2.5rem; color: var(--color-primary); margin-bottom: 0.75rem; }
.drop-zone-label { font-size: 1rem; font-weight: 600; color: var(--color-on-surface); margin: 0; }
.drop-zone-hint { font-size: 0.82rem; color: var(--color-on-surface-variant); margin: 0.25rem 0 0; }
.drop-zone-types { font-size: 0.72rem; color: var(--color-outline); margin: 0.5rem 0 0; }
.import-template-row {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.82rem; color: var(--color-on-surface-variant);
}
.import-template-text { color: var(--color-on-surface-variant); }
.link-btn {
    background: none; border: none;
    color: var(--color-primary);
    font-weight: 600; font-size: 0.82rem;
    cursor: pointer; text-decoration: underline;
    padding: 0;
}

/* Preview Table */
.import-preview { margin-top: 1.5rem; }
.import-preview.hidden { display: none; }
.import-preview-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 0.875rem;
}
.import-preview-title {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.9rem; font-weight: 600; color: var(--color-on-surface); margin: 0;
}
.import-preview-title .material-symbols-outlined { font-size: 1.1rem; color: var(--color-primary); }
.btn-ghost-sm {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.3rem 0.7rem;
    border-radius: 0.5rem;
    border: 1px solid var(--color-outline-variant);
    background: transparent;
    font-size: 0.78rem; color: var(--color-on-surface-variant);
    cursor: pointer; transition: all 0.2s;
}
.btn-ghost-sm:hover { background: var(--color-surface-container); }
.btn-ghost-sm .material-symbols-outlined { font-size: 1rem; }
.import-preview-table-wrap { overflow-x: auto; border-radius: 0.875rem; border: 1px solid var(--color-outline-variant); }
.import-preview-table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
.import-preview-table th {
    padding: 0.65rem 0.9rem;
    background: var(--color-surface-container, #eceef0);
    color: var(--color-on-surface-variant);
    font-weight: 600; text-align: left;
    border-bottom: 1px solid var(--color-outline-variant);
    white-space: nowrap;
}
.import-preview-table td {
    padding: 0.55rem 0.9rem;
    color: var(--color-on-surface);
    border-bottom: 1px solid var(--color-outline-variant);
    white-space: nowrap;
}
.import-preview-table tr:last-child td { border-bottom: none; }
.import-preview-table tr:hover td { background: var(--color-surface-container-low, #f2f4f6); }
.import-actions {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 1rem; gap: 1rem; flex-wrap: wrap;
}
.import-warning {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; color: #7d5700; margin: 0;
}
.import-warning .material-symbols-outlined { font-size: 1rem; color: #7d5700; }
.btn-import {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.6rem 1.4rem;
    background: linear-gradient(135deg, #2c694e 0%, #1a4a35 100%);
    color: white;
    border: none;
    border-radius: 0.75rem;
    font-size: 0.875rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s;
}
.btn-import:hover { opacity: 0.88; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,105,78,0.3); }
.btn-import .material-symbols-outlined { font-size: 1rem; }

/* ─── Toast ────────────────────────────────────────────────────────── */
.settings-toast {
    position: fixed; bottom: 2rem; right: 2rem; z-index: 9999;
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    border-radius: 1rem;
    font-size: 0.9rem; font-weight: 600;
    background: #1a2b1f;
    color: white;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    animation: slideInToast 0.35s ease;
    max-width: 340px;
}
.settings-toast.hidden { display: none; }
.settings-toast.toast-error { background: #4a1010; }
.settings-toast .material-symbols-outlined { font-size: 1.2rem; }
@keyframes slideInToast {
    from { opacity: 0; transform: translateY(20px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

<!-- ─── SCRIPTS ────────────────────────────────────────────────── -->
<!-- SheetJS -->
<script src="<?= BASE_URL ?>assets/vendor/xlsx.full.min.js"></script>
<script>
const i18n = {
    darkModeEnabled: '<?= __('dark_mode_enabled') ?>',
    lightModeEnabled: '<?= __('light_mode_enabled') ?>',
    uiScaleSet: '<?= __('ui_scale_set') ?>',
    usernameEmpty: '<?= __('username_empty') ?>',
    fillAllFields: '<?= __('fill_all_fields') ?>',
    passMismatch: '<?= __('pass_mismatch') ?>',
    fetchingData: '<?= __('fetching_data') ?>',
    buildingExcel: '<?= __('building_excel') ?>',
    savingFile: '<?= __('saving_file') ?>',
    exportSuccess: '<?= __('export_success') ?>',
    exportFailed: '<?= __('export_failed') ?>',
    prepWorkbook: '<?= __('prep_workbook') ?>',
    noDataExport: '<?= __('no_data_export') ?>',
    writingExcel: '<?= __('writing_excel') ?>',
    sheetsExported: '<?= __('sheets_exported') ?>',
    importing: '<?= __('importing') ?>',
    invalidFile: '<?= __('invalid_file') ?>',
    noDataFile: '<?= __('no_data_file') ?>',
    moreRows: '<?= __('more_rows') ?>',
    templateDownloaded: '<?= __('template_downloaded') ?>',
    strength: [
        '<?= __('vec_weak') ?>',
        '<?= __('weak') ?>',
        '<?= __('fair') ?>',
        '<?= __('strong') ?>',
        '<?= __('very_strong') ?>'
    ]
};

(function() {
    'use strict';

    /* ══════════════════════════════════════════════════════
       0.  COLLAPSIBLE SECTIONS
    ══════════════════════════════════════════════════════ */
    window.toggleSettingsCard = function(cardId) {
        const card = document.getElementById(cardId);
        if (card) {
            card.classList.toggle('collapsed');
            // Save state for some persistence
            const states = JSON.parse(localStorage.getItem('settings_collapse_states') || '{}');
            states[cardId] = card.classList.contains('collapsed');
            localStorage.setItem('settings_collapse_states', JSON.stringify(states));
        }
    };

    window.setLanguage = function(lang) {
        const url = new URL(window.location.href);
        url.searchParams.set('lang', lang);
        window.location.href = url.href;
    };

    // Restore collapse states
    (function restoreCollapseStates() {
        const states = JSON.parse(localStorage.getItem('settings_collapse_states') || '{}');
        // If first visit, collapse all except Profile and Appearance
        if (Object.keys(states).length === 0) {
            states['exportCard'] = true;
            states['importCard'] = true;
            localStorage.setItem('settings_collapse_states', JSON.stringify(states));
        }
        for (const [id, isCollapsed] of Object.entries(states)) {
            const card = document.getElementById(id);
            if (card && isCollapsed) card.classList.add('collapsed');
        }
    })();

    /* ══════════════════════════════════════════════════════
       1.  APPEARANCE — Dark Mode & UI Scale
    ══════════════════════════════════════════════════════ */
    const darkToggle   = document.getElementById('darkModeToggle');
    const scaleSlider  = document.getElementById('uiScaleSlider');
    const scaleBadge   = document.getElementById('scaleValueBadge');
    const darkModeIcon = document.getElementById('darkModeIcon');

    // ── Restore saved state ──────────────────────────────
    const savedTheme = localStorage.getItem('hydroTheme') || 'light';
    const savedScale = parseInt(localStorage.getItem('hydroScale') || '100', 10);

    applyTheme(savedTheme);
    applyScale(savedScale);
    darkToggle.checked  = savedTheme === 'dark';
    scaleSlider.value   = savedScale;
    updateScaleBadge(savedScale);
    updatePresetButtons(savedScale);

    // ── Dark Mode Toggle ─────────────────────────────────
    darkToggle.addEventListener('change', function () {
        const theme = this.checked ? 'dark' : 'light';
        localStorage.setItem('hydroTheme', theme);
        applyTheme(theme);
        showToast(theme === 'dark' ? i18n.darkModeEnabled : i18n.lightModeEnabled);
    });

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        if (darkModeIcon) {
            darkModeIcon.textContent = theme === 'dark' ? 'light_mode' : 'dark_mode';
        }
    }

    // ── UI Scale Slider ──────────────────────────────────
    scaleSlider.addEventListener('input', function () {
        const val = parseInt(this.value, 10);
        applyScale(val);
        updateScaleBadge(val);
        updatePresetButtons(val);
    });
    scaleSlider.addEventListener('change', function () {
        localStorage.setItem('hydroScale', this.value);
    });

    function applyScale(val) {
        document.documentElement.style.fontSize = (val / 100 * 16) + 'px';
    }
    function updateScaleBadge(val) {
        scaleBadge.textContent = val + '%';
    }
    function updatePresetButtons(val) {
        document.querySelectorAll('.scale-control .scale-preset-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const presets = { 80: 0, 100: 1, 115: 2, 130: 3 };
        const buttons = document.querySelectorAll('.scale-control .scale-preset-btn');
        if (presets[val] !== undefined && buttons[presets[val]]) {
            buttons[presets[val]].classList.add('active');
        }
    }

    window.setScale = function(val) {
        scaleSlider.value = val;
        applyScale(val);
        updateScaleBadge(val);
        updatePresetButtons(val);
        localStorage.setItem('hydroScale', val);
        showToast(i18n.uiScaleSet + ' ' + val + '%');
    };

    /* ══════════════════════════════════════════════════════
       2.  EXPORT TO EXCEL
    ══════════════════════════════════════════════════════ */
    const baseUrl = '<?= BASE_URL ?>';

    /* ══════════════════════════════════════════════════════
       0.  PROFILE — Load + Update
    ══════════════════════════════════════════════════════ */
    // Load current username into form + display
    (async function loadProfile() {
        try {
            const fd = new FormData();
            fd.append('action', 'get');
            const res  = await fetch(baseUrl + 'controllers/profileController.php', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                document.getElementById('profileDisplayName').textContent = data.username;
                document.getElementById('newUsername').value = data.username;
                document.getElementById('profileAvatar').textContent = data.username.substring(0, 2).toUpperCase();
            }
        } catch(e) { /* silent */ }
    })();

    window.updateUsername = async function() {
        const newUser = document.getElementById('newUsername').value.trim();
        if (!newUser) { showToast(i18n.usernameEmpty, true); return; }
        const fd = new FormData();
        fd.append('action', 'update_username');
        fd.append('username', newUser);
        try {
            const res  = await fetch(baseUrl + 'controllers/profileController.php', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                document.getElementById('profileDisplayName').textContent = newUser;
                document.getElementById('profileAvatar').textContent = newUser.substring(0, 2).toUpperCase();
                showToast(data.message);
            } else {
                showToast(data.message, true);
            }
        } catch(e) { showToast('Error: ' + e.message, true); }
    };

    window.updatePassword = async function() {
        const cur  = document.getElementById('currentPass').value;
        const nw   = document.getElementById('newPass').value;
        const conf = document.getElementById('confirmPass').value;
        if (!cur || !nw || !conf) { showToast(i18n.fillAllFields, true); return; }
        if (nw !== conf) { showToast(i18n.passMismatch, true); return; }
        const fd = new FormData();
        fd.append('action', 'update_password');
        fd.append('current_password', cur);
        fd.append('new_password', nw);
        fd.append('confirm_password', conf);
        try {
            const res  = await fetch(baseUrl + 'controllers/profileController.php', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                document.getElementById('currentPass').value = '';
                document.getElementById('newPass').value     = '';
                document.getElementById('confirmPass').value = '';
                document.getElementById('passStrengthWrap').style.display = 'none';
                showToast(data.message);
            } else {
                showToast(data.message, true);
            }
        } catch(e) { showToast('Error: ' + e.message, true); }
    };

    // Password visibility toggle
    window.togglePasswordVisibility = function(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('.material-symbols-outlined');
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    };

    // Password strength meter
    document.getElementById('newPass')?.addEventListener('input', function() {
        const val  = this.value;
        const wrap = document.getElementById('passStrengthWrap');
        const fill = document.getElementById('passStrengthFill');
        const lbl  = document.getElementById('passStrengthLabel');
        if (!val) { wrap.style.display = 'none'; return; }
        wrap.style.display = 'block';
        let strength = 0;
        if (val.length >= 6)  strength++;
        if (val.length >= 10) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;
        const levels = [
            { pct: '20%', color: '#ba1a1a', label: 'Very Weak' },
            { pct: '40%', color: '#e65100', label: 'Weak' },
            { pct: '60%', color: '#f9a825', label: 'Fair' },
            { pct: '80%', color: '#2e7d32', label: 'Strong' },
            { pct: '100%', color: '#1b5e20', label: 'Very Strong' },
        ];
        const lvl = levels[Math.min(strength, 4)];
        fill.style.width      = lvl.pct;
        fill.style.background = lvl.color;
        lbl.textContent       = i18n.strength[Math.min(strength, 4)];
        lbl.style.color       = lvl.color;
    });

    window.setLanguage = function(lang) {
        fetch(baseUrl + 'controllers/update_language.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'lang=' + lang
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.reload();
            }
        });
    };

    window.exportToExcel = async function(table, filename) {
        showProgress(true, 'Fetching ' + table + ' data…');
        try {
            const res  = await fetch(baseUrl + 'controllers/exportController.php?table=' + table);
            const data = await res.json();
            if (data.error) throw new Error(data.error);
            updateProgress(60, 'Building Excel file…');
            const ws  = XLSX.utils.json_to_sheet(data);
            styleWorksheet(ws, data);
            const wb  = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, table.charAt(0).toUpperCase() + table.slice(1));
            updateProgress(90, i18n.savingFile);
            XLSX.writeFile(wb, filename + '_' + today() + '.xlsx');
            updateProgress(100, 'Done!');
            showToast(filename + ' ' + i18n.exportSuccess);
        } catch(e) {
            showToast(i18n.exportFailed + ' ' + e.message, true);
        } finally {
            setTimeout(() => showProgress(false), 1500);
        }
    };

    let _exportAllRunning = false;
    window.exportAllSheets = async function() {
        if (_exportAllRunning) return;
        _exportAllRunning = true;
        const allExportBtns = document.querySelectorAll('.export-btn');
        allExportBtns.forEach(function(b) { b.disabled = true; b.style.opacity = '0.55'; });
        const tables = ['customers','motors','supply','bills','payments'];
        const names  = ['Customers','Motors','Supply','Bills','Payments'];
        showProgress(true, 'Preparing combined workbook...');
        const wb = XLSX.utils.book_new();
        let sheetsAdded = 0;
        try {
            for (let i = 0; i < tables.length; i++) {
                updateProgress(Math.round(((i + 1) / tables.length) * 85), 'Fetching ' + names[i] + ' (' + (i+1) + '/' + tables.length + ')...');
                const res  = await fetch(baseUrl + 'controllers/exportController.php?table=' + tables[i]);
                const data = await res.json();
                if (data.error || !Array.isArray(data)) continue;
                const ws = XLSX.utils.json_to_sheet(data.length ? data : [{}]);
                styleWorksheet(ws, data);
                XLSX.utils.book_append_sheet(wb, ws, names[i]);
                sheetsAdded++;
            }
            if (sheetsAdded === 0) { showToast('No data found to export.', true); return; }
            updateProgress(95, 'Writing Excel file...');
            XLSX.writeFile(wb, 'HydroFlow_Complete_Export_' + today() + '.xlsx');
            updateProgress(100, 'Done!');
            showToast(sheetsAdded + ' sheets exported in one workbook!');
        } catch(err) {
            showToast('Export failed: ' + err.message, true);
        } finally {
            _exportAllRunning = false;
            allExportBtns.forEach(function(b) { b.disabled = false; b.style.opacity = ''; });
            setTimeout(function() { showProgress(false); }, 1500);
        }
    };

    function styleWorksheet(ws, data) {
        if (!data || data.length === 0) return;
        const cols = Object.keys(data[0]);
        ws['!cols'] = cols.map(() => ({ wch: 20 }));
    }

    function today() {
        return new Date().toISOString().slice(0,10);
    }

    function showProgress(show, label) {
        const el = document.getElementById('exportProgress');
        if (show) {
            el.classList.remove('hidden');
            document.getElementById('exportProgressFill').style.width = '10%';
            document.getElementById('exportProgressLabel').textContent = label || '';
        } else {
            el.classList.add('hidden');
        }
    }
    function updateProgress(pct, label) {
        document.getElementById('exportProgressFill').style.width = pct + '%';
        document.getElementById('exportProgressLabel').textContent = label || '';
    }

    /* ══════════════════════════════════════════════════════
       3.  IMPORT FROM EXCEL
    ══════════════════════════════════════════════════════ */
    const dropZone    = document.getElementById('dropZone');
    const fileInput   = document.getElementById('excelImportFile');
    let   parsedRows  = [];

    // Click to open file dialog
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag-and-drop
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    fileInput.addEventListener('change', function() {
        if (this.files[0]) handleFile(this.files[0]);
    });

    function handleFile(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        if (!['xlsx','xls'].includes(ext)) {
            showToast(i18n.invalidFile, true);
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const wb  = XLSX.read(e.target.result, { type: 'binary' });
            const ws  = wb.Sheets[wb.SheetNames[0]];
            parsedRows = XLSX.utils.sheet_to_json(ws, { defval: '' });
            renderPreview(parsedRows);
        };
        reader.readAsBinaryString(file);
    }

    function renderPreview(rows) {
        if (!rows || rows.length === 0) {
            showToast(i18n.noDataFile, true);
            return;
        }
        const headers = Object.keys(rows[0]);
        const thead   = document.getElementById('importPreviewHead');
        const tbody   = document.getElementById('importPreviewBody');
        document.getElementById('importRowCount').textContent = rows.length;

        thead.innerHTML = '<tr>' + headers.map(h => `<th>${h}</th>`).join('') + '</tr>';
        // Show max 10 rows in preview
        const preview = rows.slice(0, 10);
        tbody.innerHTML = preview.map(row =>
            '<tr>' + headers.map(h => `<td>${row[h] ?? ''}</td>`).join('') + '</tr>'
        ).join('');
        if (rows.length > 10) {
            tbody.innerHTML += `<tr><td colspan="${headers.length}" style="text-align:center;color:var(--color-on-surface-variant);font-style:italic;padding:0.5rem">... and ${rows.length - 10} ${i18n.moreRows}</td></tr>`;
        }
        document.getElementById('importPreviewSection').classList.remove('hidden');
    }

    window.clearImport = function() {
        parsedRows = [];
        fileInput.value = '';
        document.getElementById('importPreviewSection').classList.add('hidden');
    };

    window.confirmImport = async function() {
        if (!parsedRows.length) return;
        const table = document.getElementById('importTableSelect').value;
        const btn   = document.getElementById('confirmImportBtn');
        btn.disabled = true;
        btn.innerHTML = `<span class="material-symbols-outlined" style="animation:spin 1s linear infinite">autorenew</span> ${i18n.importing}`;

        try {
            const fd = new FormData();
            fd.append('table', table);
            fd.append('data', JSON.stringify(parsedRows));
            const res  = await fetch(baseUrl + 'controllers/importController.php', { method: 'POST', body: fd });
            const resp = await res.json();
            if (resp.success) {
                showToast(resp.message);
                clearImport();
            } else {
                showToast(resp.message, true);
            }
        } catch(e) {
            showToast(i18n.exportFailed + ' ' + e.message, true);
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<span class="material-symbols-outlined">check_circle</span> <?= __('confirm_import') ?>`;
        }
    };

    /* ══════════════════════════════════════════════════════
       4.  DOWNLOAD TEMPLATE
    ══════════════════════════════════════════════════════ */
    window.downloadTemplate = function() {
        const table    = document.getElementById('importTableSelect').value;
        const templates = {
            customers: [{ 'Farmer Name':'', 'Mobile':'', 'Village':'', 'Farm Name':'', 'Connection No':'', 'Pipe Size':'' }],
            motors:    [{ 'Motor Name':'', 'Location':'', 'Status':'Active' }]
        };
        const data = templates[table] || templates['customers'];
        const ws   = XLSX.utils.json_to_sheet(data);
        ws['!cols'] = Object.keys(data[0]).map(() => ({ wch: 20 }));
        const wb   = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Template');
        XLSX.writeFile(wb, 'HydroFlow_' + table + '_template.xlsx');
        showToast('📄 ' + i18n.templateDownloaded);
    };

    /* ══════════════════════════════════════════════════════
       5.  TOAST UTILITY
    ══════════════════════════════════════════════════════ */
    let toastTimer;
    function showToast(msg, isError) {
        const el   = document.getElementById('settingsToast');
        const icon = document.getElementById('toastIcon');
        const txt  = document.getElementById('toastMsg');
        txt.textContent = msg;
        icon.textContent = isError ? 'error' : 'check_circle';
        el.classList.remove('hidden', 'toast-error');
        if (isError) el.classList.add('toast-error');
        el.style.animation = 'none';
        void el.offsetWidth;
        el.style.animation = 'slideInToast 0.35s ease';
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => el.classList.add('hidden'), 4000);
    }
})();
</script>
<style>
@keyframes spin { from{transform:rotate(0)} to{transform:rotate(360deg)} }
</style>

<?php include '../includes/footer.php'; ?>
