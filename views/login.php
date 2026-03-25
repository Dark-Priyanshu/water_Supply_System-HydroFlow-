<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
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
    <link rel="icon" href="../assets/images/icon.png" type="image/png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        
        /* Modern Abstract Background overlay */
        .bg-login {
            background-color: #f2f4f6;
            background-image: 
                radial-gradient(at 0% 0%, hsla(202,100%,75%,0.3) 0px, transparent 50%),
                radial-gradient(at 100% 100%, hsla(168,100%,75%,0.3) 0px, transparent 50%);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        
        /* Utility classes missing after migration */
        .text-primary { color: #005d90; }
        .bg-primary\/20 { background-color: rgba(0, 93, 144, 0.2); }
        .bg-gradient-to-r.from-primary.to-primary-container {
            background: linear-gradient(to right, #005d90, #007bbd);
        }
        .shadow-primary\/30 {
            box-shadow: 0 10px 15px -3px rgba(0, 93, 144, 0.3);
        }
        .text-white { color: #ffffff; }
    </style>
</head>
<body class="bg-login font-body text-on-surface antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Decorative blobs -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
    <div class="absolute w-96 h-96 bg-teal-300/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000 bottom-[-10%] right-[-10%]"></div>

    <div class="glass-card rounded-3xl w-full max-w-md p-10 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.1)] relative z-10 transition-all hover:shadow-[0_40px_80px_-12px_rgba(0,0,0,0.15)]">
        
        <div class="text-center mb-8">
            <img src="../assets/images/icon.png" alt="HydroFlow Logo" class="w-20 h-20 mx-auto mb-4 object-contain filter drop-shadow-[0_8px_16px_rgba(0,93,144,0.3)]">
            <h2 class="text-3xl font-extrabold font-headline tracking-tight text-primary">HydroFlow</h2>
            <p class="text-sm font-semibold text-on-surface-variant uppercase tracking-widest mt-2">Portal Access</p>
        </div>
        
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="bg-error-container/80 text-error-container-on font-bold text-error px-4 py-3 rounded-lg mb-6 flex items-start gap-2 text-sm border border-error/20">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <span><?= $_SESSION['error_msg'] ?></span>
            </div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>

        <form action="../controllers/authController.php" method="POST" class="space-y-5">
            <div class="space-y-2">
                <label for="username" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Username</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">person</span>
                    <input type="text" id="username" name="username" required class="w-full pl-12 pr-4 py-3.5 bg-surface-container-lowest/80 border border-outline/20 rounded-xl focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all outline-none font-medium placeholder:text-outline/50" placeholder="admin">
                </div>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label for="password" class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Password</label>
                </div>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
                    <input type="password" id="password" name="password" required class="w-full pl-12 pr-4 py-3.5 bg-surface-container-lowest/80 border border-outline/20 rounded-xl focus:ring-2 focus:ring-primary/40 focus:border-transparent transition-all outline-none font-medium placeholder:text-outline/50" placeholder="••••••••">
                </div>
            </div>
            
            <button type="submit" name="login" class="w-full mt-8 py-3.5 px-4 bg-gradient-to-r from-primary to-primary-container text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:-translate-y-0.5 hover:shadow-xl transition-all uppercase tracking-widest text-sm flex justify-center items-center gap-2">
                Secure Login <span class="material-symbols-outlined text-sm">login</span>
            </button>
        </form>
        
        <div class="mt-8 text-center border-t border-outline/10 pt-6">
            <p class="text-xs text-outline font-medium">Water Supply Management System v2.0</p>
        </div>
    </div>
</body>
</html>
