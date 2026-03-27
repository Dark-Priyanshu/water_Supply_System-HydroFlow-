<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Supply.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$supplyModel = new Supply($conn);
$supplies = $supplyModel->getAllSupply();
?>

<!-- Header Section -->
<div class="flex" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <h2 style="font-family: var(--font-headline); font-size: 1.875rem; font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;">Water Supply History</h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;">View previous irrigation logs, duration, and billing status.</p>
    </div>
    <a href="add_supply.php" class="btn bg-gradient-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.75rem;">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 1.25rem;">waves</span>
        <span>Record Supply</span>
    </a>
</div>

<?php if (isset($_SESSION['success_msg'])): ?>
<div class="error-alert" style="background-color: var(--color-secondary-container); color: var(--color-on-secondary-container); border-color: rgba(44, 105, 78, 0.2); margin-bottom: 2rem;">
    <div class="flex" style="align-items: center; gap: 0.75rem; width: 100%;">
        <span class="material-symbols-outlined">check_circle</span>
        <span style="font-weight: 500; font-size: 0.875rem; flex: 1;"><?= $_SESSION['success_msg'] ?></span>
        <button type="button" style="background: transparent; border: none; cursor: pointer; color: inherit; opacity: 0.6;" onclick="this.parentElement.parentElement.remove()">
            <span class="material-symbols-outlined" style="font-size: 1.125rem;">close</span>
        </button>
    </div>
</div>
<?php unset($_SESSION['success_msg']); endif; ?>

<!-- Professional Data Table -->
<div class="table-container">
    <div style="overflow-x: auto;">
        <table class="table-custom datatable">
            <thead>
                <tr>
                    <th style="padding-left: 1.5rem;">Supply ID</th>
                    <th>Date</th>
                    <th>Farmer Name</th>
                    <th>Motor Used</th>
                    <th>Duration</th>
                    <th style="text-right">Amount</th>
                    <th style="padding-right: 1.5rem; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $supplies->fetch_assoc()): ?>
                <tr>
                    <td style="padding-left: 1.5rem; font-weight: 700; color: var(--color-primary);">#<?= $row['supply_id'] ?></td>
                    <td>
                        <span style="font-weight: 500;"><?= date('d M, Y', strtotime($row['date'])) ?></span>
                    </td>
                    <td style="font-weight: 600;">
                        <?= htmlspecialchars($row['farmer_name'] ?? 'N/A') ?>
                    </td>
                    <td>
                        <div class="flex" style="align-items: center; gap: 0.5rem; color: var(--color-on-surface-variant); font-size: 0.8125rem;">
                            <span class="material-symbols-outlined" style="font-size: 1rem; opacity: 0.6;">water_pump</span>
                            <span style="font-weight: 500;"><?= htmlspecialchars($row['motor_name'] ?? 'N/A') ?></span>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 0.75rem; font-weight: 600; color: var(--color-on-surface-variant); margin-bottom: 0.25rem;">
                            <?= date('h:i A', strtotime($row['start_time'])) ?> - <?= date('h:i A', strtotime($row['end_time'])) ?>
                        </div>
                        <span style="padding: 0.125rem 0.5rem; background-color: var(--color-surface-container-high); border-radius: 0.25rem; font-size: 0.6875rem; font-weight: 700; color: var(--color-on-surface-variant);"><?= number_format($row['total_hours'], 2) ?> hrs</span>
                    </td>
                    <td style="text-align: right; font-weight: 800; color: var(--color-secondary);">
                        ₹<?= number_format($row['total_amount'], 2) ?>
                    </td>
                    <td style="padding-right: 1.5rem; text-align: center;">
                        <?php 
                        $check_bill = $conn->query("SELECT bill_id FROM bills WHERE supply_id = " . (int)$row['supply_id']);
                        if($check_bill && $check_bill->num_rows > 0): 
                        ?>
                            <span class="badge" style="background-color: rgba(44, 105, 78, 0.1); color: var(--color-secondary); border: 1px solid rgba(44, 105, 78, 0.1); display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined" style="font-size: 0.875rem;">check_circle</span>
                                Billed
                            </span>
                        <?php else: ?>
                            <a href="../billing/generate_bill.php?supply_id=<?= $row['supply_id'] ?>" class="btn" style="padding: 0.375rem 0.75rem; font-size: 0.6875rem; background-color: rgba(0, 93, 144, 0.1); color: var(--color-primary); border: 1px solid rgba(0, 93, 144, 0.1);" onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='rgba(0, 93, 144, 0.1)'; this.style.color='var(--color-primary)';">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">receipt_long</span> Generate Bill
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
