<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../models/Setting.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$action = $_POST['action'] ?? '';

if ($action === 'update_invoice_settings') {
    $settingModel = new Setting($conn);
    
    $settings = [
        'inv_company_name' => $_POST['inv_company_name'] ?? '',
        'inv_gst'          => $_POST['inv_gst'] ?? '',
        'inv_address'      => $_POST['inv_address'] ?? '',
        'inv_contact'      => $_POST['inv_contact'] ?? '',
        'inv_signatory'    => $_POST['inv_signatory'] ?? '',
        'inv_terms'        => $_POST['inv_terms'] ?? '',
        'inv_footer_note'  => $_POST['inv_footer_note'] ?? ''
    ];
    
    $success = true;
    foreach ($settings as $key => $value) {
        if (!$settingModel->set($key, $value)) {
            $success = false;
        }
    }
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Invoice settings saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save some invoice settings.']);
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
exit();
?>
