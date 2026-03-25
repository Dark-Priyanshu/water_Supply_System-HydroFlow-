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
<section class="mb-10 flex justify-between items-end mt-4">
    <div>
        <span class="text-secondary font-bold tracking-widest text-xs uppercase mb-2 block">System Infrastructure</span>
        <h2 class="text-4xl font-headline font-extrabold text-on-surface tracking-tight">Motor Management</h2>
        <p class="text-on-surface-variant mt-2 max-w-lg">Monitor and manage your hydrological network. Record borewell pumps and distribution motors.</p>
    </div>
    <a href="add_motor.php" class="bg-gradient-to-r from-primary to-primary-container text-white px-6 py-3 rounded-xl font-headline font-bold text-sm shadow-lg shadow-primary/20 flex items-center gap-2 hover:scale-[1.02] transition-transform">
        <span class="material-symbols-outlined">add_circle</span> Add New Motor
    </a>
</section>

<?php if (isset($_SESSION['success_msg'])): ?>
<div class="mb-8 p-4 bg-secondary/10 border border-secondary/20 rounded-xl flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-secondary">check_circle</span>
        <span class="text-secondary font-medium"><?= $_SESSION['success_msg'] ?></span>
    </div>
    <button type="button" class="text-secondary/60 hover:text-secondary p-1" onclick="this.parentElement.remove()">
        <span class="material-symbols-outlined">close</span>
    </button>
</div>
<?php unset($_SESSION['success_msg']); endif; ?>

<!-- Metrics Overview (Bento Style) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
    <div class="bg-surface-container-lowest p-6 rounded-xl relative overflow-hidden group border border-outline-variant/10">
        <div class="absolute bottom-0 left-0 w-full h-1 bg-secondary/20"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-label text-on-surface-variant mb-4">Active Motors</p>
                <h3 class="text-3xl font-headline font-bold"><?= $active_motors ?> / <?= $total_motors ?></h3>
            </div>
            <span class="material-symbols-outlined text-secondary text-3xl opacity-50">bolt</span>
        </div>
    </div>
    <div class="bg-surface-container-lowest p-6 rounded-xl relative overflow-hidden group border border-outline-variant/10">
        <div class="absolute bottom-0 left-0 w-full h-1 bg-primary/20"></div>
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-label text-on-surface-variant mb-4">Total Capacity</p>
                <h3 class="text-3xl font-headline font-bold"><?= $total_hp ?> HP</h3>
            </div>
            <span class="material-symbols-outlined text-primary text-3xl opacity-50">speed</span>
        </div>
    </div>
</div>

<!-- Motor Inventory Grid -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-12">
    <?php if ($total_motors > 0): ?>
        <?php foreach ($motors_data as $motor): ?>
        <div class="searchable-item bg-surface-container-lowest rounded-xl overflow-hidden group flex flex-col md:flex-row shadow-sm hover:shadow-md transition-shadow border border-outline-variant/10">
             <div class="md:w-32 h-32 md:h-auto relative overflow-hidden bg-surface-container-highest flex items-center justify-center">
                 <span class="material-symbols-outlined text-6xl text-outline/30 group-hover:scale-110 transition-transform duration-500">water_pump</span>
                 <?php if($motor['status'] == 'active'): ?>
                 <div class="absolute top-3 left-3 px-3 py-1 bg-secondary text-white text-[10px] font-bold rounded-full tracking-wider uppercase shadow-sm">Active</div>
                 <?php else: ?>
                 <div class="absolute top-3 left-3 px-3 py-1 bg-error text-white text-[10px] font-bold rounded-full tracking-wider uppercase shadow-sm">Inactive</div>
                 <?php endif; ?>
             </div>
             <div class="flex-1 p-6 flex flex-col justify-between">
                 <div>
                     <div class="flex justify-between items-start mb-2">
                         <h4 class="text-xl font-headline font-extrabold text-on-surface"><?= htmlspecialchars($motor['motor_name']) ?> <span class="text-sm font-medium text-outline ml-2">#<?= $motor['motor_id'] ?></span></h4>
                         <span class="text-xs font-bold text-primary bg-primary-fixed px-2 py-1 rounded"><?= number_format($motor['horsepower'], 1) ?> HP</span>
                     </div>
                     <div class="flex items-center gap-1 text-on-surface-variant text-sm mb-4">
                         <span class="material-symbols-outlined text-sm">location_on</span>
                         <span><?= htmlspecialchars($motor['location']) ?: 'Location not specified' ?></span>
                     </div>
                 </div>
                 <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/10 mt-2">
                     <a href="#" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed/50 rounded-lg transition-colors" title="Edit Motor"><span class="material-symbols-outlined">edit_note</span></a>
                     <a href="#" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-fixed/50 rounded-lg transition-colors" title="View Details"><span class="material-symbols-outlined">visibility</span></a>
                 </div>
             </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full bg-surface-container-lowest rounded-xl p-12 text-center border border-outline-variant/10">
             <span class="material-symbols-outlined text-5xl text-outline mb-4">water_pump</span>
             <h3 class="text-xl font-headline font-bold text-on-surface mb-2">No Motors Found</h3>
             <p class="text-on-surface-variant mb-6">You haven't added any motors to the system yet.</p>
             <a href="add_motor.php" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold transition-transform active:scale-95">
                 Add Your First Motor
             </a>
        </div>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
