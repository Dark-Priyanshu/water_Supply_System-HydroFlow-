<?php
// Export Controller – returns JSON data for any table
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$table = $_GET['table'] ?? '';

switch ($table) {
    // ─── Customers ─────────────────────────────────────────────────────────
    case 'customers':
        $res = $conn->query(
            "SELECT customer_id AS 'ID', farmer_name AS 'Farmer Name', mobile AS 'Mobile',
                    village AS 'Village', farm_name AS 'Farm Name',
                    connection_no AS 'Connection No', pipe_size AS 'Pipe Size',
                    created_at AS 'Created At'
             FROM customers ORDER BY customer_id"
        );
        break;

    // ─── Motors ────────────────────────────────────────────────────────────
    // Schema: motor_id, motor_name, horsepower, location, status  (no created_at)
    case 'motors':
        $res = $conn->query(
            "SELECT motor_id AS 'ID', motor_name AS 'Motor Name',
                    horsepower AS 'Horsepower (HP)', location AS 'Location',
                    status AS 'Status'
             FROM motors ORDER BY motor_id"
        );
        break;

    // ─── Supply Records ────────────────────────────────────────────────────
    // Schema: supply_id, customer_id, motor_id, date, start_time, end_time,
    //         total_hours, rate, total_amount, created_at
    case 'supply':
        $res = $conn->query(
            "SELECT ws.supply_id AS 'ID',
                    c.farmer_name AS 'Customer',
                    m.motor_name AS 'Motor',
                    ws.date AS 'Date',
                    ws.start_time AS 'Start Time',
                    ws.end_time AS 'End Time',
                    ws.total_hours AS 'Total Hours',
                    ws.rate AS 'Rate/hr (₹)',
                    ws.total_amount AS 'Total Amount (₹)',
                    ws.created_at AS 'Created At'
             FROM water_supply ws
             LEFT JOIN customers c ON ws.customer_id = c.customer_id
             LEFT JOIN motors m    ON ws.motor_id    = m.motor_id
             ORDER BY ws.supply_id"
        );
        break;

    // ─── Bills ─────────────────────────────────────────────────────────────
    // Schema: bill_id, supply_id, customer_id, bill_date, total_hours,
    //         rate, total_amount, status  (no created_at)
    case 'bills':
        $res = $conn->query(
            "SELECT b.bill_id AS 'ID',
                    c.farmer_name AS 'Customer',
                    b.bill_date AS 'Bill Date',
                    b.total_hours AS 'Total Hours',
                    b.rate AS 'Rate/hr (₹)',
                    b.total_amount AS 'Total Amount (₹)',
                    b.status AS 'Status'
             FROM bills b
             LEFT JOIN customers c ON b.customer_id = c.customer_id
             ORDER BY b.bill_id"
        );
        break;

    // ─── Payments ──────────────────────────────────────────────────────────
    // Schema: payment_id, bill_id, amount, payment_date, method, status
    case 'payments':
        $res = $conn->query(
            "SELECT p.payment_id AS 'ID',
                    p.bill_id AS 'Bill ID',
                    c.farmer_name AS 'Customer',
                    p.payment_date AS 'Date',
                    p.amount AS 'Amount (₹)',
                    p.method AS 'Payment Method',
                    p.status AS 'Status'
             FROM payments p
             LEFT JOIN bills b     ON p.bill_id      = b.bill_id
             LEFT JOIN customers c ON b.customer_id  = c.customer_id
             ORDER BY p.payment_id"
        );
        break;

    default:
        echo json_encode(['error' => 'Invalid table']);
        exit;
}

if (!$res) {
    echo json_encode(['error' => $conn->error]);
    exit;
}

$rows = [];
while ($row = $res->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
?>
