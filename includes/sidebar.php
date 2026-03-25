    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden opacity-0 transition-opacity duration-300" onclick="toggleSidebar()"></div>

    <!-- SideNavBar Component -->
    <aside id="sidebarMenu" class="fixed left-0 top-0 h-full z-40 px-6 py-8 flex flex-col w-64 glass-sidebar shadow-[8px_0_32px_rgba(25,28,30,0.06)] transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <div class="mb-12 px-2 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                <img src="<?= $base_url ?>assets/images/icon.png" alt="HydroFlow Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#005d90] tracking-tight font-headline">HydroFlow</h1>
                <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400 font-semibold">Precision Supply</p>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <?php 
            $current_page = basename($_SERVER['PHP_SELF']); 
            function getLinkClass($pages, $current) {
                if (!is_array($pages)) {
                    $pages = [$pages];
                }
                if (in_array($current, $pages)) {
                    return "relative text-[#005d90] font-semibold before:content-[''] before:absolute before:left-[-24px] before:w-1 before:h-6 before:bg-[#005d90] before:rounded-full flex items-center gap-4 px-4 py-3 rounded-xl bg-slate-100/50 transition-all duration-200 active:scale-[0.98]";
                } else {
                    return "text-slate-500 hover:text-[#005d90] hover:bg-slate-100/50 flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300";
                }
            }
            ?>
            <a class="<?= getLinkClass(['dashboard.php'], $current_page) ?>" href="<?= $base_url ?>views/dashboard.php">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                <span class="text-label-sm">Dashboard</span>
            </a>
            <a class="<?= getLinkClass(['customer_list.php', 'add_customer.php'], $current_page) ?>" href="<?= $base_url ?>views/customers/customer_list.php">
                <span class="material-symbols-outlined">agriculture</span>
                <span class="text-label-sm">Customers / Farms</span>
            </a>
            <a class="<?= getLinkClass(['motor_list.php', 'add_motor.php'], $current_page) ?>" href="<?= $base_url ?>views/motors/motor_list.php">
                <span class="material-symbols-outlined">water_pump</span>
                <span class="text-label-sm">Motor Management</span>
            </a>
            <a class="<?= getLinkClass(['supply_history.php', 'add_supply.php'], $current_page) ?>" href="<?= $base_url ?>views/supply/supply_history.php">
                <span class="material-symbols-outlined">waves</span>
                <span class="text-label-sm">Water Supply Record</span>
            </a>
            <a class="<?= getLinkClass(['bill_history.php', 'generate_bill.php', 'view_bill.php'], $current_page) ?>" href="<?= $base_url ?>views/billing/bill_history.php">
                <span class="material-symbols-outlined">receipt_long</span>
                <span class="text-label-sm">Billing / Invoices</span>
            </a>
            <a class="<?= getLinkClass(['payment_history.php', 'add_payment.php'], $current_page) ?>" href="<?= $base_url ?>views/payments/payment_history.php">
                <span class="material-symbols-outlined">payments</span>
                <span class="text-label-sm">Payments Tracking</span>
            </a>
            <a class="<?= getLinkClass(['daily_report.php'], $current_page) ?>" href="<?= $base_url ?>views/reports/daily_report.php">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="text-label-sm">Reports</span>
            </a>
            
            <div class="pt-4 mt-4 border-t border-slate-200/50">
                <a class="text-slate-500 hover:text-[#ba1a1a] hover:bg-red-50 flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300" href="<?= $base_url ?>logout.php">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-label-sm">Logout</span>
                </a>
            </div>
        </nav>
        
        <div class="mt-auto pt-6 border-t-0">
            <div class="bg-surface-container-low rounded-2xl p-4 flex items-center gap-3">
                <img class="w-10 h-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Admin&background=005d90&color=fff" alt="Admin Avatar"/>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate">Anand Kumar</p>
                    <p class="text-[10px] text-on-surface-variant truncate">System Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="lg:ml-64 min-h-screen flex flex-col transition-all duration-300 overflow-x-hidden">
        <!-- TopNavBar Component -->
        <header class="flex justify-between items-center px-4 lg:px-10 py-6 max-w-[1600px] h-20 bg-transparent shrink-0">
            <div class="flex items-center gap-4 lg:gap-8">
                <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined">menu_open</span>
                </button>
                <h2 class="font-headline text-[18px] lg:text-headline-sm font-semibold text-slate-900 hidden sm:block">
                    <?php 
                        switch($current_page) {
                            case 'dashboard.php': echo 'Dashboard Overview'; break;
                            case 'customer_list.php': echo 'Customers / Farms'; break;
                            case 'motor_list.php': echo 'Motor Management'; break;
                            case 'supply_history.php': echo 'Water Supply Records'; break;
                            case 'bill_history.php': echo 'Billing & Invoices'; break;
                            case 'payment_history.php': echo 'Payments Tracking'; break;
                            case 'daily_report.php': echo 'Reports & Analytics'; break;
                            default: echo 'Management System'; break;
                        }
                    ?>
                </h2>
                <div class="relative group hidden md:block">
                    <?php 
                        $search_placeholder = 'Search records...';
                        switch($current_page) {
                            case 'dashboard.php': $search_placeholder = 'Search dashboard...'; break;
                            case 'customer_list.php': $search_placeholder = 'Search customers or villages...'; break;
                            case 'motor_list.php': $search_placeholder = 'Search motor details...'; break;
                            case 'supply_history.php': $search_placeholder = 'Search supply logs...'; break;
                            case 'bill_history.php': $search_placeholder = 'Search bills or invoices...'; break;
                            case 'payment_history.php': $search_placeholder = 'Search payments...'; break;
                            case 'daily_report.php': $search_placeholder = 'Search reports...'; break;
                        }
                    ?>
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline">search</span>
                    <input id="topGlobalSearch" class="bg-surface-container-high border-none rounded-lg pl-10 pr-4 py-2 w-48 lg:w-72 text-sm focus:ring-2 focus:ring-[#005d90]/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant" placeholder="<?= $search_placeholder ?>" type="text"/>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100/50 transition-colors">
                    <span class="material-symbols-outlined text-slate-600">notifications</span>
                </button>
            </div>
        </header>
        
        <!-- Page Content Wrapper -->
        <div class="px-4 lg:px-10 pb-12 max-w-[1600px] flex-1">
