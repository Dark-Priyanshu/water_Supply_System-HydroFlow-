<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../config/database.php'; ?>
<?php require_once '../../models/Customer.php'; ?>

<?php
if (!isset($_GET['id'])) {
    header("Location: customer_list.php");
    exit();
}
$customerModel = new Customer($conn);
$customer = $customerModel->getCustomerById($_GET['id']);
if (!$customer) {
    header("Location: customer_list.php");
    exit();
}
?>

<!-- Header -->
<div class="flex" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="breadcrumb">
            <span><?= __('directory') ?></span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <a href="customer_list.php"><?= __('customers') ?></a>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;"><?= __('edit_customer') ?></span>
        </nav>
        <h2
            style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;">
            <?= __('edit_customer') ?></h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;"><?= __('edit_customer_desc') ?></p>
    </div>
    <a href="customer_list.php" class="btn-secondary"
        style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <span class="material-symbols-outlined" style="font-size: 1.25rem;">arrow_back</span>
        <span><?= __('back') ?></span>
    </a>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
    <div class="error-alert" style="max-width: 48rem;">
        <span class="material-symbols-outlined">error</span>
        <span style="font-weight: 700;"><?= $_SESSION['error_msg'] ?></span>
    </div>
    <?php unset($_SESSION['error_msg']); endif; ?>

<!-- Form Card -->
<div class="form-card">
    <div class="form-body">
        <form action="../../controllers/customerController.php" method="POST">
            <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer['customer_id']) ?>">
            <h4
                style="font-family: var(--font-headline); font-size: 1.125rem; font-weight: 700; color: var(--color-on-surface); margin-bottom: 1.5rem; border-bottom: 1px solid rgba(112, 120, 129, 0.1); padding-bottom: 0.5rem;">
                <?= __('personal_info') ?></h4>

            <div class="form-grid form-grid-2">
                <div class="input-group">
                    <label class="form-label"><?= __('farmer_name') ?> <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon">person</span>
                        <input type="text" name="farmer_name" required
                            value="<?= htmlspecialchars($customer['farmer_name']) ?>" class="input-field"
                            placeholder="<?= __('ph_farmer') ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label class="form-label"><?= __('mobile_number') ?> <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon">call</span>
                        <input type="text" name="mobile" required pattern="[0-9]{10}" title="10 digit mobile number"
                            value="<?= htmlspecialchars($customer['mobile']) ?>" class="input-field"
                            placeholder="<?= __('ph_mobile') ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label class="form-label"><?= __('village_location') ?> <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon">location_on</span>
                        <input type="text" name="village" required value="<?= htmlspecialchars($customer['village']) ?>"
                            class="input-field" placeholder="<?= __('ph_village') ?>">
                    </div>
                </div>
            </div>

            <h4
                style="font-family: var(--font-headline); font-size: 1.125rem; font-weight: 700; color: var(--color-on-surface); margin: 2rem 0 1.5rem; border-bottom: 1px solid rgba(112, 120, 129, 0.1); padding-bottom: 0.5rem;">
                <?= __('farm_tech_details') ?></h4>

            <div class="form-grid form-grid-2">
                <div class="input-group">
                    <label class="form-label"><?= __('farm_name') ?> <span
                            style="font-size: 0.625rem; color: var(--color-outline); margin-left: 0.25rem;">(<?= __('optional') ?>)</span></label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon">agriculture</span>
                        <input type="text" name="farm_name" value="<?= htmlspecialchars($customer['farm_name']) ?>"
                            class="input-field" placeholder="<?= __('ph_farm') ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label class="form-label"><?= __('connection_no') ?> <span
                            style="font-size: 0.625rem; color: var(--color-outline); margin-left: 0.25rem;">(<?= __('optional') ?>)</span></label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon">cable</span>
                        <input type="text" name="connection_no"
                            value="<?= htmlspecialchars($customer['connection_no']) ?>" class="input-field"
                            placeholder="<?= __('ph_conn') ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label class="form-label"><?= __('pipe_size') ?></label>
                    <div class="input-wrapper" style="display: flex; align-items: center;">
                        <span class="material-symbols-outlined input-icon">straighten</span>
                        <select name="pipe_size" class="input-field"
                            style="appearance: none; cursor: pointer; padding-right: 2.5rem;">
                            <option value=""><?= __('select_delivery_size') ?></option>
                            <option value="2 inch" <?= ($customer['pipe_size'] ?? '') === '2 inch' ? 'selected' : '' ?>>2
                                Inch Std.</option>
                            <option value="2.5 inch" <?= ($customer['pipe_size'] ?? '') === '2.5 inch' ? 'selected' : '' ?>>2.5 Inch</option>
                            <option value="3 inch" <?= ($customer['pipe_size'] ?? '') === '3 inch' ? 'selected' : '' ?>>3
                                Inch High Flow</option>
                            <option value="4 inch" <?= ($customer['pipe_size'] ?? '') === '4 inch' ? 'selected' : '' ?>>4
                                Inch Industrial</option>
                        </select>
                        <span class="material-symbols-outlined"
                            style="position: absolute; right: 0.875rem; pointer-events: none; color: var(--color-outline);">expand_more</span>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" name="update_customer" class="btn bg-gradient-primary"
                    style="flex: 1; padding: 1rem; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(44, 105, 78, 0.3); background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-secondary-container) 100%);">
                    <span class="material-symbols-outlined" style="font-size: 1.125rem;">save</span>
                    <?= __('edit_customer') ?>
                </button>
                <button type="reset" class="btn-secondary" style="padding: 1rem 2rem;">
                    <?= __('reset_changes') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>