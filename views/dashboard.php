<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php require_once '../config/database.php'; ?>

<?php
// Fetch stats for dashboard
$customers_count = $conn->query("SELECT COUNT(*) as count FROM customers")->fetch_assoc()['count'];
$motors_count = $conn->query("SELECT COUNT(*) as count FROM motors")->fetch_assoc()['count'];
$today = date('Y-m-d');
$today_supply = $conn->query("SELECT IFNULL(SUM(total_hours), 0) as hours, IFNULL(SUM(total_amount), 0) as rev FROM water_supply WHERE date = '$today'")->fetch_assoc();

// Prepare 7-day trend data
$chart_labels = [];
$chart_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $label = date('D', strtotime($date));
    if ($i == 0) $label = __('today');
    $chart_labels[] = $label;
    
    $res = $conn->query("SELECT SUM(total_hours) as hours FROM water_supply WHERE date = '$date'")->fetch_assoc();
    $chart_data[] = $res['hours'] ?: 0;
}

// Fetch recent supply logs (Limit 5)
$recent_logs = $conn->query("SELECT ws.*, c.farmer_name as customer_name, m.motor_name 
                             FROM water_supply ws 
                             JOIN customers c ON ws.customer_id = c.customer_id 
                             JOIN motors m ON ws.motor_id = m.motor_id 
                             ORDER BY ws.date DESC, ws.start_time DESC LIMIT 5");
?>

<!-- Hero Stats Bento Grid -->
<section class="grid-4 mb-10 mt-4">
    <!-- Total Customers -->
    <div class="card stat-card card-h-40">
        <div class="flex" style="justify-content: space-between; align-items: flex-start;">
            <p class="stat-label"><?= __('total_customers') ?></p>
            <span class="material-symbols-outlined text-primary stat-icon bg-primary-fixed">groups</span>
        </div>
        <div>
            <h3 class="stat-value"><?= $customers_count ?></h3>
            <p style="font-size: 0.75rem; color: var(--color-secondary); font-weight: 600; margin-top: 0.25rem;"><?= __('reg_clients') ?></p>
        </div>
        <div class="absolute" style="bottom: 0; left: 0; right: 0; height: 33%; opacity: 0.4; pointer-events: none; background: linear-gradient(180deg, rgba(44, 105, 78, 0) 0%, rgba(44, 105, 78, 0.05) 100%);"></div>
    </div>
    
    <!-- Total Motors -->
    <div class="card stat-card card-h-40">
        <div class="flex" style="justify-content: space-between; align-items: flex-start;">
            <p class="stat-label"><?= __('total_motors') ?></p>
            <span class="material-symbols-outlined text-secondary stat-icon bg-secondary-fixed">water_pump</span>
        </div>
        <div>
            <h3 class="stat-value"><?= $motors_count ?></h3>
            <p style="font-size: 0.75rem; color: var(--color-on-surface-variant); margin-top: 0.25rem;"><?= __('active_pumps') ?></p>
        </div>
        <div class="absolute" style="bottom: 0; left: 0; right: 0; height: 33%; opacity: 0.4; pointer-events: none; background: linear-gradient(180deg, rgba(44, 105, 78, 0) 0%, rgba(44, 105, 78, 0.05) 100%);"></div>
    </div>
    
    <!-- Total Hours Supplied -->
    <div class="card stat-card card-h-40">
        <div class="flex" style="justify-content: space-between; align-items: flex-start;">
            <p class="stat-label"><?= __('todays_hours') ?></p>
            <span class="material-symbols-outlined text-tertiary stat-icon bg-tertiary-fixed">speed</span>
        </div>
        <div>
            <h3 class="stat-value"><?= number_format($today_supply['hours'], 2) ?> <span style="font-size: 1.125rem; font-weight: 500; color: var(--color-on-surface-variant);"><?= __('hours') ?></span></h3>
            <p style="font-size: 0.75rem; color: var(--color-secondary); font-weight: 600; margin-top: 0.25rem;"><?= __('water_supply_today') ?></p>
        </div>
        <div class="absolute" style="bottom: 0; left: 0; right: 0; height: 33%; opacity: 0.4; pointer-events: none; background: linear-gradient(180deg, rgba(44, 105, 78, 0) 0%, rgba(44, 105, 78, 0.05) 100%);"></div>
    </div>
    
    <!-- Today's Revenue -->
    <div class="card stat-card card-h-40">
        <div class="flex" style="justify-content: space-between; align-items: flex-start;">
            <p class="stat-label"><?= __('todays_revenue') ?></p>
            <span class="material-symbols-outlined text-on-secondary-container stat-icon bg-secondary-container">payments</span>
        </div>
        <div>
            <h3 class="stat-value">₹<?= number_format($today_supply['rev'], 2) ?></h3>
            <p style="font-size: 0.75rem; color: var(--color-on-surface-variant); margin-top: 0.25rem;"><?= __('collection_today') ?></p>
        </div>
        <div class="absolute" style="bottom: 0; left: 0; right: 0; height: 33%; opacity: 0.4; pointer-events: none; background: linear-gradient(180deg, rgba(44, 105, 78, 0) 0%, rgba(44, 105, 78, 0.05) 100%);"></div>
    </div>
</section>

<!-- Charts Section -->
<section class="grid" style="grid-template-columns: repeat(1, 1fr); gap: 2rem; margin-bottom: 2.5rem;">
    <style>
        @media (min-width: 1024px) {
            .grid-charts { grid-template-columns: repeat(2, 1fr) !important; }
        }
    </style>
    <div class="grid grid-charts" style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
        <!-- Quick Actions -->
        <div class="card" style="padding: 2rem; height: 100%;">
            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-size: 1.125rem;"><?= __('quick_actions') ?></h4>
                <p style="font-size: 0.875rem; color: var(--color-on-surface-variant);"><?= __('common_tasks') ?></p>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem; flex: 1; justify-content: center;">
                <a href="<?= BASE_URL ?>views/supply/add_supply.php" class="flex" style="align-items: center; gap: 1rem; padding: 1rem; border: 1px solid rgba(191, 199, 209, 0.3); border-radius: 0.75rem;">
                    <div style="width: 3rem; height: 3rem; background-color: var(--color-primary-fixed); color: var(--color-primary); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="material-symbols-outlined">waves</span>
                    </div>
                    <div>
                        <h5 style="font-weight: 700;"><?= __('new_supply') ?></h5>
                        <p style="font-size: 0.75rem; color: var(--color-on-surface-variant);"><?= __('new_supply_desc') ?></p>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>views/billing/generate_bill.php" class="flex" style="align-items: center; gap: 1rem; padding: 1rem; border: 1px solid rgba(191, 199, 209, 0.3); border-radius: 0.75rem;">
                    <div style="width: 3rem; height: 3rem; background-color: var(--color-secondary-fixed); color: var(--color-secondary); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </div>
                    <div>
                        <h5 style="font-weight: 700;"><?= __('generate_bill') ?></h5>
                        <p style="font-size: 0.75rem; color: var(--color-on-surface-variant);"><?= __('gen_bill_desc') ?></p>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>views/customers/add_customer.php" class="flex" style="align-items: center; gap: 1rem; padding: 1rem; border: 1px solid rgba(191, 199, 209, 0.3); border-radius: 0.75rem;">
                    <div style="width: 3rem; height: 3rem; background-color: var(--color-tertiary-fixed); color: var(--color-tertiary); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <span class="material-symbols-outlined">person_add</span>
                    </div>
                    <div>
                        <h5 style="font-weight: 700;"><?= __('add_customer') ?></h5>
                        <p style="font-size: 0.75rem; color: var(--color-on-surface-variant);"><?= __('add_cust_desc') ?></p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Chart -->
        <div class="card" style="padding: 2rem;">
            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-size: 1.125rem;"><?= __('weekly_trend') ?></h4>
                <p style="font-size: 0.875rem; color: var(--color-on-surface-variant);"><?= __('weekly_trend_desc') ?></p>
            </div>
            <div style="width: 100%;">
                <canvas id="supplyChart" height="150"></canvas>
            </div>
        </div>
    </div>
</section>

<!-- Recent Supply Logs Table -->
<section class="table-container shadow-sm">
    <div class="table-header">
        <h4 style="font-size: 1.125rem;"><?= __('recent_supply') ?></h4>
        <a href="<?= BASE_URL ?>views/supply/add_supply.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">
            <span class="material-symbols-outlined" style="font-size: 1rem;">add</span> <?= __('new_entry') ?>
        </a>
    </div>
    <div style="overflow-x: auto;">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="padding-left: 2rem; white-space: nowrap;"><?= __('th_customer') ?></th>
                    <th style="white-space: nowrap;"><?= __('pump_source') ?></th>
                    <th style="white-space: nowrap;"><?= __('th_date') ?> / <?= __('time') ?></th>
                    <th style="white-space: nowrap;"><?= __('th_duration') ?></th>
                    <th style="padding-right: 2rem; text-align: right; white-space: nowrap;"><?= __('th_amount') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent_logs && $recent_logs->num_rows > 0): ?>
                    <?php while ($log = $recent_logs->fetch_assoc()): ?>
                        <tr>
                            <td style="padding-left: 2rem;">
                                <div class="flex" style="align-items: center; gap: 0.75rem;">
                                    <div style="width: 2rem; height: 2rem; border-radius: 0.25rem; background-color: var(--color-primary-fixed); display: flex; align-items: center; justify-content: center; color: var(--color-primary); font-weight: 700; font-size: 0.75rem;">
                                        <?= strtoupper(substr($log['customer_name'], 0, 2)) ?>
                                    </div>
                                    <span style="font-weight: 500; font-size: 0.875rem;"><?= htmlspecialchars($log['customer_name']) ?></span>
                                </div>
                            </td>
                            <td style="color: var(--color-on-surface-variant);"><?= htmlspecialchars($log['motor_name']) ?></td>
                            <td style="color: var(--color-on-surface-variant); white-space: nowrap;">
                                <?= date('M d, Y', strtotime($log['date'])) ?><br>
                                <span style="font-size: 0.75rem;"><?= date('h:i A', strtotime($log['start_time'])) ?></span>
                            </td>
                            <td style="color: var(--color-on-surface-variant); white-space: nowrap;"><?= number_format($log['total_hours'], 2) ?> <?= __('hours') ?></td>
                            <td style="padding-right: 2rem; text-align: right; font-weight: 600; color: var(--color-secondary);">
                                ₹<?= number_format($log['total_amount'], 2) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--color-on-surface-variant);"><?= __('no_logs_found') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding: 1rem 2rem; background-color: rgba(242, 244, 246, 0.1);">
        <a href="<?= BASE_URL ?>views/supply/supply_history.php" style="color: var(--color-primary); font-weight: 600; font-size: 0.875rem;"><?= __('view_all') ?></a>
    </div>
</section>

<!-- Contextual FAB -->
<a href="<?= BASE_URL ?>views/supply/add_supply.php" class="fixed bg-gradient-primary" style="bottom: 2.5rem; right: 2.5rem; width: 4rem; height: 4rem; border-radius: 50%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); display: flex; align-items: center; justify-content: center; z-index: 50; transition: transform 0.2s ease;">
    <span class="material-symbols-outlined" style="font-size: 2rem;">add_task</span>
</a>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('supplyChart');
    if(canvas) {
        const ctx = canvas.getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [{
                    label: '<?= __('hours_supplied') ?>',
                    data: <?= json_encode($chart_data) ?>,
                    backgroundColor: 'rgba(0, 93, 144, 0.2)', // primary color with opacity
                    borderColor: 'rgba(0, 93, 144, 1)',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>
