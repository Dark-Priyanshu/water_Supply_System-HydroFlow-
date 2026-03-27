<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

$query = "SELECT s.date, COUNT(s.supply_id) as total_supplies, SUM(s.total_hours) as hours, SUM(s.total_amount) as amount 
          FROM water_supply s 
          WHERE DATE_FORMAT(s.date, '%Y-%m') = '$month'
          GROUP BY s.date
          ORDER BY s.date ASC";
$result = $conn->query($query);

$summary = $conn->query("SELECT SUM(total_hours) as hours, SUM(total_amount) as amount FROM water_supply WHERE DATE_FORMAT(date, '%Y-%m') = '$month'")->fetch_assoc();
?>

<!-- Header -->
<div class="flex no-print" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="breadcrumb">
            <span>Reports</span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;">Monthly Supply</span>
        </nav>
        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;">Monthly Supply Report</h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;">Detailed log and summary of irrigation activity for <?= date('F Y', strtotime($month . '-01')) ?>.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <form action="" method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
            <input type="month" name="month" value="<?= $month ?>" onchange="this.form.submit()" style="padding: 0.625rem 1rem; border-radius: 0.75rem; border: 1px solid rgba(112, 120, 129, 0.2); background: white; font-size: 0.875rem; font-weight: 600; color: var(--color-on-surface); cursor: pointer;">
        </form>
        <button class="btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;" onclick="window.print()">
            <span class="material-symbols-outlined" style="font-size: 1.25rem;">print</span>
            <span>Print Report</span>
        </button>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem; overflow: hidden; border: 1px solid rgba(112, 120, 129, 0.1);">
    <div style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h3 style="font-size: 1.5rem; font-weight: 800; font-family: var(--font-headline); color: var(--color-primary); margin: 0;">Monthly Supply Overview</h3>
            <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); margin-top: 0.5rem;">Period: <strong style="color: var(--color-on-surface);"><?= date('F Y', strtotime($month . '-01')) ?></strong></p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem; text-align: center;">
            <div style="padding: 1.5rem; border-right: 1px solid rgba(112, 120, 129, 0.1);">
                <h5 style="font-size: 0.625rem; text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-outline); font-weight: 800; margin-bottom: 0.75rem;">Total Monthly Hours</h5>
                <h2 style="font-size: 2rem; font-weight: 900; color: var(--color-primary); font-family: var(--font-headline); margin: 0;"><?= number_format($summary['hours'] ?: 0, 2) ?> Hrs</h2>
            </div>
            <div style="padding: 1.5rem;">
                <h5 style="font-size: 0.625rem; text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-outline); font-weight: 800; margin-bottom: 0.75rem;">Total Monthly Revenue</h5>
                <h2 style="font-size: 2rem; font-weight: 900; color: var(--color-secondary); font-family: var(--font-headline); margin: 0;">₹<?= number_format($summary['amount'] ?: 0, 2) ?></h2>
            </div>
        </div>

        <div class="table-container" style="box-shadow: none; border: 1px solid rgba(112, 120, 129, 0.1);">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="text-align: center;">Total Supplies</th>
                        <th style="text-align: center;">Hours Supplied</th>
                        <th style="text-align: right;">Revenue Generated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700;"><?= date('d M, Y', strtotime($row['date'])) ?></td>
                            <td style="text-align: center; font-size: 0.8125rem; color: var(--color-on-surface-variant);"><?= $row['total_supplies'] ?> records</td>
                            <td style="text-align: center; font-weight: 600;"><?= number_format($row['hours'], 2) ?></td>
                            <td style="text-align: right; font-weight: 800; color: var(--color-secondary);">₹<?= number_format($row['amount'], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 3rem; color: var(--color-outline); font-style: italic;">No supply recorded for this month.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .no-print, aside, header, .sidebar_custom, .header_custom { display: none !important; }
    main { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; display: block !important; }
    .card { box-shadow: none !important; border: 1px solid #eee !important; margin: 0 !important; padding: 0 !important; border-radius: 0 !important; }
}
</style>

<?php include '../../includes/footer.php'; ?>
