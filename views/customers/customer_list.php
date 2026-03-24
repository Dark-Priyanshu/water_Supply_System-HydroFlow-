<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Customer.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$customerModel = new Customer($conn);
$customers = $customerModel->getAllCustomers();
$customer_count = $customers->num_rows;
?>

<!-- Header Section -->
<div class="flex justify-between items-end mb-10 mt-4">
    <div>
        <h2 class="text-headline-lg font-headline font-extrabold text-on-surface tracking-tight mb-2">Customer Management</h2>
        <p class="text-body-md text-on-surface-variant max-w-2xl">Monitor and manage irrigation customers, farm details, and connection statuses.</p>
    </div>
    <a href="add_customer.php" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-br from-primary to-primary-container text-white rounded-xl font-semibold transition-transform active:scale-95 shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-xl">person_add</span>
        <span class="text-sm">Add Customer</span>
    </a>
</div>

<?php if (isset($_SESSION['success_msg'])): ?>
<div class="mb-8 p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-secondary">check_circle</span>
        <span class="text-secondary font-medium"><?= $_SESSION['success_msg'] ?></span>
    </div>
    <button type="button" class="text-secondary/60 hover:text-secondary p-1" onclick="this.parentElement.remove()">
        <span class="material-symbols-outlined">close</span>
    </button>
</div>
<?php unset($_SESSION['success_msg']); endif; ?>

<!-- Dashboard Stats / Filters Bento -->
<div class="grid grid-cols-12 gap-6 mb-8">
    <!-- Filter Section (Placeholder) -->
    <div class="col-span-12 lg:col-span-8 bg-surface-container-low rounded-xl p-6 flex flex-wrap items-center gap-6">
        <div class="flex items-center gap-3">
            <span class="text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Filter By:</span>
            <select class="bg-surface-container-lowest border-none rounded-lg text-sm px-4 py-2 focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer text-on-surface-variant">
                <option>All Villages</option>
            </select>
            <select class="bg-surface-container-lowest border-none rounded-lg text-sm px-4 py-2 focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer text-on-surface-variant">
                <option>Connection Status</option>
            </select>
        </div>
        <div class="h-8 w-px bg-outline-variant/30 mx-2"></div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-surface-container-lowest text-primary text-xs font-bold rounded-lg hover:bg-primary hover:text-white transition-all">Today</button>
            <button class="px-4 py-2 bg-surface-container-lowest text-slate-500 text-xs font-bold rounded-lg hover:bg-primary hover:text-white transition-all">This Month</button>
        </div>
    </div>
    <!-- Quick Stats -->
    <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest rounded-xl p-6 shadow-sm flex items-center justify-between border border-outline-variant/15 overflow-hidden relative">
        <div>
            <p class="text-label-sm font-bold text-on-surface-variant uppercase tracking-wider mb-1">Total Active Customers</p>
            <h3 class="text-3xl font-headline font-extrabold text-primary"><?= $customer_count ?></h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined">groups</span>
        </div>
        <!-- Subtle Gradient Wave -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-secondary/20 to-transparent"></div>
    </div>
</div>

<!-- Professional Data Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/15 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse datatable">
            <thead>
                <tr class="bg-surface-container-low">
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[11px]">ID</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[11px]">Farmer Name</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[11px]">Mobile Number</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[11px]">Village</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[11px]">Conn. No</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[11px]">Pipe Size</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[11px] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                <?php while ($row = $customers->fetch_assoc()): ?>
                <tr class="hover:bg-surface-container-low/40 transition-colors group">
                    <td class="px-6 py-5 text-sm font-semibold text-primary">#<?= $row['customer_id'] ?></td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-xs font-bold text-primary">
                                <?= strtoupper(substr(trim($row['farmer_name']), 0, 2)) ?>
                            </div>
                            <span class="text-sm font-medium text-on-surface"><?= htmlspecialchars($row['farmer_name']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-sm text-on-surface-variant"><?= htmlspecialchars($row['mobile']) ?></td>
                    <td class="px-6 py-5">
                        <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider"><?= htmlspecialchars($row['village']) ?></span>
                    </td>
                    <td class="px-6 py-5 text-sm text-on-surface-variant"><?= htmlspecialchars($row['connection_no']) ?></td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full <?= $row['pipe_size'] ? 'bg-secondary' : 'bg-slate-300' ?>"></span>
                            <span class="text-sm font-medium <?= $row['pipe_size'] ? 'text-secondary' : 'text-slate-400' ?>"><?= $row['pipe_size'] ? htmlspecialchars($row['pipe_size']) : 'N/A' ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="#" class="p-1.5 text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined text-lg">visibility</span></a>
                            <a href="#" class="p-1.5 text-slate-400 hover:text-secondary transition-colors"><span class="material-symbols-outlined text-lg">edit</span></a>
                            <a href="#" class="p-1.5 text-slate-400 hover:text-error transition-colors"><span class="material-symbols-outlined text-lg">delete</span></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
