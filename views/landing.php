<?php require_once __DIR__ . '/../config/config.php'; ?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>HydroFlow – Smart Water Supply Management</title>
    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_URL ?>assets/images/icon.png" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Core & Component Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/landing.css">
</head>
<body class="antialiased">
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 w-full z-50 glass-nav shadow-sm" style="height: 4.5rem; transition: padding 0.3s ease;" id="main-nav">
        <div class="container flex h-full" style="justify-content: space-between; align-items: center;">
            <a href="#" class="flex" style="align-items: center; gap: 0.75rem;">
                <img src="<?= BASE_URL ?>assets/images/icon.png" alt="HydroFlow" style="height: 2rem; width: 2rem; object-fit: contain;">
                <div class="text-2xl font-bold text-primary" style="letter-spacing: -0.05em;">HydroFlow</div>
            </a>
            <div class="hidden-md flex nav-links" style="gap: 2rem; align-items: center;">
                <a href="#features">Features</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#dashboard">Dashboard</a>
                <a href="#benefits">Benefits</a>
                <a href="#about">About</a>
            </div>
            <div class="flex" style="align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>views/login.php" style="font-size: 0.875rem; font-weight: 600; color: var(--color-on-surface-variant);">Login</a>
                <a href="<?= BASE_URL ?>views/login.php" class="btn btn-primary" style="padding-left: 1.5rem; padding-right: 1.5rem;">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container hero-grid">
            <div class="z-10">
                <span class="hero-tag">Sustainable Agriculture</span>
                <h1 class="hero-title">
                    Smart Water Supply <span class="text-primary">Management</span> for Farming Areas
                </h1>
                <p class="hero-description">
                    HydroFlow helps manage water distribution efficiently for farmers and consumers, ensuring every drop contributes to growth and sustainability.
                </p>
                <div class="flex" style="flex-wrap: wrap; gap: 1rem;">
                    <a href="<?= BASE_URL ?>views/login.php" class="btn bg-gradient-primary" style="padding: 1rem 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                        Get Started Now <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    <button class="btn" style="color: var(--color-primary); background: transparent; padding: 1rem 2rem;">
                        <span class="material-symbols-outlined">play_circle</span>
                        View Demo
                    </button>
                </div>
            </div>
            <div class="relative">
                <div class="absolute" style="inset: -1rem; background-color: rgba(0, 93, 144, 0.05); border-radius: 50%; filter: blur(48px);"></div>
                <img alt="HydroFlow Systems" class="relative z-10 editorial-shadow" style="border-radius: 2.5rem; object-fit: cover; width: 100%; height: 500px;" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAREnSqCmv4m2FrgRDBmP8vBdz9Bq4PGz_FII0EfRXwfRtQYC5Bn6T0TzGevjAJn4_QSoj04E__EFxyrWHtS-7DGNkMewQfsgGe7hNqT6sn3s-voNAIJ1ifL8nSqZbl49TdhelPhKXTPlTNm0s4cKgnUrSwYWC86rYA-LFfstpohq964Ftdlss-rjz7sEyg8L-GbhQStRo1jAHgNObCdChvFqsXMgLhjhHAfhn5ATNAEvy5dDiqjkWs5uaDjnBpwX1bS75RODLwKdQe"/>
                <div class="absolute glass-nav" style="bottom: -1.5rem; left: -1.5rem; padding: 1.5rem; border-radius: 1.5rem; z-index: 20; border: 1px solid rgba(191, 199, 209, 0.15);">
                    <div class="flex" style="align-items: center; gap: 1rem;">
                        <div style="padding: 0.75rem; background-color: var(--color-secondary-container); border-radius: 0.75rem; display: flex;">
                            <span class="material-symbols-outlined text-on-secondary-container">waves</span>
                        </div>
                        <div>
                            <p style="font-size: 0.625rem; color: var(--color-on-surface-variant); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Current Flow</p>
                            <p style="font-family: var(--font-headline); font-size: 1.5rem; font-weight: 800; color: var(--color-secondary);">1,240 L/min</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Powerful Management Features</h2>
                <p style="color: var(--color-on-surface-variant); max-width: 32rem; margin: 0 auto;">Everything you need to streamline water distribution across large agricultural landscapes.</p>
            </div>
            <div class="feature-grid">
                <!-- Feature 1 -->
                <div class="feature-card col-span-2 wave-bg">
                    <div class="feature-icon-wrapper" style="background-color: var(--color-primary-fixed);">
                        <span class="material-symbols-outlined text-primary" style="font-size: 1.875rem;">calendar_month</span>
                    </div>
                    <h3 style="font-size: 1.875rem; margin-bottom: 1rem;">Water Supply Scheduling</h3>
                    <p style="color: var(--color-on-surface-variant); font-size: 1.125rem;">Automate and optimize water delivery schedules based on crop needs and soil moisture levels across the entire farming area.</p>
                </div>
                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper" style="background-color: var(--color-secondary-fixed);">
                        <span class="material-symbols-outlined text-secondary" style="font-size: 1.875rem;">groups</span>
                    </div>
                    <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">Farmer Portal</h3>
                    <p style="color: var(--color-on-surface-variant);">Centralized database for all agricultural stakeholders, tracking usage limits and digital billing.</p>
                </div>
                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper" style="background-color: var(--color-tertiary-fixed);">
                        <span class="material-symbols-outlined text-tertiary" style="font-size: 1.875rem;">monitoring</span>
                    </div>
                    <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">Live Telemetry</h3>
                    <p style="color: var(--color-on-surface-variant);">Live telemetry from sensors and pumps, providing instant visibility into flow and pressure.</p>
                </div>
                <!-- Feature 4 -->
                <div class="feature-card col-span-2 wave-bg">
                    <div class="feature-icon-wrapper" style="background-color: var(--color-on-tertiary-container);">
                        <span class="material-symbols-outlined text-tertiary-container" style="font-size: 1.875rem;">database</span>
                    </div>
                    <h3 style="font-size: 1.875rem; margin-bottom: 1rem;">Easy Data Management</h3>
                    <p style="color: var(--color-on-surface-variant); font-size: 1.125rem;">Exportable reports, historical data trends, and intuitive dashboards for administrators and ground operators.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="process-section" id="how-it-works">
        <div class="container process-grid">
            <div style="grid-column: span 1;">
                <h2 style="font-size: 2.25rem; margin-bottom: 1.5rem;">The 3-Step Process</h2>
                <p style="color: var(--color-on-surface-variant); font-size: 1.125rem; margin-bottom: 2rem;">Modernizing your water infrastructure is simpler than you think. Get started in minutes.</p>
                <button class="btn" style="padding: 0; background: transparent; color: var(--color-primary);">
                    Learn about our integration <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
            <div style="grid-column: span 1; display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem;">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <div class="step-content">
                        <h4 style="font-size: 1.25rem; margin-bottom: 0.75rem;">Register</h4>
                        <p style="font-size: 0.875rem; color: var(--color-on-surface-variant);">Onboard farmers and map out the supply network infrastructure.</p>
                    </div>
                </div>
                <div class="step-card">
                    <div class="step-number">02</div>
                    <div class="step-content">
                        <h4 style="font-size: 1.25rem; margin-bottom: 0.75rem;">Schedule</h4>
                        <p style="font-size: 0.875rem; color: var(--color-on-surface-variant);">Set automated cycles and allocation limits for each node.</p>
                    </div>
                </div>
                <div class="step-card">
                    <div class="step-number">03</div>
                    <div class="step-content">
                        <h4 style="font-size: 1.25rem; margin-bottom: 0.75rem;">Monitor</h4>
                        <p style="font-size: 0.875rem; color: var(--color-on-surface-variant);">Watch real-time flow and adjust distribution on the fly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Preview -->
    <section class="dashboard-preview-section" id="dashboard">
        <div class="container">
            <div class="dashboard-mockup">
                <div class="mockup-header">
                    <div class="flex" style="align-items: center; gap: 2rem;">
                        <h3 style="font-size: 1.5rem;">Control Panel</h3>
                        <nav class="flex" style="gap: 0.5rem;">
                            <span style="background-color: rgba(0, 93, 144, 0.1); color: var(--color-primary); padding: 0.375rem 1rem; border-radius: 9999px; font-weight: 700; font-size: 0.875rem;">Overview</span>
                            <span style="padding: 0.375rem 1rem; color: var(--color-on-surface-variant); font-size: 0.875rem; font-weight: 500;">Schedules</span>
                            <span style="padding: 0.375rem 1rem; color: var(--color-on-surface-variant); font-size: 0.875rem; font-weight: 500;">Analytics</span>
                        </nav>
                    </div>
                    <div class="flex" style="gap: 0.625rem;">
                        <div class="dot" style="background-color: var(--color-error);"></div>
                        <div class="dot" style="background-color: #facc15;"></div>
                        <div class="dot" style="background-color: #22c55e;"></div>
                    </div>
                </div>
                <div style="padding: 2.5rem;">
                    <div class="grid" style="grid-template-columns: 1fr 3fr; gap: 2.5rem;">
                        <div class="flex" style="flex-direction: column; gap: 1.5rem;">
                            <div class="card" style="padding: 1.5rem; background-color: var(--color-surface-container-low); border: 1px solid var(--color-outline-variant);">
                                <label style="font-size: 0.625rem; font-weight: 800; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Total Distribution</label>
                                <p style="font-family: var(--font-headline); font-size: 1.875rem; font-weight: 900; color: var(--color-primary);">45,000 <span style="font-size: 0.875rem; font-weight: 500;">m³</span></p>
                            </div>
                            <div class="card" style="padding: 1.5rem; background-color: var(--color-surface-container-low); border: 1px solid var(--color-outline-variant);">
                                <label style="font-size: 0.625rem; font-weight: 800; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Active Sectors</label>
                                <p style="font-family: var(--font-headline); font-size: 1.875rem; font-weight: 900; color: var(--color-secondary);">12 <span style="font-size: 0.875rem; color: var(--color-on-surface-variant); font-weight: 500;">/ 14</span></p>
                            </div>
                        </div>
                        <div class="table-container" style="border: 1px solid var(--color-outline-variant);">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th style="font-size: 0.6875rem; letter-spacing: 0.1em;">Farmer Name</th>
                                        <th style="font-size: 0.6875rem; letter-spacing: 0.1em;">Schedule Time</th>
                                        <th style="font-size: 0.6875rem; letter-spacing: 0.1em; text-align: center;">Area ID</th>
                                        <th style="font-size: 0.6875rem; letter-spacing: 0.1em; text-align: right;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-weight: 700;">Rajesh Kumar</td>
                                        <td style="color: var(--color-on-surface-variant);">06:00 AM - 09:00 AM</td>
                                        <td style="text-align: center; font-family: monospace;">Zone-A-04</td>
                                        <td style="text-align: right;"><span class="badge badge-active">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 700;">Amit Singh</td>
                                        <td style="color: var(--color-on-surface-variant);">09:15 AM - 12:15 PM</td>
                                        <td style="text-align: center; font-family: monospace;">Zone-C-12</td>
                                        <td style="text-align: right;"><span class="badge badge-pending">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: 700;">Suresh Verma</td>
                                        <td style="color: var(--color-on-surface-variant);">01:00 PM - 04:00 PM</td>
                                        <td style="text-align: center; font-family: monospace;">Zone-B-02</td>
                                        <td style="text-align: right;"><span class="badge badge-active">Active</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-color: white; border-top: 1px solid var(--color-outline-variant); margin-top: 6rem; border-radius: 3rem 3rem 0 0;">
        <div class="container" style="padding-top: 5rem; padding-bottom: 5rem;">
            <div class="grid" style="grid-template-columns: repeat(4, 1fr); gap: 3rem;">
                <div style="grid-column: span 1;">
                    <div class="flex" style="align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
                        <img src="<?= BASE_URL ?>assets/images/icon.png" alt="HydroFlow" style="height: 2rem;">
                        <div class="text-2xl font-bold text-primary">HydroFlow</div>
                    </div>
                    <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); line-height: 1.6;">Modernizing agricultural water infrastructure through hydrological clarity and precision management.</p>
                    <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--color-surface-container);">
                        <p style="font-size: 0.625rem; text-transform: uppercase; font-weight: 800; color: var(--color-on-surface-variant); margin-bottom: 0.5rem;">Developed by</p>
                        <p style="font-size: 0.875rem; font-weight: 700; color: var(--color-primary);">Priyanshu Shakya</p>
                        <p style="font-size: 0.75rem; color: var(--color-on-surface-variant);">Subhash Academy (9050)</p>
                    </div>
                </div>
                <div style="grid-column: span 3; display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem;">
                    <div>
                        <h5 style="font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; margin-bottom: 2rem;">Platform</h5>
                        <ul style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: var(--color-on-surface-variant);">
                            <li><a href="#features">Features</a></li>
                            <li><a href="#how-it-works">How It Works</a></li>
                            <li><a href="#dashboard">Dashboard</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 style="font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; margin-bottom: 2rem;">Support</h5>
                        <ul style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: var(--color-on-surface-variant);">
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">System Status</a></li>
                            <li><a href="#">API Access</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 style="font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; margin-bottom: 2rem;">Legal</h5>
                        <ul style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: var(--color-on-surface-variant);">
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Cookie Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" style="padding: 2rem 0; border-top: 1px solid var(--color-surface-container); display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: 0.6875rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.6;">© <?= date('Y') ?> HydroFlow Water Systems. Built with Clarity.</p>
            <div class="flex" style="gap: 2rem; color: var(--color-on-surface-variant);">
                <span class="material-symbols-outlined">public</span>
                <span class="material-symbols-outlined">mail</span>
                <span class="material-symbols-outlined">call</span>
            </div>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('main-nav');
            if (window.scrollY > 20) {
                nav.style.paddingTop = '0.5rem';
                nav.style.paddingBottom = '0.5rem';
                nav.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
            } else {
                nav.style.paddingTop = '1rem';
                nav.style.paddingBottom = '1rem';
                nav.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
            }
        });
    </script>
    <style>
        .hidden-md { display: none; }
        @media (min-width: 768px) {
            .hidden-md { display: flex; }
        }
    </style>
</body>
</html>
