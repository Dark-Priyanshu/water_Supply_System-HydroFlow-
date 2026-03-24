<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
// Get unpaid bills
$pending_bills = $conn->query("SELECT b.bill_id, b.total_amount, c.farmer_name 
                              FROM bills b 
                              JOIN customers c ON b.customer_id = c.customer_id 
                              WHERE b.status = 'pending'");
?>

<div class="flex justify-between items-end mb-8 mt-4">
    <div>
        <h2 class="text-headline-lg font-headline font-extrabold text-on-surface tracking-tight mb-2">Record New Payment</h2>
        <p class="text-body-md text-on-surface-variant max-w-2xl">Manual entry for offline, cash, and bank transfers against pending invoices.</p>
    </div>
    <a href="payment_history.php" class="flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface-variant rounded-xl font-semibold hover:bg-surface-container-highest transition-colors shadow-sm">
        <span class="material-symbols-outlined text-xl">history_edu</span>
        <span class="text-sm">Ledger</span>
    </a>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="mb-8 p-4 bg-error-container text-on-error-container rounded-xl flex items-center gap-3 border border-error/20 max-w-2xl mx-auto">
    <span class="material-symbols-outlined">error</span>
    <span class="text-sm font-bold"><?= $_SESSION['error_msg'] ?></span>
</div>
<?php unset($_SESSION['error_msg']); endif; ?>

<div class="bg-surface-container-lowest rounded-2xl shadow-[0_8px_32px_rgba(25,28,30,0.04)] border border-outline-variant/10 overflow-hidden max-w-2xl mx-auto">
    <div class="p-8 md:p-10">
        <form action="../../controllers/paymentController.php" method="POST" class="space-y-8">
            <div class="space-y-3">
                <label class="block text-sm font-bold text-on-surface uppercase tracking-wider">Select Pending Invoice <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] pointer-events-none">receipt_long</span>
                    <select name="bill_id" id="bill_id" required class="w-full pl-12 pr-10 py-4 bg-surface-container-low border border-outline-variant/30 rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all appearance-none cursor-pointer font-medium text-sm text-on-surface shadow-sm outline-none">
                        <option value="">Select Invoice to Settle</option>
                        <?php while($row = $pending_bills->fetch_assoc()): ?>
                            <option value="<?= $row['bill_id'] ?>" data-amount="<?= $row['total_amount'] ?>">
                                INV-<?= str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT) ?> - <?= htmlspecialchars($row['farmer_name']) ?> (₹<?= $row['total_amount'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline pointer-events-none">expand_more</span>
                </div>
            </div>
            
            <div class="bg-primary/5 rounded-xl p-6 border border-primary/20 flex flex-col items-center justify-center">
                <label class="block text-xs font-bold text-primary uppercase tracking-widest mb-2">Amount Paid (₹) <span class="text-error">*</span></label>
                <input type="number" step="0.5" name="amount" id="amount" required class="w-full max-w-[250px] bg-surface-container-lowest border-none text-center rounded-xl py-3 focus:ring-2 focus:ring-primary/40 font-headline font-extrabold text-3xl text-primary shadow-inner outline-none placeholder:text-primary/30" placeholder="0.00">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Payment Method <span class="text-error">*</span></label>
                    <div class="relative">
                        <select name="method" required class="w-full pl-4 pr-10 py-3 bg-surface border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary/40 appearance-none text-sm transition-all outline-none font-medium">
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI / Online</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Check">Check</option>
                        </select>
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline pointer-events-none">expand_more</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Payment Date <span class="text-error">*</span></label>
                    <input type="date" name="payment_date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 bg-surface border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary/40 text-sm transition-all outline-none font-medium text-on-surface">
                </div>
            </div>
            
            <div class="pt-6 border-t border-outline-variant/10">
                <button type="submit" name="add_payment" class="w-full flex justify-center items-center gap-2 py-4 bg-gradient-to-r from-primary to-primary-container text-white font-bold rounded-xl shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-0.5 transition-all text-sm uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[20px]">task_alt</span>
                    Log Payment Transaction
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('bill_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const amount = selected.getAttribute('data-amount');
    if(amount) {
        document.getElementById('amount').value = parseFloat(amount).toFixed(2);
    } else {
        document.getElementById('amount').value = '';
    }
});
</script>

<?php include '../../includes/footer.php'; ?>
