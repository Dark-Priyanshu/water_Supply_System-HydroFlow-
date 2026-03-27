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

$summary = [];
$summary_query = $conn->query("SELECT SUM(total_hours) as hours, SUM(total_amount) as amount FROM water_supply WHERE date = '$date'");
if($summary_query) {
    $summary = $summary_query->fetch_assoc();
}
?>

<style>
.report-paper {
    background-image: radial-gradient(#eceef0 0.5px, transparent 0.5px);
    background-size: 24px 24px;
}
@media print {
    body { background-color: #fff !important; margin: 0 !important; }
    aside, .no-print, header { display: none !important; }
    main { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; min-height: 0 !important; }
    .report-card { box-shadow: none !important; border: 1px solid #ddd !important; border-radius: 0 !important; margin: 0 !important; max-width: 100% !important; page-break-inside: avoid; }
    .report-paper { background-image: none !important; }
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
@page {
    size: auto;
    margin: 1cm;
}
</style>

<!-- Action Bar (Not Printed) -->
<div class="flex no-print" style="flex-direction: column; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; margin-top: 1rem; gap: 1rem;">
    <style>
        @media (min-width: 768px) {
            .action-bar-row { flex-direction: row !important; align-items: flex-end !important; }
        }
    </style>
    <div class="flex action-bar-row" style="width: 100%; justify-content: space-between; gap: 1rem;">
        <div>
            <nav class="flex" style="align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-on-surface-variant); margin-bottom: 0.5rem;">
                <span>Analytics</span>
                <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
                <span style="color: var(--color-primary); font-weight: 500;">Daily Report</span>
            </nav>
            <h2 style="font-size: 2.25rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em;">Supply Report</h2>
        </div>
        
        <div class="flex" style="align-items: center; gap: 0.75rem;">
            <form action="" method="GET" class="flex" style="gap: 0.5rem;">
                <input type="date" name="date" value="<?= $date ?>" onchange="this.form.submit()" style="padding: 0.625rem 1rem; background-color: var(--color-surface-container-highest); border: none; border-radius: 0.75rem; font-weight: 500; font-size: 0.875rem; color: var(--color-on-surface); outline: none;">
            </form>
            <button class="btn" style="padding: 0.625rem 1.25rem; background-color: var(--color-surface-container-lowest); color: var(--color-on-surface-variant); border: 1px solid rgba(112, 120, 129, 0.2); border-radius: 0.75rem; display: flex; align-items: center; gap: 0.5rem;" onclick="window.print()">
                <span class="material-symbols-outlined" style="font-size: 1.25rem;">print</span>
                <span>Print</span>
            </button>
        </div>
    </div>
</div>

<!-- Report Container -->
<div class="report-card" style="background-color: var(--color-surface-container-lowest); border-radius: 1.25rem; box-shadow: 0 32px 64px -12px rgba(0,0,0,0.08); overflow: hidden; max-width: 64rem; margin: 0 auto; border: 1px solid rgba(112, 120, 129, 0.1);">
    <!-- Top Accent Bar -->
    <div style="height: 0.5rem; background: linear-gradient(to right, var(--color-primary), var(--color-primary-container), var(--color-secondary));"></div>
    
    <div class="report-paper" style="padding: 3rem;">
        <!-- Header -->
        <div style="text-align: center; border-bottom: 1px solid rgba(112, 120, 129, 0.2); padding-bottom: 2rem; margin-bottom: 2rem;">
            <h3 style="font-size: 2rem; font-weight: 800; color: var(--color-primary); font-family: var(--font-headline); letter-spacing: -0.025em; margin-bottom: 0.5rem;">Daily Supply Report</h3>
            <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: var(--color-on-surface-variant);">HydroFlow Management System</p>
            <div style="display: inline-block; margin-top: 1rem; padding: 0.5rem 1.5rem; background-color: var(--color-surface-container-high); border-radius: 9999px; border: 1px solid rgba(112, 120, 129, 0.2);">
                <span style="font-size: 0.875rem; font-weight: 500; color: var(--color-on-surface-variant); margin-right: 0.5rem;">Date:</span>
                <span style="font-size: 0.875rem; font-weight: 800; color: var(--color-on-surface);"><?= date('F d, Y', strtotime($date)) ?></span>
            </div>
        </div>

        <!-- Summary Widgets -->
        <div class="grid" style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
            <style>
                @media (min-width: 768px) {
                    .report-summary-grid { grid-template-columns: repeat(2, 1fr) !important; }
                }
            </style>
            <div class="grid report-summary-grid" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                <div style="background-color: rgba(0, 93, 144, 0.05); padding: 1.5rem; border-radius: 1rem; border: 1px solid rgba(0, 93, 144, 0.1); display: flex; align-items: center; gap: 1.5rem;">
                    <div style="width: 4rem; height: 4rem; border-radius: 0.75rem; background-color: var(--color-primary); color: white; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                        <span class="material-symbols-outlined" style="font-size: 2rem;">timer</span>
                    </div>
                    <div>
                        <h5 style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">Total Hours Supplied</h5>
                        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-primary);"><?= number_format($summary['hours'] ?? 0, 2) ?> <span style="font-size: 1.125rem; font-weight: 500; opacity: 0.7;">Hrs</span></h2>
                    </div>
                </div>
                
                <div style="background-color: rgba(44, 105, 78, 0.05); padding: 1.5rem; border-radius: 1rem; border: 1px solid rgba(44, 105, 78, 0.1); display: flex; align-items: center; gap: 1.5rem;">
                    <div style="width: 4rem; height: 4rem; border-radius: 0.75rem; background-color: var(--color-secondary); color: white; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                        <span class="material-symbols-outlined" style="font-size: 2rem;">payments</span>
                    </div>
                    <div>
                        <h5 style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.25rem;">Total Revenue Generated</h5>
                        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-secondary);">₹<?= number_format($summary['amount'] ?? 0, 2) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid rgba(112, 120, 129, 0.2);">
            <table class="table-custom" style="width: 100%; text-align: left; background-color: white;">
                <thead style="background-color: var(--color-surface-container-low); border-bottom: 1px solid rgba(112, 120, 129, 0.2);">
                    <tr>
                        <th style="padding-left: 1.5rem;">Time Slot</th>
                        <th>Customer</th>
                        <th>Motor / Pump</th>
                        <th style="text-align: center;">Duration</th>
                        <th style="padding-right: 1.5rem; text-align: right;">Amount Billed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr onmouseover="this.style.backgroundColor='rgba(242, 244, 246, 0.5)';" onmouseout="this.style.backgroundColor='transparent';">
                            <td style="padding-left: 1.5rem; color: var(--color-on-surface-variant); font-size: 0.875rem; font-weight: 500;">
                                <?= date('h:i A', strtotime($row['start_time'])) ?> - <?= date('h:i A', strtotime($row['end_time'])) ?>
                            </td>
                            <td style="font-weight: 700; font-size: 0.875rem;"><?= htmlspecialchars($row['farmer_name'] ?? 'N/A') ?></td>
                            <td style="font-size: 0.875rem; color: var(--color-on-surface-variant);"><?= htmlspecialchars($row['motor_name'] ?? 'N/A') ?></td>
                            <td style="text-align: center; font-weight: 700; font-size: 0.875rem; color: var(--color-primary);"><?= number_format($row['total_hours'], 2) ?> h</td>
                            <td style="padding-right: 1.5rem; text-align: right; font-weight: 800; font-size: 0.875rem; color: var(--color-secondary);">₹<?= number_format($row['total_amount'], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="padding: 3rem; text-align: center; color: var(--color-on-surface-variant);">
                                <span class="material-symbols-outlined" style="font-size: 2.5rem; opacity: 0.5; margin-bottom: 0.75rem; display: block;">search_off</span>
                                <p style="font-weight: 500; font-size: 0.875rem;">No supply recorded on this date.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if($result && $result->num_rows > 0): ?>
                <tfoot style="background-color: var(--color-surface-container-lowest); border-top: 2px solid rgba(112, 120, 129, 0.2);">
                    <tr>
                        <td colspan="3" style="padding: 1rem 1.5rem; text-align: right; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-on-surface-variant);">Daily Totals</td>
                        <td style="padding: 1rem; text-align: center; font-weight: 800; color: var(--color-primary);"><?= number_format($summary['hours'] ?? 0, 2) ?> h</td>
                        <td style="padding: 1rem 1.5rem; text-align: right; font-weight: 800; color: var(--color-secondary); font-size: 1.125rem;">₹<?= number_format($summary['amount'] ?? 0, 2) ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
        
        <div style="margin-top: 3rem; text-align: center;" class="no-print">
            <p style="font-size: 0.75rem; color: var(--color-outline); font-weight: 500;">Report generated via HydroFlow Analytics Engine</p>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
