<?php
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>" data-theme="<?= htmlspecialchars($_COOKIE['hydroTheme'] ?? 'light') ?>">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>404 - <?= __('page_not_found') ?? 'Page Not Found' ?> | HydroFlow</title>
<link rel="icon" href="<?= BASE_URL ?>assets/images/icon.png">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap">
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: var(--font-body, 'Inter', sans-serif);
        background: var(--color-surface, #f7f9fb);
        color: var(--color-on-surface, #191c1e);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ── Top Nav ─────────────────────────────── */
    .nav-404 {
        position: fixed; top: 0; width: 100%; z-index: 50;
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(191,199,209,0.4);
        padding: 1rem 2rem;
        display: flex; justify-content: space-between; align-items: center;
    }
    [data-theme="dark"] .nav-404 { background: rgba(29,31,33,0.75); }

    .nav-logo {
        font-size: 1.4rem; font-weight: 800;
        color: var(--color-primary, #005d90);
        font-family: var(--font-headline, 'Inter', sans-serif);
        display: flex; align-items: center; gap: 0.6rem; text-decoration: none;
    }
    .nav-logo img { width: 2rem; height: 2rem; object-fit: contain; }
    .nav-back-btn {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.5rem 1.25rem;
        background: var(--color-surface-container, #eceef0);
        color: var(--color-on-surface, #191c1e);
        border: 1px solid var(--color-outline-variant, #bfc7d1);
        border-radius: 0.75rem; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: all 0.2s;
    }
    .nav-back-btn:hover { background: var(--color-surface-container-high, #e6e8ea); transform: translateY(-1px); }
    .nav-back-btn .material-symbols-outlined { font-size: 1.1rem; }

    /* ── Main Layout ─────────────────────────── */
    main {
        flex: 1; display: flex; align-items: center; justify-content: center;
        padding: 7rem 2rem 4rem;
    }
    .error-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        max-width: 72rem;
        width: 100%;
        align-items: center;
    }
    @media (max-width: 900px) {
        .error-grid { grid-template-columns: 1fr; text-align: center; }
        .illustration-col { order: -1; }
    }

    /* ── Illustration Column ────────────────── */
    .illustration-col { position: relative; }
    .illustration-wrap {
        position: relative;
        width: 100%; max-width: 28rem; margin: 0 auto;
        aspect-ratio: 1;
    }
    .illustration-glow {
        position: absolute; inset: 0;
        background: rgba(0,93,144,0.06);
        border-radius: 50%; filter: blur(60px);
    }
    .illustration-box {
        position: relative; z-index: 1;
        width: 100%; height: 100%;
        background: var(--color-surface-container-low, #f2f4f6);
        border-radius: 2rem; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        padding: 2rem;
        border: 1px solid var(--color-outline-variant, #bfc7d1);
    }
    .illustration-box img {
        width: 100%; height: 100%;
        object-fit: contain; opacity: 0.9;
        mix-blend-mode: multiply;
    }
    [data-theme="dark"] .illustration-box img { mix-blend-mode: normal; opacity: 0.8; }

    /* Floating badge */
    .float-badge {
        position: absolute; top: 1.5rem; right: 1.5rem; z-index: 2;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.6);
        border-radius: 1.25rem; padding: 0.75rem 1rem;
        display: flex; align-items: center; gap: 0.75rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        animation: float 3s ease-in-out infinite;
    }
    [data-theme="dark"] .float-badge { background: rgba(40,44,48,0.85); border-color: rgba(80,88,96,0.4); }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    .float-badge-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 50%;
        background: var(--color-error-container, #ffdad6);
        display: flex; align-items: center; justify-content: center;
    }
    .float-badge-icon .material-symbols-outlined { color: var(--color-error, #ba1a1a); font-size: 1.25rem; }
    .float-badge-label { font-size: 0.625rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: var(--color-on-surface-variant, #404850); }
    .float-badge-value { font-size: 1.1rem; font-weight: 800; color: var(--color-primary, #005d90); }

    /* ── Text Column ─────────────────────────── */
    .text-col { display: flex; flex-direction: column; gap: 2rem; }
    .chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.35rem 1rem;
        background: rgba(0,93,144,0.1); color: var(--color-primary, #005d90);
        border-radius: 999px; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.12em;
        width: fit-content;
    }
    @media (max-width: 900px) { .chip { margin: 0 auto; } }

    .num-404 {
        font-size: clamp(6rem, 16vw, 10rem);
        font-weight: 900;
        color: rgba(0,93,144,0.08);
        font-family: var(--font-headline, 'Inter', sans-serif);
        line-height: 1;
        letter-spacing: -0.05em;
        user-select: none;
    }
    .headline-wrap { margin-top: -2.5rem; }
    .headline-wrap h1 {
        font-size: clamp(1.75rem, 5vw, 3rem);
        font-weight: 800;
        font-family: var(--font-headline, 'Inter', sans-serif);
        line-height: 1.15;
        color: var(--color-on-surface, #191c1e);
    }
    .headline-wrap h1 span { color: var(--color-primary, #005d90); }
    .subtitle {
        font-size: 1rem; color: var(--color-on-surface-variant, #404850);
        line-height: 1.6; max-width: 32rem;
    }
    @media (max-width: 900px) { .subtitle { margin: 0 auto; } }

    /* Buttons */
    .btn-row {
        display: flex; flex-wrap: wrap; gap: 0.875rem;
        align-items: center;
    }
    @media (max-width: 900px) { .btn-row { justify-content: center; } }
    .btn-primary-404 {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.75rem 1.75rem;
        background: linear-gradient(135deg, var(--color-primary, #005d90), #0077b6);
        color: #fff; border: none; border-radius: 0.875rem;
        font-weight: 700; font-size: 0.9rem;
        cursor: pointer; text-decoration: none;
        box-shadow: 0 8px 24px rgba(0,93,144,0.25);
        transition: all 0.2s;
    }
    .btn-primary-404:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,93,144,0.3); }
    .btn-secondary-404 {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.75rem 1.75rem;
        background: var(--color-surface-container-high, #e6e8ea);
        color: var(--color-primary, #005d90);
        border: 1px solid var(--color-outline-variant, #bfc7d1);
        border-radius: 0.875rem; font-weight: 700; font-size: 0.9rem;
        cursor: pointer; text-decoration: none;
        transition: all 0.2s;
    }
    .btn-secondary-404:hover { background: var(--color-surface-container-highest, #e0e3e5); transform: translateY(-2px); }
    .btn-primary-404 .material-symbols-outlined,
    .btn-secondary-404 .material-symbols-outlined { font-size: 1.1rem; }

    /* Quick links */
    .quick-links { border-top: 1px solid var(--color-outline-variant, #bfc7d1); padding-top: 1.5rem; }
    .quick-links-label {
        font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--color-on-surface-variant, #404850);
        margin-bottom: 0.875rem;
    }
    .quick-links-list { display: flex; flex-wrap: wrap; gap: 1rem; }
    @media (max-width: 900px) { .quick-links-list { justify-content: center; } }
    .quick-link {
        display: inline-flex; align-items: center; gap: 0.35rem;
        font-size: 0.82rem; color: var(--color-on-surface-variant, #404850);
        text-decoration: none; transition: color 0.2s;
    }
    .quick-link:hover { color: var(--color-primary, #005d90); }
    .quick-link .material-symbols-outlined { font-size: 1rem; }

    /* ── Footer ──────────────────────────────── */
    footer {
        border-top: 1px solid var(--color-outline-variant, #bfc7d1);
        background: var(--color-surface-container-low, #f2f4f6);
        padding: 1.5rem 2rem;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 1rem;
    }
    .footer-copy { font-size: 0.75rem; color: var(--color-on-surface-variant, #404850); }
    .footer-links { display: flex; gap: 1.5rem; }
    .footer-links a {
        font-size: 0.75rem; color: var(--color-on-surface-variant, #404850);
        text-decoration: none; transition: color 0.2s;
    }
    .footer-links a:hover { color: var(--color-primary, #005d90); }
</style>
</head>
<body>

<!-- Top Nav -->
<nav class="nav-404">
    <a href="<?= BASE_URL ?>dashboard.php" class="nav-logo">
        <img src="<?= BASE_URL ?>assets/images/icon.png" alt="HydroFlow Logo">
        HydroFlow
    </a>
    <a href="<?= BASE_URL ?>dashboard.php" class="nav-back-btn">
        <span class="material-symbols-outlined">arrow_back</span>
        Back to Dashboard
    </a>
</nav>

<!-- Main 404 Content -->
<main>
    <div class="error-grid">

        <!-- Illustration -->
        <div class="illustration-col">
            <div class="illustration-wrap">
                <div class="illustration-glow"></div>
                <div class="illustration-box">
                    <img src="<?= BASE_URL ?>assets/images/404-illustration.png" alt="Dripping water tap illustration">
                    <!-- Floating Badge -->
                    <div class="float-badge">
                        <div class="float-badge-icon">
                            <span class="material-symbols-outlined">water_drop</span>
                        </div>
                        <div>
                            <p class="float-badge-label">Flow Rate</p>
                            <p class="float-badge-value">0.00 L/s</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Text Content -->
        <div class="text-col">
            <div>
                <span class="chip">
                    <span class="material-symbols-outlined" style="font-size:0.85rem">warning</span>
                    System Alert
                </span>
            </div>

            <div>
                <div class="num-404">404</div>
                <div class="headline-wrap">
                    <h1>Oops! This well<br><span>has run dry.</span></h1>
                </div>
            </div>

            <p class="subtitle">
                It looks like you've drifted off course. The page you are looking for might have been moved, or the pipe has burst.
            </p>

            <div class="btn-row">
                <a href="<?= BASE_URL ?>dashboard.php" class="btn-primary-404">
                    <span class="material-symbols-outlined">dashboard</span>
                    Back to Dashboard
                </a>
                <a href="javascript:history.back()" class="btn-secondary-404">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Go Back
                </a>
            </div>

            <!-- Quick Access -->
            <div class="quick-links">
                <p class="quick-links-label">Quick Access</p>
                <div class="quick-links-list">
                    <a href="<?= BASE_URL ?>views/customers/customer_list.php" class="quick-link">
                        <span class="material-symbols-outlined">people</span> Customers
                    </a>
                    <a href="<?= BASE_URL ?>views/billing/bill_history.php" class="quick-link">
                        <span class="material-symbols-outlined">receipt_long</span> Billing
                    </a>
                    <a href="<?= BASE_URL ?>views/motors/motor_list.php" class="quick-link">
                        <span class="material-symbols-outlined">settings_input_component</span> Motors
                    </a>
                    <a href="<?= BASE_URL ?>views/reports/reports.php" class="quick-link">
                        <span class="material-symbols-outlined">bar_chart</span> Reports
                    </a>
                    <a href="<?= BASE_URL ?>views/settings.php" class="quick-link">
                        <span class="material-symbols-outlined">settings</span> Settings
                    </a>
                    <a href="<?= BASE_URL ?>login.php" class="quick-link">
                        <span class="material-symbols-outlined">login</span> Login
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Footer -->
<footer>
    <span class="footer-copy">© <?= date('Y') ?> HydroFlow Water Supply Management System</span>
    <div class="footer-links">
        <a href="<?= BASE_URL ?>views/settings.php">Settings</a>
        <a href="<?= BASE_URL ?>views/reports/reports.php">Reports</a>
        <a href="<?= BASE_URL ?>dashboard.php">Dashboard</a>
    </div>
</footer>

</body>
</html>