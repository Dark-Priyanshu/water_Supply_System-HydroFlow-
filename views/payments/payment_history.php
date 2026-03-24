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
if($payments->num_rows > 0) {
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
<section class="mb-10 mt-4">
    <div class="flex justify-between items-end mb-8 block hidden-print">
        <div>
            <nav class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
                <span>Finance</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-medium">Ledger</span>
            </nav>
            <h2 class="font-headline text-4xl font-extrabold tracking-tight text-on-surface">Payment Ledger</h2>
            <p class="text-on-surface-variant mt-2 font-medium">Manage revenue streams and track hydrological billing cycles.</p>
        </div>
        <div class="flex gap-3 hidden-print">
            <a href="add_payment.php" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-primary-container text-white font-semibold rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform">
                <span class="material-symbols-outlined text-sm">add_circle</span> Record Payment
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success_msg'])): ?>
    <div class="mb-8 p-4 bg-secondary-container text-on-secondary-container rounded-xl flex items-center gap-3">
        <span class="material-symbols-outlined">check_circle</span>
        <span class="text-sm font-bold"><?= $_SESSION['success_msg'] ?></span>
    </div>
    <?php unset($_SESSION['success_msg']); endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="relative overflow-hidden bg-surface-container-lowest p-6 rounded-xl group border border-outline-variant/10">
            <div class="flex justify-between items-start mb-4">
                <span class="p-3 bg-primary/10 text-primary rounded-lg material-symbols-outlined">account_balance_wallet</span>
                <span class="text-[10px] font-bold text-on-surface-variant border border-outline-variant/20 px-2 py-1 rounded">Overall</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest">Total Collections</p>
            <h3 class="font-headline text-3xl font-extrabold mt-2 text-on-surface">₹<?= number_format($total_collections, 2) ?></h3>
            <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary/20 to-transparent"></div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-all"></div>
        </div>
        
        <!-- Card 2 -->
        <div class="relative overflow-hidden bg-surface-container-lowest p-6 rounded-xl group border border-outline-variant/10">
            <div class="flex justify-between items-start mb-4">
                <span class="p-3 bg-error/10 text-error rounded-lg material-symbols-outlined">pending_actions</span>
                <span class="text-xs font-bold text-error flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">warning</span> High Priority
                </span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest">Pending Receivables</p>
            <h3 class="font-headline text-3xl font-extrabold mt-2 text-on-surface">₹<?= number_format($pending_sum, 2) ?></h3>
            <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-error/20 to-transparent"></div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-error/5 rounded-full blur-2xl group-hover:bg-error/10 transition-all"></div>
        </div>
        
        <!-- Card 3 -->
        <div class="relative overflow-hidden bg-surface-container-lowest p-6 rounded-xl group border border-outline-variant/10">
            <div class="flex justify-between items-start mb-4">
                <span class="p-3 bg-secondary/10 text-secondary rounded-lg material-symbols-outlined">splitscreen</span>
                <span class="text-[10px] font-bold text-on-surface-variant">Method Splitting</span>
            </div>
            <p class="text-on-surface-variant text-xs font-bold uppercase tracking-widest">Cash vs Online Ratio</p>
            <div class="flex items-center gap-4 mt-2">
                <div class="w-full">
                    <h3 class="font-headline text-2xl font-extrabold text-on-surface"><?= $cash_count ?> <span class="text-sm font-normal text-on-surface-variant">/ <?= $digital_count ?></span></h3>
                    <?php 
                        $total = $cash_count + $digital_count;
                        $cash_pct = $total > 0 ? ($cash_count / $total) * 100 : 50; 
                        $digi_pct = $total > 0 ? ($digital_count / $total) * 100 : 50;
                    ?>
                    <div class="w-full h-2 bg-surface-container-high rounded-full mt-2 overflow-hidden flex">
                        <div style="width: <?= $cash_pct ?>%" class="h-full bg-tertiary"></div>
                        <div style="width: <?= $digi_pct ?>%" class="h-full bg-secondary"></div>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-secondary/20 to-transparent"></div>
        </div>
    </div>
</section>

<!-- Transactions Table -->
<section class="xl:col-span-2 mb-12">
    <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0_8px_32px_rgba(25,28,30,0.04)] border border-outline-variant/10">
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-low/50">
            <h4 class="font-headline text-lg font-bold text-on-surface">Transaction History</h4>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full border border-primary/20">All Records</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left datatable">
                <thead class="bg-surface-container-high/30">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Receipt ID</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Ref Invoice</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Method</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    <?php foreach ($payments_arr as $row): ?>
                    <tr class="hover:bg-surface-container-low/40 transition-colors group">
                        <td class="px-6 py-5 font-mono text-xs text-primary font-bold">#REC-<?= str_pad($row['payment_id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td class="px-6 py-5 text-sm text-on-surface-variant font-medium"><?= date('M d, Y', strtotime($row['payment_date'])) ?></td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant font-bold text-xs uppercase"><?= substr($row['farmer_name'], 0, 2) ?></div>
                                <span class="text-sm font-bold text-on-surface"><?= htmlspecialchars($row['farmer_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <a href="../billing/view_bill.php?id=<?= $row['bill_id'] ?>" class="text-sm font-semibold text-primary hover:underline hover:text-primary-container transition-colors">INV-<?= str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT) ?></a>
                        </td>
                        <td class="px-6 py-5">
                            <?php 
                            $icon = 'payments';
                            if(strtolower($row['method']) == 'upi' || strtolower($row['method']) == 'online') $icon = 'qr_code_2';
                            if(strtolower($row['method']) == 'bank transfer') $icon = 'account_balance';
                            ?>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold py-1 px-2 rounded bg-surface-container-high text-on-surface-variant uppercase border border-outline-variant/20">
                                <span class="material-symbols-outlined text-[14px]"><?= $icon ?></span> <?= htmlspecialchars($row['method']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right font-headline">
                            <span class="text-sm font-extrabold text-secondary">+ ₹<?= number_format($row['amount'], 2) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>
