<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php
require_once '../../config/database.php';
require_once '../../models/Motor.php';

if (!isset($_GET['id'])) {
    header("Location: motor_list.php");
    exit();
}

$motorModel = new Motor($conn);
$motor = $motorModel->getMotorById($_GET['id']);

if (!$motor) {
    header("Location: motor_list.php");
    exit();
}
?>

<!-- Header -->
<div class="flex" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="breadcrumb">
            <span>Directory</span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <a href="motor_list.php">Motors</a>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;">Edit Details</span>
        </nav>
        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;">Edit Motor Details</h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;">Update information for this specific pump or borewell.</p>
    </div>
    <a href="motor_list.php" class="btn-secondary" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <span class="material-symbols-outlined" style="font-size: 1.25rem;">arrow_back</span>
        <span>Back to List</span>
    </a>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="error-alert" style="max-width: 40rem; margin: 0 auto 1.5rem;">
    <span class="material-symbols-outlined">error</span>
    <span style="font-weight: 700;"><?= $_SESSION['error_msg'] ?></span>
</div>
<?php unset($_SESSION['error_msg']); endif; ?>

<!-- Form Card -->
<div class="form-card" style="max-width: 40rem; margin: 0 auto;">
    <div class="form-body">
        <form action="../../controllers/motorController.php" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <input type="hidden" name="motor_id" value="<?= htmlspecialchars($motor['motor_id']) ?>">
            
            <div class="input-group">
                <label class="form-label">Motor Name <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="material-symbols-outlined input-icon">water_pump</span>
                    <input type="text" name="motor_name" value="<?= htmlspecialchars($motor['motor_name']) ?>" required class="input-field">
                </div>
            </div>
            
            <div class="input-group">
                <label class="form-label">Horsepower (HP) <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="material-symbols-outlined input-icon">bolt</span>
                    <input type="number" step="0.5" name="horsepower" value="<?= htmlspecialchars($motor['horsepower']) ?>" required class="input-field">
                </div>
            </div>
            
            <div class="input-group">
                <label class="form-label">Location</label>
                <div class="input-wrapper">
                    <span class="material-symbols-outlined input-icon" style="top: 1rem; transform: none;">location_on</span>
                    <textarea name="location" rows="3" class="input-field" style="padding-top: 0.75rem; min-height: 100px; resize: vertical;"><?= htmlspecialchars($motor['location']) ?></textarea>
                </div>
            </div>
            
            <div class="form-footer" style="margin-top: 1rem; padding-top: 1.5rem;">
                <button type="submit" name="update_motor" class="btn bg-gradient-primary" style="width: 100%; padding: 1rem; border-radius: 0.75rem;">
                    <span class="material-symbols-outlined" style="font-size: 1.25rem;">save</span>
                    Update Motor Details
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
