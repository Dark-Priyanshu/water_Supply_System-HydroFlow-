<?php
require_once 'config/database.php';

$sql = "CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
);";

if ($conn->query($sql) === TRUE) {
    echo "Table created.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$insertSql = "INSERT IGNORE INTO settings (setting_key, setting_value) VALUES 
('inv_company_name', 'HydroFlow Water Supply'),
('inv_address', '123 Main Street, City, Country'),
('inv_contact', '+91 98765 43210'),
('inv_gst', 'GSTIN: 22AAAAA0000A1Z5'),
('inv_terms', '1. Payment is due within 7 days of invoice issue.\n2. This invoice is system-generated based on supply logs.'),
('inv_footer_note', 'Thank you for your business!'),
('inv_signatory', 'Authorized Signatory');";

if ($conn->query($insertSql) === TRUE) {
    echo "Default settings inserted.\n";
} else {
    echo "Error inserting settings: " . $conn->error . "\n";
}
?>
