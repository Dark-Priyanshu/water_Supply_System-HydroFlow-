<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Customer.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: customer_list.php');
    exit();
}

$customer_id = (int)$_GET['id'];
$customerModel = new Customer($conn);
$customer = $customerModel->getCustomerById($customer_id);

if (!$customer) {
    header('Location: customer_list.php');
    exit();
}

// Fetch Stats
$stats = [
    'total_supplies' => 0,
    'total_hours' => 0,
    'total_billed' => 0,
    'total_paid' => 0,
    'total_pending' => 0
];

// Supplies
$supplies_query = "SELECT s.*, m.motor_name FROM water_supply s JOIN motors m ON s.motor_id = m.motor_id WHERE s.customer_id = $customer_id ORDER BY s.date DESC";
$supplies = $conn->query($supplies_query);

while ($row = $supplies->fetch_assoc()) {
    $stats['total_supplies']++;
    $stats['total_hours'] += $row['total_hours'];
}
$supplies->data_seek(0); // Reset pointer

// Bills
$bills_query = "SELECT b.*, s.date as supply_date FROM bills b LEFT JOIN water_supply s ON b.supply_id = s.supply_id WHERE b.customer_id = $customer_id ORDER BY b.bill_date DESC";
$bills = $conn->query($bills_query);

while ($row = $bills->fetch_assoc()) {
    $stats['total_billed'] += $row['total_amount'];
    if ($row['status'] == 'pending') {
        $stats['total_pending'] += $row['total_amount'];
    }
}
$bills->data_seek(0);

// Payments (Calculated from paid bills or payment table)
// Based on payment_history.php, payments link to bill_id
$payments_query = "SELECT p.*, b.bill_date as ref_bill_date FROM payments p JOIN bills b ON p.bill_id = b.bill_id WHERE b.customer_id = $customer_id ORDER BY p.payment_date DESC";
$payments = $conn->query($payments_query);

while ($row = $payments->fetch_assoc()) {
    $stats['total_paid'] += $row['amount'];
}
$payments->data_seek(0);

?>

<!-- Header Section -->
<section style="margin-bottom: 2.5rem; margin-top: 1rem;">
    <div class="flex no-print" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
        <div>
            <nav class="flex" style="align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-on-surface-variant); margin-bottom: 0.5rem;">
                <a href="customer_list.php" style="color: inherit; text-decoration: none;"><?= __('customer_management') ?></a>
                <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
                <span style="color: var(--color-primary); font-weight: 500;"><?= htmlspecialchars($customer['farmer_name']) ?></span>
            </nav>
            <h2 style="font-size: 2.25rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em;"><?= __('farmer_details') ?></h2>
            <p style="color: var(--color-on-surface-variant); margin-top: 0.5rem; font-weight: 500;"><?= __('history_ledger_for') ?> <?= htmlspecialchars($customer['farmer_name']) ?></p>
        </div>
        <div class="flex" style="gap: 0.75rem;">
            <a href="edit_customer.php?id=<?= $customer_id ?>" class="btn" style="padding: 0.75rem 1.5rem; border-radius: 0.75rem; background-color: var(--color-surface-container-highest); color: var(--color-on-surface);">
                <span class="material-symbols-outlined" style="font-size: 1.125rem;">edit</span> <?= __('edit_details') ?>
            </a>
            <button onclick="window.print()" class="btn bg-gradient-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.75rem;">
                <span class="material-symbols-outlined" style="font-size: 1.125rem;">print</span> <?= __('print_report') ?>
            </button>
        </div>
    </div>

    <!-- Farmer Info & Stats Bento -->
    <div class="grid" style="grid-template-columns: repeat(12, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <style>
            .col-info { grid-column: span 12; }
            .col-stats { grid-column: span 12; display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
            @media (min-width: 1024px) {
                .col-info { grid-column: span 4; }
                .col-stats { grid-column: span 8; grid-template-columns: repeat(4, 1fr); }
            }
            
            @media print {
                /* Show all tabs sequentially for full report */
                .tab-content { display: block !important; margin-bottom: 2rem; page-break-inside: avoid; }
                
                /* Add dynamic titles before each section */
                #tab-bills::before { content: '<?= addslashes(__('tab_bills_ledger')) ?>'; display: block; font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #000; border-bottom: 2px solid #000; padding-bottom: 0.5rem; }
                #tab-supply::before { content: '<?= addslashes(__('tab_supply_history')) ?>'; display: block; font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #000; border-bottom: 2px solid #000; padding-bottom: 0.5rem; }
                #tab-payments::before { content: '<?= addslashes(__('tab_payments')) ?>'; display: block; font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #000; border-bottom: 2px solid #000; padding-bottom: 0.5rem; }
                
                /* Fix table getting cut off, change overflow */
                .tab-content > div { overflow-x: visible !important; padding: 0 !important; }
                
                table { width: 100% !important; border-collapse: collapse !important; table-layout: fixed; }
                th, td { border-bottom: 1px solid #ccc !important; padding: 0.5rem !important; word-wrap: break-word; }
                
                /* Hide components not needed in printed report */
                .no-print-tabs { display: none !important; }
            }
        </style>
        
        <!-- Info Card -->
        <div class="card col-info" style="padding: 1.5rem; border-top: 4px solid var(--color-primary);">
            <div class="flex" style="align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 3.5rem; height: 3.5rem; border-radius: 50%; background-color: rgba(0, 93, 144, 0.1); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800;">
                    <?= strtoupper(substr(trim($customer['farmer_name']), 0, 2)) ?>
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface);"><?= htmlspecialchars($customer['farmer_name']) ?></h3>
                    <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); font-weight: 500;">ID: #<?= $customer_id ?> &bull; <?= htmlspecialchars($customer['village']) ?></p>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="flex" style="justify-content: space-between; border-bottom: 1px dashed rgba(112, 120, 129, 0.2); padding-bottom: 0.5rem;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase;"><?= __('mobile_number') ?></span>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-on-surface);"><span class="material-symbols-outlined" style="font-size: 0.875rem; vertical-align: middle; margin-right: 0.25rem; color: var(--color-primary);">call</span><?= htmlspecialchars($customer['mobile']) ?></span>
                </div>
                <div class="flex" style="justify-content: space-between; border-bottom: 1px dashed rgba(112, 120, 129, 0.2); padding-bottom: 0.5rem;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase;"><?= __('connection_no') ?></span>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-on-surface);"><?= htmlspecialchars($customer['connection_no'] ?? __('na_placeholder')) ?></span>
                </div>
                <div class="flex" style="justify-content: space-between; padding-bottom: 0.5rem;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase;"><?= __('pipe_size') ?></span>
                    <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-on-surface);"><span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background-color: var(--color-secondary); display: inline-block; margin-right: 0.35rem;"></span><?= htmlspecialchars($customer['pipe_size'] ?? __('na_placeholder')) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Stats Row -->
        <div class="col-stats">
            <!-- Total Hours -->
            <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div class="flex" style="justify-content: space-between; align-items: flex-start;">
                    <span class="material-symbols-outlined" style="padding: 0.5rem; background-color: rgba(94, 203, 198, 0.1); color: var(--color-tertiary); border-radius: 0.5rem;">water_drop</span>
                </div>
                <div>
                    <p style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;"><?= __('total_supply') ?></p>
                    <h3 style="font-size: 1.5rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface);"><?= number_format($stats['total_hours'], 1) ?> <span style="font-size: 0.875rem; font-weight: 500; color: var(--color-on-surface-variant);"><?= __('hours') ?></span></h3>
                </div>
            </div>
            
            <!-- Total Billed -->
            <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div class="flex" style="justify-content: space-between; align-items: flex-start;">
                    <span class="material-symbols-outlined" style="padding: 0.5rem; background-color: rgba(0, 93, 144, 0.1); color: var(--color-primary); border-radius: 0.5rem;">receipt_long</span>
                </div>
                <div>
                    <p style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;"><?= __('total_billed_amount') ?></p>
                    <h3 style="font-size: 1.5rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-primary);">₹<?= number_format($stats['total_billed'], 2) ?></h3>
                </div>
            </div>
            
            <!-- Total Paid -->
            <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
                <div class="flex" style="justify-content: space-between; align-items: flex-start;">
                    <span class="material-symbols-outlined" style="padding: 0.5rem; background-color: rgba(44, 105, 78, 0.1); color: var(--color-secondary); border-radius: 0.5rem;">payments</span>
                </div>
                <div>
                    <p style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;"><?= __('total_paid_amount') ?></p>
                    <h3 style="font-size: 1.5rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-secondary);">₹<?= number_format($stats['total_paid'], 2) ?></h3>
                </div>
            </div>
            
            <!-- Total Pending -->
            <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; background: linear-gradient(135deg, rgba(186,26,26,0.05), transparent);">
                <div class="flex" style="justify-content: space-between; align-items: flex-start;">
                    <span class="material-symbols-outlined" style="padding: 0.5rem; background-color: rgba(186, 26, 26, 0.1); color: var(--color-error); border-radius: 0.5rem;">warning</span>
                </div>
                <div>
                    <p style="font-size: 0.625rem; font-weight: 700; color: var(--color-error); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;"><?= __('amount_due') ?></p>
                    <h3 style="font-size: 1.5rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-error);">₹<?= number_format($stats['total_pending'], 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tables Component -->
    <div style="background-color: var(--color-surface); border-radius: 0.75rem; border: 1px solid var(--color-surface-container-high); overflow: hidden; margin-bottom: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <!-- Custom Tabs -->
        <div class="flex no-print-tabs" style="border-bottom: 1px solid var(--color-surface-container-high); background-color: var(--color-surface-container-lowest);">
            <button class="tab-btn active" onclick="switchTab('bills', this)" style="flex: 1; padding: 1rem; border: none; background: transparent; font-family: var(--font-headline); font-weight: 700; font-size: 0.875rem; color: var(--color-primary); border-bottom: 2px solid var(--color-primary); cursor: pointer; transition: all 0.2s;">
                <span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem;">receipt</span> <?= __('tab_bills_ledger') ?>
            </button>
            <button class="tab-btn" onclick="switchTab('supply', this)" style="flex: 1; padding: 1rem; border: none; background: transparent; font-family: var(--font-headline); font-weight: 700; font-size: 0.875rem; color: var(--color-on-surface-variant); border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;">
                <span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem;">water</span> <?= __('tab_supply_history') ?>
            </button>
            <button class="tab-btn" onclick="switchTab('payments', this)" style="flex: 1; padding: 1rem; border: none; background: transparent; font-family: var(--font-headline); font-weight: 700; font-size: 0.875rem; color: var(--color-on-surface-variant); border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;">
                <span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem;">account_balance_wallet</span> <?= __('tab_payments') ?>
            </button>
        </div>
        
        <!-- Bills Content -->
        <div id="tab-bills" class="tab-content" style="display: block;">
            <div style="overflow-x: auto; padding: 1rem;">
                <table class="table-custom datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding-left: 1rem;"><?= __('th_inv_no') ?></th>
                            <th><?= __('lbl_date') ?></th>
                            <th><?= __('th_supply_hrs') ?></th>
                            <th><?= __('th_rate_rs') ?></th>
                            <th style="text-align: right;"><?= __('th_amount_rs') ?></th>
                            <th style="text-align: center;"><?= __('th_status') ?></th>
                            <th class="no-print" style="padding-right: 1rem; text-align: right;"><?= __('th_actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bills->num_rows > 0): ?>
                            <?php while($b = $bills->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid var(--color-surface-container-high);">
                                <td style="padding-left: 1rem; font-weight: 700; color: var(--color-primary);">INV-<?= str_pad($b['bill_id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td style="color: var(--color-on-surface-variant);"><?= date('M d, Y', strtotime($b['bill_date'])) ?></td>
                                <td><span style="background-color: rgba(94, 203, 198, 0.1); color: var(--color-tertiary); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 700;"><?= $b['total_hours'] ?> <?= __('hours') ?></span></td>
                                <td>₹<?= number_format($b['rate'], 2) ?></td>
                                <td style="text-align: right; font-weight: 700;">₹<?= number_format($b['total_amount'], 2) ?></td>
                                <td style="text-align: center;">
                                    <?php if ($b['status'] == 'paid'): ?>
                                        <span class="badge" style="background-color: rgba(44, 105, 78, 0.1); color: var(--color-secondary); border: 1px solid rgba(44, 105, 78, 0.2);"><?= __('status_paid') ?></span>
                                    <?php else: ?>
                                        <span class="badge" style="background-color: rgba(186, 26, 26, 0.1); color: var(--color-error); border: 1px solid rgba(186, 26, 26, 0.2);"><?= __('status_pending') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="no-print" style="padding-right: 1rem; text-align: right;">
                                    <a href="../billing/view_bill.php?id=<?= $b['bill_id'] ?>" style="color: var(--color-primary); padding: 0.25rem;" title="<?= __('tooltip_view') ?>"><span class="material-symbols-outlined" style="font-size: 1.125rem;">visibility</span></a>
                                    <?php if($b['status'] == 'pending'): ?>
                                    <a href="../payments/add_payment.php?bill_id=<?= $b['bill_id'] ?>" style="color: var(--color-secondary); padding: 0.25rem;" title="<?= __('tooltip_pay_now') ?>"><span class="material-symbols-outlined" style="font-size: 1.125rem;">payments</span></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center; padding: 2rem; color: var(--color-on-surface-variant);"><?= __('no_billing_records') ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Supply Content -->
        <div id="tab-supply" class="tab-content" style="display: none;">
            <div style="overflow-x: auto; padding: 1rem;">
                <table class="table-custom datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding-left: 1rem;"><?= __('th_supply_id') ?></th>
                            <th><?= __('lbl_date') ?></th>
                            <th><?= __('th_motor_used') ?></th>
                            <th><?= __('th_time_period') ?></th>
                            <th><?= __('th_total_hours') ?></th>
                            <th style="padding-right: 1rem; text-align: right;"><?= __('th_cost_evaluated') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($supplies->num_rows > 0): ?>
                            <?php while($s = $supplies->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid var(--color-surface-container-high);">
                                <td style="padding-left: 1rem; font-weight: 700; color: var(--color-primary);">#SUP-<?= str_pad($s['supply_id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td style="color: var(--color-on-surface-variant);"><?= date('M d, Y', strtotime($s['date'])) ?></td>
                                <td><?= htmlspecialchars($s['motor_name']) ?></td>
                                <td><span style="background-color: var(--color-surface-container-highest); color: var(--color-on-surface); padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-family: monospace; font-weight: 600;"><?= date('H:i', strtotime($s['start_time'])) ?> - <?= date('H:i', strtotime($s['end_time'])) ?></span></td>
                                <td><span style="font-weight: 700; color: var(--color-tertiary);"><?= $s['total_hours'] ?> <?= __('hours') ?></span></td>
                                <td style="padding-right: 1rem; text-align: right; font-weight: 600;">₹<?= number_format($s['total_amount'], 2) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--color-on-surface-variant);"><?= __('no_supply_records') ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Payments Content -->
        <div id="tab-payments" class="tab-content" style="display: none;">
            <div style="overflow-x: auto; padding: 1rem;">
                <table class="table-custom datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding-left: 1rem;"><?= __('th_receipt_id') ?></th>
                            <th><?= __('payment_date') ?></th>
                            <th><?= __('th_against_bill') ?></th>
                            <th><?= __('th_method') ?></th>
                            <th style="padding-right: 1rem; text-align: right;"><?= __('th_amount_rs') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($payments->num_rows > 0): ?>
                            <?php while($p = $payments->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid var(--color-surface-container-high);">
                                <td style="padding-left: 1rem; font-weight: 700; color: var(--color-secondary);">#REC-<?= str_pad($p['payment_id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td style="color: var(--color-on-surface-variant);"><?= date('M d, Y', strtotime($p['payment_date'])) ?></td>
                                <td><a href="../billing/view_bill.php?id=<?= $p['bill_id'] ?>" style="color: var(--color-primary); font-weight: 600; text-decoration: underline;">INV-<?= str_pad($p['bill_id'], 4, '0', STR_PAD_LEFT) ?></a></td>
                                <td>
                                    <?php 
                                    $icon = 'payments';
                                    $method = strtolower($p['method']);
                                    if($method == 'upi' || $method == 'online') $icon = 'qr_code_2';
                                    if($method == 'bank transfer' || $method == 'bank') $icon = 'account_balance';
                                    ?>
                                    <span style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; font-weight: 600; background-color: var(--color-surface-container-highest); padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                        <span class="material-symbols-outlined" style="font-size: 0.875rem;"><?= $icon ?></span> <?= __($method, ucfirst($p['method'])) ?>
                                    </span>
                                </td>
                                <td style="padding-right: 1rem; text-align: right; font-weight: 700; color: var(--color-secondary);">+ ₹<?= number_format($p['amount'], 2) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align: center; padding: 2rem; color: var(--color-on-surface-variant);"><?= __('no_payment_records') ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
function switchTab(tabId, element) {
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    // Depress all buttons
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.style.color = 'var(--color-on-surface-variant)';
        el.style.borderBottomColor = 'transparent';
        el.classList.remove('active');
    });
    
    // Show selected content
    document.getElementById('tab-' + tabId).style.display = 'block';
    // Highlight button
    element.style.color = 'var(--color-primary)';
    element.style.borderBottomColor = 'var(--color-primary)';
    element.classList.add('active');
}
</script>

<?php include '../../includes/footer.php'; ?>
