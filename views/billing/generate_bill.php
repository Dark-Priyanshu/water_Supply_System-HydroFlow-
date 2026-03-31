<?php 
require_once '../../config/config.php';
require_once '../../config/database.php';

if (!isset($_GET['supply_id'])) {
    header("Location: ../supply/supply_history.php");
    exit();
}

$supply_id = (int)$_GET['supply_id'];

// Check if bill already exists
$check = $conn->query("SELECT bill_id FROM bills WHERE supply_id = $supply_id");
if($check->num_rows > 0) {
    $existing = $check->fetch_assoc();
    header("Location: view_bill.php?id=" . $existing['bill_id']);
    exit();
}

// Fetch supply data
$query = "SELECT s.*, c.farmer_name, c.mobile, c.village, m.motor_name 
          FROM water_supply s 
          JOIN customers c ON s.customer_id = c.customer_id 
          JOIN motors m ON s.motor_id = m.motor_id 
          WHERE s.supply_id = $supply_id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    include '../../includes/header.php';
    include '../../includes/sidebar.php';
    echo "<div style='padding: 2.5rem; text-align: center; color: var(--color-error); font-weight: 700; font-family: var(--font-headline);'>Supply record not found.</div>";
    include '../../includes/footer.php';
    exit();
}

$supply = $result->fetch_assoc();

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<!-- Header -->
<div class="flex" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="breadcrumb">
            <span><?= __('directory') ?></span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <a href="../supply/supply_history.php"><?= __('supply_record') ?></a>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;"><?= __('generate_bill') ?></span>
        </nav>
        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;"><?= __('confirm_bill_generation') ?></h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;"><?= __('bill_gen_desc') ?></p>
    </div>
    <a href="../supply/supply_history.php" class="btn-secondary" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <span class="material-symbols-outlined" style="font-size: 1.25rem;">close</span>
        <span><?= __('cancel') ?></span>
    </a>
</div>

<div class="card" style="max-width: 40rem; margin: 2rem auto; overflow: hidden; border: 1px solid rgba(112, 120, 129, 0.2); box-shadow: 0 12px 48px rgba(0, 0, 0, 0.08);">
    <div style="height: 0.5rem; background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-primary-container) 50%, var(--color-secondary) 100%);"></div>
    <div style="padding: 3rem;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <h3 style="font-size: 1.75rem; font-weight: 800; font-family: var(--font-headline); color: var(--color-primary); margin: 0;">HydroFlow</h3>
            <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; color: var(--color-outline); font-weight: 700; margin-top: 0.25rem;"><?= __('invoice_preview') ?></p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(112, 120, 129, 0.1);">
            <div>
                <h6 style="font-size: 0.625rem; text-transform: uppercase; font-weight: 800; color: var(--color-on-surface-variant); letter-spacing: 0.15em; margin-bottom: 0.75rem;"><?= __('billed_to') ?>:</h6>
                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                    <p style="font-size: 1.125rem; font-weight: 700; color: var(--color-on-surface); margin: 0;"><?= htmlspecialchars($supply['farmer_name']) ?></p>
                    <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); margin: 0;"><?= htmlspecialchars($supply['village']) ?></p>
                    <p style="font-size: 0.8125rem; color: var(--color-on-surface-variant); font-weight: 600; margin-top: 0.25rem;">+91 <?= htmlspecialchars($supply['mobile']) ?></p>
                </div>
            </div>
            <div style="text-align: right;">
                <h6 style="font-size: 0.625rem; text-transform: uppercase; font-weight: 800; color: var(--color-on-surface-variant); letter-spacing: 0.15em; margin-bottom: 0.75rem;"><?= __('supply_details') ?>:</h6>
                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                    <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); margin: 0;"><span style="font-weight: 600; margin-right: 0.5rem;"><?= __('th_date') ?>:</span> <?= date('d M, Y', strtotime($supply['date'])) ?></p>
                    <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); margin: 0;"><span style="font-weight: 600; margin-right: 0.5rem;"><?= __('th_motor_used') ?>:</span> <?= htmlspecialchars($supply['motor_name']) ?></p>
                    <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); margin: 0;"><span style="font-weight: 600; margin-right: 0.5rem;"><?= __('th_duration') ?>:</span> <?= date('h:i A', strtotime($supply['start_time'])) ?> - <?= date('h:i A', strtotime($supply['end_time'])) ?></p>
                </div>
            </div>
        </div>

        <div style="background: var(--color-surface-container-low); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2.5rem; border: 1px solid rgba(112, 120, 129, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-on-surface-variant);"><?= __('usage_hours') ?></span>
                <span style="font-weight: 700; color: var(--color-on-surface);"><?= number_format($supply['total_hours'], 2) ?> h</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid rgba(112, 120, 129, 0.1); padding-bottom: 1rem;">
                <span style="font-size: 0.875rem; font-weight: 600; color: var(--color-on-surface-variant);"><?= __('rate_per_hour') ?></span>
                <span style="font-weight: 700; color: var(--color-on-surface);">₹<?= number_format($supply['rate'], 2) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                <span style="font-size: 1.125rem; font-weight: 800; font-family: var(--font-headline); color: var(--color-primary);"><?= __('est_amount') ?></span>
                <span style="font-size: 1.75rem; font-weight: 900; font-family: var(--font-headline); color: var(--color-primary);">₹<?= number_format($supply['total_amount'], 2) ?></span>
            </div>
        </div>

        <form action="../../controllers/billingController.php" method="POST">
            <input type="hidden" name="supply_id" value="<?= $supply['supply_id'] ?>">
            <input type="hidden" name="customer_id" value="<?= $supply['customer_id'] ?>">
            <input type="hidden" name="total_hours" value="<?= $supply['total_hours'] ?>">
            <input type="hidden" name="rate" value="<?= $supply['rate'] ?>">
            <input type="hidden" name="total_amount" value="<?= $supply['total_amount'] ?>">
            
            <button type="submit" name="generate_bill" class="btn bg-gradient-primary" style="width: 100%; padding: 1.25rem; border-radius: 0.875rem; font-size: 1rem; box-shadow: 0 12px 24px -6px rgba(0, 93, 144, 0.3);">
                <span class="material-symbols-outlined" style="font-size: 1.5rem;">receipt_long</span>
                <?= __('generate_final_bill') ?>
            </button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
