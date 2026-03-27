    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed" style="inset: 0; background-color: rgba(0,0,0,0.5); z-index: 30; display: none; opacity: 0; transition: opacity 0.3s ease;" onclick="toggleSidebar()"></div>

    <!-- SideNavBar Component -->
    <aside id="sidebarMenu" class="side-sidebar">
        <div style="margin-bottom: 3rem; padding: 0 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                <img src="<?= $base_url ?>assets/images/icon.png" alt="HydroFlow Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: var(--color-primary); letter-spacing: -0.025em; font-family: var(--font-headline);">HydroFlow</h1>
                <p style="font-size: 0.625rem; text-transform: uppercase; letter-spacing: 0.2em; color: #94a3b8; font-weight: 600;">Precision Supply</p>
            </div>
        </div>
        <nav style="flex: 1; display: flex; flex-direction: column; gap: 0.25rem;">
            <?php 
            $current_page = basename($_SERVER['PHP_SELF']); 
            function getLinkClass($pages, $current) {
                if (!is_array($pages)) {
                    $pages = [$pages];
                }
                if (in_array($current, $pages)) {
                    return "nav-link nav-link-active";
                } else {
                    return "nav-link";
                }
            }
            ?>
            <a class="<?= getLinkClass(['dashboard.php'], $current_page) ?>" href="<?= $base_url ?>views/dashboard.php">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="<?= getLinkClass(['customer_list.php', 'add_customer.php'], $current_page) ?>" href="<?= $base_url ?>views/customers/customer_list.php">
                <span class="material-symbols-outlined">agriculture</span>
                <span>Customers / Farms</span>
            </a>
            <a class="<?= getLinkClass(['motor_list.php', 'add_motor.php'], $current_page) ?>" href="<?= $base_url ?>views/motors/motor_list.php">
                <span class="material-symbols-outlined">water_pump</span>
                <span>Motor Management</span>
            </a>
            <a class="<?= getLinkClass(['supply_history.php', 'add_supply.php'], $current_page) ?>" href="<?= $base_url ?>views/supply/supply_history.php">
                <span class="material-symbols-outlined">waves</span>
                <span>Water Supply Record</span>
            </a>
            <a class="<?= getLinkClass(['bill_history.php', 'generate_bill.php', 'view_bill.php'], $current_page) ?>" href="<?= $base_url ?>views/billing/bill_history.php">
                <span class="material-symbols-outlined">receipt_long</span>
                <span>Billing / Invoices</span>
            </a>
            <a class="<?= getLinkClass(['payment_history.php', 'add_payment.php'], $current_page) ?>" href="<?= $base_url ?>views/payments/payment_history.php">
                <span class="material-symbols-outlined">payments</span>
                <span>Payments Tracking</span>
            </a>
            <a class="<?= getLinkClass(['daily_report.php'], $current_page) ?>" href="<?= $base_url ?>views/reports/daily_report.php">
                <span class="material-symbols-outlined">bar_chart</span>
                <span>Reports</span>
            </a>
            <a class="<?= getLinkClass(['settings.php'], $current_page) ?>" href="<?= $base_url ?>views/settings.php">
                <span class="material-symbols-outlined">settings</span>
                <span>Settings</span>
            </a>
            
            <div style="padding-top: 1rem; margin-top: 1rem; border-top: 1px solid rgba(226, 232, 240, 0.5);">
                <a class="nav-link" style="color: #64748b;" onmouseover="this.style.color='#ba1a1a'; this.style.backgroundColor='rgba(254, 226, 226, 0.5)';" onmouseout="this.style.color='#64748b'; this.style.backgroundColor='transparent';" href="<?= $base_url ?>logout.php">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
        
        <div style="margin-top: auto; padding-top: 1.5rem;">
            <div style="background-color: var(--color-surface-container-low); border-radius: 1rem; padding: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                <?php
                    // Fetch current admin's username dynamically
                    $admin_username = 'Admin';
                    if (isset($_SESSION['admin_id'])) {
                        $adm_stmt = $conn->prepare("SELECT username FROM admins WHERE admin_id = ?");
                        $adm_stmt->bind_param("i", $_SESSION['admin_id']);
                        $adm_stmt->execute();
                        $adm_row = $adm_stmt->get_result()->fetch_assoc();
                        if ($adm_row) $admin_username = htmlspecialchars($adm_row['username']);
                    }
                ?>
                <img style="width: 2.5rem; height: 2.5rem; border-radius: 50%; object-fit: cover;" src="https://ui-avatars.com/api/?name=<?= urlencode($admin_username) ?>&background=005d90&color=fff" alt="Admin Avatar"/>
                <div style="overflow: hidden;">
                    <p style="font-size: 0.875rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $admin_username ?></p>
                    <p style="font-size: 0.625rem; color: var(--color-on-surface-variant); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">System Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="main-container">
        <!-- TopNavBar Component -->
        <header style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2.5rem; height: 5rem; background-color: transparent; flex-shrink: 0;">
            <div style="display: flex; align-items: center; gap: 2rem;">
                <button onclick="toggleSidebar()" style="display: none;" id="mobileMenuBtn">
                    <span class="material-symbols-outlined">menu_open</span>
                </button>
                <style>
                    @media (max-width: 1023px) {
                        #mobileMenuBtn { display: flex !important; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background-color: rgba(0, 93, 144, 0.1); color: var(--color-primary); border: none; cursor: pointer; }
                    }
                </style>
                <h2 style="font-family: var(--font-headline); font-size: 1.125rem; font-weight: 600; color: #0f172a;" class="hidden-sm">
                    <?php 
                        switch($current_page) {
                            case 'dashboard.php': echo 'Dashboard Overview'; break;
                            case 'customer_list.php': echo 'Customers / Farms'; break;
                            case 'motor_list.php': echo 'Motor Management'; break;
                            case 'supply_history.php': echo 'Water Supply Records'; break;
                            case 'bill_history.php': echo 'Billing & Invoices'; break;
                            case 'payment_history.php': echo 'Payments Tracking'; break;
                            case 'daily_report.php': echo 'Reports & Analytics'; break;
                            case 'settings.php': echo 'Settings'; break;
                            default: echo 'Management System'; break;
                        }
                    ?>
                </h2>
                <div style="position: relative;" class="hidden-md">
                    <span class="material-symbols-outlined" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--color-outline);">search</span>
                    <input id="topGlobalSearch" style="background-color: var(--color-surface-container-high); border: none; border-radius: 0.5rem; padding: 0.5rem 1rem 0.5rem 2.5rem; width: 18rem; font-size: 0.875rem; transition: all 0.3s ease;" placeholder="Search records..." type="text"/>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button style="width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: none; background: transparent; cursor: pointer;" onmouseover="this.style.backgroundColor='rgba(241, 245, 249, 0.5)';" onmouseout="this.style.backgroundColor='transparent';">
                    <span class="material-symbols-outlined" style="color: #475569;">notifications</span>
                </button>
            </div>
        </header>
        
        <!-- Page Content Wrapper -->
        <div style="padding: 0 2.5rem 3rem; flex: 1;">
