<?php
require_once __DIR__ . '/../config/config.php';
if (isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "views/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HydroFlow Portal</title>
    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_URL ?>assets/images/icon.png" type="image/png">
    
    <!-- Core & Component Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/components.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
</head>
<body class="login-container">

    <!-- Decorative blobs -->
    <div style="position: absolute; top: -10%; left: -10%; width: 24rem; height: 24rem; background-color: rgba(0, 93, 144, 0.2); border-radius: 50%; filter: blur(64px); opacity: 0.7; z-index: 0;"></div>
    <div style="position: absolute; bottom: -10%; right: -10%; width: 24rem; height: 24rem; background-color: rgba(45, 212, 191, 0.4); border-radius: 50%; filter: blur(64px); opacity: 0.7; z-index: 0;"></div>

    <div class="login-card">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="<?= BASE_URL ?>assets/images/icon.png" alt="HydroFlow Logo" style="width: 5rem; height: 5rem; margin: 0 auto 1rem; object-fit: contain; filter: drop-shadow(0 8px 16px rgba(0,93,144,0.3));">
            <h2 style="font-size: 1.875rem; font-weight: 800; font-family: var(--font-headline); color: var(--color-primary); letter-spacing: -0.025em;">HydroFlow</h2>
            <p style="font-size: 0.75rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.2em; margin-top: 0.5rem;">Portal Access</p>
        </div>
        
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="error-alert">
                <span class="material-symbols-outlined" style="font-size: 1.125rem;">error</span>
                <span><?= $_SESSION['error_msg'] ?></span>
            </div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>controllers/authController.php" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div class="input-group">
                <label for="username" class="input-label">Username</label>
                <div class="input-wrapper">
                    <span class="material-symbols-outlined input-icon">person</span>
                    <input type="text" id="username" name="username" required class="input-field" placeholder="admin">
                </div>
            </div>
            
            <div class="input-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label for="password" class="input-label">Password</label>
                </div>
                <div class="input-wrapper">
                    <span class="material-symbols-outlined input-icon">lock</span>
                    <input type="password" id="password" name="password" required class="input-field" placeholder="••••••••">
                </div>
            </div>
            
            <button type="submit" name="login" class="btn bg-gradient-primary" style="width: 100%; margin-top: 2rem; padding: 1rem; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 93, 144, 0.3);">
                Secure Login <span class="material-symbols-outlined" style="font-size: 1rem;">login</span>
            </button>
        </form>
        
        <div style="margin-top: 2rem; text-align: center; border-top: 1px solid rgba(112, 120, 129, 0.1); pt-1.5rem;">
            <p style="font-size: 0.75rem; color: var(--color-outline); font-weight: 500; margin-top: 1.5rem;">Water Supply Management System</p>
        </div>
    </div>
</body>
</html>