<?php
// Import Controller – receives parsed Excel JSON and inserts into DB
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$table  = $_POST['table'] ?? '';
$raw    = $_POST['data']  ?? '[]';
$rows   = json_decode($raw, true);

if (!is_array($rows) || count($rows) === 0) {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
    exit;
}

$inserted = 0;
$errors   = 0;

switch ($table) {
    // ─── Import Customers ──────────────────────────────────────────────────
    case 'customers':
        $stmt = $conn->prepare("INSERT INTO customers (farmer_name, mobile, village, farm_name, connection_no, pipe_size) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($rows as $r) {
            $name   = $r['Farmer Name']   ?? $r['farmer_name']   ?? '';
            $mobile = $r['Mobile']         ?? $r['mobile']         ?? '';
            $vill   = $r['Village']        ?? $r['village']        ?? '';
            $farm   = $r['Farm Name']      ?? $r['farm_name']      ?? '';
            $conn_  = $r['Connection No']  ?? $r['connection_no']  ?? '';
            $pipe   = $r['Pipe Size']      ?? $r['pipe_size']      ?? '';
            if (!$name) { $errors++; continue; }
            $stmt->bind_param("ssssss", $name, $mobile, $vill, $farm, $conn_, $pipe);
            $stmt->execute() ? $inserted++ : $errors++;
        }
        break;

    // ─── Import Motors ────────────────────────────────────────────────────
    case 'motors':
        $stmt = $conn->prepare("INSERT INTO motors (motor_name, location, status) VALUES (?, ?, ?)");
        foreach ($rows as $r) {
            $name   = $r['Motor Name'] ?? $r['motor_name'] ?? '';
            $loc    = $r['Location']   ?? $r['location']   ?? '';
            $status = $r['Status']     ?? $r['status']     ?? 'Active';
            if (!$name) { $errors++; continue; }
            $stmt->bind_param("sss", $name, $loc, $status);
            $stmt->execute() ? $inserted++ : $errors++;
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Import not supported for this table yet.']);
        exit;
}

echo json_encode([
    'success'  => true,
    'inserted' => $inserted,
    'errors'   => $errors,
    'message'  => "$inserted records imported successfully. $errors skipped."
]);
?>
