<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Bill.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$billModel = new Bill($conn);
$bills = $billModel->getAllBills();
?>

<!-- Action Bar -->
<div class="flex no-print" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="flex" style="align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-on-surface-variant); margin-bottom: 0.5rem;">
            <span>Finance</span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;">Invoices</span>
        </nav>
        <h2 style="font-size: 1.875rem; font-weight: 800; font-family: var(--font-headline); color: var(--color-on-surface); letter-spacing: -0.025em;">Billing & Invoices</h2>
    </div>
</div>

<?php if (isset($_SESSION['success_msg'])): ?>
<div class="error-alert no-print" style="background-color: var(--color-secondary-container); color: var(--color-on-secondary-container); border-color: rgba(44, 105, 78, 0.2); margin-bottom: 2rem;">
    <div class="flex" style="align-items: center; gap: 0.75rem; width: 100%;">
        <span class="material-symbols-outlined">check_circle</span>
        <span style="font-weight: 500; font-size: 0.875rem; flex: 1;"><?= $_SESSION['success_msg'] ?></span>
        <button type="button" style="background: transparent; border: none; cursor: pointer; color: inherit; opacity: 0.6;" onclick="this.parentElement.parentElement.remove()">
            <span class="material-symbols-outlined" style="font-size: 1.125rem;">close</span>
        </button>
    </div>
</div>
<?php unset($_SESSION['success_msg']); endif; ?>

<!-- Invoice List Container -->
<div class="table-container">
    <div style="overflow-x: auto;">
        <table class="table-custom datatable">
            <thead>
                <tr>
                    <th style="padding-left: 1.5rem;">Invoice #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th style="padding-right: 1.5rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bills->fetch_assoc()): ?>
                <tr onmouseover="this.style.backgroundColor='rgba(242, 244, 246, 0.5)';" onmouseout="this.style.backgroundColor='transparent';">
                    <td style="padding-left: 1.5rem; font-weight: 700; font-size: 0.875rem;">INV-<?= str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT) ?></td>
                    <td style="font-size: 0.875rem; color: var(--color-on-surface-variant);"><?= date('d M, Y', strtotime($row['bill_date'])) ?></td>
                    <td>
                        <div style="font-weight: 600; color: var(--color-primary); font-size: 0.875rem;"><?= htmlspecialchars($row['farmer_name'] ?? 'N/A') ?></div>
                        <div style="font-size: 0.6875rem; color: var(--color-on-surface-variant);">+91 <?= htmlspecialchars($row['mobile'] ?? 'N/A') ?></div>
                    </td>
                    <td style="font-weight: 700; font-family: var(--font-headline);">₹<?= number_format($row['total_amount'], 2) ?></td>
                    <td>
                        <?php if($row['status'] == 'paid'): ?>
                            <span class="badge" style="background-color: rgba(44, 105, 78, 0.1); color: var(--color-secondary); border: 1px solid rgba(44, 105, 78, 0.1); display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined" style="font-size: 0.875rem;">check_circle</span> Paid
                            </span>
                        <?php elseif($row['status'] == 'cancelled'): ?>
                            <span class="badge" style="background-color: rgba(186, 26, 26, 0.1); color: var(--color-error); border: 1px solid rgba(186, 26, 26, 0.1); display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined" style="font-size: 0.875rem;">cancel</span> Cancelled
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background-color: var(--color-surface-dim); color: var(--color-on-surface-variant); border: 1px solid rgba(112, 120, 129, 0.2); display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined" style="font-size: 0.875rem;">schedule</span> Pending
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="padding-right: 1.5rem; text-align: right;">
                        <div class="flex" style="justify-content: flex-end; align-items: center; gap: 0.5rem;">
                            <a href="view_bill.php?id=<?= $row['bill_id'] ?>" style="padding: 0.5rem; color: var(--color-on-surface-variant); display: flex; border-radius: 0.5rem;" onmouseover="this.style.backgroundColor='rgba(0, 93, 144, 0.1)'; this.style.color='var(--color-primary)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-on-surface-variant)';" title="View Invoice">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                            
                            <?php if($row['status'] == 'pending'): ?>
                                <div style="position: relative;" class="legacy-dropdown">
                                    <button type="button" style="padding: 0.5rem; color: var(--color-on-surface-variant); display: flex; border-radius: 0.5rem; background: transparent; border: none; cursor: pointer;" onclick="this.nextElementSibling.style.display = (this.nextElementSibling.style.display === 'block' ? 'none' : 'block')">
                                        <span class="material-symbols-outlined">more_vert</span>
                                    </button>
                                    <div style="position: absolute; right: 0; top: 100%; margin-top: 0.25rem; background-color: var(--color-surface-container-lowest); border: 1px solid rgba(112, 120, 129, 0.1); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-radius: 0.75rem; overflow: hidden; display: none; z-index: 10; width: 10rem;" onmouseleave="this.style.display='none'">
                                        <a style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 500; color: var(--color-secondary); text-decoration: none;" onmouseover="this.style.backgroundColor='var(--color-surface-container-low)';" onmouseout="this.style.backgroundColor='transparent';" href="../../controllers/billingController.php?update_status=paid&id=<?= $row['bill_id'] ?>">
                                            <span class="material-symbols-outlined" style="font-size: 1.125rem;">payments</span> Mark Paid
                                        </a>
                                        <a style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 500; color: var(--color-error); text-decoration: none;" onmouseover="this.style.backgroundColor='rgba(186, 26, 26, 0.05)';" onmouseout="this.style.backgroundColor='transparent';" href="../../controllers/billingController.php?update_status=cancelled&id=<?= $row['bill_id'] ?>">
                                            <span class="material-symbols-outlined" style="font-size: 1.125rem;">cancel</span> Cancel Bill
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
