<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Bill.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
if (!isset($_GET['id'])) {
    header("Location: bill_history.php");
    exit();
}

$bill_id = (int)$_GET['id'];
$billModel = new Bill($conn);
$bill = $billModel->getBillById($bill_id);

if (!$bill) {
    echo "<div class='text-error font-bold p-10'>Bill not found.</div>";
    include '../../includes/footer.php';
    exit();
}
?>

<style>
.invoice-paper {
    background-image: radial-gradient(#eceef0 0.5px, transparent 0.5px);
    background-size: 24px 24px;
}
@media print {
    body { background-color: #fff !important; }
    .no-print, aside, header { display: none !important; }
    main { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; min-height: 0 !important; }
    .px-10 { padding-left: 0 !important; padding-right: 0 !important; }
    .pb-12 { padding-bottom: 0 !important; }
    .invoice-card { box-shadow: none !important; border: none !important; border-radius: 0 !important; margin: 0 !important; max-width: 100% !important; page-break-inside: avoid; }
    html, body { min-height: 0 !important; height: auto !important; }
    * { 
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
@page {
    size: auto;
    margin: 0;
}
</style>

<!-- Action Bar (Not Printed) -->
<div class="flex justify-between items-end mb-8 mt-4 no-print">
    <div>
        <nav class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
            <span>Finance</span>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a href="bill_history.php" class="hover:text-primary transition-colors">Invoices</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary font-medium">Invoice View</span>
        </nav>
        <h2 class="text-3xl font-bold font-headline text-on-surface tracking-tight">Invoice Details</h2>
    </div>
    <div class="flex gap-3">
        <button class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-lowest hover:bg-surface-container-low transition-all text-sm font-semibold text-on-surface-variant shadow-sm" onclick="window.print()">
            <span class="material-symbols-outlined text-[20px]">print</span>
            Print Bill
        </button>
    </div>
</div>

<?php if (isset($_SESSION['success_msg'])): ?>
<div class="mb-6 p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center gap-3 no-print">
    <span class="material-symbols-outlined text-secondary">check_circle</span>
    <span class="text-secondary font-medium"><?= $_SESSION['success_msg'] ?></span>
</div>
<?php unset($_SESSION['success_msg']); endif; ?>

<!-- Invoice Container -->
<div class="bg-surface-container-lowest rounded-2xl p-0 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)] overflow-hidden invoice-card max-w-5xl">
    <!-- Top Accent Bar -->
    <div class="h-2 bg-gradient-to-r from-primary via-primary-container to-secondary"></div>
    
    <div class="p-8 md:p-12 invoice-paper">
        <!-- Invoice Header -->
        <div class="flex flex-col md:flex-row justify-between md:items-start mb-16 gap-8">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center">
                        <img src="../../assets/images/icon.png" alt="HydroFlow Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-primary font-headline tracking-tight">HydroFlow</h3>
                        <p class="text-[11px] uppercase tracking-[0.2em] text-on-surface-variant font-bold">Irrigation Systems</p>
                    </div>
                </div>
                <div class="text-sm text-on-surface-variant leading-relaxed font-medium">
                    <p>Professional Water Supply Management</p>
                    <p>Generated via HydroFlow System</p>
                </div>
            </div>
            <div class="md:text-right border-l-[4px] pl-6 md:border-l-0 md:pl-0 border-primary">
                <h4 class="text-4xl font-extrabold text-on-surface font-headline mb-2">INVOICE</h4>
                <div class="space-y-1">
                    <p class="text-sm font-bold text-on-surface tracking-wider">#INV-<?= str_pad($bill['bill_id'], 4, '0', STR_PAD_LEFT) ?></p>
                    <p class="text-xs text-on-surface-variant font-medium">Issue Date: <?= date('M d, Y', strtotime($bill['bill_date'])) ?></p>
                    <p class="text-xs text-on-surface-variant font-medium">Due Date: <?= date('M d, Y', strtotime($bill['bill_date']. ' + 7 days')) ?></p>
                </div>
            </div>
        </div>

        <!-- Client Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="p-8 rounded-2xl bg-surface-container-low/50 border border-outline-variant/10">
                <h5 class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-4">Bill To (Customer):</h5>
                <div class="space-y-1">
                    <p class="text-xl font-bold text-on-surface"><?= htmlspecialchars($bill['farmer_name']) ?></p>
                    <?php if(!empty($bill['farm_name'])): ?>
                        <p class="text-sm text-on-surface-variant font-bold"><?= htmlspecialchars($bill['farm_name']) ?></p>
                    <?php endif; ?>
                    <p class="text-sm text-on-surface-variant font-medium mt-2"><?= htmlspecialchars($bill['village']) ?></p>
                    <p class="text-sm text-on-surface-variant">+91 <?= htmlspecialchars($bill['mobile']) ?></p>
                    <?php if(!empty($bill['connection_no'])): ?>
                        <p class="text-xs text-on-surface-variant mt-2 px-2 py-1 bg-surface-container rounded inline-block font-bold">Conn: <?= htmlspecialchars($bill['connection_no']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="p-8 flex flex-col justify-end md:text-right">
                <div class="space-y-2">
                    <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Payment Status</p>
                    <?php if($bill['status'] == 'paid'): ?>
                        <span class="inline-block px-5 py-2 rounded-full bg-secondary/10 text-secondary text-xs font-bold uppercase tracking-wider border border-secondary/20">PAID & SETTLED</span>
                    <?php elseif($bill['status'] == 'cancelled'): ?>
                        <span class="inline-block px-5 py-2 rounded-full bg-error/10 text-error text-xs font-bold uppercase tracking-wider border border-error/20">CANCELLED</span>
                    <?php else: ?>
                        <span class="inline-block px-5 py-2 rounded-full bg-surface-container-high text-on-surface-variant text-xs font-bold uppercase tracking-wider border border-outline-variant/30">PENDING / AWAITING</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Invoice Table -->
        <div class="mb-12 overflow-x-auto">
            <table class="w-full border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-surface-container-high">
                        <th class="text-left py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant rounded-l-xl">Supply Date</th>
                        <th class="text-left py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Details</th>
                        <th class="text-center py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Usage (hrs)</th>
                        <th class="text-right py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Rate/Hr</th>
                        <th class="text-right py-4 px-6 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant rounded-r-xl">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container border-b border-outline-variant/10">
                    <tr class="hover:bg-surface-container-low/30 transition-colors">
                        <td class="py-6 px-6 text-sm text-on-surface font-semibold"><?= date('M d, Y', strtotime($bill['supply_date'])) ?></td>
                        <td class="py-6 px-6">
                            <p class="text-sm font-bold text-on-surface mb-1">Irrigation Water Supply</p>
                            <p class="text-xs text-on-surface-variant font-medium">Time: <?= date('h:i A', strtotime($bill['start_time'])) ?> - <?= date('h:i A', strtotime($bill['end_time'])) ?></p>
                        </td>
                        <td class="py-6 px-6 text-center text-sm font-bold text-on-surface"><?= number_format($bill['total_hours'], 2) ?></td>
                        <td class="py-6 px-6 text-right text-sm text-on-surface-variant font-medium">₹<?= number_format($bill['rate'], 2) ?></td>
                        <td class="py-6 px-6 text-right text-sm font-bold text-on-surface">₹<?= number_format($bill['total_amount'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="flex flex-col items-end">
            <div class="w-full max-w-sm space-y-4">
                <div class="flex justify-between items-center text-sm text-on-surface-variant font-medium">
                    <span>Subtotal</span>
                    <span class="font-semibold text-on-surface">₹<?= number_format($bill['total_amount'], 2) ?></span>
                </div>
                <div class="h-[1px] bg-outline-variant/30"></div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-lg font-bold text-primary font-headline tracking-tight">TOTAL DUE</span>
                    <span class="text-3xl font-extrabold text-primary font-headline tracking-tighter">₹<?= number_format($bill['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="mt-20 pt-8 border-t border-outline-variant/20 grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h6 class="text-[10px] uppercase font-bold text-on-surface-variant tracking-widest mb-3">Terms & Conditions</h6>
                <ul class="text-[11px] text-on-surface-variant leading-relaxed list-disc pl-4 space-y-1 font-medium">
                    <li>Payment is due within 7 days of invoice issue.</li>
                    <li>This invoice is system-generated based on supply logs.</li>
                </ul>
            </div>
            <div class="md:text-right flex flex-col md:justify-end md:items-end">
                <div class="h-[1px] w-48 bg-outline-variant/50 mb-3 block"></div>
                <p class="text-[10px] uppercase font-bold text-on-surface-variant tracking-widest">Authorized Signatory</p>
                <p class="text-[10px] text-on-surface-variant mt-1">HydroFlow Mgmt.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
