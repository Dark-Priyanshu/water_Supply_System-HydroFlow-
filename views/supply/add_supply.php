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
<div class="flex justify-between items-end mb-8 mt-4">
    <div>
        <h2 class="text-headline-lg font-bold text-slate-900 font-headline tracking-tight">Water Supply Recording</h2>
        <p class="text-slate-500 mt-1 font-body">Log new irrigation activity and calculate usage metrics.</p>
    </div>
    <a href="supply_history.php" class="flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface-variant rounded-xl font-semibold hover:bg-surface-container-highest transition-colors">
        <span class="material-symbols-outlined text-[20px]">history</span>
        <span class="text-sm">History</span>
    </a>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="mb-8 p-4 bg-error-container text-on-error-container rounded-xl flex items-center gap-3 border border-error/20">
    <span class="material-symbols-outlined">error</span>
    <span class="text-sm font-bold"><?= $_SESSION['error_msg'] ?></span>
</div>
<?php unset($_SESSION['error_msg']); endif; ?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start mb-12">
    <!-- Main Form Section -->
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_8px_32px_rgba(25,28,30,0.04)] border border-outline-variant/10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1 bg-primary h-6 rounded-full"></div>
                <h3 class="text-xl font-bold font-headline text-slate-800">Recording Details</h3>
            </div>
            
            <form action="../../controllers/supplyController.php" method="POST" id="supplyForm" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Customer Selection -->
                    <div class="space-y-2">
                        <label class="text-label-sm font-semibold text-on-surface-variant uppercase tracking-wider block">Customer (Farmer) <span class="text-error">*</span></label>
                        <div class="relative">
                            <select name="customer_id" required class="w-full pl-4 pr-10 py-3 bg-surface-container border-none rounded-lg focus:ring-2 focus:ring-primary/40 appearance-none font-body text-on-surface transition-all text-sm">
                                <option value="">Select Customer</option>
                                <?php while($row = $customers->fetch_assoc()): ?>
                                    <option value="<?= $row['customer_id'] ?>"><?= htmlspecialchars($row['farmer_name']) ?><?= !empty($row['connection_no']) ? ' (' . htmlspecialchars($row['connection_no']) . ')' : '' ?></option>
                                <?php endwhile; ?>
                            </select>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    
                    <!-- Motor Selection -->
                    <div class="space-y-2">
                        <label class="text-label-sm font-semibold text-on-surface-variant uppercase tracking-wider block">Motor Used <span class="text-error">*</span></label>
                        <div class="relative">
                            <select name="motor_id" required class="w-full pl-4 pr-10 py-3 bg-surface-container border-none rounded-lg focus:ring-2 focus:ring-primary/40 appearance-none font-body text-on-surface transition-all text-sm">
                                <option value="">Select Motor</option>
                                <?php while($row = $motors->fetch_assoc()): ?>
                                    <option value="<?= $row['motor_id'] ?>"><?= htmlspecialchars($row['motor_name']) ?> (<?= number_format($row['horsepower'], 1) ?> HP)</option>
                                <?php endwhile; ?>
                            </select>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    
                    <!-- Date Picker -->
                    <div class="space-y-2">
                        <label class="text-label-sm font-semibold text-on-surface-variant uppercase tracking-wider block">Supply Date <span class="text-error">*</span></label>
                        <input type="date" name="date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 bg-surface-container border-none rounded-lg focus:ring-2 focus:ring-primary/40 font-body text-on-surface transition-all text-sm"/>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-label-sm font-semibold text-on-surface-variant uppercase tracking-wider block">Rate (₹/hour) <span class="text-error">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold">₹</span>
                            <input type="number" step="0.5" name="rate" id="rate" value="125" required class="w-full pl-8 pr-4 py-3 bg-surface-container border-none rounded-lg focus:ring-2 focus:ring-primary/40 font-body text-on-surface transition-all text-sm font-bold"/>
                        </div>
                    </div>
                    
                    <!-- Time Range -->
                    <div class="space-y-2">
                        <label class="text-label-sm font-semibold text-on-surface-variant uppercase tracking-wider block">Start Time <span class="text-error">*</span></label>
                        <input type="time" name="start_time" id="start_time" required class="w-full px-4 py-3 bg-surface-container border-none rounded-lg focus:ring-2 focus:ring-primary/40 font-body text-on-surface transition-all text-sm"/>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-label-sm font-semibold text-on-surface-variant uppercase tracking-wider block">End Time <span class="text-error">*</span></label>
                        <input type="time" name="end_time" id="end_time" required class="w-full px-4 py-3 bg-surface-container border-none rounded-lg focus:ring-2 focus:ring-primary/40 font-body text-on-surface transition-all text-sm"/>
                    </div>
                </div>
                
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" name="record_supply" class="px-10 py-3 rounded-xl bg-gradient-to-r from-primary to-primary-container text-white font-bold text-sm shadow-lg shadow-primary/20 hover:shadow-xl transition-all active:scale-[0.98] flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Save Supply Record
                    </button>
                </div>
                
                <!-- Hidden inputs for calculated fields (if needed by backend) -->
                <input type="hidden" name="total_hours" id="hidden_total_hours" value="0">
                <input type="hidden" name="total_amount" id="hidden_total_amount" value="0">
            </form>
        </div>
        
        <!-- Informational Bento -->
        <div class="grid grid-cols-2 gap-6">
            <div class="bg-surface-container-low rounded-xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="material-symbols-outlined text-primary mb-3 text-3xl">info</span>
                    <h4 class="font-bold text-slate-800 font-headline">Operating Protocol</h4>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Ensure the pressure valve is adjusted before starting high-HP motors to prevent pipe fatigue.</p>
                </div>
            </div>
            <div class="bg-surface-container-low rounded-xl p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="material-symbols-outlined text-secondary mb-3 text-3xl">eco</span>
                    <h4 class="font-bold text-slate-800 font-headline">Conservation Tip</h4>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Early morning supply reduces evaporation by up to 22% compared to afternoon irrigation.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Preview & Actions Sidebar -->
    <div class="space-y-6">
        <!-- Calculated Preview Card -->
        <div class="bg-slate-900 rounded-2xl p-8 text-white relative overflow-hidden shadow-xl">
            <!-- Decorative Water Wave -->
            <div class="absolute inset-0 bg-gradient-to-t from-primary/30 to-transparent pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-primary-fixed-dim to-transparent opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-10">
                    <p class="text-slate-400 text-[11px] uppercase tracking-widest font-semibold flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span> Live Calculator</p>
                    <span class="material-symbols-outlined text-primary-fixed-dim">analytics</span>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-slate-400 text-xs font-medium">Total Duration</p>
                        <h4 class="text-3xl font-bold font-headline mt-1 tracking-tight" id="durationDisplay">0h 0m</h4>
                        <p class="text-[10px] text-slate-500 mt-1" id="hoursDisplay">0.00 hrs</p>
                    </div>
                    
                    <div class="pt-6 border-t border-slate-800">
                        <p class="text-slate-400 text-xs font-medium mb-1">Estimated Amount Invoice</p>
                        <div class="flex items-baseline gap-2">
                            <h2 class="text-4xl font-extrabold font-headline text-primary-fixed-dim" id="amountDisplay">₹0.00</h2>
                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Gross</span>
                        </div>
                    </div>
                </div>
                <!-- Submit button handled by form text above, giving preview vibes here -->
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
        
        // Let's populate hidden inputs if backend needs them, else backend recalculates anyway (our backend calculates it usually, but keeping form standard)
        
        let rate = parseFloat(rateInput.value) || 0;
        let amount = diffHrs * rate;
        
        amountDisplay.textContent = `₹${amount.toFixed(2)}`;
    }

    startTime.addEventListener('change', calculate);
    endTime.addEventListener('change', calculate);
    rateInput.addEventListener('input', calculate);
});
</script>

<?php include '../../includes/footer.php'; ?>
