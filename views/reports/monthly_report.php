<?php include '../../includes/header.php'; ?>
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

<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h2 class="fw-bold m-0">Monthly Supply Report</h2>
    <div class="d-flex gap-2">
        <form action="" method="GET" class="d-flex gap-2">
            <input type="month" class="form-control" name="month" value="<?= $month ?>" onchange="this.form.submit()">
        </form>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-2"></i>Print</button>
    </div>
</div>

<div class="card border-0 shadow-sm invoice-box" style="max-width: 100%;">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">Monthly Supply Overview</h3>
            <p class="text-muted fs-5">Month: <strong><?= date('F Y', strtotime($month . '-01')) ?></strong></p>
        </div>

        <div class="row mb-4 text-center">
            <div class="col-md-6 border-end">
                <h5 class="text-muted text-uppercase mb-2">Total Monthly Hours</h5>
                <h2 class="fw-bold text-info"><?= number_format($summary['hours'] ?: 0, 2) ?> Hrs</h2>
            </div>
            <div class="col-md-6">
                <h5 class="text-muted text-uppercase mb-2">Total Monthly Revenue</h5>
                <h2 class="fw-bold text-success">₹<?= number_format($summary['amount'] ?: 0, 2) ?></h2>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th class="text-center">Total Supplies</th>
                        <th class="text-center">Hours Supplied</th>
                        <th class="text-end">Revenue Generated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-semibold"><?= date('d M, Y', strtotime($row['date'])) ?></td>
                            <td class="text-center"><?= $row['total_supplies'] ?> records</td>
                            <td class="text-center"><?= number_format($row['hours'], 2) ?></td>
                            <td class="text-end fw-bold">₹<?= number_format($row['amount'], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No supply recorded for this month.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    body { background-color: #fff; }
    #sidebar-wrapper, .navbar, .d-print-none { display: none !important; }
    #page-content-wrapper { margin: 0; padding: 0 !important; width: 100%; }
    .invoice-box { box-shadow: none !important; margin: 0 !important; padding: 0 !important; }
}
</style>

<?php include '../../includes/footer.php'; ?>
