<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$query = "SELECT p.*, b.customer_id, c.farmer_name 
          FROM payments p 
          JOIN bills b ON p.bill_id = b.bill_id 
          JOIN customers c ON b.customer_id = c.customer_id 
          ORDER BY p.payment_date DESC, p.payment_id DESC";
$payments = $conn->query($query);

// Dashboard Stats Calculation
$total_collections = 0;
$cash_count = 0;
$digital_count = 0;

$payments_arr = [];
if($payments && $payments->num_rows > 0) {
    while($row = $payments->fetch_assoc()) {
        $payments_arr[] = $row;
        $total_collections += (float)$row['amount'];
        if(strtolower($row['method']) == 'cash') {
            $cash_count++;
        } else {
            $digital_count++;
        }
    }
}

// Pending sum
$pending_bills = $conn->query("SELECT SUM(total_amount) as pending_sum FROM bills WHERE status = 'pending'");
$pending_sum = $pending_bills->fetch_assoc()['pending_sum'] ?? 0;
?>

<!-- Header Section -->
<section style="margin-bottom: 2.5rem; margin-top: 1rem;">
    <div class="flex no-print" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
        <div>
            <nav class="flex" style="align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-on-surface-variant); margin-bottom: 0.5rem;">
                <span><?= __('finance') ?></span>
                <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
                <span style="color: var(--color-primary); font-weight: 500;"><?= __('ledger') ?></span>
            </nav>
            <h2 style="font-size: 2.25rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em;"><?= __('payment_ledger') ?></h2>
            <p style="color: var(--color-on-surface-variant); margin-top: 0.5rem; font-weight: 500;"><?= __('ledger_desc') ?></p>
        </div>
        <div class="flex" style="gap: 0.75rem;">
            <a href="add_payment.php" class="btn bg-gradient-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.75rem;">
                <span class="material-symbols-outlined" style="font-size: 1.125rem;">add_circle</span> <?= __('record_payment') ?>
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success_msg'])): ?>
    <div class="error-alert" style="background-color: var(--color-secondary-container); color: var(--color-on-secondary-container); border-color: rgba(44, 105, 78, 0.2); margin-bottom: 2rem;">
        <span class="material-symbols-outlined">check_circle</span>
        <span style="font-size: 0.875rem; font-weight: 700;"><?= $_SESSION['success_msg'] ?></span>
    </div>
    <?php unset($_SESSION['success_msg']); endif; ?>

    <div class="grid-4" style="grid-template-columns: repeat(3, 1fr) !important;">
        <style>
            @media (max-width: 1023px) {
                .grid-finance { grid-template-columns: repeat(1, 1fr) !important; }
            }
        </style>
        <div class="grid grid-finance" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; grid-column: 1 / -1;">
            <!-- Card 1 -->
            <div class="card" style="padding: 1.5rem; border-bottom: 4px solid rgba(0, 93, 144, 0.2);">
                <div class="flex" style="justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <span class="material-symbols-outlined" style="padding: 0.75rem; background-color: rgba(0, 93, 144, 0.1); color: var(--color-primary); border-radius: 0.5rem;">account_balance_wallet</span>
                    <span style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); border: 1px solid rgba(112, 120, 129, 0.2); padding: 0.25rem 0.5rem; border-radius: 0.25rem; text-transform: uppercase;"><?= __('overall') ?></span>
                </div>
                <p style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.1em;"><?= __('total_collections') ?></p>
                <h3 style="font-family: var(--font-headline); font-size: 1.875rem; font-weight: 800; margin-top: 0.5rem;">₹<?= number_format($total_collections, 2) ?></h3>
            </div>
            
            <!-- Card 2 -->
            <div class="card" style="padding: 1.5rem; border-bottom: 4px solid rgba(186, 26, 26, 0.2);">
                <div class="flex" style="justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <span class="material-symbols-outlined" style="padding: 0.75rem; background-color: rgba(186, 26, 26, 0.1); color: var(--color-error); border-radius: 0.5rem;">pending_actions</span>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-error); display: flex; align-items: center; gap: 0.25rem;">
                        <span class="material-symbols-outlined" style="font-size: 0.875rem;">warning</span> <?= __('high_priority') ?>
                    </span>
                </div>
                <p style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.1em;"><?= __('pending_receivables') ?></p>
                <h3 style="font-family: var(--font-headline); font-size: 1.875rem; font-weight: 800; margin-top: 0.5rem;">₹<?= number_format($pending_sum, 2) ?></h3>
            </div>
            
            <!-- Card 3 -->
            <div class="card" style="padding: 1.5rem; border-bottom: 4px solid rgba(44, 105, 78, 0.2);">
                <div class="flex" style="justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <span class="material-symbols-outlined" style="padding: 0.75rem; background-color: rgba(44, 105, 78, 0.1); color: var(--color-secondary); border-radius: 0.5rem;">splitscreen</span>
                    <span style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase;"><?= __('method_ratio') ?></span>
                </div>
                <p style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.1em;"><?= __('cash_vs_online') ?></p>
                <div style="margin-top: 0.5rem;">
                    <h3 style="font-family: var(--font-headline); font-size: 1.5rem; font-weight: 800;"><?= $cash_count ?> <span style="font-size: 0.875rem; font-weight: 400; color: var(--color-on-surface-variant);">/ <?= $digital_count ?></span></h3>
                    <?php 
                        $total = $cash_count + $digital_count;
                        $cash_pct = $total > 0 ? ($cash_count / $total) * 100 : 50; 
                        $digi_pct = $total > 0 ? ($digital_count / $total) * 100 : 50;
                    ?>
                    <div style="width: 100%; height: 0.5rem; background-color: var(--color-surface-container-high); border-radius: 9999px; margin-top: 0.5rem; overflow: hidden; display: flex;">
                        <div style="width: <?= $cash_pct ?>%; height: 100%; background-color: var(--color-tertiary);"></div>
                        <div style="width: <?= $digi_pct ?>%; height: 100%; background-color: var(--color-secondary);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Transactions Table -->
<section style="margin-bottom: 3rem;">
    <div class="table-container">
        <div class="table-header">
            <h4 style="font-family: var(--font-headline); font-size: 1.125rem; font-weight: 700;"><?= __('transaction_history') ?></h4>
            <span class="badge" style="background-color: rgba(0, 93, 144, 0.1); color: var(--color-primary); border: 1px solid rgba(0, 93, 144, 0.1);"><?= __('all_records') ?></span>
        </div>
        <div style="overflow-x: auto;">
            <table class="table-custom datatable">
                <thead>
                    <tr>
                        <th style="padding-left: 1.5rem;"><?= __('th_receipt_id') ?></th>
                        <th><?= __('th_date') ?></th>
                        <th><?= __('th_customer') ?></th>
                        <th><?= __('th_ref_invoice') ?></th>
                        <th><?= __('th_method') ?></th>
                        <th style="padding-right: 1.5rem; text-align: right;"><?= __('th_amount') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments_arr as $row): ?>
                    <tr onmouseover="this.style.backgroundColor='rgba(242, 244, 246, 0.5)';" onmouseout="this.style.backgroundColor='transparent';">
                        <td style="padding-left: 1.5rem; font-family: monospace; font-size: 0.75rem; color: var(--color-primary); font-weight: 700;">#REC-<?= str_pad($row['payment_id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td style="font-size: 0.875rem; color: var(--color-on-surface-variant); font-weight: 500;"><?= date('M d, Y', strtotime($row['payment_date'])) ?></td>
                        <td>
                            <div class="flex" style="align-items: center; gap: 0.75rem;">
                                <div style="width: 2rem; height: 2rem; border-radius: 50%; background-color: var(--color-surface-container-highest); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;"><?= substr($row['farmer_name'] ?? __('na_placeholder'), 0, 2) ?></div>
                                <span style="font-weight: 700; font-size: 0.875rem;"><?= htmlspecialchars($row['farmer_name'] ?? __('na_placeholder')) ?></span>
                            </div>
                        </td>
                        <td>
                            <a href="../billing/view_bill.php?id=<?= $row['bill_id'] ?>" style="font-size: 0.875rem; font-weight: 600; color: var(--color-primary); text-decoration: underline;">INV-<?= str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT) ?></a>
                        </td>
                        <td>
                            <?php 
                            $icon = 'payments';
                            $method = strtolower($row['method']);
                            if($method == 'upi' || $method == 'online') $icon = 'qr_code_2';
                            if($method == 'bank transfer' || $method == 'bank') $icon = 'account_balance';
                            ?>
                            <span class="badge" style="background-color: var(--color-surface-container-high); color: var(--color-on-surface-variant); border: 1px solid rgba(112, 120, 129, 0.2); display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined" style="font-size: 0.875rem;"><?= $icon ?></span> <?= __($method, $row['method']) ?>
                            </span>
                        </td>
                        <td style="padding-right: 1.5rem; text-align: right; font-family: var(--font-headline); font-weight: 800; color: var(--color-secondary);">
                            + ₹<?= number_format($row['amount'], 2) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>
