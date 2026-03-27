<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../models/Customer.php'; ?>
<?php require_once '../../models/Motor.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
$customerModel = new Customer($conn);
$motorModel = new Motor($conn);

$customers = $customerModel->getAllCustomers();
$motors = $motorModel->getAllMotors();
?>

<!-- Header Section -->
<div class="flex" style="justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; margin-top: 1rem;">
    <div>
        <nav class="breadcrumb">
            <span>Directory</span>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <a href="supply_history.php">Supply</a>
            <span class="material-symbols-outlined" style="font-size: 0.875rem;">chevron_right</span>
            <span style="color: var(--color-primary); font-weight: 500;">New Recording</span>
        </nav>
        <h2 style="font-size: 1.875rem; font-family: var(--font-headline); font-weight: 800; color: var(--color-on-surface); letter-spacing: -0.025em; margin-bottom: 0.5rem;">Water Supply Recording</h2>
        <p style="font-size: 1rem; color: var(--color-on-surface-variant); max-width: 40rem;">Log new irrigation activity and calculate usage metrics.</p>
    </div>
    <a href="supply_history.php" class="btn-secondary" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <span class="material-symbols-outlined" style="font-size: 1.25rem;">history</span>
        <span>View History</span>
    </a>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="error-alert" style="margin-bottom: 2rem;">
    <span class="material-symbols-outlined">error</span>
    <span style="font-weight: 700;"><?= $_SESSION['error_msg'] ?></span>
</div>
<?php unset($_SESSION['error_msg']); endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Main Form Section -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="form-card" style="max-width: none;">
            <div class="form-body">
                <form action="../../controllers/supplyController.php" method="POST" id="supplyForm">
                    <h3 style="font-size: 1.125rem; font-weight: 700; font-family: var(--font-headline); color: var(--color-on-surface); margin-bottom: 2rem; border-left: 4px solid var(--color-primary); padding-left: 1rem;">Recording Details</h3>
                    
                    <div class="form-grid form-grid-2">
                        <!-- Customer Selection -->
                        <div class="input-group">
                            <label class="form-label">Customer (Farmer) <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <span class="material-symbols-outlined input-icon">person</span>
                                <select name="customer_id" required class="input-field" style="appearance: none; cursor: pointer;">
                                    <option value="">Select Customer</option>
                                    <?php while($row = $customers->fetch_assoc()): ?>
                                        <option value="<?= $row['customer_id'] ?>"><?= htmlspecialchars($row['farmer_name']) ?><?= !empty($row['connection_no']) ? ' (' . htmlspecialchars($row['connection_no']) . ')' : '' ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <span class="material-symbols-outlined" style="position: absolute; right: 0.875rem; pointer-events: none; color: var(--color-outline);">expand_more</span>
                            </div>
                        </div>
                        
                        <!-- Motor Selection -->
                        <div class="input-group">
                            <label class="form-label">Motor Used <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <span class="material-symbols-outlined input-icon">water_pump</span>
                                <select name="motor_id" required class="input-field" style="appearance: none; cursor: pointer;">
                                    <option value="">Select Motor</option>
                                    <?php while($row = $motors->fetch_assoc()): ?>
                                        <option value="<?= $row['motor_id'] ?>"><?= htmlspecialchars($row['motor_name']) ?> (<?= number_format($row['horsepower'], 1) ?> HP)</option>
                                    <?php endwhile; ?>
                                </select>
                                <span class="material-symbols-outlined" style="position: absolute; right: 0.875rem; pointer-events: none; color: var(--color-outline);">expand_more</span>
                            </div>
                        </div>
                        
                        <!-- Date Picker -->
                        <div class="input-group">
                            <label class="form-label">Supply Date <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <span class="material-symbols-outlined input-icon">calendar_today</span>
                                <input type="date" name="date" required value="<?= date('Y-m-d') ?>" class="input-field"/>
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <label class="form-label">Rate (₹/hour) <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <span class="material-symbols-outlined input-icon">payments</span>
                                <input type="number" step="0.5" name="rate" id="rate" value="125" required class="input-field" style="font-weight: 700;"/>
                            </div>
                        </div>
                        
                        <!-- Time Range -->
                        <div class="input-group">
                            <label class="form-label">Start Time <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <span class="material-symbols-outlined input-icon">schedule</span>
                                <input type="time" name="start_time" id="start_time" required class="input-field"/>
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <label class="form-label">End Time <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <span class="material-symbols-outlined input-icon">schedule</span>
                                <input type="time" name="end_time" id="end_time" required class="input-field"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-footer">
                        <button type="submit" name="record_supply" class="btn bg-gradient-primary" style="flex: 1; padding: 1rem; border-radius: 0.75rem;">
                            <span class="material-symbols-outlined" style="font-size: 1.125rem;">save</span>
                            Save Supply Record
                        </button>
                    </div>
                    
                    <input type="hidden" name="total_hours" id="hidden_total_hours" value="0">
                    <input type="hidden" name="total_amount" id="hidden_total_amount" value="0">
                </form>
            </div>
        </div>
        
        <!-- Info Cards -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="card" style="padding: 1.5rem; background: var(--color-surface-container-low);">
                <span class="material-symbols-outlined" style="font-size: 2rem; color: var(--color-primary); margin-bottom: 0.75rem;">info</span>
                <h4 style="font-weight: 700; color: var(--color-on-surface); margin-bottom: 0.5rem; font-family: var(--font-headline);">Operating Protocol</h4>
                <p style="font-size: 0.8125rem; color: var(--color-on-surface-variant); line-height: 1.5;">Ensure the pressure valve is adjusted before starting high-HP motors to prevent pipe fatigue.</p>
            </div>
            <div class="card" style="padding: 1.5rem; background: var(--color-surface-container-low);">
                <span class="material-symbols-outlined" style="font-size: 2rem; color: var(--color-secondary); margin-bottom: 0.75rem;">eco</span>
                <h4 style="font-weight: 700; color: var(--color-on-surface); margin-bottom: 0.5rem; font-family: var(--font-headline);">Conservation Tip</h4>
                <p style="font-size: 0.8125rem; color: var(--color-on-surface-variant); line-height: 1.5;">Early morning supply reduces evaporation by up to 22% compared to afternoon irrigation.</p>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card" style="background: #191c1e; color: white; padding: 2rem; border: none; position: relative; overflow: hidden;">
            <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(0, 93, 144, 0.2) 0%, transparent 100%);"></div>
            <div style="position: relative; z-index: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                    <p style="font-size: 0.625rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: rgba(255,255,255,0.6); display: flex; align-items: center; gap: 0.5rem;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--color-secondary);"></span> Live Calculator
                    </p>
                    <span class="material-symbols-outlined" style="color: var(--color-primary-container);">analytics</span>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 2rem;">
                    <div>
                        <p style="font-size: 0.75rem; color: rgba(255,255,255,0.6); margin-bottom: 0.5rem;">Total Duration</p>
                        <h4 id="durationDisplay" style="font-size: 2.25rem; font-weight: 800; font-family: var(--font-headline); margin: 0;">0h 0m</h4>
                        <p id="hoursDisplay" style="font-size: 0.625rem; color: rgba(255,255,255,0.4); margin-top: 0.25rem;">0.00 hrs</p>
                    </div>
                    
                    <div style="padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
                        <p style="font-size: 0.75rem; color: rgba(255,255,255,0.6); margin-bottom: 0.5rem;">Estimated Amount</p>
                        <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                            <h2 id="amountDisplay" style="font-size: 2.5rem; font-weight: 900; font-family: var(--font-headline); color: var(--color-primary-container); margin: 0;">₹0.00</h2>
                            <span style="font-size: 0.625rem; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.4);">Gross</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const rateInput = document.getElementById('rate');
    
    const durationDisplay = document.getElementById('durationDisplay');
    const hoursDisplay = document.getElementById('hoursDisplay');
    const amountDisplay = document.getElementById('amountDisplay');

    function calculate() {
        if (!startTime.value || !endTime.value) return;

        let start = new Date(`2000-01-01T${startTime.value}`);
        let end = new Date(`2000-01-01T${endTime.value}`);

        if (end < start) {
            end.setDate(end.getDate() + 1); // Next day
        }

        let diffMs = end - start;
        let diffHrs = diffMs / (1000 * 60 * 60);
        
        let hours = Math.floor(diffHrs);
        let minutes = Math.round((diffHrs - hours) * 60);
        
        durationDisplay.textContent = `${hours}h ${minutes}m`;
        hoursDisplay.textContent = `${diffHrs.toFixed(2)} hrs`;
        
        let rate = parseFloat(rateInput.value) || 0;
        let amount = diffHrs * rate;
        
        amountDisplay.textContent = `₹${amount.toFixed(2)}`;
        
        document.getElementById('hidden_total_hours').value = diffHrs.toFixed(2);
        document.getElementById('hidden_total_amount').value = amount.toFixed(2);
    }

    startTime.addEventListener('change', calculate);
    endTime.addEventListener('change', calculate);
    rateInput.addEventListener('input', calculate);
});
</script>

<?php include '../../includes/footer.php'; ?>
