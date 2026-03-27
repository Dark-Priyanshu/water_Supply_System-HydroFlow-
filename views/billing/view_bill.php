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
    echo "<div style='padding: 2.5rem; text-align: center; color: var(--color-error); font-weight: 700; font-family: var(--font-headline);'>Bill not found.</div>";
    include '../../includes/footer.php';
    exit();
}
?>

<style>
.invoice-paper {
    background-image: radial-gradient(rgba(112, 120, 129, 0.2) 0.5px, transparent 0.5px);
    background-size: 24px 24px;
}
@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    html, body {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
        font-size: 11px !important;
    }
    .no-print, aside, header, .sidebar_custom, .header_custom { display: none !important; }
    main {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        display: block !important;
    }
    .invoice-card {
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        background-color: #ffffff !important;
    }
    .invoice-paper {
        background-image: none !important;
        padding: 10mm !important;
    }
    .invoice-card, table { page-break-inside: avoid; }
}
</style>

<!-- Action Bar -->
<div class="flex no-print" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="breadcrumb">
            <span>Finance</span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <a href="bill_history.php">Invoices</a>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;">Invoice View</span>
        </nav>
        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;">Invoice Details</h2>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <button class="btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;" onclick="window.print()">
            <span class="material-symbols-outlined" style="font-size: 1.25rem;">print</span>
            <span>Print Bill</span>
        </button>
    </div>
</div>

<?php if (isset($_SESSION['success_msg'])): ?>
<div class="card no-print" style="margin-bottom: 1.5rem; padding: 1rem 1.5rem; background: rgba(44, 105, 78, 0.1); border: 1px solid rgba(44, 105, 78, 0.2); display: flex; align-items: center; gap: 0.75rem;">
    <span class="material-symbols-outlined" style="color: var(--color-secondary);">check_circle</span>
    <span style="color: var(--color-secondary); font-weight: 600; font-size: 0.875rem;"><?= $_SESSION['success_msg'] ?></span>
</div>
<?php unset($_SESSION['success_msg']); endif; ?>

<!-- Invoice Container -->
<div class="card invoice-card" style="max-width: 60rem; margin: 0 auto; padding: 0; overflow: hidden; border: 1px solid rgba(112, 120, 129, 0.2);">
    
    <div class="invoice-paper" style="padding: 4rem;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3rem; height: 3rem; display: flex; align-items: center; justify-content: center; opacity: 0.8;">
                    <img src="../../assets/images/icon.png" alt="HydroFlow Logo" style="width: 100%; height: 100%; object-contain;">
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0; font-family: var(--font-headline);">HydroFlow</h3>
                    <p style="font-size: 0.625rem; text-transform: uppercase; letter-spacing: 0.2em; color: #64748b; font-weight: 700; margin: 0.25rem 0 0.5rem;">Irrigation Systems</p>
                    <p style="font-size: 0.6875rem; color: #475569; font-weight: 600;">Professional Water Supply Management</p>
                </div>
            </div>
            <div style="text-align: right;">
                <h4 style="font-size: 2.5rem; font-weight: 900; color: #0f172a; margin: 0; font-family: var(--font-headline); letter-spacing: -0.025em;">INVOICE</h4>
                <div style="margin-top: 1rem; display: flex; flex-direction: column; gap: 0.25rem;">
                    <p style="font-size: 0.8125rem; font-weight: 700; color: #0f172a; letter-spacing: 0.05em;">#INV-<?= str_pad($bill['bill_id'], 4, '0', STR_PAD_LEFT) ?></p>
                    <p style="font-size: 0.6875rem; color: #64748b; font-weight: 600;">Issue Date: <?= date('M d, Y', strtotime($bill['bill_date'])) ?></p>
                    <p style="font-size: 0.6875rem; color: #64748b; font-weight: 600;">Due Date: <?= date('M d, Y', strtotime($bill['bill_date']. ' + 7 days')) ?></p>
                </div>
            </div>
        </div>

        <!-- Middle Section -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 4rem; align-items: start;">
            <div style="padding: 2rem; border-radius: 1.25rem; border: 1px solid #e2e8f0; background: rgba(255,255,255,0.5); max-width: 25rem;">
                <h5 style="font-size: 0.5625rem; text-transform: uppercase; letter-spacing: 0.2em; color: #64748b; font-weight: 800; margin-bottom: 1rem;">Bill To (Customer):</h5>
                <p style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;"><?= htmlspecialchars($bill['farmer_name']) ?></p>
                <p style="font-size: 0.875rem; color: #475569; margin-bottom: 0.25rem;"><?= htmlspecialchars($bill['village']) ?></p>
                <p style="font-size: 0.8125rem; color: #475569; font-weight: 600;">+91 <?= htmlspecialchars($bill['mobile']) ?></p>
            </div>
            
            <div style="display: flex; flex-direction: column; align-items: flex-end; justify-content: center; height: 100%;">
                <p style="font-size: 0.5625rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.2em; font-weight: 800; margin-bottom: 0.75rem;">Payment Status</p>
                <?php if($bill['status'] == 'paid'): ?>
                    <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 999px; background: #f1f5f9; color: #1e293b; font-size: 0.625rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; border: 1px solid #e2e8f0;">PAID & SETTLED</span>
                <?php elseif($bill['status'] == 'cancelled'): ?>
                    <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 999px; background: #f1f5f9; color: #1e293b; font-size: 0.625rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; border: 1px solid #e2e8f0;">CANCELLED</span>
                <?php else: ?>
                    <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 999px; background: #f8fafc; color: #64748b; font-size: 0.625rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; border: 1px solid #f1f5f9;">PENDING / AWAITING</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Invoice Table -->
        <div style="margin-bottom: 3rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 44rem;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="text-align: left; padding: 1rem 1.5rem; font-size: 0.5625rem; text-transform: uppercase; letter-spacing: 0.2em; font-weight: 800; color: #475569; border-radius: 0.75rem 0 0 0.75rem; width: 20%;">Supply Date</th>
                        <th style="text-align: left; padding: 1rem 1.5rem; font-size: 0.5625rem; text-transform: uppercase; letter-spacing: 0.2em; font-weight: 800; color: #475569; width: 33%;">Details</th>
                        <th style="text-align: center; padding: 1rem 1.5rem; font-size: 0.5625rem; text-transform: uppercase; letter-spacing: 0.2em; font-weight: 800; color: #475569;">Usage (hrs)</th>
                        <th style="text-align: right; padding: 1rem 1.5rem; font-size: 0.5625rem; text-transform: uppercase; letter-spacing: 0.2em; font-weight: 800; color: #475569;">Rate/Hr</th>
                        <th style="text-align: right; padding: 1rem 1.5rem; font-size: 0.5625rem; text-transform: uppercase; letter-spacing: 0.2em; font-weight: 800; color: #475569; border-radius: 0 0.75rem 0.75rem 0;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 1.5rem; font-size: 0.8125rem; color: #0f172a; font-weight: 800; border-bottom: 1px solid #f1f5f9;"><?= date('M d, Y', strtotime($bill['supply_date'])) ?></td>
                        <td style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9;">
                            <p style="font-size: 0.8125rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">Irrigation Water Supply</p>
                            <p style="font-size: 0.6875rem; color: #64748b; font-weight: 600;">Time: <?= date('h:i A', strtotime($bill['start_time'])) ?> - <?= date('h:i A', strtotime($bill['end_time'])) ?></p>
                        </td>
                        <td style="padding: 1.5rem; text-align: center; font-size: 0.8125rem; font-weight: 800; color: #0f172a; border-bottom: 1px solid #f1f5f9;"><?= number_format($bill['total_hours'], 2) ?></td>
                        <td style="padding: 1.5rem; text-align: right; font-size: 0.8125rem; color: #475569; font-weight: 600; border-bottom: 1px solid #f1f5f9;">₹<?= number_format($bill['rate'], 2) ?></td>
                        <td style="padding: 1.5rem; text-align: right; font-size: 0.875rem; font-weight: 800; color: #0f172a; border-bottom: 1px solid #f1f5f9;">₹<?= number_format($bill['total_amount'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div style="display: flex; flex-direction: column; align-items: flex-end; margin-bottom: 4rem;">
            <div style="width: 100%; max-width: 24rem; display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8125rem; color: #475569; font-weight: 600; padding: 0 0.5rem;">
                    <span>Subtotal</span>
                    <span style="font-weight: 800; color: #1e293b;">₹<?= number_format($bill['total_amount'], 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0.5rem; border-top: 1px solid #f1f5f9; margin-top: 1rem;">
                    <span style="font-size: 0.9375rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.1em;">Total Due</span>
                    <span style="font-size: 1.75rem; font-weight: 900; color: #1e293b; font-family: var(--font-headline); letter-spacing: -0.025em;">₹<?= number_format($bill['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>
        
        <div style="height: 1px; width: 100%; background: #f1f5f9; margin-bottom: 2rem;"></div>

        <!-- Footer Notes -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            <div>
                <h6 style="font-size: 0.5625rem; text-transform: uppercase; font-weight: 800; color: #475569; letter-spacing: 0.2em; margin-bottom: 0.75rem;">Terms & Conditions</h6>
                <ul style="font-size: 0.625rem; color: #64748b; line-height: 1.6; list-style: disc; padding-left: 1rem; font-weight: 600;">
                    <li>Payment is due within 7 days of invoice issue.</li>
                    <li>This invoice is system-generated based on supply logs.</li>
                </ul>
            </div>
            <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; justify-content: flex-end;">
                <p style="font-size: 0.5625rem; text-transform: uppercase; font-weight: 800; color: #475569; letter-spacing: 0.2em;">Authorized Signatory</p>
                <p style="font-size: 0.625rem; color: #64748b; margin-top: 0.25rem; font-weight: 600;">HydroFlow Mgmt.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
