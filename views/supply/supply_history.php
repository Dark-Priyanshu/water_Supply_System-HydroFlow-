<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Supply.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$supplyModel = new Supply($conn);
$supplies = $supplyModel->getAllSupply();
?>

<!-- Header Section -->
<div class="flex justify-between items-end mb-8 mt-4">
    <div>
        <h2 class="text-headline-lg font-headline font-extrabold text-on-surface tracking-tight mb-2">Water Supply History</h2>
        <p class="text-body-md text-on-surface-variant max-w-2xl">View previous irrigation logs, duration, and billing status.</p>
    </div>
    <a href="add_supply.php" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-br from-primary to-primary-container text-white rounded-xl font-semibold transition-transform active:scale-95 shadow-lg shadow-primary/20 hover:shadow-xl">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">waves</span>
        <span class="text-sm">Record Supply</span>
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

<!-- Professional Data Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-[0_8px_32px_rgba(25,28,30,0.04)] border border-outline-variant/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse datatable">
            <thead>
                <tr class="bg-surface-container-low/50">
                    <th class="px-6 py-4 text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant/10">Supply ID</th>
                    <th class="px-6 py-4 text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant/10">Date</th>
                    <th class="px-6 py-4 text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant/10">Farmer Name</th>
                    <th class="px-6 py-4 text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant/10">Motor Used</th>
                    <th class="px-6 py-4 text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant/10">Duration</th>
                    <th class="px-6 py-4 text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant/10 text-right">Amount</th>
                    <th class="px-6 py-4 text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest border-b border-outline-variant/10 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/5">
                <?php while ($row = $supplies->fetch_assoc()): ?>
                <tr class="hover:bg-surface-container-low/30 transition-colors group">
                    <td class="px-6 py-5 text-sm font-semibold text-primary">#<?= $row['supply_id'] ?></td>
                    <td class="px-6 py-5">
                        <span class="text-sm font-medium text-on-surface"><?= date('d M, Y', strtotime($row['date'])) ?></span>
                    </td>
                    <td class="px-6 py-5 text-sm font-semibold text-on-surface">
                        <?= htmlspecialchars($row['farmer_name']) ?>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant opacity-60">water_pump</span>
                            <span class="text-[13px] text-on-surface-variant font-medium"><?= htmlspecialchars($row['motor_name']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-[12px] font-semibold text-on-surface-variant mb-1"><?= date('h:i A', strtotime($row['start_time'])) ?> - <?= date('h:i A', strtotime($row['end_time'])) ?></div>
                        <span class="px-2 py-0.5 bg-surface-container-high rounded text-[11px] font-bold tracking-wider text-on-surface-variant"><?= number_format($row['total_hours'], 2) ?> hrs</span>
                    </td>
                    <td class="px-6 py-5 text-right font-headline">
                        <span class="text-sm font-extrabold text-secondary">₹<?= number_format($row['total_amount'], 2) ?></span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <?php 
                        $check_bill = $conn->query("SELECT bill_id FROM bills WHERE supply_id = {$row['supply_id']}");
                        if($check_bill->num_rows > 0): 
                        ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-secondary/10 text-secondary text-[11px] font-extrabold rounded-full tracking-wider uppercase border border-secondary/20">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                Billed
                            </span>
                        <?php else: ?>
                            <a href="../billing/generate_bill.php?supply_id=<?= $row['supply_id'] ?>" class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary text-[11px] font-bold rounded hover:bg-primary hover:text-white transition-colors border border-primary/20 hover:border-primary" title="Generate Bill">
                                <span class="material-symbols-outlined text-[16px]">receipt_long</span> Generate Bill
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
