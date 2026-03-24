<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<!-- Header Section -->
<div class="flex justify-between items-end mb-8 mt-4">
    <div>
        <h2 class="text-headline-lg font-headline font-extrabold text-on-surface tracking-tight mb-2">Add New Motor</h2>
        <p class="text-body-md text-on-surface-variant max-w-2xl">Register a new pump or borewell into the hydrological network.</p>
    </div>
    <a href="motor_list.php" class="flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface-variant rounded-xl font-semibold hover:bg-surface-container-highest transition-colors">
        <span class="material-symbols-outlined text-xl">arrow_back</span>
        <span class="text-sm">Back to List</span>
    </a>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="mb-8 p-4 bg-error/10 border border-error/20 rounded-xl flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-error">error</span>
        <span class="text-error font-medium"><?= $_SESSION['error_msg'] ?></span>
    </div>
    <button type="button" class="text-error/60 hover:text-error p-1" onclick="this.parentElement.remove()">
        <span class="material-symbols-outlined">close</span>
    </button>
</div>
<?php unset($_SESSION['error_msg']); endif; ?>

<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/15 overflow-hidden max-w-2xl mx-auto mt-4">
    <div class="p-8">
        <form action="../../controllers/motorController.php" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-2">Motor Name <span class="text-error">*</span></label>
                <input type="text" name="motor_name" required placeholder="e.g. North Field Submersible" class="w-full bg-surface border border-outline-variant/30 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm placeholder:text-outline/50">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-2">Horsepower (HP) <span class="text-error">*</span></label>
                <input type="number" step="0.5" name="horsepower" required placeholder="e.g. 5.5" class="w-full bg-surface border border-outline-variant/30 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm placeholder:text-outline/50">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-on-surface mb-2">Location</label>
                <textarea name="location" rows="3" placeholder="Description of where this motor is installed" class="w-full bg-surface border border-outline-variant/30 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm placeholder:text-outline/50"></textarea>
            </div>
            
            <div class="pt-4">
                <button type="submit" name="add_motor" class="w-full flex justify-center items-center gap-2 px-6 py-3 bg-gradient-to-br from-primary to-primary-container text-white rounded-xl font-bold transition-transform active:scale-[0.98] shadow-lg shadow-primary/20 hover:shadow-xl">
                    <span class="material-symbols-outlined">save</span>
                    Save Motor Details
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
