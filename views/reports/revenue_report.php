<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$query = "SELECT DATE_FORMAT(payment_date, '%m') as month, SUM(amount) as collected 
          FROM payments 
          WHERE DATE_FORMAT(payment_date, '%Y') = '$year'
          GROUP BY month
          ORDER BY month ASC";
$result = $conn->query($query);

$revenue = array_fill(1, 12, 0);
$total_collected = 0;

while($row = $result->fetch_assoc()) {
    $m = (int)$row['month'];
    $revenue[$m] = $row['collected'];
    $total_collected += $row['collected'];
}

$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
?>

<!-- Header -->
<div class="flex no-print" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="breadcrumb">
            <span>Reports</span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;">Annual Revenue</span>
        </nav>
        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;">Annual Revenue Report</h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;">Collection analysis and trends for the fiscal year <?= $year ?>.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <form action="" method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
            <select name="year" onchange="this.form.submit()" style="padding: 0.625rem 1rem; border-radius: 0.75rem; border: 1px solid rgba(112, 120, 129, 0.2); background: white; font-size: 0.875rem; font-weight: 600; color: var(--color-on-surface); cursor: pointer; outline: none;">
                <?php for($i=date('Y'); $i>=date('Y')-5; $i--): ?>
                    <option value="<?= $i ?>" <?= $i==$year ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </form>
        <button class="btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;" onclick="window.print()">
            <span class="material-symbols-outlined" style="font-size: 1.25rem;">print</span>
            <span>Print Report</span>
        </button>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    <div class="card" style="padding: 2.5rem; text-align: center;">
        <h4 style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; color: var(--color-outline); font-weight: 800; margin-bottom: 0.75rem;">Total Collection in <?= $year ?></h4>
        <h1 style="font-size: 3.5rem; font-weight: 900; color: var(--color-secondary); font-family: var(--font-headline); margin: 0; letter-spacing: -0.05em;">₹<?= number_format($total_collected, 2) ?></h1>
        
        <div style="margin-top: 3rem; height: 350px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border: 1px solid rgba(112, 120, 129, 0.1);">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Month</th>
                    <th style="text-align: right;">Revenue Collected</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($revenue as $m => $amount): ?>
                <tr>
                    <td style="font-weight: 700;"><?= date('F', mktime(0, 0, 0, $m, 10)) ?></td>
                    <td style="text-align: right; font-weight: 800; color: <?= $amount > 0 ? 'var(--color-secondary)' : 'var(--color-outline)' ?>;">₹<?= number_format($amount, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="background: var(--color-surface-container-low);">
                    <td style="font-weight: 900; font-family: var(--font-headline);">Total Annual Collection</td>
                    <td style="text-align: right; font-weight: 900; font-size: 1.25rem; font-family: var(--font-headline); color: var(--color-secondary);">₹<?= number_format($total_collected, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                label: 'Revenue (₹)',
                data: <?= json_encode(array_values($revenue)) ?>,
                borderColor: '#2c694e',
                backgroundColor: 'rgba(44, 105, 78, 0.1)',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#2c694e',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { 
                        font: { weight: '600', family: 'Inter' },
                        callback: function(value) { return '₹' + value; } 
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: '600', family: 'Inter' } }
                }
            }
        }
    });
});
</script>

<style>
@media print {
    body { background: white !important; }
    .no-print, aside, header, .sidebar_custom, .header_custom { display: none !important; }
    main { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; display: block !important; }
    .card { box-shadow: none !important; border: 1px solid #eee !important; margin: 0 !important; padding: 0 !important; border-radius: 0 !important; }
}
</style>

<?php include '../../includes/footer.php'; ?>
