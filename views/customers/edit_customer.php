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
<div class="flex justify-between items-end mb-8 mt-4">
    <div>
        <nav class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
            <span>Directory</span>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a href="customer_list.php" class="hover:text-primary transition-colors">Customers</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary font-medium">Edit Customer</span>
        </nav>
        <h2 class="text-headline-lg font-headline font-extrabold text-on-surface tracking-tight mb-2">Edit Customer</h2>
        <p class="text-body-md text-on-surface-variant max-w-2xl">Modify the details of an existing farmer or land owner.</p>
    </div>
    <a href="customer_list.php" class="flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface-variant rounded-xl font-semibold hover:bg-surface-container-highest transition-colors shadow-sm">
        <span class="material-symbols-outlined text-xl">arrow_back</span>
        <span class="text-sm">Back to List</span>
    </a>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="mb-8 p-4 bg-error-container text-on-error-container rounded-xl flex items-center gap-3 border border-error/20 max-w-3xl">
    <span class="material-symbols-outlined">error</span>
    <span class="text-sm font-bold"><?= $_SESSION['error_msg'] ?></span>
</div>
<?php unset($_SESSION['error_msg']); endif; ?>

<!-- Form Card -->
<div class="bg-surface-container-lowest rounded-2xl shadow-[0_8px_32px_rgba(25,28,30,0.04)] border border-outline-variant/10 overflow-hidden max-w-3xl">
    <div class="p-8 md:p-10 border-b border-outline-variant/10 bg-surface-container-low/30">
        <form action="../../controllers/customerController.php" method="POST" class="space-y-6">
            <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer['customer_id']) ?>">
            <h4 class="font-headline text-lg font-bold text-on-surface mb-6 border-b border-outline-variant/10 pb-2">Personal Information</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Farmer Name <span class="text-error">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">person</span>
                        <input type="text" name="farmer_name" required value="<?= htmlspecialchars($customer['farmer_name']) ?>" class="w-full pl-12 pr-4 py-3 bg-surface border border-outline-variant/30 rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all outline-none font-medium text-sm" placeholder="e.g. Rajeshwar Singh">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Mobile Number <span class="text-error">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">call</span>
                        <input type="text" name="mobile" required pattern="[0-9]{10}" title="10 digit mobile number" value="<?= htmlspecialchars($customer['mobile']) ?>" class="w-full pl-12 pr-4 py-3 bg-surface border border-outline-variant/30 rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all outline-none font-medium text-sm" placeholder="10 Digits (e.g. 9876543210)">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Village / Location <span class="text-error">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">location_on</span>
                        <input type="text" name="village" required value="<?= htmlspecialchars($customer['village']) ?>" class="w-full pl-12 pr-4 py-3 bg-surface border border-outline-variant/30 rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all outline-none font-medium text-sm" placeholder="Village Name">
                    </div>
                </div>
            </div>

            <h4 class="font-headline text-lg font-bold text-on-surface mb-6 mt-8 border-b border-outline-variant/10 pb-2">Farm & Technical Details</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Farm Name <span class="text-outline text-[10px] ml-1">(Optional)</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">agriculture</span>
                        <input type="text" name="farm_name" value="<?= htmlspecialchars($customer['farm_name']) ?>" class="w-full pl-12 pr-4 py-3 bg-surface border border-outline-variant/30 rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all outline-none font-medium text-sm" placeholder="e.g. Green Meadows">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Connection No. <span class="text-outline text-[10px] ml-1">(Optional)</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">cable</span>
                        <input type="text" name="connection_no" value="<?= htmlspecialchars($customer['connection_no']) ?>" class="w-full pl-12 pr-4 py-3 bg-surface border border-outline-variant/30 rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all outline-none font-medium text-sm" placeholder="Utility Conn. Ref">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pipe Size</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">straighten</span>
                        <select name="pipe_size" class="w-full pl-12 pr-10 py-3 bg-surface border border-outline-variant/30 rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest appearance-none transition-all outline-none font-medium text-sm cursor-pointer">
                            <option value="">Select Delivery Size</option>
                            <option value="2 inch" <?= $customer['pipe_size'] === '2 inch' ? 'selected' : '' ?>>2 Inch Std.</option>
                            <option value="2.5 inch" <?= $customer['pipe_size'] === '2.5 inch' ? 'selected' : '' ?>>2.5 Inch</option>
                            <option value="3 inch" <?= $customer['pipe_size'] === '3 inch' ? 'selected' : '' ?>>3 Inch High Flow</option>
                            <option value="4 inch" <?= $customer['pipe_size'] === '4 inch' ? 'selected' : '' ?>>4 Inch Industrial</option>
                        </select>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline pointer-events-none">expand_more</span>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col md:flex-row gap-4 items-center mt-4 border-t border-outline-variant/10">
                <button type="submit" name="update_customer" class="w-full md:w-auto flex-1 flex justify-center items-center gap-2 py-3.5 px-6 bg-gradient-to-r from-secondary to-secondary-container text-white font-bold rounded-xl shadow-lg shadow-secondary/25 hover:shadow-xl hover:-translate-y-0.5 transition-all text-sm uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Update Customer
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
