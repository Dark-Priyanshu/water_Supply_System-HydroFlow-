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

<!-- Header -->
<div class="flex" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="breadcrumb">
            <span><?= __('directory') ?></span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <a href="payment_history.php"><?= __('payments') ?></a>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;"><?= __('new_entry') ?></span>
        </nav>
        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;"><?= __('record_payment') ?></h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;"><?= __('ledger_desc') ?></p>
    </div>
    <a href="payment_history.php" class="btn-secondary" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <span class="material-symbols-outlined" style="font-size: 1.25rem;">history_edu</span>
        <span><?= __('view_ledger') ?></span>
    </a>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="error-alert" style="max-width: 40rem; margin: 0 auto 1.5rem;">
    <span class="material-symbols-outlined">error</span>
    <span style="font-weight: 700;"><?= $_SESSION['error_msg'] ?></span>
</div>
<?php unset($_SESSION['error_msg']); endif; ?>

<!-- Form Card -->
<div class="form-card" style="max-width: 40rem; margin: 0 auto;">
    <div class="form-body">
        <form action="../../controllers/paymentController.php" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="input-group">
                <label class="form-label"><?= __('select_pending_invoice') ?> <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="material-symbols-outlined input-icon">receipt_long</span>
                    <select name="bill_id" id="bill_id" required class="input-field" style="appearance: none; cursor: pointer;">
                        <option value=""><?= __('select_invoice_to_settle') ?></option>
                        <?php while($row = $pending_bills->fetch_assoc()): ?>
                            <option value="<?= $row['bill_id'] ?>" data-amount="<?= $row['total_amount'] ?>">
                                INV-<?= str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT) ?> - <?= htmlspecialchars($row['farmer_name']) ?> (₹<?= $row['total_amount'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <span class="material-symbols-outlined" style="position: absolute; right: 0.875rem; pointer-events: none; color: var(--color-outline);">expand_more</span>
                </div>
            </div>
            
            <div style="background: rgba(0, 93, 144, 0.05); padding: 2rem; border-radius: 1rem; border: 1px solid rgba(0, 93, 144, 0.1); display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                <label style="font-size: 0.625rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.15em;"><?= __('amount_paid') ?> (₹) <span class="required">*</span></label>
                <input type="number" step="0.5" name="amount" id="amount" required class="input-field" style="max-width: 250px; text-align: center; font-size: 2.25rem; font-weight: 900; background: white; border: none; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);" placeholder="0.00">
            </div>
            
            <div class="form-grid form-grid-2">
                <div class="input-group">
                    <label class="form-label"><?= __('payment_method') ?> <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <select name="method" required class="input-field" style="appearance: none; cursor: pointer;">
                            <option value="Cash"><?= __('cash') ?></option>
                            <option value="UPI"><?= __('upi') ?> / Online</option>
                            <option value="Bank Transfer"><?= __('bank_transfer') ?></option>
                            <option value="Check"><?= __('check') ?></option>
                        </select>
                        <span class="material-symbols-outlined" style="position: absolute; right: 0.875rem; pointer-events: none; color: var(--color-outline);">expand_more</span>
                    </div>
                </div>

                <div class="input-group">
                    <label class="form-label"><?= __('payment_date') ?> <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <input type="date" name="payment_date" required value="<?= date('Y-m-d') ?>" class="input-field">
                    </div>
                </div>
            </div>
            
            <div class="form-footer" style="padding-top: 1.5rem;">
                <button type="submit" name="add_payment" class="btn bg-gradient-primary" style="width: 100%; padding: 1rem; border-radius: 0.75rem;">
                    <span class="material-symbols-outlined" style="font-size: 1.25rem;">task_alt</span>
                    <?= __('log_payment') ?>
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
