<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Motor.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$motorModel = new Motor($conn);
$motors = $motorModel->getAllMotors();
$total_motors = $motors->num_rows;
$active_motors = 0;
$total_hp = 0;

// Need to quickly calculate stats
$motors_data = [];
if($total_motors > 0) {
    while($row = $motors->fetch_assoc()) {
        $motors_data[] = $row;
        $total_hp += (float)$row['horsepower'];
        if($row['status'] == 'active') {
            $active_motors++;
        }
    }
}
?>

<!-- Editorial Header Section -->
<section style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-end; margin-top: 1rem;">
    <div>
        <span style="color: var(--color-secondary); font-weight: 700; letter-spacing: 0.2em; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.5rem; display: block;"><?= __('system_infrastructure') ?></span>
        <h2 style="font-size: 2.25rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em;"><?= __('motor_management') ?></h2>
        <p style="color: var(--color-on-surface-variant); margin-top: 0.5rem; max-width: 32rem;"><?= __('motor_desc') ?></p>
    </div>
    <a href="add_motor.php" class="btn bg-gradient-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.75rem;">
        <span class="material-symbols-outlined">add_circle</span> <?= __('add_new_motor') ?>
    </a>
</section>

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

<!-- Metrics Overview -->
<div class="grid-4" style="margin-bottom: 2.5rem;">
    <div class="card" style="padding: 1.5rem; border-bottom: 4px solid rgba(44, 105, 78, 0.2);">
        <div class="flex" style="justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); margin-bottom: 1rem;"><?= __('active_motors') ?></p>
                <h3 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 700;"><?= $active_motors ?> / <?= $total_motors ?></h3>
            </div>
            <span class="material-symbols-outlined" style="color: var(--color-secondary); font-size: 2rem; opacity: 0.5;">bolt</span>
        </div>
    </div>
    <div class="card" style="padding: 1.5rem; border-bottom: 4px solid rgba(0, 93, 144, 0.2);">
        <div class="flex" style="justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="font-size: 0.875rem; color: var(--color-on-surface-variant); margin-bottom: 1rem;"><?= __('total_capacity') ?></p>
                <h3 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 700;"><?= $total_hp ?> HP</h3>
            </div>
            <span class="material-symbols-outlined" style="color: var(--color-primary); font-size: 2rem; opacity: 0.5;">speed</span>
        </div>
    </div>
</div>

<!-- Motor Inventory Grid -->
<div class="grid" style="grid-template-columns: repeat(1, 1fr); gap: 2rem; margin-bottom: 3rem;">
    <style>
        @media (min-width: 1280px) {
            .motor-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
    </style>
    <div class="grid motor-grid" style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
        <?php if ($total_motors > 0): ?>
            <?php foreach ($motors_data as $motor): ?>
            <div class="searchable-item card" style="flex-direction: row; padding: 0; overflow: hidden; border: 1px solid rgba(112, 120, 129, 0.1);">
                 <div style="width: 8rem; background-color: var(--color-surface-container-highest); display: flex; align-items: center; justify-content: center; position: relative; flex-shrink: 0;">
                     <span class="material-symbols-outlined" style="font-size: 3.5rem; color: rgba(112, 120, 129, 0.3);">water_pump</span>
                     <?php if($motor['status'] == 'active'): ?>
                     <div style="position: absolute; top: 0.75rem; left: 0.75rem; padding: 0.25rem 0.5rem; background-color: var(--color-secondary); color: white; font-size: 0.625rem; font-weight: 700; border-radius: 9999px; text-transform: uppercase;"><?= __('val_active') ?></div>
                     <?php else: ?>
                     <div style="position: absolute; top: 0.75rem; left: 0.75rem; padding: 0.25rem 0.5rem; background-color: var(--color-error); color: white; font-size: 0.625rem; font-weight: 700; border-radius: 9999px; text-transform: uppercase;"><?= __('val_inactive') ?></div>
                     <?php endif; ?>
                 </div>
                 <div style="flex: 1; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;">
                     <div>
                         <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                             <h4 style="font-size: 1.25rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface);"><?= htmlspecialchars($motor['motor_name']) ?> <span style="font-size: 0.875rem; font-weight: 500; color: var(--color-outline); margin-left: 0.5rem;">#<?= $motor['motor_id'] ?></span></h4>
                             <span class="badge" style="background-color: var(--color-primary-fixed); color: var(--color-primary);"><?= number_format($motor['horsepower'], 1) ?> HP</span>
                         </div>
                         <div style="display: flex; align-items: center; gap: 0.25rem; color: var(--color-on-surface-variant); font-size: 0.875rem;">
                             <span class="material-symbols-outlined" style="font-size: 0.875rem;">location_on</span>
                             <span><?= htmlspecialchars($motor['location'] ?: __('loc_not_specified')) ?></span>
                         </div>
                     </div>
                     <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid rgba(112, 120, 129, 0.1); margin-top: 0.5rem;">
                         <a href="edit_motor.php?id=<?= $motor['motor_id'] ?>" style="padding: 0.5rem; color: var(--color-on-surface-variant); display: flex; border-radius: 0.5rem;" onmouseover="this.style.backgroundColor='rgba(0, 93, 144, 0.1)'; this.style.color='var(--color-primary)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-on-surface-variant)';" title="<?= __('edit_motor') ?>"><span class="material-symbols-outlined">edit_note</span></a>
                         <a href="../../controllers/motorController.php?action=toggle_status&id=<?= $motor['motor_id'] ?>" style="padding: 0.5rem; color: var(--color-on-surface-variant); display: flex; border-radius: 0.5rem;" onmouseover="this.style.backgroundColor='rgba(0, 93, 144, 0.1)'; this.style.color='var(--color-primary)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--color-on-surface-variant)';" title="<?= $motor['status'] == 'active' ? __('deactivate_motor') : __('activate_motor') ?>">
                            <span class="material-symbols-outlined"><?= $motor['status'] == 'active' ? 'visibility_off' : 'visibility' ?></span>
                         </a>
                     </div>
                 </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; background-color: var(--color-surface-container-lowest); border-radius: 0.75rem; padding: 3rem; text-align: center; border: 1px solid rgba(112, 120, 129, 0.1);">
                 <span class="material-symbols-outlined" style="font-size: 3rem; color: var(--color-outline); margin-bottom: 1rem;">water_pump</span>
                 <h3 style="font-size: 1.25rem; font-family: var(--font-headline); font-weight: 700; color: var(--color-on-surface); margin-bottom: 0.5rem;"><?= __('no_motors_found') ?></h3>
                 <p style="color: var(--color-on-surface-variant); margin-bottom: 1.5rem;"><?= __('no_motors_desc') ?></p>
                 <a href="add_motor.php" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                     <?= __('add_first_motor') ?>
                 </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
