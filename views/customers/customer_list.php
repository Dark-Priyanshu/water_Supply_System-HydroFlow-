<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Customer.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$customerModel = new Customer($conn);
$customers = $customerModel->getAllCustomers();
$customer_count = $customers->num_rows;
?>

<!-- Header Section -->
<div class="flex" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; margin-top: 1rem;">
    <div>
        <h2 style="font-family: var(--font-headline); font-size: 1.875rem; font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;"><?= __('customer_management') ?></h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;"><?= __('customer_desc') ?></p>
    </div>
    <a href="add_customer.php" class="btn bg-gradient-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.75rem;">
        <span class="material-symbols-outlined" style="font-size: 1.25rem;">person_add</span>
        <span><?= __('add_customer') ?></span>
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

<!-- Dashboard Stats / Filters Bento -->
<div class="grid" style="grid-template-columns: repeat(12, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <style>
        .col-8 { grid-column: span 12; }
        .col-4 { grid-column: span 12; }
        @media (min-width: 1024px) {
            .col-8 { grid-column: span 8; }
            .col-4 { grid-column: span 4; }
        }
    </style>
    <!-- Filter Section -->
    <div class="col-8" style="background-color: var(--color-surface-container-low); border-radius: 0.75rem; padding: 1.5rem; display: flex; flex-wrap: wrap; align-items: center; gap: 1.5rem;">
        <div class="flex" style="align-items: center; gap: 0.75rem;">
            <span style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em;"><?= __('filter_by') ?></span>
            <select style="background-color: var(--color-surface-container-lowest); border: none; border-radius: 0.5rem; font-size: 0.875rem; padding: 0.5rem 1rem; color: var(--color-on-surface-variant); cursor: pointer; outline: none;">
                <option><?= __('all_villages') ?></option>
            </select>
            <select style="background-color: var(--color-surface-container-lowest); border: none; border-radius: 0.5rem; font-size: 0.875rem; padding: 0.5rem 1rem; color: var(--color-on-surface-variant); cursor: pointer; outline: none;">
                <option><?= __('connection_status') ?></option>
            </select>
        </div>
        <div style="height: 2rem; width: 1px; background-color: rgba(112, 120, 129, 0.2); margin: 0 0.5rem;" class="hidden-mobile"></div>
        <div class="flex" style="gap: 0.5rem;">
            <button style="padding: 0.5rem 1rem; background-color: var(--color-surface-container-lowest); color: var(--color-primary); border: none; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; cursor: pointer;" onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--color-surface-container-lowest)'; this.style.color='var(--color-primary)';"><?= __('today') ?></button>
            <button style="padding: 0.5rem 1rem; background-color: var(--color-surface-container-lowest); color: #64748b; border: none; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; cursor: pointer;" onmouseover="this.style.backgroundColor='var(--color-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--color-surface-container-lowest)'; this.style.color='#64748b';"><?= __('this_month') ?></button>
        </div>
    </div>
    <!-- Quick Stats -->
    <div class="col-4 card" style="padding: 1.5rem; flex-direction: row; justify-content: space-between; align-items: center;">
        <div>
            <p style="font-size: 0.625rem; font-weight: 700; color: var(--color-on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;"><?= __('total_active_customers') ?></p>
            <h3 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-primary);"><?= $customer_count ?></h3>
        </div>
        <div style="width: 3rem; height: 3rem; border-radius: 50%; background-color: rgba(44, 105, 78, 0.1); color: var(--color-secondary); display: flex; align-items: center; justify-content: center;">
            <span class="material-symbols-outlined">groups</span>
        </div>
        <div class="absolute" style="bottom: 0; left: 0; right: 0; height: 4px; background: linear-gradient(to right, transparent, rgba(44, 105, 78, 0.2), transparent);"></div>
    </div>
</div>

<!-- Professional Data Table -->
<div class="table-container">
    <div style="overflow-x: auto;">
        <table class="table-custom datatable">
            <thead>
                <tr>
                    <th style="padding-left: 1.5rem;"><?= __('th_id') ?></th>
                    <th><?= __('th_farmer_name') ?></th>
                    <th><?= __('th_mobile_number') ?></th>
                    <th><?= __('th_village') ?></th>
                    <th><?= __('th_conn_no') ?></th>
                    <th><?= __('th_pipe_size') ?></th>
                    <th style="padding-right: 1.5rem; text-align: right;"><?= __('th_actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $customers->fetch_assoc()): ?>
                <tr>
                    <td style="padding-left: 1.5rem; font-weight: 700; color: var(--color-primary);">#<?= $row['customer_id'] ?></td>
                    <td>
                        <div class="flex" style="align-items: center; gap: 0.75rem;">
                            <div style="width: 2rem; height: 2rem; border-radius: 50%; background-color: rgba(0, 93, 144, 0.1); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                <?= strtoupper(substr(trim($row['farmer_name'] ?? __('na_placeholder')), 0, 2)) ?>
                            </div>
                            <span style="font-weight: 500;"><?= htmlspecialchars($row['farmer_name'] ?? __('na_placeholder')) ?></span>
                        </div>
                    </td>
                    <td style="color: var(--color-on-surface-variant);"><?= htmlspecialchars($row['mobile'] ?? __('na_placeholder')) ?></td>
                    <td>
                        <span style="padding: 0.25rem 0.5rem; background-color: #f1f5f9; color: #475569; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700; text-transform: uppercase;"><?= htmlspecialchars($row['village'] ?? __('na_placeholder')) ?></span>
                    </td>
                    <td style="color: var(--color-on-surface-variant);"><?= htmlspecialchars($row['connection_no'] ?? __('na_placeholder')) ?></td>
                    <td>
                        <div class="flex" style="align-items: center; gap: 0.5rem;">
                            <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background-color: <?= $row['pipe_size'] ? 'var(--color-secondary)' : '#cbd5e1' ?>;"></span>
                            <span style="font-weight: 500; font-size: 0.875rem; color: <?= $row['pipe_size'] ? 'var(--color-secondary)' : '#94a3b8' ?>;"><?= $row['pipe_size'] ? htmlspecialchars($row['pipe_size']) : __('na_placeholder') ?></span>
                        </div>
                    </td>
                    <td style="padding-right: 1.5rem; text-align: right;">
                        <div class="flex" style="justify-content: flex-end; gap: 0.5rem;">
                            <a href="#" style="padding: 0.375rem; color: #94a3b8;" onmouseover="this.style.color='var(--color-primary)';" onmouseout="this.style.color='#94a3b8';" data-tooltip="<?= __('tooltip_view') ?>"><span class="material-symbols-outlined" style="font-size: 1.125rem;">visibility</span></a>
                            <a href="edit_customer.php?id=<?= $row['customer_id'] ?>" style="padding: 0.375rem; color: #94a3b8;" onmouseover="this.style.color='var(--color-secondary)';" onmouseout="this.style.color='#94a3b8';" data-tooltip="<?= __('tooltip_edit') ?>"><span class="material-symbols-outlined" style="font-size: 1.125rem;">edit</span></a>
                            <a href="#" style="padding: 0.375rem; color: #94a3b8;" onmouseover="this.style.color='var(--color-error)';" onmouseout="this.style.color='#94a3b8';" data-tooltip="<?= __('tooltip_delete') ?>"><span class="material-symbols-outlined" style="font-size: 1.125rem;">delete</span></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @media (max-width: 767px) {
        .hidden-mobile { display: none !important; }
    }
</style>

<?php include '../../includes/footer.php'; ?>
