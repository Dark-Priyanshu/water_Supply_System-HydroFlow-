<?php include '../../includes/header.php'; ?>
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

<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h2 class="fw-bold m-0">Annual Revenue Report</h2>
    <div class="d-flex gap-2">
        <form action="" method="GET" class="d-flex gap-2">
            <select name="year" class="form-select" onchange="this.form.submit()">
                <?php for($i=date('Y'); $i>=date('Y')-5; $i--): ?>
                    <option value="<?= $i ?>" <?= $i==$year ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </form>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-2"></i>Print</button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm invoice-box p-4" style="max-width: 100%;">
            <div class="text-center mb-4">
                <h4 class="text-muted text-uppercase mb-2">Total Collection in <?= $year ?></h4>
                <h1 class="fw-bold text-success display-4">₹<?= number_format($total_collected, 2) ?></h1>
            </div>
            
            <div class="mt-4" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm invoice-box" style="max-width: 100%;">
    <div class="card-body p-4">
        <table class="table table-bordered">
            <thead class="bg-light">
                <tr>
                    <th>Month</th>
                    <th class="text-end">Revenue Collected</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($revenue as $m => $amount): ?>
                <tr>
                    <td class="fw-semibold"><?= date('F', mktime(0, 0, 0, $m, 10)) ?></td>
                    <td class="text-end fw-bold <?= $amount > 0 ? 'text-success' : 'text-muted' ?>">₹<?= number_format($amount, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-light fw-bold">
                <tr>
                    <td>Total</td>
                    <td class="text-end fs-5 text-success">₹<?= number_format($total_collected, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

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
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true
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
                    ticks: { callback: function(value) { return '₹' + value; } }
                }
            }
        }
    });
});
</script>

<style>
@media print {
    body { background-color: #fff; }
    #sidebar-wrapper, .navbar, .d-print-none { display: none !important; }
    #page-content-wrapper { margin: 0; padding: 0 !important; width: 100%; }
    .invoice-box { box-shadow: none !important; margin: 0 !important; padding: 0 !important; }
}
</style>

<?php include '../../includes/footer.php'; ?>
