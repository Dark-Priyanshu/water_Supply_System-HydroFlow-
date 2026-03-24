<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Bill.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$billModel = new Bill($conn);
$bills = $billModel->getAllBills();
?>

<!-- Action Bar -->
<div class="flex justify-between items-end mb-8 mt-4 no-print">
    <div>
        <nav class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
            <span>Finance</span>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary font-medium">Invoices</span>
        </nav>
        <h2 class="text-3xl font-bold font-headline text-on-surface tracking-tight">Billing & Invoices</h2>
    </div>
</div>

<?php if (isset($_SESSION['success_msg'])): ?>
<div class="mb-8 p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center justify-between no-print">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-secondary">check_circle</span>
        <span class="text-secondary font-medium"><?= $_SESSION['success_msg'] ?></span>
    </div>
    <button type="button" class="text-secondary/60 hover:text-secondary p-1" onclick="this.parentElement.remove()">
        <span class="material-symbols-outlined">close</span>
    </button>
</div>
<?php unset($_SESSION['success_msg']); endif; ?>

<!-- Invoice List Container -->
<div class="bg-surface-container-lowest rounded-2xl shadow-[0_8px_32px_rgba(25,28,30,0.04)] overflow-hidden border border-outline-variant/10">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse datatable">
            <thead class="bg-surface-container-high/50">
                <tr>
                    <th class="py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Invoice #</th>
                    <th class="py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Date</th>
                    <th class="py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Customer</th>
                    <th class="py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Amount</th>
                    <th class="py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Status</th>
                    <th class="py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container">
                <?php while ($row = $bills->fetch_assoc()): ?>
                <tr class="hover:bg-surface-container-low/30 transition-colors group">
                    <td class="py-5 px-6 font-bold text-sm text-on-surface">INV-<?= str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td class="py-5 px-6 text-sm text-on-surface-variant"><?= date('d M, Y', strtotime($row['bill_date'])) ?></td>
                    <td class="py-5 px-6">
                        <div class="font-semibold text-primary text-sm"><?= htmlspecialchars($row['farmer_name']) ?></div>
                        <div class="text-[11px] text-on-surface-variant">+91 <?= htmlspecialchars($row['mobile']) ?></div>
                    </td>
                    <td class="py-5 px-6 font-bold text-on-surface font-headline">₹<?= number_format($row['total_amount'], 2) ?></td>
                    <td class="py-5 px-6">
                        <?php if($row['status'] == 'paid'): ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-secondary/10 text-secondary text-[10px] font-bold uppercase tracking-wider rounded-full border border-secondary/20">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span> Paid
                            </span>
                        <?php elseif($row['status'] == 'cancelled'): ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-error/10 text-error text-[10px] font-bold uppercase tracking-wider rounded-full border border-error/20">
                                <span class="material-symbols-outlined text-[14px]">cancel</span> Cancelled
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-surface-dim text-on-surface-variant text-[10px] font-bold uppercase tracking-wider rounded-full border border-outline-variant/30">
                                <span class="material-symbols-outlined text-[14px]">schedule</span> Pending
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="py-5 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="view_bill.php?id=<?= $row['bill_id'] ?>" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" title="View Invoice">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                            
                            <?php if($row['status'] == 'pending'): ?>
                                <div class="relative group/dropdown inline-block">
                                    <button type="button" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined">more_vert</span>
                                    </button>
                                    <div class="absolute right-0 top-full mt-1 bg-surface-container-lowest border border-outline-variant/10 shadow-lg rounded-xl overflow-hidden hidden group-hover/dropdown:block z-10 w-40">
                                        <a class="flex items-center gap-2 px-4 py-3 text-sm font-medium text-secondary hover:bg-surface-container-low transition-colors" href="../../controllers/billingController.php?update_status=paid&id=<?= $row['bill_id'] ?>">
                                            <span class="material-symbols-outlined text-[18px]">payments</span> Mark Paid
                                        </a>
                                        <a class="flex items-center gap-2 px-4 py-3 text-sm font-medium text-error hover:bg-error/10 transition-colors" href="../../controllers/billingController.php?update_status=cancelled&id=<?= $row['bill_id'] ?>">
                                            <span class="material-symbols-outlined text-[18px]">cancel</span> Cancel Bill
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
