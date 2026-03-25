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
    /* Reset everything */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    html, body {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        min-height: 0 !important;
        height: auto !important;
        font-size: 11px !important;
    }
    /* Hide all non-invoice elements */
    .no-print, aside, header, nav { display: none !important; }
    /* Remove main wrapper padding/margin so invoice fills page */
    main {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        min-height: 0 !important;
        display: block !important;
    }
    .px-4, .px-10, .lg\:px-10 { padding-left: 0 !important; padding-right: 0 !important; }
    .pb-12 { padding-bottom: 0 !important; }
    /* Invoice card: full page, clean white, no shadow/border */
    .invoice-card {
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        background-color: #ffffff !important;
        transform: scale(0.8);
        transform-origin: top left;
    }
    /* Remove the dot background in print */
    .invoice-paper {
        background-image: none !important;
        padding: 20mm 18mm !important;
    }
    /* Ensure slate colors render correctly */
    .text-slate-900 { color: #0f172a !important; }
    .text-slate-800 { color: #1e293b !important; }
    .text-slate-600 { color: #475569 !important; }
    .text-slate-500 { color: #64748b !important; }
    .border-slate-200 { border-color: #e2e8f0 !important; }
    .bg-slate-200\/50, .bg-slate-100 { background-color: #f1f5f9 !important; }
    /* Keep bill-to box border visible */
    .rounded-2xl { border-radius: 12px !important; }
    /* Table header */
    thead tr { background-color: #f1f5f9 !important; }
    /* Prevent page breaks inside invoice */
    .invoice-card, table { page-break-inside: avoid; }
}
@page {
    size: A4;
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
<div class="bg-white rounded-xl p-0 overflow-hidden invoice-card max-w-5xl mx-auto shadow-sm border border-slate-200/60 font-sans">
    
    <div class="p-8 md:p-14 invoice-paper">
        <!-- Header: Logo/Text (Left) and INVOICE text (Right) -->
        <div class="flex flex-col md:flex-row justify-between md:items-start mb-16 gap-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 flex items-center justify-center grayscale opacity-80">
                    <img src="../../assets/images/icon.png" alt="HydroFlow Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">HydroFlow</h3>
                    <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold mb-3">Irrigation Systems</p>
                    <p class="text-[11px] text-slate-600 font-medium tracking-wide">Professional Water Supply Management</p>
                    <p class="text-[11px] text-slate-500 tracking-wide">Generated via HydroFlow System</p>
                </div>
            </div>
            <div class="md:text-right">
                <h4 class="text-4xl font-extrabold text-slate-900 mb-2 tracking-tight">INVOICE</h4>
                <div class="space-y-1 mt-3">
                    <p class="text-[13px] font-bold text-slate-900 tracking-wider">#INV-<?= str_pad($bill['bill_id'], 4, '0', STR_PAD_LEFT) ?></p>
                    <p class="text-[11px] text-slate-600 font-medium tracking-wide">Issue Date: <?= date('M d, Y', strtotime($bill['bill_date'])) ?></p>
                    <p class="text-[11px] text-slate-600 font-medium tracking-wide">Due Date: <?= date('M d, Y', strtotime($bill['bill_date']. ' + 7 days')) ?></p>
                </div>
            </div>
        </div>

        <!-- Middle Section: Bill To and Payment Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16 items-start">
            <div class="p-6 md:p-8 rounded-2xl border border-slate-200 bg-white/50 w-full max-w-[400px]">
                <h5 class="text-[9px] uppercase tracking-widest text-slate-500 font-bold mb-4">Bill To (Customer):</h5>
                <p class="text-xl font-bold text-slate-900 mb-1"><?= htmlspecialchars($bill['farmer_name']) ?></p>
                <p class="text-sm text-slate-600 mb-1"><?= htmlspecialchars($bill['village']) ?></p>
                <p class="text-[13px] text-slate-600">+91 <?= htmlspecialchars($bill['mobile']) ?></p>
            </div>
            
            <div class="flex flex-col md:items-end justify-center h-full pt-8 md:pt-0">
                <div class="text-center md:text-right w-full flex flex-col md:items-end">
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest font-bold mb-3 pl-2">Payment Status</p>
                    <?php if($bill['status'] == 'paid'): ?>
                        <span class="inline-block px-4 py-2 rounded-[20px] bg-slate-100 text-slate-800 text-[10px] font-bold uppercase tracking-widest border border-slate-200">PAID & SETTLED</span>
                    <?php elseif($bill['status'] == 'cancelled'): ?>
                        <span class="inline-block px-4 py-2 rounded-[20px] bg-slate-100 text-slate-800 text-[10px] font-bold uppercase tracking-widest border border-slate-200">CANCELLED</span>
                    <?php else: ?>
                        <span class="inline-block px-4 py-2 rounded-[20px] bg-slate-200/50 text-slate-600 text-[10px] font-bold uppercase tracking-widest border border-slate-200/50">PENDING / AWAITING</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Invoice Table -->
        <div class="mb-12 overflow-x-auto">
            <table class="w-full border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-200/50">
                        <th class="text-left py-4 px-6 text-[9px] uppercase tracking-widest font-bold text-slate-600 rounded-l-xl w-1/5">Supply Date</th>
                        <th class="text-left py-4 px-6 text-[9px] uppercase tracking-widest font-bold text-slate-600 w-1/3">Details</th>
                        <th class="text-center py-4 px-6 text-[9px] uppercase tracking-widest font-bold text-slate-600">Usage (hrs)</th>
                        <th class="text-right py-4 px-6 text-[9px] uppercase tracking-widest font-bold text-slate-600">Rate/Hr</th>
                        <th class="text-right py-4 px-6 text-[9px] uppercase tracking-widest font-bold text-slate-600 rounded-r-xl">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-slate-100">
                        <td class="py-6 px-6 text-[13px] text-slate-900 font-bold"><?= date('M d, Y', strtotime($bill['supply_date'])) ?></td>
                        <td class="py-6 px-6">
                            <p class="text-[13px] font-bold text-slate-900 mb-1">Irrigation Water Supply</p>
                            <p class="text-[11px] text-slate-500 font-medium">Time: <?= date('h:i A', strtotime($bill['start_time'])) ?> - <?= date('h:i A', strtotime($bill['end_time'])) ?></p>
                        </td>
                        <td class="py-6 px-6 text-center text-[13px] font-bold text-slate-900"><?= number_format($bill['total_hours'], 2) ?></td>
                        <td class="py-6 px-6 text-right text-[13px] text-slate-600 font-medium">₹<?= number_format($bill['rate'], 2) ?></td>
                        <td class="py-6 px-6 text-right text-sm font-bold text-slate-900">₹<?= number_format($bill['total_amount'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="flex flex-col items-end mb-16">
            <div class="w-full max-w-sm space-y-4">
                <div class="flex justify-between items-center text-[13px] text-slate-600 font-medium px-2">
                    <span>Subtotal</span>
                    <span class="font-bold text-slate-800">₹<?= number_format($bill['total_amount'], 2) ?></span>
                </div>
                <div class="flex justify-between items-center py-4 px-2 mt-4">
                    <span class="text-[15px] font-bold text-slate-600 tracking-wide uppercase">Total Due</span>
                    <span class="text-[28px] font-extrabold text-slate-800 tracking-tight">₹<?= number_format($bill['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>
        
        <div class="h-[1px] w-full bg-slate-100 mb-8 block"></div>

        <!-- Footer Notes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
            <div>
                <h6 class="text-[9px] uppercase font-bold text-slate-600 tracking-widest mb-3">Terms & Conditions</h6>
                <ul class="text-[10px] text-slate-600 leading-relaxed list-disc pl-4 space-y-1 font-medium">
                    <li>Payment is due within 7 days of invoice issue.</li>
                    <li>This invoice is system-generated based on supply logs.</li>
                </ul>
            </div>
            <div class="md:text-right flex flex-col md:justify-end md:items-end">
                <p class="text-[9px] uppercase font-bold text-slate-600 tracking-widest">Authorized Signatory</p>
                <p class="text-[10px] text-slate-500 mt-1">HydroFlow Mgmt.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
