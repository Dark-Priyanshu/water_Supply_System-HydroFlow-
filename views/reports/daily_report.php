<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$query = "SELECT s.*, c.farmer_name, m.motor_name 
          FROM water_supply s 
          JOIN customers c ON s.customer_id = c.customer_id 
          JOIN motors m ON s.motor_id = m.motor_id 
          WHERE s.date = '$date'
          ORDER BY s.start_time ASC";
$result = $conn->query($query);

$summary = $conn->query("SELECT SUM(total_hours) as hours, SUM(total_amount) as amount FROM water_supply WHERE date = '$date'")->fetch_assoc();
?>

<style>
.report-paper {
    background-image: radial-gradient(#eceef0 0.5px, transparent 0.5px);
    background-size: 24px 24px;
}
@media print {
    body { background-color: #fff !important; }
    .no-print, aside, header { display: none !important; }
    main { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; min-height: 0 !important; }
    .px-10 { padding-left: 0 !important; padding-right: 0 !important; }
    .pb-12 { padding-bottom: 0 !important; }
    .report-card { box-shadow: none !important; border: none !important; border-radius: 0 !important; margin: 0 !important; max-width: 100% !important; page-break-inside: avoid; }
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
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 mt-4 gap-4 no-print">
    <div>
        <nav class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
            <span>Analytics</span>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary font-medium">Daily Report</span>
        </nav>
        <h2 class="text-3xl font-bold font-headline text-on-surface tracking-tight">Supply Report</h2>
    </div>
    
    <div class="flex items-center gap-3 w-full md:w-auto">
        <form action="" method="GET" class="flex gap-2 flex-1 md:flex-none">
            <input type="date" name="date" value="<?= $date ?>" onchange="this.form.submit()" class="px-4 py-2.5 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary/40 font-medium text-sm text-on-surface flex-1">
        </form>
        <button class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant/30 bg-surface-container-lowest hover:bg-surface-container-low transition-all text-sm font-semibold text-on-surface-variant shadow-sm" onclick="window.print()">
            <span class="material-symbols-outlined text-[20px]">print</span>
            <span class="hidden md:inline">Print</span>
        </button>
    </div>
</div>

<!-- Report Container -->
<div class="bg-surface-container-lowest rounded-2xl p-0 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)] overflow-hidden report-card max-w-5xl mx-auto border border-outline-variant/10">
    <!-- Top Accent Bar -->
    <div class="h-2 bg-gradient-to-r from-primary via-primary-container to-secondary"></div>
    
    <div class="p-8 md:p-12 report-paper">
        <!-- Header -->
        <div class="text-center border-b border-outline-variant/20 pb-8 mb-8">
            <h3 class="text-3xl font-bold text-primary font-headline tracking-tight mb-2">Daily Supply Report</h3>
            <p class="text-sm uppercase tracking-widest text-on-surface-variant font-bold">HydroFlow Management System</p>
            <div class="inline-block mt-4 px-6 py-2 bg-surface-container-high rounded-full border border-outline-variant/20">
                <span class="text-sm font-medium text-on-surface-variant mr-2">Date:</span>
                <span class="text-sm font-bold text-on-surface"><?= date('F d, Y', strtotime($date)) ?></span>
            </div>
        </div>

        <!-- Summary Widgets -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="bg-primary/5 rounded-2xl p-6 border border-primary/10 flex items-center gap-6">
                <div class="w-16 h-16 rounded-xl bg-primary text-white flex items-center justify-center shadow-inner">
                    <span class="material-symbols-outlined text-3xl">timer</span>
                </div>
                <div>
                    <h5 class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-1">Total Hours Supplied</h5>
                    <h2 class="text-3xl font-extrabold font-headline text-primary"><?= number_format($summary['hours'] ?: 0, 2) ?> <span class="text-lg font-medium text-primary/70">Hrs</span></h2>
                </div>
            </div>
            
            <div class="bg-secondary/5 rounded-2xl p-6 border border-secondary/10 flex items-center gap-6">
                <div class="w-16 h-16 rounded-xl bg-secondary text-white flex items-center justify-center shadow-inner">
                    <span class="material-symbols-outlined text-3xl">payments</span>
                </div>
                <div>
                    <h5 class="text-xs text-on-surface-variant uppercase tracking-widest font-bold mb-1">Total Revenue Generated</h5>
                    <h2 class="text-3xl font-extrabold font-headline text-secondary">₹<?= number_format($summary['amount'] ?: 0, 2) ?></h2>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto rounded-xl border border-outline-variant/20">
            <table class="w-full text-left bg-white">
                <thead class="bg-surface-container-low border-b border-outline-variant/20">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Time Slot</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Motor / Pump</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest text-center">Duration</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Amount Billed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    <?php if($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-on-surface-variant">
                                <?= date('h:i A', strtotime($row['start_time'])) ?> - <?= date('h:i A', strtotime($row['end_time'])) ?>
                            </td>
                            <td class="px-6 py-4 font-bold text-sm text-on-surface"><?= htmlspecialchars($row['farmer_name']) ?></td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant"><?= htmlspecialchars($row['motor_name']) ?></td>
                            <td class="px-6 py-4 text-center font-bold text-sm text-primary"><?= number_format($row['total_hours'], 2) ?> h</td>
                            <td class="px-6 py-4 text-right font-extrabold text-sm text-secondary">₹<?= number_format($row['total_amount'], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl opacity-50 mb-3 block">search_off</span>
                                <p class="font-medium text-sm">No supply recorded on this date.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if($result->num_rows > 0): ?>
                <tfoot class="bg-surface-container-lowest border-t-2 border-outline-variant/20">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold text-xs uppercase tracking-widest text-on-surface-variant">Daily Totals</td>
                        <td class="px-6 py-4 text-center font-extrabold text-primary"><?= number_format($summary['hours'] ?: 0, 2) ?> h</td>
                        <td class="px-6 py-4 text-right font-extrabold text-secondary text-lg">₹<?= number_format($summary['amount'] ?: 0, 2) ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
        
        <div class="mt-12 text-center no-print">
            <p class="text-xs text-outline font-medium">Report generated via HydroFlow Analytics Engine</p>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
