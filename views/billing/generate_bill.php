<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>
<?php require_once '../../config/database.php'; ?>

<?php
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
    echo "<div class='p-10 text-center text-error font-bold'>Supply record not found.</div>";
    include '../../includes/footer.php';
    exit();
}

$supply = $result->fetch_assoc();
?>

<div class="flex justify-between items-end mb-8 mt-4">
    <div>
        <h2 class="text-headline-lg font-headline font-extrabold text-on-surface tracking-tight mb-2">Confirm Bill Generation</h2>
        <p class="text-body-md text-on-surface-variant max-w-2xl">Review supply details before creating the final invoice.</p>
    </div>
    <a href="../supply/supply_history.php" class="flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface-variant rounded-xl font-semibold hover:bg-surface-container-highest transition-colors">
        <span class="material-symbols-outlined text-[20px]">close</span>
        <span class="text-sm">Cancel</span>
    </a>
</div>

<div class="bg-surface-container-lowest border border-outline-variant/20 shadow-xl rounded-2xl mx-auto max-w-2xl overflow-hidden mt-8">
    <div class="h-2 bg-gradient-to-r from-primary via-primary-container to-secondary"></div>
    <div class="p-8 md:p-12">
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold font-headline text-primary">HydroFlow</h3>
            <p class="text-[12px] uppercase tracking-widest text-outline font-semibold">Proforma Invoice Preview</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-outline-variant/10">
            <div>
                <h6 class="text-[10px] uppercase font-bold text-on-surface-variant tracking-widest mb-3">Billed To:</h6>
                <div class="space-y-1">
                    <p class="text-lg font-bold text-on-surface"><?= htmlspecialchars($supply['farmer_name']) ?></p>
                    <p class="text-sm text-on-surface-variant"><?= htmlspecialchars($supply['village']) ?></p>
                    <p class="text-sm text-on-surface-variant font-medium mt-1">+91 <?= htmlspecialchars($supply['mobile']) ?></p>
                </div>
            </div>
            <div class="md:text-right">
                <h6 class="text-[10px] uppercase font-bold text-on-surface-variant tracking-widest mb-3">Supply Details:</h6>
                <div class="space-y-1">
                    <p class="text-sm text-on-surface-variant"><span class="font-medium mr-2">Date:</span> <?= date('d M, Y', strtotime($supply['date'])) ?></p>
                    <p class="text-sm text-on-surface-variant"><span class="font-medium mr-2">Motor:</span> <?= htmlspecialchars($supply['motor_name']) ?></p>
                    <p class="text-sm text-on-surface-variant"><span class="font-medium mr-2">Time:</span> <?= date('h:i A', strtotime($supply['start_time'])) ?> - <?= date('h:i A', strtotime($supply['end_time'])) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-low rounded-xl p-6 mb-8 border border-outline-variant/10">
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-semibold text-on-surface-variant">Usage (Hours)</span>
                <span class="font-bold text-on-surface"><?= number_format($supply['total_hours'], 2) ?> h</span>
            </div>
            <div class="flex justify-between items-center mb-4 border-b border-outline-variant/10 pb-4">
                <span class="text-sm font-semibold text-on-surface-variant">Rate/Hour</span>
                <span class="font-bold text-on-surface">₹<?= number_format($supply['rate'], 2) ?></span>
            </div>
            <div class="flex justify-between items-center mt-4">
                <span class="text-lg font-bold font-headline text-primary">Est. Amount</span>
                <span class="text-2xl font-extrabold font-headline text-primary">₹<?= number_format($supply['total_amount'], 2) ?></span>
            </div>
        </div>

        <form action="../../controllers/billingController.php" method="POST">
            <input type="hidden" name="supply_id" value="<?= $supply['supply_id'] ?>">
            <input type="hidden" name="customer_id" value="<?= $supply['customer_id'] ?>">
            <input type="hidden" name="total_hours" value="<?= $supply['total_hours'] ?>">
            <input type="hidden" name="rate" value="<?= $supply['rate'] ?>">
            <input type="hidden" name="total_amount" value="<?= $supply['total_amount'] ?>">
            
            <button type="submit" name="generate_bill" class="w-full flex justify-center items-center gap-2 px-6 py-4 bg-gradient-to-br from-primary to-primary-container text-white rounded-xl font-bold transition-transform active:scale-[0.98] shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 text-lg">
                <span class="material-symbols-outlined text-[24px]">receipt_long</span>
                Generate Final Bill
            </button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
