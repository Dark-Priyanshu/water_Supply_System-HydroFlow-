<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php require_once '../config/database.php'; ?>

<?php
// Fetch some stats
$customers_count = $conn->query("SELECT COUNT(*) as count FROM customers")->fetch_assoc()['count'];
$motors_count = $conn->query("SELECT COUNT(*) as count FROM motors")->fetch_assoc()['count'];
$today = date('Y-m-d');
$today_supply = $conn->query("SELECT IFNULL(SUM(total_hours), 0) as hours, IFNULL(SUM(total_amount), 0) as rev FROM water_supply WHERE date = '$today'")->fetch_assoc();

// Mock data logic for charts if we don't have enough DB entries natively
$recent_logs = $conn->query("SELECT ws.*, c.name as customer_name, m.name as motor_name 
                             FROM water_supply ws 
                             JOIN customers c ON ws.customer_id = c.id 
                             JOIN motors m ON ws.motor_id = m.id 
                             ORDER BY date DESC, start_time DESC LIMIT 5");
?>

<!-- Hero Stats Bento Grid -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 mt-4">
    <!-- Total Customers -->
    <div class="bg-surface-container-lowest p-6 rounded-xl relative overflow-hidden flex flex-col justify-between h-40 group">
        <div class="flex justify-between items-start">
            <p class="text-on-surface-variant font-medium text-sm">Total Customers</p>
            <span class="material-symbols-outlined text-primary p-2 bg-primary-fixed rounded-lg">groups</span>
        </div>
        <div>
            <h3 class="text-4xl font-headline font-extrabold text-on-surface"><?= $customers_count ?></h3>
            <p class="text-xs text-secondary font-semibold mt-1">Registered clients</p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1/3 wave-bg opacity-40"></div>
    </div>
    
    <!-- Total Motors -->
    <div class="bg-surface-container-lowest p-6 rounded-xl relative overflow-hidden flex flex-col justify-between h-40 group">
        <div class="flex justify-between items-start">
            <p class="text-on-surface-variant font-medium text-sm">Total Motors</p>
            <span class="material-symbols-outlined text-secondary p-2 bg-secondary-fixed rounded-lg">water_pump</span>
        </div>
        <div>
            <h3 class="text-4xl font-headline font-extrabold text-on-surface"><?= $motors_count ?></h3>
            <p class="text-xs text-on-surface-variant mt-1">Active pumps/borewells</p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1/3 wave-bg opacity-40"></div>
    </div>
    
    <!-- Total Hours Supplied -->
    <div class="bg-surface-container-lowest p-6 rounded-xl relative overflow-hidden flex flex-col justify-between h-40 group">
        <div class="flex justify-between items-start">
            <p class="text-on-surface-variant font-medium text-sm">Today's Hours</p>
            <span class="material-symbols-outlined text-tertiary p-2 bg-tertiary-fixed rounded-lg">speed</span>
        </div>
        <div>
            <h3 class="text-4xl font-headline font-extrabold text-on-surface"><?= number_format($today_supply['hours'], 2) ?> <span class="text-lg font-medium text-on-surface-variant">Hrs</span></h3>
            <p class="text-xs text-secondary font-semibold mt-1">Water supply today</p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1/3 wave-bg opacity-40"></div>
    </div>
    
    <!-- Today's Revenue -->
    <div class="bg-surface-container-lowest p-6 rounded-xl relative overflow-hidden flex flex-col justify-between h-40 group">
        <div class="flex justify-between items-start">
            <p class="text-on-surface-variant font-medium text-sm">Today's Revenue</p>
            <span class="material-symbols-outlined text-on-secondary-container p-2 bg-secondary-container rounded-lg">payments</span>
        </div>
        <div>
            <h3 class="text-4xl font-headline font-extrabold text-on-surface">₹<?= number_format($today_supply['rev'], 2) ?></h3>
            <p class="text-xs text-on-surface-variant mt-1">Collection today</p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1/3 wave-bg opacity-40"></div>
    </div>
</section>

<!-- Charts Section -->
<section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Quick Actions (Instead of Weekly supply mock) -->
    <div class="bg-surface-container-lowest p-8 rounded-xl h-full flex flex-col">
        <div class="mb-6">
            <h4 class="text-lg font-headline font-bold">Quick Actions</h4>
            <p class="text-sm text-on-surface-variant">Common tasks</p>
        </div>
        <div class="flex-1 flex flex-col justify-center gap-4">
            <a href="supply/add_supply.php" class="flex items-center gap-4 p-4 border border-outline-variant/30 rounded-xl hover:bg-primary/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 bg-primary-fixed text-primary rounded-lg flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">waves</span>
                </div>
                <div>
                    <h5 class="font-bold text-on-surface">New Water Supply</h5>
                    <p class="text-xs text-on-surface-variant">Record a new supply log</p>
                </div>
            </a>
            <a href="billing/generate_bill.php" class="flex items-center gap-4 p-4 border border-outline-variant/30 rounded-xl hover:bg-secondary/5 hover:border-secondary/30 transition-all group">
                <div class="w-12 h-12 bg-secondary-fixed text-secondary rounded-lg flex items-center justify-center shrink-0 group-hover:bg-secondary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">receipt_long</span>
                </div>
                <div>
                    <h5 class="font-bold text-on-surface">Generate Bill</h5>
                    <p class="text-xs text-on-surface-variant">Create invoices for customers</p>
                </div>
            </a>
            <a href="customers/add_customer.php" class="flex items-center gap-4 p-4 border border-outline-variant/30 rounded-xl hover:bg-tertiary/5 hover:border-tertiary/30 transition-all group">
                <div class="w-12 h-12 bg-tertiary-fixed text-tertiary rounded-lg flex items-center justify-center shrink-0 group-hover:bg-tertiary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">person_add</span>
                </div>
                <div>
                    <h5 class="font-bold text-on-surface">Add Customer</h5>
                    <p class="text-xs text-on-surface-variant">Register a new client or farm</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Chart from the old PHP using tailwind wrapper -->
    <div class="bg-surface-container-lowest p-8 rounded-xl">
        <div class="mb-6">
            <h4 class="text-lg font-headline font-bold">Weekly Supply Hours</h4>
            <p class="text-sm text-on-surface-variant">Trends over the last 7 days</p>
        </div>
        <div class="w-full">
            <canvas id="supplyChart" height="150" class="w-full"></canvas>
        </div>
    </div>
</section>

<!-- Recent Supply Logs Table -->
<section class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
    <div class="px-8 py-6 flex justify-between items-center bg-surface-container-low/30">
        <h4 class="text-lg font-headline font-bold">Recent Supply Logs</h4>
        <a href="supply/add_supply.php" class="px-4 py-2 bg-primary text-white text-xs font-semibold rounded-lg hover:shadow-lg transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span> New Supply Entry
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-8 py-4 text-label-md uppercase tracking-wider text-on-surface-variant font-bold border-b border-outline-variant/10">Farm / Customer</th>
                    <th class="px-8 py-4 text-label-md uppercase tracking-wider text-on-surface-variant font-bold border-b border-outline-variant/10">Pump Source</th>
                    <th class="px-8 py-4 text-label-md uppercase tracking-wider text-on-surface-variant font-bold border-b border-outline-variant/10">Date & Time</th>
                    <th class="px-8 py-4 text-label-md uppercase tracking-wider text-on-surface-variant font-bold border-b border-outline-variant/10">Duration</th>
                    <th class="px-8 py-4 text-label-md uppercase tracking-wider text-on-surface-variant font-bold border-b border-outline-variant/10">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if ($recent_logs && $recent_logs->num_rows > 0): ?>
                    <?php while ($log = $recent_logs->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-primary-fixed flex items-center justify-center text-primary font-bold">
                                        <?= strtoupper(substr($log['customer_name'], 0, 2)) ?>
                                    </div>
                                    <span class="font-medium"><?= htmlspecialchars($log['customer_name']) ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-on-surface-variant"><?= htmlspecialchars($log['motor_name']) ?></td>
                            <td class="px-8 py-5 text-on-surface-variant">
                                <?= date('M d, Y', strtotime($log['date'])) ?><br>
                                <span class="text-xs"><?= date('h:i A', strtotime($log['start_time'])) ?></span>
                            </td>
                            <td class="px-8 py-5 text-on-surface-variant"><?= number_format($log['total_hours'], 2) ?> Hours</td>
                            <td class="px-8 py-5 font-semibold text-secondary">
                                ₹<?= number_format($log['total_amount'], 2) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-8 py-8 text-center text-on-surface-variant">No recent supply logs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-8 py-4 bg-surface-container-low/10 flex justify-between items-center text-sm font-medium text-on-surface-variant">
        <a href="supply/supply_history.php" class="text-primary hover:underline font-semibold text-sm">View All History</a>
    </div>
</section>

<!-- Contextual FAB for Dashboard -->
<a href="supply/add_supply.php" class="fixed bottom-10 right-10 w-16 h-16 bg-gradient-to-br from-primary to-primary-container text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all group z-50">
    <span class="material-symbols-outlined text-3xl">add_task</span>
</a>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('supplyChart');
    if(canvas) {
        const ctx = canvas.getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Today'],
                datasets: [{
                    label: 'Hours Supplied',
                    data: [0, 0, 0, 0, 0, 0, <?= $today_supply['hours'] ?: 0 ?>],
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
